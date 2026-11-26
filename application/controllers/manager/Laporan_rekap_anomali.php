<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_rekap_anomali extends Member_Controller {
    private $kode_menu = 'laporan-rekap-anomali';
    private $kelompok = 'laporan';
    private $url = 'manager/laporan_rekap_anomali';

    function __construct(){
        parent::__construct();
        $this->load->model('cbt_user_model');
        $this->load->model('cbt_user_grup_model');
        $this->load->model('cbt_tes_model');

        parent::cek_akses($this->kode_menu);
        if(strtolower(trim((string)$this->access->get_level())) !== 'admin'){
            redirect('manager');
        }
    }

    public function index(){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        $tgl_awal  = date('Y-m-d', strtotime('-1 days'));
        $tgl_akhir = date('Y-m-d');
        $data['rentang_waktu'] = $tgl_awal.' - '.$tgl_akhir;

        $ruang_list = array();
        $q_ruang = $this->db->select('user_detail')
                            ->from('cbt_user')
                            ->where('user_detail IS NOT NULL', null, false)
                            ->where("TRIM(user_detail) !=", '')
                            ->group_by('user_detail')
                            ->get();
        if($q_ruang && $q_ruang->num_rows() > 0){
            $raw = $q_ruang->result();
            usort($raw, function($a, $b){
                return strnatcasecmp(trim((string)$a->user_detail), trim((string)$b->user_detail));
            });
            foreach($raw as $r){
                $ruang_list[] = trim((string)$r->user_detail);
            }
        }
        $data['ruang_list'] = $ruang_list;

        $this->template->display_admin($this->kelompok.'/laporan_rekap_anomali_view', 'Rekap Anomali Jaringan', $data);
    }

    private function _parse_dates($rentang){
        $tgl_awal  = date('Y-m-d', strtotime('-1 days'));
        $tgl_akhir = date('Y-m-d');
        $rentang = trim((string)$rentang);
        if($rentang !== '' && strpos($rentang, ' - ') !== false){
            $parts = explode(' - ', $rentang);
            $d0 = trim($parts[0]);
            $d1 = trim($parts[1]);
            if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $d0) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $d1)){
                if($d0 <= $d1){
                    $tgl_awal = $d0;
                    $tgl_akhir = $d1;
                }else{
                    $tgl_awal = $d1;
                    $tgl_akhir = $d0;
                }
            }
        }
        return array($tgl_awal, $tgl_akhir);
    }

    /**
     * Kumpulkan rekap anomali lintas hari dari user_log + cbt_tes_soal (PUB|)
     * tanpa mengubah struktur tabel database.
     */
    private function _collect_rekap($tgl_awal, $tgl_akhir, $ruang_filter = 'semua'){
        $records = array(); // key: tanggal|username

        // 1. Ambil seluruh log dari user_log dalam rentang tanggal
        $sql_logs = "SELECT ul.id, ul.username, ul.log, ul.ts,
                            DATE(ul.ts) AS tgl,
                            u.user_id, u.user_firstname, u.user_detail, u.user_ip, u.user_login_date,
                            g.grup_nama
                     FROM user_log ul
                     LEFT JOIN cbt_user u ON u.user_name = ul.username
                     LEFT JOIN cbt_user_grup g ON g.grup_id = u.user_grup_id
                     WHERE DATE(ul.ts) >= ? AND DATE(ul.ts) <= ?
                     ORDER BY ul.ts ASC, ul.id ASC";
        $log_rows = $this->db->query($sql_logs, array($tgl_awal, $tgl_akhir))->result();

        foreach($log_rows as $lr){
            $ruang_val = trim((string)$lr->user_detail);
            if($ruang_filter !== '' && $ruang_filter !== 'semua' && $ruang_val !== $ruang_filter){
                continue;
            }
            $key = $lr->tgl.'|'.$lr->username;
            if(!isset($records[$key])){
                $records[$key] = array(
                    'tanggal'         => $lr->tgl,
                    'user_id'         => (int)$lr->user_id,
                    'username'        => (string)$lr->username,
                    'nama'            => !empty($lr->user_firstname) ? stripslashes((string)$lr->user_firstname) : (string)$lr->username,
                    'kelas'           => !empty($lr->grup_nama) ? (string)$lr->grup_nama : '-',
                    'ruang'           => ($ruang_val !== '') ? $ruang_val : '-',
                    'tes_nama'        => '-',
                    'user_ip'         => trim((string)$lr->user_ip),
                    'user_login_date' => trim((string)$lr->user_login_date),
                    'jml_pindah'      => 0,
                    'jml_pub'         => 0,
                    'jml_kembali'     => 0,
                    'jam_pertama'     => date('H:i:s', strtotime($lr->ts)),
                    'jam_terakhir'    => date('H:i:s', strtotime($lr->ts)),
                    'last_ts'         => $lr->ts,
                    'last_log'        => (string)$lr->log,
                    'kronologi'       => array(),
                    'soal_pub'        => array(),
                    'status_akhir'    => 'KUNING'
                );
            }

            $records[$key]['jml_pindah']++;
            if(strpos($lr->log, 'KEMBALI KE LAN') === 0){
                $records[$key]['jml_kembali']++;
            }elseif(strpos($lr->log, 'PAKET DATA') !== false || strpos($lr->log, 'PUB|') !== false){
                $records[$key]['jml_pub']++;
            }

            $jam_str = date('H:i:s', strtotime($lr->ts));
            if($lr->ts > $records[$key]['last_ts']){
                $records[$key]['last_ts'] = $lr->ts;
                $records[$key]['jam_terakhir'] = $jam_str;
                $records[$key]['last_log'] = (string)$lr->log;
            }
            $records[$key]['kronologi'][] = array(
                'jam'   => $jam_str,
                'pesan' => (string)$lr->log
            );
        }

        // 2. Ambil daftar butir soal yang dikirim via jalur publik (tessoal_user_ip LIKE 'PUB|%') dalam rentang tanggal
        $sql_soal_pub = "SELECT DATE(COALESCE(ts.tessoal_change_time, tu.tesuser_creation_time)) AS tgl,
                                u.user_id, u.user_name, u.user_firstname, u.user_detail, u.user_ip, u.user_login_date,
                                g.grup_nama, t.tes_nama,
                                ts.tessoal_order, ts.tessoal_user_ip,
                                COALESCE(ts.tessoal_change_time, ts.tessoal_creation_time, tu.tesuser_creation_time) AS waktu_ts
                         FROM cbt_tes_soal ts
                         INNER JOIN cbt_tes_user tu ON tu.tesuser_id = ts.tessoal_tesuser_id
                         INNER JOIN cbt_user u ON u.user_id = tu.tesuser_user_id
                         LEFT JOIN cbt_user_grup g ON g.grup_id = u.user_grup_id
                         INNER JOIN cbt_tes t ON t.tes_id = tu.tesuser_tes_id
                         WHERE ts.tessoal_user_ip LIKE 'PUB|%'
                           AND DATE(COALESCE(ts.tessoal_change_time, tu.tesuser_creation_time)) >= ?
                           AND DATE(COALESCE(ts.tessoal_change_time, tu.tesuser_creation_time)) <= ?
                         ORDER BY waktu_ts ASC, ts.tessoal_order ASC";
        $soal_rows = $this->db->query($sql_soal_pub, array($tgl_awal, $tgl_akhir))->result();

        foreach($soal_rows as $sr){
            $ruang_val = trim((string)$sr->user_detail);
            if($ruang_filter !== '' && $ruang_filter !== 'semua' && $ruang_val !== $ruang_filter){
                continue;
            }
            $key = $sr->tgl.'|'.$sr->user_name;
            $jam_str = date('H:i:s', strtotime($sr->waktu_ts));
            if(!isset($records[$key])){
                $records[$key] = array(
                    'tanggal'         => $sr->tgl,
                    'user_id'         => (int)$sr->user_id,
                    'username'        => (string)$sr->user_name,
                    'nama'            => stripslashes((string)$sr->user_firstname),
                    'kelas'           => !empty($sr->grup_nama) ? (string)$sr->grup_nama : '-',
                    'ruang'           => ($ruang_val !== '') ? $ruang_val : '-',
                    'tes_nama'        => (string)$sr->tes_nama,
                    'user_ip'         => trim((string)$sr->user_ip),
                    'user_login_date' => trim((string)$sr->user_login_date),
                    'jml_pindah'      => 0,
                    'jml_pub'         => 0,
                    'jml_kembali'     => 0,
                    'jam_pertama'     => $jam_str,
                    'jam_terakhir'    => $jam_str,
                    'last_ts'         => $sr->waktu_ts,
                    'last_log'        => 'Jawaban soal dikirim via '.$sr->tessoal_user_ip,
                    'kronologi'       => array(),
                    'soal_pub'        => array(),
                    'status_akhir'    => 'KUNING'
                );
            }else{
                $records[$key]['tes_nama'] = (string)$sr->tes_nama;
                if($sr->waktu_ts < $records[$key]['tanggal'].' '.$records[$key]['jam_pertama']){
                    $records[$key]['jam_pertama'] = $jam_str;
                }
                if($sr->waktu_ts > $records[$key]['last_ts']){
                    $records[$key]['last_ts'] = $sr->waktu_ts;
                    $records[$key]['jam_terakhir'] = $jam_str;
                }
            }
            $records[$key]['soal_pub'][] = array(
                'nomor' => (int)$sr->tessoal_order,
                'ip'    => substr((string)$sr->tessoal_user_ip, 4),
                'jam'   => $jam_str
            );
        }

        // 3. Lengkapi nama tes untuk baris yang hanya berasal dari user_log + tentukan status akhir (MERAH / KUNING)
        if(!empty($records)){
            $user_ids = array();
            foreach($records as $rec){
                if($rec['user_id'] > 0 && $rec['tes_nama'] === '-'){
                    $user_ids[$rec['user_id']] = $rec['user_id'];
                }
            }
            if(!empty($user_ids)){
                $ids_csv = implode(',', $user_ids);
                $q_sesi = $this->db->query("
                    SELECT tu.tesuser_user_id, DATE(tu.tesuser_creation_time) AS tgl, t.tes_nama
                    FROM cbt_tes_user tu
                    INNER JOIN cbt_tes t ON t.tes_id = tu.tesuser_tes_id
                    WHERE tu.tesuser_user_id IN ({$ids_csv})
                      AND DATE(tu.tesuser_creation_time) >= ?
                      AND DATE(tu.tesuser_creation_time) <= ?
                    ORDER BY tu.tesuser_id ASC
                ", array($tgl_awal, $tgl_akhir))->result();
                $tes_map = array();
                foreach($q_sesi as $qs){
                    $tes_map[$qs->tgl.'|'.$qs->tesuser_user_id] = (string)$qs->tes_nama;
                }
                foreach($records as $k => $rec){
                    $tk = $rec['tanggal'].'|'.$rec['user_id'];
                    if($rec['tes_nama'] === '-' && isset($tes_map[$tk])){
                        $records[$k]['tes_nama'] = $tes_map[$tk];
                    }
                }
            }

            foreach($records as $k => $rec){
                $last_log = (string)$rec['last_log'];
                $is_merah = false;
                if(strpos($last_log, 'KEMBALI KE LAN') === 0){
                    $is_merah = false;
                }elseif(strpos($last_log, 'PAKET DATA') !== false || strpos($last_log, 'PUB|') !== false){
                    $is_merah = true;
                }elseif($rec['user_login_date'] === $rec['tanggal'] && strpos($rec['user_ip'], 'PUB|') === 0){
                    $is_merah = true;
                }
                $records[$k]['status_akhir'] = $is_merah ? 'MERAH' : 'KUNING';
            }
        }

        $items = array_values($records);
        usort($items, function($a, $b){
            if($a['tanggal'] !== $b['tanggal']){
                return strcmp($b['tanggal'], $a['tanggal']); // tanggal terbaru dulu
            }
            if($a['status_akhir'] !== $b['status_akhir']){
                return ($a['status_akhir'] === 'MERAH') ? -1 : 1; // MERAH dulu baru KUNING
            }
            $r_cmp = strnatcasecmp($a['ruang'], $b['ruang']);
            if($r_cmp !== 0) return $r_cmp;
            return strcasecmp($a['nama'], $b['nama']);
        });

        return $items;
    }

    public function get_data(){
        header('Content-Type: application/json; charset=utf-8');
        $rentang = $this->input->get('rentang', TRUE);
        $ruang   = trim((string)$this->input->get('ruang', TRUE));
        if($ruang === '') $ruang = 'semua';

        list($tgl_awal, $tgl_akhir) = $this->_parse_dates($rentang);
        $items = $this->_collect_rekap($tgl_awal, $tgl_akhir, $ruang);

        $merah = 0;
        $kuning = 0;
        foreach($items as $it){
            if($it['status_akhir'] === 'MERAH') $merah++;
            else $kuning++;
        }

        echo json_encode(array(
            'status'    => 1,
            'tgl_awal'  => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'summary'   => array('total' => count($items), 'merah' => $merah, 'kuning' => $kuning),
            'items'     => $items
        ));
    }

    public function export(){
        $rentang = $this->input->get('rentang', TRUE);
        $ruang   = trim((string)$this->input->get('ruang', TRUE));
        if($ruang === '') $ruang = 'semua';

        list($tgl_awal, $tgl_akhir) = $this->_parse_dates($rentang);
        $items = $this->_collect_rekap($tgl_awal, $tgl_akhir, $ruang);

        $this->load->library('excel');
        $excel = new PHPExcel();
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle('Rekap Anomali Jaringan');

        $sheet->setCellValue('A1', 'LAPORAN REKAP ANOMALI JARINGAN PESERTA UJIAN (ZYA CBT SMK NU DARUSSALAM)')
              ->setCellValue('A2', 'Periode: '.$tgl_awal.' s.d. '.$tgl_akhir.' | Filter Ruang: '.($ruang === 'semua' ? 'Semua Ruang' : $ruang));

        $headers = array(
            'No', 'Tanggal', 'NISN / Username', 'Nama Peserta', 'Kelas', 'Ruang',
            'Mapel / Tes', 'Status Akhir', 'Jml Pindah/Keluar Jalur', 'Jam Pertama', 'Jam Terakhir',
            'Soal Terkirim via Publik', 'Aksi / Log Terakhir'
        );
        $col = 0;
        foreach($headers as $h){
            $sheet->setCellValueByColumnAndRow($col, 4, $h);
            $col++;
        }
        $sheet->getStyle('A4:M4')->getFont()->setBold(true);

        $row = 5;
        $no = 1;
        foreach($items as $it){
            $soal_nums = array();
            foreach($it['soal_pub'] as $sp){
                $soal_nums[] = $sp['nomor'];
            }
            $soal_str = !empty($soal_nums) ? ('No. '.implode(', ', $soal_nums).' ('.count($soal_nums).' soal)') : '-';

            $sheet->setCellValueByColumnAndRow(0, $row, $no++)
                  ->setCellValueByColumnAndRow(1, $row, $it['tanggal'])
                  ->setCellValueByColumnAndRow(2, $row, $it['username'])
                  ->setCellValueByColumnAndRow(3, $row, $it['nama'])
                  ->setCellValueByColumnAndRow(4, $row, $it['kelas'])
                  ->setCellValueByColumnAndRow(5, $row, $it['ruang'])
                  ->setCellValueByColumnAndRow(6, $row, $it['tes_nama'])
                  ->setCellValueByColumnAndRow(7, $row, $it['status_akhir'])
                  ->setCellValueByColumnAndRow(8, $row, $it['jml_pindah'])
                  ->setCellValueByColumnAndRow(9, $row, $it['jam_pertama'])
                  ->setCellValueByColumnAndRow(10, $row, $it['jam_terakhir'])
                  ->setCellValueByColumnAndRow(11, $row, $soal_str)
                  ->setCellValueByColumnAndRow(12, $row, $it['last_log']);
            $row++;
        }

        foreach(range('A', 'M') as $colID){
            $sheet->getColumnDimension($colID)->setAutoSize(true);
        }

        if(ob_get_length()){
            ob_end_clean();
        }
        $filename = 'Rekap Anomali Jaringan - '.$tgl_awal.' sd '.$tgl_akhir.'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
