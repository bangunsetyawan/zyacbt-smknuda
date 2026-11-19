<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Peserta_importer
 * Library import peserta Excel (.xlsx / .xls) dengan fitur:
 * - Auto-Create Kelas (Group) di cbt_user_grup bila belum ada
 * - Preview sebelum simpan + deteksi duplikat username (dalam file & DB)
 * - Validasi panjang user_detail (maks 25 char sesuai skema DB)
 * - Transaksi DB aman (zero perubahan skema DB)
 */
class Peserta_importer {
    protected $CI;
    protected $tmp_dir = './public/uploads/batch_tmp/';

    public function __construct(){
        $this->CI =& get_instance();
        $this->CI->load->model('cbt_user_grup_model');
        $this->CI->load->model('cbt_user_model');
    }

    /**
     * Download template Excel (.xlsx) siap pakai untuk staf TU / Proktor
     */
    public function download_template(){
        $this->CI->load->library('excel');
        $objPHPExcel = new PHPExcel();

        // Sheet 1: Data Peserta
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('Data Peserta');

        $headers = array(
            'A1' => 'No',
            'B1' => 'Username',
            'C1' => 'Password',
            'D1' => 'Nama Lengkap',
            'E1' => 'Email (Opsional)',
            'F1' => 'Kelas (Group)',
            'G1' => 'Keterangan (Ruang/Sesi)'
        );
        foreach($headers as $cell => $label){
            $sheet->setCellValue($cell, $label);
        }

        // Contoh baris realistis (mudah ditimpa staf TU)
        $contoh = array(
            array(1, '2026001', '123456', 'Ahmad Fauzi', '', 'X TKJ 1', 'Ruang 1, Sesi 1'),
            array(2, '2026002', '123456', 'Siti Nurhaliza', '', 'XI AKL 1', 'Ruang 2, Sesi 1'),
            array(3, '2026003', '123456', 'Budi Santoso', '', 'XII TKR 1', 'Ruang 3, Sesi 2')
        );

        $rowNum = 2;
        foreach($contoh as $c){
            $sheet->setCellValue('A'.$rowNum, $c[0]);
            $sheet->setCellValueExplicit('B'.$rowNum, $c[1], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C'.$rowNum, $c[2], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValue('D'.$rowNum, $c[3]);
            $sheet->setCellValue('E'.$rowNum, $c[4]);
            $sheet->setCellValue('F'.$rowNum, $c[5]);
            $sheet->setCellValue('G'.$rowNum, $c[6]);
            $rowNum++;
        }

        // Format kolom Username & Password sebagai Text agar angka 0 di depan tidak hilang
        $sheet->getStyle('B2:C500')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

        // Styling header & lebar kolom
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
              ->getStartColor()->setRGB('D1FAE5');
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->getColumnDimension('D')->setWidth(28);
        $sheet->getColumnDimension('E')->setWidth(22);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(24);

        // Sheet 2: Petunjuk Singkat
        $guide = $objPHPExcel->createSheet(1);
        $guide->setTitle('Petunjuk');
        $guide->setCellValue('A1', 'PETUNJUK IMPORT PESERTA & KELAS ZYACBT');
        $guide->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $notes = array(
            '1. Isi data peserta mulai dari baris ke-2 pada sheet "Data Peserta" (timpa atau hapus baris contoh).',
            '2. Kolom wajib diisi: Username, Password, Nama Lengkap, dan Kelas (Group).',
            '3. Kelas (Group) yang belum ada di sistem akan OTOMATIS DIBUATKAN saat import.',
            '4. Pastikan penulisan nama kelas konsisten (misal "X TKJ 1", bukan campur "X-TKJ-1" dan "X TKJ 1").',
            '5. Kolom Email boleh dikosongkan.',
            '6. Kolom Keterangan maksimal 25 karakter (cocok diisi Ruang & Sesi, contoh: "Ruang 1, Sesi 1").',
            '7. Simpan file dalam format .xlsx atau .xls lalu upload melalui menu Peserta & Kelas atau Import Data Peserta.'
        );
        $r = 3;
        foreach($notes as $n){
            $guide->setCellValue('A'.$r, $n);
            $r++;
        }
        $guide->getColumnDimension('A')->setWidth(95);

        $objPHPExcel->setActiveSheetIndex(0);

        if(ob_get_length()){
            ob_end_clean();
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_import_peserta_zyacbt.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

    /**
     * Parse file Excel yang diupload, analisis kelas baru & duplikat, simpan staging JSON
     * @param string $file_field nama field $_FILES
     * @return array
     */
    public function parse_upload($file_field = 'userfile'){
        if(empty($_FILES[$file_field]['name'])){
            return array('status' => 0, 'pesan' => 'Pilih file Excel (.xlsx atau .xls) terlebih dahulu.');
        }

        $orig_name = $_FILES[$file_field]['name'];
        $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
        if(!in_array($ext, array('xlsx', 'xls'), true)){
            return array('status' => 0, 'pesan' => 'Format file tidak didukung. Gunakan file Excel .xlsx atau .xls.');
        }

        if(!is_dir($this->tmp_dir)){
            @mkdir($this->tmp_dir, 0775, true);
        }
        $this->_cleanup_old_tmp();

        $token = date('YmdHis').'_'.bin2hex(random_bytes(5));
        $tmp_excel = $this->tmp_dir.'peserta_'.$token.'.'.$ext;

        if(!move_uploaded_file($_FILES[$file_field]['tmp_name'], $tmp_excel)){
            return array('status' => 0, 'pesan' => 'Gagal menyimpan file sementara. Periksa izin folder uploads.');
        }

        try {
            $this->CI->load->library('excel');
            $excel = PHPExcel_IOFactory::load($tmp_excel);
            $sheet = $excel->getSheet(0);
            $highestRow = (int)$sheet->getHighestRow();
        } catch (Exception $e) {
            @unlink($tmp_excel);
            return array('status' => 0, 'pesan' => 'Gagal membaca file Excel: '.$e->getMessage());
        }

        @unlink($tmp_excel);

        if($highestRow < 2){
            return array('status' => 0, 'pesan' => 'File Excel kosong atau belum berisi data peserta mulai baris ke-2.');
        }

        // Ambil daftar kelas & username existing dalam 2 query cepat
        $existing_groups = array(); // lower => canonical name
        $q_grup = $this->CI->db->select('grup_id, grup_nama')->from('cbt_user_grup')->get()->result();
        foreach($q_grup as $g){
            $existing_groups[$this->_norm_key($g->grup_nama)] = array(
                'grup_id' => (int)$g->grup_id,
                'grup_nama' => $g->grup_nama
            );
        }

        $existing_users = array(); // lower => user_id
        $q_user = $this->CI->db->select('user_id, user_name')->from('cbt_user')->get()->result();
        foreach($q_user as $u){
            $existing_users[$this->_norm_key($u->user_name)] = (int)$u->user_id;
        }

        $rows_parsed = array();
        $seen_usernames = array();
        $groups_map = array(); // lower => array(nama, status, count)

        $stats = array(
            'total_rows' => 0,
            'valid_new' => 0,
            'existing_users' => 0,
            'dup_in_file' => 0,
            'invalid_rows' => 0,
            'detail_trimmed' => 0
        );

        for($r = 2; $r <= $highestRow; $r++){
            $username = $this->_cell_str($sheet, 1, $r);
            $password = $this->_cell_str($sheet, 2, $r);
            $nama     = $this->_cell_str($sheet, 3, $r);
            $email    = $this->_cell_str($sheet, 4, $r);
            $group    = $this->_cell_str($sheet, 5, $r);
            $detail   = $this->_cell_str($sheet, 6, $r);

            // Lewati baris yang benar-benar kosong
            if($username === '' && $password === '' && $nama === '' && $email === '' && $group === '' && $detail === ''){
                continue;
            }

            $stats['total_rows']++;

            // Rapikan spasi ganda di nama kelas & nama peserta
            $group = preg_replace('/\s+/', ' ', $group);
            $nama  = preg_replace('/\s+/', ' ', $nama);

            $errors = array();
            $warnings = array();

            if($username === '') $errors[] = 'Username kosong';
            if($password === '') $errors[] = 'Password kosong';
            if($nama === '')     $errors[] = 'Nama kosong';
            if($group === '')    $errors[] = 'Kelas (Group) kosong';

            if(strlen($detail) > 25){
                $detail = substr($detail, 0, 25);
                $warnings[] = 'Keterangan dipotong maks 25 karakter';
                $stats['detail_trimmed']++;
            }

            $row_status = 'new';
            $u_key = $this->_norm_key($username);
            $g_key = $this->_norm_key($group);

            // Canonical nama grup bila sudah ada di DB (hindari tabrakan case-insensitive)
            $group_canonical = $group;
            $group_is_new = false;
            if($group !== ''){
                if(isset($existing_groups[$g_key])){
                    $group_canonical = $existing_groups[$g_key]['grup_nama'];
                    $group_is_new = false;
                }else{
                    if(isset($groups_map[$g_key])){
                        $group_canonical = $groups_map[$g_key]['nama'];
                    }
                    $group_is_new = true;
                }
            }

            if(!empty($errors)){
                $row_status = 'invalid';
                $stats['invalid_rows']++;
            }elseif(isset($seen_usernames[$u_key])){
                $row_status = 'dup_file';
                $errors[] = 'Duplikat username di dalam file Excel (baris '.$seen_usernames[$u_key].')';
                $stats['dup_in_file']++;
            }else{
                $seen_usernames[$u_key] = $r;
                if(isset($existing_users[$u_key])){
                    $row_status = 'existing';
                    $stats['existing_users']++;
                }else{
                    $row_status = 'new';
                    $stats['valid_new']++;
                }

                // Catat rekap kelas hanya untuk baris valid (new / existing)
                if(!isset($groups_map[$g_key])){
                    $groups_map[$g_key] = array(
                        'nama' => $group_canonical,
                        'status' => $group_is_new ? 'new' : 'existing',
                        'count' => 0
                    );
                }
                $groups_map[$g_key]['count']++;
            }

            $rows_parsed[] = array(
                'row' => $r,
                'username' => $username,
                'password' => $password,
                'nama' => $nama,
                'email' => $email,
                'group' => $group_canonical,
                'group_key' => $g_key,
                'group_is_new' => $group_is_new,
                'detail' => $detail,
                'status' => $row_status,
                'errors' => $errors,
                'warnings' => $warnings
            );
        }

        if($stats['total_rows'] === 0){
            return array('status' => 0, 'pesan' => 'Tidak ada baris data peserta yang ditemukan di file Excel.');
        }

        // Urutkan ringkasan kelas (kelas baru di atas, lalu alfabetis)
        $groups_summary = array_values($groups_map);
        usort($groups_summary, function($a, $b){
            if($a['status'] !== $b['status']){
                return ($a['status'] === 'new') ? -1 : 1;
            }
            return strcasecmp($a['nama'], $b['nama']);
        });

        $new_groups_count = 0;
        foreach($groups_summary as $gs){
            if($gs['status'] === 'new') $new_groups_count++;
        }

        // Simpan hasil parsing ke staging JSON
        $staging_file = $this->tmp_dir.'peserta_'.$token.'.json';
        $payload = array(
            'filename' => $orig_name,
            'created_at' => time(),
            'rows' => $rows_parsed
        );
        if(file_put_contents($staging_file, json_encode($payload)) === false){
            return array('status' => 0, 'pesan' => 'Gagal menyimpan data preview sementara.');
        }

        // Kirim maksimal 100 baris pertama + semua baris bermasalah untuk preview tabel agar ringan di browser
        $preview_rows = array();
        $normal_shown = 0;
        foreach($rows_parsed as $item){
            if($item['status'] === 'invalid' || $item['status'] === 'dup_file'){
                $preview_rows[] = $item;
            }elseif($normal_shown < 100){
                $preview_rows[] = $item;
                $normal_shown++;
            }
        }
        usort($preview_rows, function($a, $b){
            return $a['row'] - $b['row'];
        });

        return array(
            'status' => 1,
            'token' => $token,
            'filename' => $orig_name,
            'stats' => $stats,
            'new_groups_count' => $new_groups_count,
            'total_groups_count' => count($groups_summary),
            'groups_summary' => $groups_summary,
            'preview_rows' => $preview_rows,
            'preview_truncated' => (count($rows_parsed) > count($preview_rows))
        );
    }

    /**
     * Eksekusi simpan hasil staging JSON ke database dalam transaksi aman
     * @param string $token
     * @param string $duplicate_mode 'skip' | 'update'
     * @return array
     */
    public function commit_import($token, $duplicate_mode = 'skip'){
        $token = preg_replace('/[^a-zA-Z0-9_]/', '', (string)$token);
        if($token === ''){
            return array('status' => 0, 'pesan' => 'Token preview tidak valid. Silakan upload ulang file Excel.');
        }

        $staging_file = $this->tmp_dir.'peserta_'.$token.'.json';
        if(!is_file($staging_file)){
            return array('status' => 0, 'pesan' => 'Sesi preview sudah kedaluwarsa atau sudah disimpan. Silakan upload ulang file Excel.');
        }

        $raw = file_get_contents($staging_file);
        $payload = json_decode($raw, true);
        if(!is_array($payload) || empty($payload['rows'])){
            @unlink($staging_file);
            return array('status' => 0, 'pesan' => 'Data preview rusak atau kosong.');
        }

        $duplicate_mode = ($duplicate_mode === 'update') ? 'update' : 'skip';

        // Muat ulang kondisi DB terkini tepat sebelum transaksi
        $group_id_map = array(); // lower => grup_id
        $q_grup = $this->CI->db->select('grup_id, grup_nama')->from('cbt_user_grup')->get()->result();
        foreach($q_grup as $g){
            $group_id_map[$this->_norm_key($g->grup_nama)] = (int)$g->grup_id;
        }

        $user_id_map = array(); // lower => user_id
        $q_user = $this->CI->db->select('user_id, user_name')->from('cbt_user')->get()->result();
        foreach($q_user as $u){
            $user_id_map[$this->_norm_key($u->user_name)] = (int)$u->user_id;
        }

        $kelas_baru = array();
        $peserta_ditambah = 0;
        $peserta_diupdate = 0;
        $peserta_dilewati = 0;
        $baris_gagal = 0;

        $this->CI->db->trans_begin();

        foreach($payload['rows'] as $row){
            if($row['status'] === 'invalid' || $row['status'] === 'dup_file'){
                $baris_gagal++;
                continue;
            }

            $u_key = $this->_norm_key($row['username']);
            $g_key = $this->_norm_key($row['group']);

            // Cek apakah user sudah ada di DB saat ini
            $is_existing_user = isset($user_id_map[$u_key]);
            if($is_existing_user && $duplicate_mode === 'skip'){
                $peserta_dilewati++;
                continue;
            }

            // Auto-create kelas bila belum ada di DB
            if(!isset($group_id_map[$g_key])){
                $this->CI->db->insert('cbt_user_grup', array('grup_nama' => $row['group']));
                $new_gid = (int)$this->CI->db->insert_id();
                $group_id_map[$g_key] = $new_gid;
                $kelas_baru[] = $row['group'];
            }
            $grup_id = $group_id_map[$g_key];

            $user_data = array(
                'user_password'  => $row['password'],
                'user_email'     => $row['email'],
                'user_firstname' => $row['nama'],
                'user_grup_id'   => $grup_id,
                'user_detail'    => substr((string)$row['detail'], 0, 25)
            );

            if($is_existing_user){
                // Update data tanpa mengubah user_id / user_name (aman untuk riwayat cbt_tes_user)
                $this->CI->cbt_user_model->update('user_id', $user_id_map[$u_key], $user_data);
                $peserta_diupdate++;
            }else{
                $user_data['user_name'] = $row['username'];
                $this->CI->db->insert('cbt_user', $user_data);
                $user_id_map[$u_key] = (int)$this->CI->db->insert_id();
                $peserta_ditambah++;
            }
        }

        if($this->CI->db->trans_status() === FALSE){
            $this->CI->db->trans_rollback();
            return array('status' => 0, 'pesan' => 'Terjadi kesalahan database saat menyimpan. Seluruh perubahan dibatalkan (aman).');
        }

        $this->CI->db->trans_commit();
        @unlink($staging_file);

        $ringkas = array();
        if(count($kelas_baru) > 0){
            $ringkas[] = count($kelas_baru).' kelas baru otomatis dibuat';
        }
        if($peserta_ditambah > 0){
            $ringkas[] = $peserta_ditambah.' peserta baru ditambahkan';
        }
        if($peserta_diupdate > 0){
            $ringkas[] = $peserta_diupdate.' peserta diperbarui';
        }
        if($peserta_dilewati > 0){
            $ringkas[] = $peserta_dilewati.' duplikat dilewati';
        }
        if($baris_gagal > 0){
            $ringkas[] = $baris_gagal.' baris tidak valid diabaikan';
        }

        return array(
            'status' => 1,
            'pesan' => !empty($ringkas) ? 'Import selesai: '.implode(', ', $ringkas).'.' : 'Tidak ada data yang diubah.',
            'kelas_baru' => $kelas_baru,
            'kelas_baru_count' => count($kelas_baru),
            'peserta_ditambah' => $peserta_ditambah,
            'peserta_diupdate' => $peserta_diupdate,
            'peserta_dilewati' => $peserta_dilewati,
            'baris_gagal' => $baris_gagal
        );
    }

    protected function _cell_str($sheet, $col, $row){
        $cell = $sheet->getCellByColumnAndRow($col, $row);
        if(!$cell) return '';
        $val = $cell->getValue();
        if($val === null) return '';
        if($val instanceof PHPExcel_RichText){
            $val = $val->getPlainText();
        }elseif(is_float($val) || is_int($val)){
            // Hindari notasi eksponensial / .0 untuk angka bulat (misal NIS / password angka)
            if(floor($val) == $val){
                $val = sprintf('%.0f', $val);
            }else{
                $val = (string)$val;
            }
        }else{
            $val = (string)$val;
        }
        return trim($val);
    }

    protected function _norm_key($str){
        return strtolower(trim(preg_replace('/\s+/', ' ', (string)$str)));
    }

    protected function _cleanup_old_tmp(){
        $files = @glob($this->tmp_dir.'peserta_*');
        if(!is_array($files)) return;
        $cutoff = time() - 7200; // 2 jam
        foreach($files as $f){
            if(is_file($f) && @filemtime($f) < $cutoff){
                @unlink($f);
            }
        }
    }
}
