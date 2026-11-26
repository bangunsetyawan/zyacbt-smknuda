<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* ZYA CBT
* Achmad Lutfi
* achmdlutfi@gmail.com
* achmadlutfi.wordpress.com
*/
class Tes_daftar extends Member_Controller {
	private $kode_menu = 'tes-daftar';
	private $kelompok = 'tes';
	private $url = 'manager/tes_daftar';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_tes_model');
		$this->load->model('cbt_topik_model');
		$this->load->model('cbt_modul_model');
		$this->load->model('cbt_tesgrup_model');
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_tes_topik_set_model');

		parent::cek_akses($this->kode_menu);
	}

    private function _get_status_counts(){
        $today = date('Y-m-d');
        $now   = date('Y-m-d H:i:s');
        $sql = "SELECT
                    COUNT(*) AS semua,
                    SUM(CASE WHEN DATE(tes_begin_time) <= ? AND DATE(tes_end_time) >= ? THEN 1 ELSE 0 END) AS hari_ini,
                    SUM(CASE WHEN tes_begin_time <= ? AND tes_end_time >= ? THEN 1 ELSE 0 END) AS berlangsung,
                    SUM(CASE WHEN tes_begin_time > ? THEN 1 ELSE 0 END) AS akan_datang,
                    SUM(CASE WHEN tes_end_time < ? THEN 1 ELSE 0 END) AS berakhir
                FROM cbt_tes";
        $row = $this->db->query($sql, array($today, $today, $now, $now, $now, $now))->row();
        return array(
            'semua'       => (int)($row ? $row->semua : 0),
            'hari_ini'    => (int)($row ? $row->hari_ini : 0),
            'berlangsung' => (int)($row ? $row->berlangsung : 0),
            'akan_datang' => (int)($row ? $row->akan_datang : 0),
            'berakhir'    => (int)($row ? $row->berakhir : 0)
        );
    }
	
    public function index($status=null, $pesan=null){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        if(!empty($pesan)){
        	if($status=='0'){
        		$data['pesan_hapus'] = '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-info"></i> Perhatian</h4>'.html_escape($pesan).'</div>';
        	}else{
        		$data['pesan_hapus'] = '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-info"></i> Informasi</h4>'.html_escape($pesan).'</div>';
        	}
        }

        $data['counts'] = $this->_get_status_counts();
        $data['groups'] = $this->cbt_user_grup_model->get_group()->result();

        $this->cbt_modul_model->ensure_single_aktif();
        $active_modul = $this->db->select('modul_id, modul_nama')
                                 ->from('cbt_modul')
                                 ->where('modul_aktif', 1)
                                 ->order_by('modul_id', 'DESC')
                                 ->limit(1)
                                 ->get()->row();
        $data['active_modul_id'] = $active_modul ? (int)$active_modul->modul_id : 0;
        $data['active_modul_nama'] = $active_modul ? $active_modul->modul_nama : 'Belum ada modul aktif';
        
        $this->template->display_admin($this->kelompok.'/tes_daftar_view', 'Daftar Tes', $data);
    }

    function status_counts(){
        echo json_encode(array(
            'status' => 1,
            'counts' => $this->_get_status_counts()
        ));
    }

    /**
     * Master Jadwal Ringkas: kelompokkan seluruh jadwal terbit per Hari/Tanggal (zero DB schema mod)
     */
    function master_summary(){
        $hari_id = array('Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu');
        $bln_id  = array(1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des');

        $total_all_groups = (int)$this->db->count_all('cbt_user_grup');
        $tes_rows = $this->db->from('cbt_tes')
                             ->order_by('tes_begin_time', 'ASC')
                             ->order_by('tes_id', 'ASC')
                             ->get()->result();

        $tes_ids = array();
        foreach($tes_rows as $r){ $tes_ids[] = (int)$r->tes_id; }

        $grup_map = array();
        $topik_map = array();
        if(!empty($tes_ids)){
            $id_list = implode(',', $tes_ids);
            $q_g = $this->db->query("SELECT tg.tstgrp_tes_id, g.grup_nama
                                     FROM cbt_tesgrup tg
                                     JOIN cbt_user_grup g ON g.grup_id = tg.tstgrp_grup_id
                                     WHERE tg.tstgrp_tes_id IN ($id_list)
                                     ORDER BY g.grup_nama ASC")->result();
            foreach($q_g as $g){
                $grup_map[(int)$g->tstgrp_tes_id][] = $g->grup_nama;
            }

            $q_t = $this->db->query("SELECT ts.tset_tes_id, ts.tset_tipe, ts.tset_jumlah
                                     FROM cbt_tes_topik_set ts
                                     WHERE ts.tset_tes_id IN ($id_list)")->result();
            foreach($q_t as $t){
                $tid = (int)$t->tset_tes_id;
                if(!isset($topik_map[$tid])){
                    $topik_map[$tid] = array('pg' => 0, 'esai' => 0, 'total' => 0);
                }
                $topik_map[$tid]['total'] += (int)$t->tset_jumlah;
                if((int)$t->tset_tipe === 1){
                    $topik_map[$tid]['pg'] += (int)$t->tset_jumlah;
                }elseif((int)$t->tset_tipe === 2 || (int)$t->tset_tipe === 3){
                    $topik_map[$tid]['esai'] += (int)$t->tset_jumlah;
                }
            }
        }

        $now_ts = time();
        $today = date('Y-m-d');
        $days = array();

        foreach($tes_rows as $r){
            $tid = (int)$r->tes_id;
            $b_ts = strtotime($r->tes_begin_time);
            $e_ts = strtotime($r->tes_end_time);
            $date_key = date('Y-m-d', $b_ts);

            if(!isset($days[$date_key])){
                $w = (int)date('w', $b_ts);
                $m = (int)date('n', $b_ts);
                $days[$date_key] = array(
                    'date_key'   => $date_key,
                    'date_label' => $hari_id[$w].', '.date('d', $b_ts).' '.$bln_id[$m].' '.date('Y', $b_ts),
                    'is_today'   => ($date_key === $today),
                    'items'      => array()
                );
            }

            $tm = isset($topik_map[$tid]) ? $topik_map[$tid] : array('pg'=>0,'esai'=>0,'total'=>0);
            if($tm['pg'] > 0 && $tm['esai'] > 0) $jenis = 'CAMPURAN';
            elseif($tm['esai'] > 0) $jenis = 'ESAI';
            elseif($tm['pg'] > 0) $jenis = 'PG';
            else $jenis = 'KOSONG';

            if($now_ts > $e_ts) $st = 'berakhir';
            elseif($now_ts >= $b_ts && $now_ts <= $e_ts) $st = 'berlangsung';
            elseif($date_key === $today) $st = 'hari_ini';
            else $st = 'akan_datang';

            $g_list = isset($grup_map[$tid]) ? $grup_map[$tid] : array();
            $g_count = count($g_list);
            if($total_all_groups > 0 && $g_count >= $total_all_groups){
                $kelas_ringkas = 'Semua Kelas ('.$g_count.')';
            }elseif($g_count > 0){
                $kelas_ringkas = implode(', ', $g_list);
            }else{
                $kelas_ringkas = 'Belum ada kelas';
            }

            $same_day = (date('Y-m-d', $b_ts) === date('Y-m-d', $e_ts));
            $waktu_label = $same_day
                ? date('H:i', $b_ts).' – '.date('H:i', $e_ts).' WIB'
                : date('d/m H:i', $b_ts).' s/d '.date('d/m H:i', $e_ts).' WIB';

            $days[$date_key]['items'][] = array(
                'tes_id'        => $tid,
                'tes_nama'      => $r->tes_nama,
                'waktu'         => $waktu_label,
                'durasi'        => (int)$r->tes_duration_time,
                'jenis'         => $jenis,
                'total_soal'    => $tm['total'],
                'kelas_ringkas' => $kelas_ringkas,
                'kelas_list'    => $g_list,
                'is_all_kelas'  => ($total_all_groups > 0 && $g_count >= $total_all_groups),
                'kelas_count'   => $g_count,
                'status'        => $st,
                'token'         => (int)$r->tes_token
            );
        }

        echo json_encode(array(
            'status' => 1,
            'total_tes' => count($tes_rows),
            'days' => array_values($days)
        ));
    }

    /**
     * Terbitkan banyak jadwal sekaligus dari Master Jadwal dalam 1 transaksi database
     */
    function batch_create(){
        $raw = $this->input->post('rows_json', false);
        $rows = json_decode((string)$raw, true);
        if(!is_array($rows) || empty($rows)){
            echo json_encode(array('status' => 0, 'pesan' => 'Belum ada baris jadwal yang disusun.'));
            return;
        }

        // Ambil daftar nama tes existing (lowercase) untuk cek duplikat
        $existing_names = array();
        $q_names = $this->db->select('LOWER(tes_nama) AS ln')->from('cbt_tes')->get()->result();
        foreach($q_names as $n){
            $existing_names[$n->ln] = true;
        }

        $planned = array();
        $batch_names = array();

        foreach($rows as $idx => $row){
            $no = $idx + 1;
            $tgl         = trim((string)(isset($row['tgl']) ? $row['tgl'] : ''));
            $jam_mulai   = trim((string)(isset($row['jam_mulai']) ? $row['jam_mulai'] : ''));
            $jam_selesai = trim((string)(isset($row['jam_selesai']) ? $row['jam_selesai'] : ''));
            $durasi      = (int)(isset($row['durasi']) ? $row['durasi'] : 60);
            $topik_id    = (int)(isset($row['topik_id']) ? $row['topik_id'] : 0);
            $nama        = trim((string)(isset($row['nama']) ? $row['nama'] : ''));
            $pair_mode   = trim((string)(isset($row['pair_mode']) ? $row['pair_mode'] : 'utama_saja'));
            $pair_tid    = (int)(isset($row['pair_topik_id']) ? $row['pair_topik_id'] : 0);
            $pair_nama   = trim((string)(isset($row['pair_nama']) ? $row['pair_nama'] : ''));
            $pair_durasi = (int)(isset($row['pair_durasi']) ? $row['pair_durasi'] : 30);
            $groups      = isset($row['groups']) && is_array($row['groups']) ? $row['groups'] : array();

            if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl)){
                echo json_encode(array('status' => 0, 'pesan' => 'Baris #'.$no.': Tanggal ujian belum valid.'));
                return;
            }
            if(!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $jam_mulai) || !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $jam_selesai)){
                echo json_encode(array('status' => 0, 'pesan' => 'Baris #'.$no.': Format jam harus 24 jam (contoh 07:30).'));
                return;
            }
            if(strlen($jam_mulai) === 5) $jam_mulai .= ':00';
            if(strlen($jam_selesai) === 5) $jam_selesai .= ':00';
            $begin = $tgl.' '.$jam_mulai;
            $end   = $tgl.' '.$jam_selesai;
            if(strtotime($end) <= strtotime($begin)){
                echo json_encode(array('status' => 0, 'pesan' => 'Baris #'.$no.': Jam Tutup harus lebih akhir dari Jam Buka.'));
                return;
            }
            if($durasi <= 0){
                echo json_encode(array('status' => 0, 'pesan' => 'Baris #'.$no.': Durasi harus lebih dari 0 menit.'));
                return;
            }
            if($topik_id <= 0 || $nama === ''){
                echo json_encode(array('status' => 0, 'pesan' => 'Baris #'.$no.': Pilih Topik / Mata Pelajaran terlebih dahulu.'));
                return;
            }

            $clean_groups = array();
            foreach($groups as $g){
                $gid = (int)$g;
                if($gid > 0) $clean_groups[$gid] = $gid;
            }
            if(empty($clean_groups)){
                echo json_encode(array('status' => 0, 'pesan' => 'Baris #'.$no.': Pilih minimal 1 kelas peserta.'));
                return;
            }

            // Hitung stok soal topik utama
            $q_s = $this->db->query("SELECT
                    SUM(CASE WHEN soal_tipe=1 THEN 1 ELSE 0 END) AS jml_pg,
                    SUM(CASE WHEN soal_tipe=2 THEN 1 ELSE 0 END) AS jml_esai,
                    SUM(CASE WHEN soal_tipe=3 THEN 1 ELSE 0 END) AS jml_singkat,
                    COUNT(*) AS jml_total
                FROM cbt_soal WHERE soal_topik_id = ?", array($topik_id))->row();
            $jml_pg = (int)($q_s ? $q_s->jml_pg : 0);
            $jml_esai = (int)($q_s ? $q_s->jml_esai : 0);
            $jml_singkat = (int)($q_s ? $q_s->jml_singkat : 0);
            $jml_total = (int)($q_s ? $q_s->jml_total : 0);
            if($jml_total <= 0){
                echo json_encode(array('status' => 0, 'pesan' => 'Baris #'.$no.': Topik yang dipilih belum memiliki soal.'));
                return;
            }

            if($jml_pg > 0){
                $main_tipe = 1;
                $main_jml  = $jml_pg;
                $main_opsi = 5;
                $main_acak = 1;
                $main_acak_j = 1;
            }elseif($jml_esai > 0){
                $main_tipe = 2;
                $main_jml  = $jml_esai;
                $main_opsi = 1;
                $main_acak = 0;
                $main_acak_j = 0;
            }else{
                $main_tipe = ($jml_singkat > 0) ? 3 : 0;
                $main_jml  = ($jml_singkat > 0) ? $jml_singkat : $jml_total;
                $main_opsi = 1;
                $main_acak = 0;
                $main_acak_j = 0;
            }

            $ln = strtolower($nama);
            if(isset($existing_names[$ln]) || isset($batch_names[$ln])){
                echo json_encode(array('status' => 0, 'pesan' => 'Baris #'.$no.': Nama Tes "'.html_escape($nama).'" sudah dipakai. Ubah namanya agar unik (mis. tambah kelas/hari).'));
                return;
            }
            $batch_names[$ln] = true;

            $pair_info = null;
            if(($pair_mode === 'pisah' || $pair_mode === 'gabung') && $pair_tid > 0){
                $pe_jml = (int)$this->db->select('COUNT(*) AS c')->from('cbt_soal')
                                        ->where('soal_topik_id', $pair_tid)->where('soal_tipe', 2)
                                        ->get()->row()->c;
                if($pe_jml > 0){
                    if($pair_mode === 'pisah'){
                        if($pair_nama === '') $pair_nama = 'Esai '.$nama;
                        $pln = strtolower($pair_nama);
                        if(isset($existing_names[$pln]) || isset($batch_names[$pln])){
                            echo json_encode(array('status' => 0, 'pesan' => 'Baris #'.$no.': Nama Tes Esai "'.html_escape($pair_nama).'" sudah dipakai.'));
                            return;
                        }
                        $batch_names[$pln] = true;
                    }
                    $pair_info = array(
                        'mode'     => $pair_mode,
                        'topik_id' => $pair_tid,
                        'nama'     => $pair_nama,
                        'durasi'   => ($pair_durasi > 0 ? $pair_durasi : 30),
                        'jml'      => $pe_jml
                    );
                }
            }

            $planned[] = array(
                'nama'       => $nama,
                'begin'      => $begin,
                'end'        => $end,
                'durasi'     => $durasi,
                'topik_id'   => $topik_id,
                'tipe'       => $main_tipe,
                'jml'        => $main_jml,
                'opsi'       => $main_opsi,
                'acak_soal'  => $main_acak,
                'acak_jwb'   => $main_acak_j,
                'groups'     => array_values($clean_groups),
                'pair'       => $pair_info
            );
        }

        $this->db->trans_begin();
        $created_tests = 0;

        foreach($planned as $p){
            $is_gabung = ($p['pair'] !== null && $p['pair']['mode'] === 'gabung');
            $total_jml = $is_gabung ? ($p['jml'] + $p['pair']['jml']) : $p['jml'];
            $poin = round(100 / $total_jml, 2);
            $max_score = (!$is_gabung && (int)$p['tipe'] === 1) ? 100.00 : round($total_jml * $poin, 2);

            $main_id = $this->cbt_tes_model->save(array(
                'tes_nama'             => $p['nama'],
                'tes_detail'           => 'Ujian '.$p['nama'],
                'tes_begin_time'       => $p['begin'],
                'tes_end_time'         => $p['end'],
                'tes_duration_time'    => $p['durasi'],
                'tes_score_right'      => $poin,
                'tes_score_wrong'      => 0,
                'tes_score_unanswered' => 0,
                'tes_max_score'        => $max_score,
                'tes_results_to_users' => 0,
                'tes_detail_to_users'  => 0,
                'tes_token'            => 0
            ));

            foreach($p['groups'] as $gid){
                $this->cbt_tesgrup_model->save(array('tstgrp_tes_id' => $main_id, 'tstgrp_grup_id' => $gid));
            }
            $this->cbt_tes_topik_set_model->save(array(
                'tset_tes_id'       => $main_id,
                'tset_topik_id'     => $p['topik_id'],
                'tset_tipe'         => $p['tipe'],
                'tset_difficulty'   => 1,
                'tset_jumlah'       => $p['jml'],
                'tset_jawaban'      => $p['opsi'],
                'tset_acak_soal'    => $p['acak_soal'],
                'tset_acak_jawaban' => $p['acak_jwb']
            ));
            $created_tests++;

            if($is_gabung){
                $this->cbt_tes_topik_set_model->save(array(
                    'tset_tes_id'       => $main_id,
                    'tset_topik_id'     => $p['pair']['topik_id'],
                    'tset_tipe'         => 2,
                    'tset_difficulty'   => 1,
                    'tset_jumlah'       => $p['pair']['jml'],
                    'tset_jawaban'      => 1,
                    'tset_acak_soal'    => 0,
                    'tset_acak_jawaban' => 0
                ));
            }elseif($p['pair'] !== null && $p['pair']['mode'] === 'pisah'){
                $pe_poin = round(100 / $p['pair']['jml'], 2);
                $pe_max  = round($p['pair']['jml'] * $pe_poin, 2);
                $pair_id = $this->cbt_tes_model->save(array(
                    'tes_nama'             => $p['pair']['nama'],
                    'tes_detail'           => 'Ujian Esai - '.$p['nama'],
                    'tes_begin_time'       => $p['begin'],
                    'tes_end_time'         => $p['end'],
                    'tes_duration_time'    => $p['pair']['durasi'],
                    'tes_score_right'      => $pe_poin,
                    'tes_score_wrong'      => 0,
                    'tes_score_unanswered' => 0,
                    'tes_max_score'        => $pe_max,
                    'tes_results_to_users' => 0,
                    'tes_detail_to_users'  => 0,
                    'tes_token'            => 0
                ));
                foreach($p['groups'] as $gid){
                    $this->cbt_tesgrup_model->save(array('tstgrp_tes_id' => $pair_id, 'tstgrp_grup_id' => $gid));
                }
                $this->cbt_tes_topik_set_model->save(array(
                    'tset_tes_id'       => $pair_id,
                    'tset_topik_id'     => $p['pair']['topik_id'],
                    'tset_tipe'         => 2,
                    'tset_difficulty'   => 1,
                    'tset_jumlah'       => $p['pair']['jml'],
                    'tset_jawaban'      => 1,
                    'tset_acak_soal'    => 0,
                    'tset_acak_jawaban' => 0
                ));
                $created_tests++;
            }
        }

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal menyimpan batch jadwal ke database.'));
            return;
        }
        $this->db->trans_commit();

        echo json_encode(array(
            'status' => 1,
            'created_count' => $created_tests,
            'pesan' => 'Berhasil menerbitkan '.$created_tests.' jadwal tes sekaligus dari Master Jadwal!'
        ));
    }

    /**
     * Ubah Cepat Jam Buka-Tutup, Durasi & Token (aman meski tes sudah ada peserta)
     */
    function quick_schedule(){
        $tes_id      = (int)$this->input->post('qs-id', true);
        $tgl_mulai   = trim((string)$this->input->post('qs-tgl-mulai', true));
        $jam_mulai   = trim((string)$this->input->post('qs-jam-mulai', true));
        $tgl_selesai = trim((string)$this->input->post('qs-tgl-selesai', true));
        $jam_selesai = trim((string)$this->input->post('qs-jam-selesai', true));
        $durasi      = (int)$this->input->post('qs-durasi', true);
        $token       = $this->input->post('qs-token', true) ? 1 : 0;

        if($tes_id <= 0 || $durasi <= 0){
            echo json_encode(array('status' => 0, 'pesan' => 'ID Tes atau durasi tidak valid.'));
            return;
        }
        if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_mulai) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_selesai)){
            echo json_encode(array('status' => 0, 'pesan' => 'Format tanggal tidak valid.'));
            return;
        }
        if(!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $jam_mulai) || !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $jam_selesai)){
            echo json_encode(array('status' => 0, 'pesan' => 'Format jam tidak valid.'));
            return;
        }
        if(strlen($jam_mulai) === 5) $jam_mulai .= ':00';
        if(strlen($jam_selesai) === 5) $jam_selesai .= ':00';

        $begin = $tgl_mulai.' '.$jam_mulai;
        $end   = $tgl_selesai.' '.$jam_selesai;
        if(strtotime($end) <= strtotime($begin)){
            echo json_encode(array('status' => 0, 'pesan' => 'Waktu Tutup harus lebih akhir dari Waktu Buka.'));
            return;
        }

        $cek = $this->cbt_tes_model->get_by_kolom_limit('tes_id', $tes_id, 1);
        if($cek->num_rows() === 0){
            echo json_encode(array('status' => 0, 'pesan' => 'Data Tes tidak ditemukan.'));
            return;
        }

        $this->cbt_tes_model->update('tes_id', $tes_id, array(
            'tes_begin_time'    => $begin,
            'tes_end_time'      => $end,
            'tes_duration_time' => $durasi,
            'tes_token'         => $token
        ));

        echo json_encode(array(
            'status' => 1,
            'pesan'  => 'Jadwal waktu & durasi Tes berhasil diperbarui.'
        ));
    }

    /**
     * Duplikat / Kloning Tes (salin cbt_tes + cbt_tesgrup + cbt_tes_topik_set dalam 1 transaksi)
     */
    function duplikat_tes(){
        $src_id      = (int)$this->input->post('dup-src-id', true);
        $nama_baru   = trim((string)$this->input->post('dup-nama', true));
        $tgl_mulai   = trim((string)$this->input->post('dup-tgl-mulai', true));
        $jam_mulai   = trim((string)$this->input->post('dup-jam-mulai', true));
        $tgl_selesai = trim((string)$this->input->post('dup-tgl-selesai', true));
        $jam_selesai = trim((string)$this->input->post('dup-jam-selesai', true));

        if($src_id <= 0 || $nama_baru === ''){
            echo json_encode(array('status' => 0, 'pesan' => 'Nama Tes baru wajib diisi.'));
            return;
        }

        $q_src = $this->db->get_where('cbt_tes', array('tes_id' => $src_id), 1);
        if($q_src->num_rows() === 0){
            echo json_encode(array('status' => 0, 'pesan' => 'Tes sumber tidak ditemukan.'));
            return;
        }
        $src = $q_src->row();

        // Cek nama unik case-insensitive
        $cek_nama = $this->db->select('COUNT(*) AS hasil')
                             ->from('cbt_tes')
                             ->where('LOWER(tes_nama)', strtolower($nama_baru))
                             ->get()->row()->hasil;
        if((int)$cek_nama > 0){
            echo json_encode(array('status' => 0, 'pesan' => 'Nama Tes "'.html_escape($nama_baru).'" sudah dipakai. Gunakan nama lain.'));
            return;
        }

        if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_mulai) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_selesai) ||
           !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $jam_mulai) || !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $jam_selesai)){
            echo json_encode(array('status' => 0, 'pesan' => 'Format tanggal atau jam tidak valid.'));
            return;
        }
        if(strlen($jam_mulai) === 5) $jam_mulai .= ':00';
        if(strlen($jam_selesai) === 5) $jam_selesai .= ':00';

        $begin = $tgl_mulai.' '.$jam_mulai;
        $end   = $tgl_selesai.' '.$jam_selesai;
        if(strtotime($end) <= strtotime($begin)){
            echo json_encode(array('status' => 0, 'pesan' => 'Waktu Tutup harus lebih akhir dari Waktu Buka.'));
            return;
        }

        $src_groups = $this->cbt_tesgrup_model->get_by_kolom('tstgrp_tes_id', $src_id)->result();
        $src_topiks = $this->cbt_tes_topik_set_model->get_by_kolom('tset_tes_id', $src_id)->result();

        $this->db->trans_begin();

        $new_id = $this->cbt_tes_model->save(array(
            'tes_nama'             => $nama_baru,
            'tes_detail'           => $src->tes_detail,
            'tes_begin_time'       => $begin,
            'tes_end_time'         => $end,
            'tes_duration_time'    => $src->tes_duration_time,
            'tes_ip_range'         => $src->tes_ip_range,
            'tes_results_to_users' => $src->tes_results_to_users,
            'tes_detail_to_users'  => $src->tes_detail_to_users,
            'tes_score_right'      => $src->tes_score_right,
            'tes_score_wrong'      => $src->tes_score_wrong,
            'tes_score_unanswered' => $src->tes_score_unanswered,
            'tes_max_score'        => $src->tes_max_score,
            'tes_token'            => $src->tes_token
        ));

        foreach($src_groups as $g){
            $this->cbt_tesgrup_model->save(array(
                'tstgrp_tes_id'  => $new_id,
                'tstgrp_grup_id' => $g->tstgrp_grup_id
            ));
        }

        foreach($src_topiks as $t){
            $this->cbt_tes_topik_set_model->save(array(
                'tset_tes_id'       => $new_id,
                'tset_topik_id'     => $t->tset_topik_id,
                'tset_tipe'         => $t->tset_tipe,
                'tset_difficulty'   => $t->tset_difficulty,
                'tset_jumlah'       => $t->tset_jumlah,
                'tset_jawaban'      => $t->tset_jawaban,
                'tset_acak_jawaban' => $t->tset_acak_jawaban,
                'tset_acak_soal'    => $t->tset_acak_soal
            ));
        }

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal menduplikat Tes.'));
            return;
        }

        $this->db->trans_commit();
        echo json_encode(array(
            'status' => 1,
            'new_id' => (int)$new_id,
            'pesan'  => 'Tes berhasil diduplikat menjadi "'.html_escape($nama_baru).'".'
        ));
    }

    function hapus_tes(){
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('hapus-id', 'ID Tes','required|integer');
        $this->form_validation->set_rules('hapus-nama', 'Nama Tes','required|strip_tags');
        
        if($this->form_validation->run() == TRUE){
            $tes_id = (int)$this->input->post('hapus-id', true);
            $this->cbt_tes_model->delete('tes_id', $tes_id);

            $status['status'] = 1;
            $status['pesan'] = 'Tes berhasil dihapus';
        }else{
            $status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        
        echo json_encode($status);
    }

    function hapus_daftar_tes(){
    	$this->load->library('form_validation');
        $this->form_validation->set_rules('edit-tes-id[]', 'ID Tes','required|strip_tags');
        
        $status=0;
        $pesan='';
        if($this->form_validation->run() == TRUE){
        	$tes_id = $this->input->post('edit-tes-id', TRUE);
            $centang = $this->input->post('centang', TRUE);
            if($centang=='1'){
                $deleted = 0;
            	foreach( $tes_id as $kunci => $isi ) {
					if($isi=="on"){
						$this->db->trans_start();
		            	$this->cbt_tes_model->delete('tes_id', (int)$kunci);
						$this->db->trans_complete();
						$deleted++;
	            	}
	            }
            	$status=1;
        		$pesan=$deleted.' jadwal Tes yang dipilih berhasil dihapus';
            }else{
            	$pesan='Centang pernyataan konfirmasi terlebih dahulu.';
            }
        }else{
            $pesan='Pilih terlebih dahulu Tes yang akan dihapus';
        }
        
    	$this->index($status, $pesan);
    }
    
    function get_by_id($id=null){
    	$data['data'] = 0;
		if(!empty($id)){
			$query = $this->cbt_tes_model->get_by_kolom('tes_id', (int)$id);
			if($query->num_rows()>0){
				$query = $query->row();
				$data['data'] = 1;
				$data['id'] = $query->tes_id;
				$data['nama'] = $query->tes_nama;
				$data['deskripsi'] = $query->tes_detail;
				$data['waktu'] = $query->tes_duration_time;
	            $data['poin'] = $query->tes_score_right;
	            $data['poin_salah'] = $query->tes_score_wrong;
	            $data['poin_kosong'] = $query->tes_score_unanswered;
	            $data['tunjukkan_hasil'] = $query->tes_results_to_users;
	            $data['token'] = $query->tes_token;
	            $data['tgl_mulai'] = substr($query->tes_begin_time, 0, 10);
	            $data['jam_mulai'] = substr($query->tes_begin_time, 11, 5);
	            $data['tgl_selesai'] = substr($query->tes_end_time, 0, 10);
	            $data['jam_selesai'] = substr($query->tes_end_time, 11, 5);
	            $data['rentang_waktu'] = $query->tes_begin_time.' - '.$query->tes_end_time;
			}
		}
		echo json_encode($data);
    }
    
    function get_datatable(){
		$search        = isset($_GET['sSearch']) ? trim($_GET['sSearch']) : '';
		$status_filter = trim((string)$this->input->get('status_filter', TRUE));
		$group_filter  = trim((string)$this->input->get('group_filter', TRUE));
		$jenis_filter  = trim((string)$this->input->get('jenis_filter', TRUE));

		$start = $this->get_start();
		$rows  = $this->get_rows();

		$today = date('Y-m-d');
		$now   = date('Y-m-d H:i:s');

		$this->db->start_cache();
		$this->db->from('cbt_tes t');

		if($search !== ''){
			$like = $this->db->escape_like_str($search);
			$this->db->where("(t.tes_nama LIKE '%".$like."%' ESCAPE '!' OR t.tes_detail LIKE '%".$like."%' ESCAPE '!')", NULL, FALSE);
		}

		if($status_filter === 'hari_ini'){
			$this->db->where('DATE(t.tes_begin_time) <=', $today);
			$this->db->where('DATE(t.tes_end_time) >=', $today);
		}elseif($status_filter === 'berlangsung'){
			$this->db->where('t.tes_begin_time <=', $now);
			$this->db->where('t.tes_end_time >=', $now);
		}elseif($status_filter === 'akan_datang'){
			$this->db->where('t.tes_begin_time >', $now);
		}elseif($status_filter === 'berakhir'){
			$this->db->where('t.tes_end_time <', $now);
		}

		if($group_filter !== '' && $group_filter !== 'semua' && is_numeric($group_filter)){
			$gid = (int)$group_filter;
			$this->db->where("EXISTS (SELECT 1 FROM cbt_tesgrup tg WHERE tg.tstgrp_tes_id = t.tes_id AND tg.tstgrp_grup_id = ".$gid.")", NULL, FALSE);
		}

		if($jenis_filter === 'pg'){
			$this->db->where("EXISTS (SELECT 1 FROM cbt_tes_topik_set ts WHERE ts.tset_tes_id = t.tes_id AND ts.tset_tipe = 1)", NULL, FALSE);
			$this->db->where("NOT EXISTS (SELECT 1 FROM cbt_tes_topik_set ts WHERE ts.tset_tes_id = t.tes_id AND ts.tset_tipe IN (2,3))", NULL, FALSE);
		}elseif($jenis_filter === 'esai'){
			$this->db->where("EXISTS (SELECT 1 FROM cbt_tes_topik_set ts WHERE ts.tset_tes_id = t.tes_id AND ts.tset_tipe IN (2,3))", NULL, FALSE);
			$this->db->where("NOT EXISTS (SELECT 1 FROM cbt_tes_topik_set ts WHERE ts.tset_tes_id = t.tes_id AND ts.tset_tipe = 1)", NULL, FALSE);
		}elseif($jenis_filter === 'campuran'){
			$this->db->where("EXISTS (SELECT 1 FROM cbt_tes_topik_set ts WHERE ts.tset_tes_id = t.tes_id AND ts.tset_tipe = 1)", NULL, FALSE);
			$this->db->where("EXISTS (SELECT 1 FROM cbt_tes_topik_set ts WHERE ts.tset_tes_id = t.tes_id AND ts.tset_tipe IN (2,3))", NULL, FALSE);
		}

		$this->db->stop_cache();
		$iTotal = $this->db->count_all_results();

		$this->db->select('t.*')
		         ->order_by('t.tes_begin_time', 'DESC')
		         ->order_by('t.tes_id', 'DESC')
		         ->limit($rows, $start);
		$tes_rows = $this->db->get()->result();
		$this->db->flush_cache();

		// Pre-fetch jumlah peserta & esai belum dikoreksi dalam 2 query batch (zero N+1!)
		$tes_ids = array();
		foreach($tes_rows as $r){
			$tes_ids[] = (int)$r->tes_id;
		}

		$peserta_map = array();
		$ungraded_map = array();
		if(!empty($tes_ids)){
			$id_list = implode(',', $tes_ids);
			$q_p = $this->db->query("SELECT tesuser_tes_id, COUNT(*) AS cnt FROM cbt_tes_user WHERE tesuser_tes_id IN ($id_list) GROUP BY tesuser_tes_id")->result();
			foreach($q_p as $p){
				$peserta_map[(int)$p->tesuser_tes_id] = (int)$p->cnt;
			}
			$q_e = $this->db->query("SELECT tu.tesuser_tes_id, COUNT(*) AS cnt
			                         FROM cbt_tes_user tu
			                         JOIN cbt_tes_soal ts ON ts.tessoal_tesuser_id = tu.tesuser_id
			                         JOIN cbt_soal s ON s.soal_id = ts.tessoal_soal_id
			                         WHERE tu.tesuser_tes_id IN ($id_list)
			                           AND s.soal_tipe = 2
			                           AND ts.tessoal_jawaban_text IS NOT NULL
			                           AND ts.tessoal_comment IS NULL
			                         GROUP BY tu.tesuser_tes_id")->result();
			foreach($q_e as $e){
				$ungraded_map[(int)$e->tesuser_tes_id] = (int)$e->cnt;
			}
		}

		$output = array(
			"sEcho" => isset($_GET['sEcho']) ? intval($_GET['sEcho']) : 1,
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

		$i = $start;
		$now_ts = time();
	    foreach ($tes_rows as $temp) {			
			$record = array();
			$tid = (int)$temp->tes_id;

			// Topik set & deteksi PG/ESAI/CAMPURAN
			$query_topik_set = $this->cbt_tes_topik_set_model->get_by_kolom('tset_tes_id', $tid);
			$has_pg = false;
			$has_essay = false;
			$total_soal = 0;
			$topik_names = array();

			if($query_topik_set->num_rows() > 0){
				foreach($query_topik_set->result() as $topik){
					$total_soal += (int)$topik->tset_jumlah;
					if((int)$topik->tset_tipe === 1){
						$has_pg = true;
					}elseif((int)$topik->tset_tipe === 2 || (int)$topik->tset_tipe === 3){
						$has_essay = true;
					}else{
						if(preg_match('/\b(esai|essay|uraian)\b/i', $temp->tes_nama)){
							$has_essay = true;
						}else{
							$has_pg = true;
						}
					}
					$qt = $this->cbt_topik_model->get_by_kolom_limit('topik_id', $topik->tset_topik_id, 1);
					if($qt->num_rows() > 0){
						$topik_names[] = html_escape($qt->row()->topik_nama).' ('.$topik->tset_jumlah.')';
					}
				}
			}

			if($has_essay && !$has_pg){
				$badge_tipe = '<span class="label" style="background-color:#4f46e5;">ESAI</span>';
			}elseif($has_essay && $has_pg){
				$badge_tipe = '<span class="label label-warning">CAMPURAN</span>';
			}elseif($has_pg){
				$badge_tipe = '<span class="label" style="background-color:#059669;">PG</span>';
			}else{
				$badge_tipe = '<span class="label label-danger">BELUM ADA SOAL</span>';
			}

			// Badge status waktu
			$begin_ts = strtotime($temp->tes_begin_time);
			$end_ts   = strtotime($temp->tes_end_time);
			if($now_ts > $end_ts){
				$badge_jadwal = '<span class="label label-default">Berakhir</span>';
			}elseif($now_ts >= $begin_ts && $now_ts <= $end_ts){
				$badge_jadwal = '<span class="label label-success"><i class="fa fa-circle"></i> Berlangsung</span>';
			}elseif(substr($temp->tes_begin_time, 0, 10) === $today){
				$badge_jadwal = '<span class="label label-info">Hari Ini</span>';
			}else{
				$badge_jadwal = '<span class="label label-primary">Akan Datang</span>';
			}

			$badge_token = ((int)$temp->tes_token === 1)
				? ' <span class="label label-warning" title="Wajib Token"><i class="fa fa-key"></i> Token</span>'
				: '';

			$sub_soal = !empty($topik_names)
				? '<div class="text-muted" style="font-size:11.5px; margin-top:3px;">'.implode(' &bull; ', $topik_names).'</div>'
				: '';

			// Format jadwal ringkas & mudah dibaca
			$b_date = date('d M Y', $begin_ts);
			$e_date = date('d M Y', $end_ts);
			$b_time = date('H:i', $begin_ts);
			$e_time = date('H:i', $end_ts);
			if($b_date === $e_date){
				$jadwal_html = '<strong>'.$b_date.'</strong><br><span class="text-muted" style="font-size:12px;"><i class="fa fa-clock-o"></i> '.$b_time.' &ndash; '.$e_time.'</span>';
			}else{
				$jadwal_html = '<span style="font-size:12px;">'.$b_date.' '.$b_time.'<br>s/d '.$e_date.' '.$e_time.'</span>';
			}

			// Kelas peserta
			$query_grup = $this->cbt_tesgrup_model->get_by_tes_id($tid);
			if($query_grup->num_rows() > 0){
				$g_badges = array();
				foreach($query_grup->result() as $grup){
					$g_badges[] = '<span class="label label-default" style="display:inline-block; margin:1px 2px 1px 0; font-weight:500;">'.html_escape($grup->grup_nama).'</span>';
				}
				$grup_html = implode('', $g_badges);
			}else{
				$grup_html = '<span class="text-danger">Belum ada kelas</span>';
			}

			// Tombol Aksi Cepat
			$jml_peserta = isset($peserta_map[$tid]) ? $peserta_map[$tid] : 0;
			$jml_ungraded = isset($ungraded_map[$tid]) ? $ungraded_map[$tid] : 0;

			$hasil_badge = ($jml_peserta > 0) ? ' <span class="badge bg-navy" style="font-size:10px;">'.$jml_peserta.'</span>' : '';
			$aksi = '<div style="display:flex; flex-wrap:wrap; gap:3px;">';
			$aksi .= '<a href="'.site_url('manager/tes_hasil?tes_id='.$tid).'" class="btn btn-info btn-xs" title="Lihat Hasil & Monitoring Peserta"><i class="fa fa-bar-chart"></i> Hasil'.$hasil_badge.'</a>';

			if($has_essay){
				$ev_badge = ($jml_ungraded > 0) ? ' <span class="badge bg-orange" style="font-size:10px;">'.$jml_ungraded.'</span>' : '';
				$aksi .= '<a href="'.site_url('manager/tes_evaluasi?tes_id='.$tid).'" class="btn btn-xs" style="background:#4f46e5; color:#fff;" title="Koreksi Jawaban Esai"><i class="fa fa-check-square-o"></i> Evaluasi'.$ev_badge.'</a>';
			}

			$aksi .= '<button type="button" onclick="bukaQuickSchedule('.$tid.')" class="btn btn-default btn-xs" title="Ubah Cepat Jam/Durasi/Token"><i class="fa fa-clock-o"></i> Jam</button>';
			$aksi .= '<button type="button" onclick="bukaDuplikat('.$tid.')" class="btn btn-default btn-xs" title="Duplikat Jadwal & Soal Tes"><i class="fa fa-copy"></i> Duplikat</button>';
			$aksi .= '<button type="button" onclick="edit('.$tid.')" class="btn btn-default btn-xs" title="Edit Lengkap"><i class="fa fa-pencil"></i></button>';
			$aksi .= '<button type="button" onclick="hapus('.$tid.')" class="btn btn-danger btn-xs" title="Hapus Tes"><i class="fa fa-trash"></i></button>';
			$aksi .= '</div>';

			$display_max = ($has_pg && !$has_essay) ? '100' : rtrim(rtrim(number_format((float)$temp->tes_max_score, 2, '.', ''), '0'), '.');
			$record[] = ++$i;
            $record[] = '<strong>'.html_escape($temp->tes_nama).'</strong><div style="margin-top:3px;">'.$badge_tipe.' '.$badge_jadwal.$badge_token.'</div>'.$sub_soal;
            $record[] = $jadwal_html;
            $record[] = '<strong>'.(int)$temp->tes_duration_time.' mnt</strong><br><small class="text-muted">Max: '.$display_max.' &bull; '.$total_soal.' soal</small>';
            $record[] = $grup_html;
            $record[] = $aksi;
            $record[] = '<input type="checkbox" class="chk-tes-item" name="edit-tes-id['.$tid.']" >';

			$output['aaData'][] = $record;
		}
        
		echo json_encode($output);
	}
	
	function get_start() {
		$start = 0;
		if (isset($_GET['iDisplayStart'])) {
			$start = intval($_GET['iDisplayStart']);
			if ($start < 0) $start = 0;
		}
		return $start;
	}

	function get_rows() {
		$rows = 25;
		if (isset($_GET['iDisplayLength'])) {
			$rows = intval($_GET['iDisplayLength']);
			if ($rows < 5 || $rows > 500) $rows = 25;
		}
		return $rows;
	}

	function get_sort_dir() {
		$sort_dir = "ASC";
		if (isset($_GET['sSortDir_0'])) {
			$sdir = strip_tags($_GET['sSortDir_0']);
			if ($sdir != "asc" ) $sort_dir = "DESC";
		}
		return $sort_dir;
	}
}
