<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* ZYA CBT
* Achmad Lutfi
* achmdlutfi@gmail.com
* achmadlutfi.wordpress.com
*/
class Tes_tambah extends Member_Controller {
	private $kode_menu = 'tes-tambah';
	private $kelompok = 'tes';
	private $url = 'manager/tes_tambah';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_modul_model');
		$this->load->model('cbt_tes_model');
        $this->load->model('cbt_tes_user_model');
		$this->load->model('cbt_tesgrup_model');
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_tes_topik_set_model');
		$this->load->model('cbt_topik_model');
		$this->load->model('cbt_soal_model');

        parent::cek_akses($this->kode_menu);
	}
	
    public function index($tes_id=null){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        $is_edit = 0;
        $selected_groups = array();
        $has_participants = 0;
        if(!empty($tes_id)){
            $tes_id = (int)$tes_id;
        	$query_tes = $this->cbt_tes_model->count_by_kolom('tes_id', $tes_id)->row()->hasil;
        	if($query_tes > 0){
        		$is_edit = 1;
                $q_tg = $this->cbt_tesgrup_model->get_by_kolom('tstgrp_tes_id', $tes_id)->result();
                foreach($q_tg as $tg){
                    $selected_groups[] = (int)$tg->tstgrp_grup_id;
                }
                $has_participants = (int)$this->cbt_tes_user_model->count_by_kolom('tesuser_tes_id', $tes_id)->row()->hasil;
        	}
        }
        $data['is_edit'] = $is_edit;
        $data['edit_tes_id'] = $is_edit ? $tes_id : 0;
        $data['has_participants'] = $has_participants;
        $data['selected_groups'] = $selected_groups;

        // Default tanggal & sesi hari ini
        $today = date('Y-m-d');
        $data['default_tgl_mulai'] = $today;
        $data['default_jam_mulai'] = '07:30';
        $data['default_tgl_selesai'] = $today;
        $data['default_jam_selesai'] = '09:30';
        $data['rentang_waktu'] = $today.' 07:30 - '.$today.' 09:30';

        // Daftar kelas (Group) dengan jumlah siswa
        $sql_grup = "SELECT g.grup_id, g.grup_nama, COUNT(u.user_id) AS jml_peserta
                     FROM cbt_user_grup g
                     LEFT JOIN cbt_user u ON u.user_grup_id = g.grup_id
                     GROUP BY g.grup_id, g.grup_nama
                     ORDER BY g.grup_nama ASC";
        $groups_list = $this->db->query($sql_grup)->result();
        $data['groups_list'] = $groups_list;

        // Backward-compatible select_group
        $select_g = '';
        if(!empty($groups_list)){
            foreach($groups_list as $temp){
                $sel = in_array((int)$temp->grup_id, $selected_groups, true) ? ' selected' : '';
                $select_g .= '<option value="'.(int)$temp->grup_id.'"'.$sel.'>'.html_escape($temp->grup_nama).'</option>';
            }
        }else{
            $select_g = '<option value="0">Tidak Ada Kelas</option>';
        }
        $data['select_group'] = $select_g;

        // Daftar modul (urutkan Modul Aktif di paling atas!)
        $this->cbt_modul_model->ensure_single_aktif();
        $moduls = $this->db->select('modul_id, modul_nama, modul_aktif')
                           ->from('cbt_modul')
                           ->order_by('modul_aktif', 'DESC')
                           ->order_by('modul_id', 'DESC')
                           ->get()->result();
        $select_m = '';
        $active_modul_id = 0;
        $active_modul_nama = 'Belum ada modul aktif';
        if(!empty($moduls)){
            foreach($moduls as $m){
                if($active_modul_id === 0){
                    $active_modul_id = (int)$m->modul_id;
                    $active_modul_nama = $m->modul_nama;
                }
                $tag = ((int)$m->modul_aktif === 1) ? ' [AKTIF]' : '';
                $select_m .= '<option value="'.(int)$m->modul_id.'">'.html_escape($m->modul_nama.$tag).'</option>';
            }
        }else{
            $select_m = '<option value="kosong">Tidak Ada Modul</option>';
        }
        $data['select_modul'] = $select_m;
        $data['active_modul_id'] = $active_modul_id;
        $data['active_modul_nama'] = $active_modul_nama;

        if($is_edit != 0){
        	$data['data_tes'] = "edit('".$tes_id."');";
        }
        
        $title = $is_edit ? 'Edit Jadwal Tes' : 'Buat Jadwal Tes';
        $this->template->display_admin($this->kelompok.'/tes_tambah_view', $title, $data);
    }

    /**
     * JSON detail seluruh topik dalam 1 modul:
     * - jumlah PG, Esai, Singkat, max opsi PG
     * - deteksi otomatis topik pasangan "Esai <Nama Topik>" untuk Auto-Pair 1-klik
     */
    function get_topik_detail_by_modul($modul = null){
        $modul_id = (int)$modul;
        if($modul_id <= 0){
            echo json_encode(array('status' => 0, 'topiks' => array()));
            return;
        }

        $sql = "SELECT t.topik_id, t.topik_nama, t.topik_detail,
                    COUNT(s.soal_id) AS jml_total,
                    SUM(CASE WHEN s.soal_tipe = 1 THEN 1 ELSE 0 END) AS jml_pg,
                    SUM(CASE WHEN s.soal_tipe = 2 THEN 1 ELSE 0 END) AS jml_esai,
                    SUM(CASE WHEN s.soal_tipe = 3 THEN 1 ELSE 0 END) AS jml_singkat,
                    COALESCE(MAX(oj.jml_opsi), 0) AS max_opsi_pg
                FROM cbt_topik t
                LEFT JOIN cbt_soal s ON s.soal_topik_id = t.topik_id
                LEFT JOIN (
                    SELECT jawaban_soal_id, COUNT(*) AS jml_opsi
                    FROM cbt_jawaban
                    GROUP BY jawaban_soal_id
                ) oj ON oj.jawaban_soal_id = s.soal_id AND s.soal_tipe = 1
                WHERE t.topik_modul_id = ?
                GROUP BY t.topik_id, t.topik_nama, t.topik_detail
                ORDER BY t.topik_nama ASC";
        $rows = $this->db->query($sql, array($modul_id))->result();

        // Petakan topik Esai berawalan "Esai ..." untuk auto-pairing
        $esai_map = array(); // lower(base_name) => topik object
        foreach($rows as $r){
            if((int)$r->jml_esai > 0 && preg_match('/^esai[\s\-_:]+(.+)$/i', trim($r->topik_nama), $m)){
                $base_key = strtolower(trim(preg_replace('/\s+/', ' ', $m[1])));
                if(!isset($esai_map[$base_key])){
                    $esai_map[$base_key] = array(
                        'topik_id' => (int)$r->topik_id,
                        'topik_nama' => $r->topik_nama,
                        'jml_esai' => (int)$r->jml_esai
                    );
                }
            }
        }

        $topiks = array();
        foreach($rows as $r){
            $jml_total = (int)$r->jml_total;
            $jml_pg = (int)$r->jml_pg;
            $jml_esai = (int)$r->jml_esai;
            $jml_singkat = (int)$r->jml_singkat;
            $max_opsi = (int)$r->max_opsi_pg;

            if($jml_pg > 0 && $jml_esai === 0 && $jml_singkat === 0){
                $default_tipe = 1;
                $default_jml = $jml_pg;
                $default_opsi = ($max_opsi >= 2) ? $max_opsi : 5;
            }elseif($jml_esai > 0 && $jml_pg === 0 && $jml_singkat === 0){
                $default_tipe = 2;
                $default_jml = $jml_esai;
                $default_opsi = 1;
            }elseif($jml_singkat > 0 && $jml_pg === 0 && $jml_esai === 0){
                $default_tipe = 3;
                $default_jml = $jml_singkat;
                $default_opsi = 1;
            }else{
                $default_tipe = ($jml_pg > 0) ? 1 : 0;
                $default_jml = ($jml_pg > 0) ? $jml_pg : $jml_total;
                $default_opsi = ($max_opsi >= 2) ? $max_opsi : 5;
            }

            // Cek apakah topik PG ini punya pasangan "Esai <Nama>" di modul yang sama
            $paired_esai = null;
            if($jml_pg > 0 && !preg_match('/^esai[\s\-_:]+/i', trim($r->topik_nama))){
                $self_key = strtolower(trim(preg_replace('/\s+/', ' ', $r->topik_nama)));
                if(isset($esai_map[$self_key]) && $esai_map[$self_key]['topik_id'] !== (int)$r->topik_id){
                    $paired_esai = $esai_map[$self_key];
                }
            }

            $topiks[] = array(
                'topik_id' => (int)$r->topik_id,
                'topik_nama' => $r->topik_nama,
                'jml_total' => $jml_total,
                'jml_pg' => $jml_pg,
                'jml_esai' => $jml_esai,
                'jml_singkat' => $jml_singkat,
                'max_opsi_pg' => $max_opsi,
                'default_tipe' => $default_tipe,
                'default_jml' => $default_jml,
                'default_opsi' => $default_opsi,
                'paired_esai' => $paired_esai
            );
        }

        echo json_encode(array(
            'status' => 1,
            'topiks' => $topiks
        ));
    }

    function get_topik_by_modul($modul=null){
        $data['data']=0;

        $data['select_topik'] = '<option value="kosong">Tidak Ada Topik</option>';
        if(!empty($modul)){
            $data['data']=1;
            $query_topik = $this->cbt_topik_model->get_by_kolom('topik_modul_id', (int)$modul);
            if($query_topik->num_rows()){
                $query_topik = $query_topik->result();
                $select = '';
                foreach ($query_topik as $topik) {
                    $jml_soal = $this->cbt_soal_model->count_by_kolom('soal_topik_id', $topik->topik_id)->row()->hasil;
                    $select .= '<option value="'.$topik->topik_id.'">'.html_escape($topik->topik_nama).' ['.$jml_soal.' soal]</option>';
                }
                $data['select_topik'] = $select;
            }
        }

        echo json_encode($data);
    }

    /**
     * Endpoint 1-Halaman Pintar: Buat 1 Tes (atau langsung 2 Tes berpasangan PG + Esai)
     * dalam satu transaksi database yang aman.
     */
    function simpan_cepat(){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('tambah-nama', 'Nama Tes', 'required|trim|strip_tags|max_length[255]');
        $this->form_validation->set_rules('tambah-waktu', 'Durasi Pengerjaan', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('tambah-poin', 'Poin Dasar', 'required|numeric');
        $this->form_validation->set_rules('tambah-poin-salah', 'Poin Salah', 'required|numeric');
        $this->form_validation->set_rules('tambah-poin-kosong', 'Poin Kosong', 'required|numeric');
        $this->form_validation->set_rules('soal-topik', 'Topik / Mata Pelajaran', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('soal-jml', 'Jumlah Soal', 'required|integer|greater_than[0]');

        if($this->form_validation->run() !== TRUE){
            echo json_encode(array('status' => 0, 'pesan' => validation_errors()));
            return;
        }

        $groups = $this->input->post('tambah-group', true);
        if(!is_array($groups) || empty($groups)){
            echo json_encode(array('status' => 0, 'pesan' => 'Pilih minimal satu Kelas (Group) peserta!'));
            return;
        }
        $clean_groups = array();
        foreach($groups as $g){
            $gid = (int)$g;
            if($gid > 0) $clean_groups[$gid] = $gid;
        }
        if(empty($clean_groups)){
            echo json_encode(array('status' => 0, 'pesan' => 'Pilih minimal satu Kelas (Group) peserta!'));
            return;
        }

        // Parse tanggal & jam buka-tutup
        $tgl_mulai   = trim((string)$this->input->post('tgl-mulai', true));
        $jam_mulai   = trim((string)$this->input->post('jam-mulai', true));
        $multi_hari  = $this->input->post('multi-hari', true) ? true : false;
        $tgl_selesai = $multi_hari ? trim((string)$this->input->post('tgl-selesai', true)) : $tgl_mulai;
        $jam_selesai = trim((string)$this->input->post('jam-selesai', true));

        $begin_time = $this->_build_datetime($tgl_mulai, $jam_mulai);
        $end_time   = $this->_build_datetime($tgl_selesai, $jam_selesai);
        if(!$begin_time || !$end_time){
            echo json_encode(array('status' => 0, 'pesan' => 'Format tanggal atau jam buka-tutup tidak valid.'));
            return;
        }
        if(strtotime($end_time) <= strtotime($begin_time)){
            echo json_encode(array('status' => 0, 'pesan' => 'Waktu Selesai (Tutup) harus lebih akhir dari Waktu Mulai (Buka).'));
            return;
        }

        $tes_nama     = trim((string)$this->input->post('tambah-nama', true));
        $tes_detail   = trim((string)$this->input->post('tambah-deskripsi', true));
        if($tes_detail === ''){
            $tes_detail = 'Ujian '.$tes_nama;
        }
        $durasi       = (int)$this->input->post('tambah-waktu', true);
        $poin_benar   = (float)$this->input->post('tambah-poin', true);
        $poin_salah   = (float)$this->input->post('tambah-poin-salah', true);
        $poin_kosong  = (float)$this->input->post('tambah-poin-kosong', true);
        $show_hasil   = $this->input->post('tambah-tunjukkan-hasil', true) ? 1 : 0;
        $detail_hasil = $this->input->post('tambah-detail-hasil', true) ? 1 : 0;
        $pakai_token  = $this->input->post('tambah-token', true) ? 1 : 0;

        $topik_id     = (int)$this->input->post('soal-topik', true);
        $soal_tipe    = (int)$this->input->post('soal-tipe', true);
        $soal_diff    = (int)$this->input->post('soal-kesulitan', true);
        if($soal_diff < 1 || $soal_diff > 5) $soal_diff = 1;
        $soal_jml     = (int)$this->input->post('soal-jml', true);
        $soal_opsi    = (int)$this->input->post('soal-jml-jawaban', true);
        if($soal_tipe === 1 && $soal_opsi < 2) $soal_opsi = 5;
        if($soal_tipe !== 1 && $soal_opsi < 1) $soal_opsi = 1;
        $acak_soal    = $this->input->post('soal-acak-soal', true) ? 1 : 0;
        $acak_jawaban = ($soal_tipe === 2 || $soal_tipe === 3) ? 0 : ($this->input->post('soal-acak-jawaban', true) ? 1 : 0);

        // Validasi ketersediaan soal pada topik utama
        $avail_main = $this->_count_soal_available($topik_id, $soal_tipe);
        if($avail_main <= 0){
            echo json_encode(array('status' => 0, 'pesan' => 'Topik yang dipilih belum memiliki soal dengan tipe tersebut.'));
            return;
        }
        if($soal_jml > $avail_main){
            echo json_encode(array('status' => 0, 'pesan' => 'Jumlah soal diminta ('.$soal_jml.') melebihi soal tersedia di topik ('.$avail_main.').'));
            return;
        }

        // Cek nama tes unik (case-insensitive)
        if($this->_tes_name_exists($tes_nama)){
            echo json_encode(array('status' => 0, 'pesan' => 'Nama Tes "'.html_escape($tes_nama).'" sudah digunakan. Gunakan nama lain.'));
            return;
        }

        // Validasi Mode Pasangan Esai ('pisah' | 'gabung' | 'utama_saja')
        $pair_mode = trim((string)$this->input->post('pair-mode', true));
        if($pair_mode === ''){
            $pair_mode = $this->input->post('pair-esai-aktif', true) ? 'pisah' : 'utama_saja';
        }
        $pair_data = null;
        if($pair_mode === 'pisah' || $pair_mode === 'gabung'){
            $pair_topik_id = (int)$this->input->post('pair-esai-topik-id', true);
            $pair_nama     = trim((string)$this->input->post('pair-esai-nama', true));
            $pair_durasi   = (int)$this->input->post('pair-esai-durasi', true);
            $pair_jml      = (int)$this->input->post('pair-esai-jml', true);
            $pair_poin     = (float)$this->input->post('pair-esai-poin', true);
            $pair_jadwal   = trim((string)$this->input->post('pair-esai-jadwal', true)); // 'sama' | 'menyusul'
            $pair_acak     = $this->input->post('pair-esai-acak', true) ? 1 : 0;

            if($pair_topik_id <= 0 || $pair_jml <= 0){
                echo json_encode(array('status' => 0, 'pesan' => 'Data pasangan Topik Esai belum lengkap.'));
                return;
            }
            $avail_esai = $this->_count_soal_available($pair_topik_id, 2);
            if($pair_jml > $avail_esai){
                echo json_encode(array('status' => 0, 'pesan' => 'Jumlah soal Esai diminta ('.$pair_jml.') melebihi soal Esai tersedia ('.$avail_esai.').'));
                return;
            }

            if($pair_mode === 'pisah'){
                if($pair_nama === '' || $pair_durasi <= 0){
                    echo json_encode(array('status' => 0, 'pesan' => 'Nama dan durasi Tes Esai pasangan wajib diisi.'));
                    return;
                }
                if(strtolower($pair_nama) === strtolower($tes_nama)){
                    echo json_encode(array('status' => 0, 'pesan' => 'Nama Tes Esai pasangan tidak boleh sama persis dengan Nama Tes PG.'));
                    return;
                }
                if($this->_tes_name_exists($pair_nama)){
                    echo json_encode(array('status' => 0, 'pesan' => 'Nama Tes Esai "'.html_escape($pair_nama).'" sudah digunakan. Gunakan nama lain.'));
                    return;
                }

                if($pair_jadwal === 'menyusul'){
                    $pair_begin = date('Y-m-d H:i:s', strtotime($begin_time) + ($durasi * 60));
                    $pair_end_ts = max(strtotime($end_time), strtotime($pair_begin) + ($pair_durasi * 60) + 1800);
                    $pair_end = date('Y-m-d H:i:s', $pair_end_ts);
                }else{
                    $pair_begin = $begin_time;
                    $pair_end   = $end_time;
                }
            }else{
                $pair_begin = $begin_time;
                $pair_end   = $end_time;
            }

            $pair_data = array(
                'mode'      => $pair_mode,
                'topik_id'  => $pair_topik_id,
                'nama'      => $pair_nama,
                'detail'    => 'Ujian Esai - '.$tes_nama,
                'durasi'    => $pair_durasi > 0 ? $pair_durasi : 30,
                'jml'       => $pair_jml,
                'poin'      => $pair_poin > 0 ? $pair_poin : 20,
                'begin'     => $pair_begin,
                'end'       => $pair_end,
                'acak_soal' => $pair_acak
            );
        }

        // Eksekusi simpan dalam 1 transaksi database aman
        $this->db->trans_begin();

        $total_soal_main = ($pair_data !== null && $pair_data['mode'] === 'gabung')
            ? ($soal_jml + $pair_data['jml'])
            : $soal_jml;
        $main_max = round($total_soal_main * $poin_benar, 2);

        $main_tes_id = $this->cbt_tes_model->save(array(
            'tes_nama' => $tes_nama,
            'tes_detail' => $tes_detail,
            'tes_begin_time' => $begin_time,
            'tes_end_time' => $end_time,
            'tes_duration_time' => $durasi,
            'tes_score_right' => $poin_benar,
            'tes_score_wrong' => $poin_salah,
            'tes_score_unanswered' => $poin_kosong,
            'tes_max_score' => $main_max,
            'tes_results_to_users' => $show_hasil,
            'tes_detail_to_users' => $detail_hasil,
            'tes_token' => $pakai_token
        ));

        foreach($clean_groups as $gid){
            $this->cbt_tesgrup_model->save(array(
                'tstgrp_tes_id' => $main_tes_id,
                'tstgrp_grup_id' => $gid
            ));
        }

        $this->cbt_tes_topik_set_model->save(array(
            'tset_tes_id' => $main_tes_id,
            'tset_topik_id' => $topik_id,
            'tset_tipe' => $soal_tipe,
            'tset_difficulty' => $soal_diff,
            'tset_jumlah' => $soal_jml,
            'tset_jawaban' => $soal_opsi,
            'tset_acak_soal' => $acak_soal,
            'tset_acak_jawaban' => $acak_jawaban
        ));

        $pair_tes_id = 0;
        if($pair_data !== null && $pair_data['mode'] === 'gabung'){
            // Gabungkan paket Esai ke dalam 1 kartu Tes yang sama
            $this->cbt_tes_topik_set_model->save(array(
                'tset_tes_id' => $main_tes_id,
                'tset_topik_id' => $pair_data['topik_id'],
                'tset_tipe' => 2,
                'tset_difficulty' => 1,
                'tset_jumlah' => $pair_data['jml'],
                'tset_jawaban' => 1,
                'tset_acak_soal' => $pair_data['acak_soal'],
                'tset_acak_jawaban' => 0
            ));
        }elseif($pair_data !== null && $pair_data['mode'] === 'pisah'){
            $pair_max = round($pair_data['jml'] * $pair_data['poin'], 2);
            $pair_tes_id = $this->cbt_tes_model->save(array(
                'tes_nama' => $pair_data['nama'],
                'tes_detail' => $pair_data['detail'],
                'tes_begin_time' => $pair_data['begin'],
                'tes_end_time' => $pair_data['end'],
                'tes_duration_time' => $pair_data['durasi'],
                'tes_score_right' => $pair_data['poin'],
                'tes_score_wrong' => 0,
                'tes_score_unanswered' => 0,
                'tes_max_score' => $pair_max,
                'tes_results_to_users' => 0,
                'tes_detail_to_users' => 0,
                'tes_token' => $pakai_token
            ));

            foreach($clean_groups as $gid){
                $this->cbt_tesgrup_model->save(array(
                    'tstgrp_tes_id' => $pair_tes_id,
                    'tstgrp_grup_id' => $gid
                ));
            }

            $this->cbt_tes_topik_set_model->save(array(
                'tset_tes_id' => $pair_tes_id,
                'tset_topik_id' => $pair_data['topik_id'],
                'tset_tipe' => 2,
                'tset_difficulty' => 1,
                'tset_jumlah' => $pair_data['jml'],
                'tset_jawaban' => 1,
                'tset_acak_soal' => $pair_data['acak_soal'],
                'tset_acak_jawaban' => 0
            ));
        }

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal menyimpan jadwal tes ke database. Seluruh perubahan dibatalkan.'));
            return;
        }

        $this->db->trans_commit();

        if($pair_tes_id > 0){
            $pesan = 'Berhasil membuat 2 jadwal tes terpisah: "'.$tes_nama.'" (PG) & "'.$pair_data['nama'].'" (Esai)!';
        }elseif($pair_data !== null && $pair_data['mode'] === 'gabung'){
            $pesan = 'Berhasil membuat 1 jadwal tes gabungan (PG + Esai): "'.$tes_nama.'"!';
        }else{
            $pesan = 'Jadwal tes "'.$tes_nama.'" berhasil dibuat!';
        }

        echo json_encode(array(
            'status' => 1,
            'pesan' => $pesan,
            'tes_id' => (int)$main_tes_id,
            'pair_tes_id' => (int)$pair_tes_id,
            'summary' => array(
                'nama' => $tes_nama,
                'pair_nama' => ($pair_tes_id > 0) ? $pair_data['nama'] : '',
                'mode' => ($pair_tes_id > 0) ? 'pisah' : (($pair_data !== null && $pair_data['mode'] === 'gabung') ? 'gabung' : 'tunggal'),
                'waktu' => substr($begin_time, 0, 16).' s/d '.substr($end_time, 11, 5),
                'jml_kelas' => count($clean_groups)
            )
        ));
    }

    private function _build_datetime($tgl, $jam){
        if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl)) return false;
        if(!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $jam)) return false;
        if(strlen($jam) === 5) $jam .= ':00';
        return $tgl.' '.$jam;
    }

    private function _count_soal_available($topik_id, $tipe){
        $this->db->select('COUNT(*) AS hasil')->from('cbt_soal')->where('soal_topik_id', (int)$topik_id);
        if((int)$tipe > 0){
            $this->db->where('soal_tipe', (int)$tipe);
        }
        return (int)$this->db->get()->row()->hasil;
    }

    private function _tes_name_exists($nama, $exclude_id = 0){
        $this->db->select('COUNT(*) AS hasil')
                 ->from('cbt_tes')
                 ->where('LOWER(tes_nama)', strtolower(trim($nama)));
        if((int)$exclude_id > 0){
            $this->db->where('tes_id !=', (int)$exclude_id);
        }
        return ((int)$this->db->get()->row()->hasil > 0);
    }

    /**
     * Endpoint simpan/update tes (dipakai untuk mode Edit & backward-compatible)
     */
    function tambah_tes(){
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('tambah-nama', 'Nama Tes','required|trim|strip_tags|max_length[255]');
        $this->form_validation->set_rules('tambah-waktu', 'Waktu Pengerjaan Tes','required|integer|greater_than[0]');
        $this->form_validation->set_rules('tambah-group[]', 'Kelas (Group)','required|strip_tags');
        $this->form_validation->set_rules('tambah-poin', 'Poin Dasar','required|numeric|strip_tags');
        $this->form_validation->set_rules('tambah-poin-salah', 'Poin Jawaban Salah','required|numeric|strip_tags');
        $this->form_validation->set_rules('tambah-poin-kosong', 'Poin Jawaban Kosong','required|numeric|strip_tags');
        
        if($this->form_validation->run() == TRUE){
        	$tes_id = (int)$this->input->post('tambah-id', true);
        	$nama_lama = trim((string)$this->input->post('tambah-nama-lama', true));
            $data['tes_nama'] = trim((string)$this->input->post('tambah-nama', true));
            $desk = trim((string)$this->input->post('tambah-deskripsi', true));
            $data['tes_detail'] = ($desk !== '') ? $desk : 'Ujian '.$data['tes_nama'];
            $data['tes_duration_time'] = (int)$this->input->post('tambah-waktu', true);
            $data['tes_score_right'] = (float)$this->input->post('tambah-poin', true);
            $data['tes_score_wrong'] = (float)$this->input->post('tambah-poin-salah', true);
            $data['tes_score_unanswered'] = (float)$this->input->post('tambah-poin-kosong', true);

            $data['tes_results_to_users'] = $this->input->post('tambah-tunjukkan-hasil', true) ? 1 : 0;
            $data['tes_detail_to_users']  = $this->input->post('tambah-detail-hasil', true) ? 1 : 0;
            $data['tes_token']            = $this->input->post('tambah-token', true) ? 1 : 0;

            // Dukung input tanggal+jam baru maupun format rentang lama
            $tgl_mulai = trim((string)$this->input->post('tgl-mulai', true));
            $jam_mulai = trim((string)$this->input->post('jam-mulai', true));
            if($tgl_mulai !== '' && $jam_mulai !== ''){
                $multi_hari  = $this->input->post('multi-hari', true) ? true : false;
                $tgl_selesai = $multi_hari ? trim((string)$this->input->post('tgl-selesai', true)) : $tgl_mulai;
                $jam_selesai = trim((string)$this->input->post('jam-selesai', true));
                $begin = $this->_build_datetime($tgl_mulai, $jam_mulai);
                $end   = $this->_build_datetime($tgl_selesai, $jam_selesai);
                if(!$begin || !$end || strtotime($end) <= strtotime($begin)){
                    echo json_encode(array('status' => 0, 'pesan' => 'Rentang tanggal & jam tidak valid (Waktu Tutup harus setelah Waktu Buka).'));
                    return;
                }
                $data['tes_begin_time'] = $begin;
                $data['tes_end_time']   = $end;
            }else{
                $rentang_waktu = $this->input->post('tambah-rentang-waktu', true);
                $tanggal = explode(" - ", (string)$rentang_waktu);
                if(count($tanggal) !== 2){
                    echo json_encode(array('status' => 0, 'pesan' => 'Rentang waktu tes wajib diisi.'));
                    return;
                }
                $data['tes_begin_time'] = trim($tanggal[0]);
                $data['tes_end_time']   = trim($tanggal[1]);
            }

            if($this->_tes_name_exists($data['tes_nama'], $tes_id)){
            	$status['status'] = 0;
            	$status['pesan'] = 'Nama Tes sudah digunakan, silakan gunakan nama lain.';
            }else{
                $is_process = 1;
            	if($tes_id <= 0){
            		$tes_id = $this->cbt_tes_model->save($data);
            	}else{
                    if($this->cbt_tes_user_model->count_by_kolom('tesuser_tes_id', $tes_id)->row()->hasil > 0){
                        // Jika sudah ada peserta, tetap izinkan ubah rentang waktu, durasi, & status token dengan aman
                        $data_safe = array(
                            'tes_begin_time' => $data['tes_begin_time'],
                            'tes_end_time' => $data['tes_end_time'],
                            'tes_duration_time' => $data['tes_duration_time'],
                            'tes_token' => $data['tes_token']
                        );
                        $this->cbt_tes_model->update('tes_id', $tes_id, $data_safe);

                        $status['status'] = 1;
                        $status['tes_id'] = $tes_id;
                        $status['tes_nama'] = $nama_lama ? $nama_lama : $data['tes_nama'];
                        $status['pesan'] = 'Jadwal waktu, durasi & token berhasil diperbarui (pengaturan soal/skor dikunci karena sudah ada peserta).';
                        $is_process = 0;
                    }else{
                        $this->cbt_tes_model->update('tes_id', $tes_id, $data);
                    }
            	}

                if($is_process == 1){
                    $groups = $this->input->post('tambah-group', true);
                    $this->cbt_tesgrup_model->delete('tstgrp_tes_id', $tes_id);
                    foreach ($groups as $group) {
                        $gid = (int)$group;
                        if($gid > 0){
                            $this->cbt_tesgrup_model->save(array(
                                'tstgrp_tes_id' => $tes_id,
                                'tstgrp_grup_id' => $gid
                            ));
                        }
                    }

                    $data_tes['tes_max_score'] = $this->hitung_skor($tes_id);
                    $this->cbt_tes_model->update('tes_id', $tes_id, $data_tes);

                    $status['status'] = 1;
                    $status['tes_id'] = $tes_id;
                    $status['tes_nama'] = $data['tes_nama'];
                    $status['max_score'] = $data_tes['tes_max_score'];
                    $status['pesan'] = 'Perubahan Tes berhasil disimpan.';
                }
            }
        }else{
            $status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        
        echo json_encode($status);
    }

    function tambah_soal(){
    	$this->load->library('form_validation');
        
        $this->form_validation->set_rules('soal-tes-id', 'Tes','required|integer|greater_than[0]');
        $this->form_validation->set_rules('soal-topik', 'Topik','required|integer|greater_than[0]');
        $this->form_validation->set_rules('soal-jml', 'Jumlah Soal','required|greater_than[0]|integer');
        $this->form_validation->set_rules('soal-tipe', 'Tipe Soal','required|integer');
        $this->form_validation->set_rules('soal-kesulitan', 'Tingkat Kesulitan','required|integer');
        $this->form_validation->set_rules('soal-jml-jawaban', 'Jumlah Jawaban','required|greater_than[0]|integer');
        
        if($this->form_validation->run() == TRUE){
        	$data['tset_tes_id'] = (int)$this->input->post('soal-tes-id', true);
        	$data['tset_topik_id'] = (int)$this->input->post('soal-topik', true);
        	$data['tset_tipe'] = (int)$this->input->post('soal-tipe', true);
        	$data['tset_difficulty'] = (int)$this->input->post('soal-kesulitan', true);
        	$data['tset_jumlah'] = (int)$this->input->post('soal-jml', true);
            $data['tset_jawaban'] = (int)$this->input->post('soal-jml-jawaban', true);
            if($data['tset_tipe'] === 1 && $data['tset_jawaban'] < 2){
                $data['tset_jawaban'] = 5;
            }
            $data['tset_acak_soal'] = $this->input->post('soal-acak-soal', true) ? 1 : 0;
            $data['tset_acak_jawaban'] = ($data['tset_tipe'] === 2 || $data['tset_tipe'] === 3) ? 0 : ($this->input->post('soal-acak-jawaban', true) ? 1 : 0);

            if($this->cbt_tes_user_model->count_by_kolom('tesuser_tes_id', $data['tset_tes_id'])->row()->hasil > 0){
                $status['status'] = 0;
                $status['pesan'] = 'Data Soal tidak bisa ditambah karena Tes sudah dikerjakan peserta.';
            }else{
                $jml_soal = $this->_count_soal_available($data['tset_topik_id'], $data['tset_tipe']);

                if($data['tset_jumlah'] <= $jml_soal){
                    if($this->cbt_tes_topik_set_model->count_by_test_topik($data['tset_tes_id'], $data['tset_topik_id'])->row()->hasil > 0){
                        $status['status'] = 0;
                        $status['pesan'] = 'Topik soal tersebut sudah ada pada Tes ini.';
                    }else{
                        $this->cbt_tes_topik_set_model->save($data);

                        $data_tes['tes_max_score'] = $this->hitung_skor($data['tset_tes_id']);
                        $this->cbt_tes_model->update('tes_id', $data['tset_tes_id'], $data_tes);

                        $status['status'] = 1;
                        $status['pesan'] = 'Soal berhasil ditambahkan';
                        $status['max_score'] = $data_tes['tes_max_score'];
                    }
                }else{
                    $status['status'] = 0;
                    $status['pesan'] = 'Jumlah soal diminta ('.$data['tset_jumlah'].') melebihi soal tersedia pada topik ('.$jml_soal.').';
                }
            }   
        }else{
        	$status['status'] = 0;
            $status['pesan'] = validation_errors();
        }

        echo json_encode($status);
    }

    function hapus_soal_by_id($id=null){
    	$data['data'] = 0;
		if(!empty($id)){
            $q = $this->cbt_tes_topik_set_model->get_by_kolom_limit('tset_id', (int)$id, 1);
            if($q->num_rows() > 0){
                $query_soal = $q->row();
                if($this->cbt_tes_user_model->count_by_kolom('tesuser_tes_id', $query_soal->tset_tes_id)->row()->hasil > 0){
                    $data['data'] = 0;
                    $data['pesan'] = 'Data Soal tidak bisa dihapus karena Tes sudah dikerjakan peserta.';
                }else{
                    $this->cbt_tes_topik_set_model->delete('tset_id', (int)$id);
                    $data['data'] = 1;
                    $data['pesan'] = 'Data Soal berhasil dihapus';

                    $data_tes['tes_max_score'] = $this->hitung_skor($query_soal->tset_tes_id);
                    $this->cbt_tes_model->update('tes_id', $query_soal->tset_tes_id, $data_tes);
                    $data['max_score'] = $data_tes['tes_max_score'];
                }
            }
		}
		echo json_encode($data);
    }

    private function hitung_skor($tes_id=null){
        $max_score = 0;
        if(!empty($tes_id)){
            $query_tes = $this->cbt_tes_model->get_by_kolom_limit('tes_id', (int)$tes_id, 1);
            if($query_tes->num_rows()>0){
                $query_tes = $query_tes->row();
                $query_soal = $this->cbt_tes_topik_set_model->get_by_kolom('tset_tes_id', (int)$tes_id)->result();
                foreach ($query_soal as $soal) {
                    $max_score = $max_score + ($soal->tset_jumlah * $query_tes->tes_score_right);
                }
            }
        }
        return round($max_score, 2);
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
	            $data['max_score'] = $query->tes_max_score;
	            $data['tunjukkan_hasil'] = $query->tes_results_to_users;
                $data['detail_hasil'] = $query->tes_detail_to_users;
	            $data['token'] = $query->tes_token;
	            $data['begin_time'] = $query->tes_begin_time;
	            $data['end_time'] = $query->tes_end_time;
	            $data['tgl_mulai'] = substr($query->tes_begin_time, 0, 10);
	            $data['jam_mulai'] = substr($query->tes_begin_time, 11, 5);
	            $data['tgl_selesai'] = substr($query->tes_end_time, 0, 10);
	            $data['jam_selesai'] = substr($query->tes_end_time, 11, 5);
	            $data['rentang_waktu'] = $query->tes_begin_time.' - '.$query->tes_end_time;
			}
		}
		echo json_encode($data);
    }
    
    function get_datatable_soal(){
    	$tes_id = (int)$this->input->get('tes-id');
		$search = "";
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}
		$start = $this->get_start();
		$rows = $this->get_rows();

		$query = $this->cbt_tes_topik_set_model->get_datatable($start, $rows, $tes_id);
		$iTotal= $this->cbt_tes_topik_set_model->get_datatable_count($tes_id)->row()->hasil;
	    
		$output = array(
			"sEcho" => isset($_GET['sEcho']) ? intval($_GET['sEcho']) : 1,
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

		$i=$start;
		$query = $query->result();
	    foreach ($query as $temp) {			
			$record = array();
			$record[] = ++$i;

			$query_topik = $this->cbt_topik_model->get_by_kolom_limit('topik_id', $temp->tset_topik_id, 1)->row();
			$query_modul = $this->cbt_modul_model->get_by_kolom_limit('modul_id', $query_topik->topik_modul_id, 1);

			$tipe_lbl = 'Semua';
			if((int)$temp->tset_tipe === 1) $tipe_lbl = '<span class="label label-success">PG</span>';
			elseif((int)$temp->tset_tipe === 2) $tipe_lbl = '<span class="label label-primary" style="background:#4f46e5;">ESAI</span>';
			elseif((int)$temp->tset_tipe === 3) $tipe_lbl = '<span class="label label-warning">Singkat</span>';

			$ket_acak = ($temp->tset_acak_soal == 1 ? 'Acak Soal' : 'Urut');
			if((int)$temp->tset_tipe === 1){
				$ket_acak .= ' &bull; '.($temp->tset_acak_jawaban == 1 ? 'Acak Opsi' : 'Opsi Tetap').' ('.$temp->tset_jawaban.' opsi)';
			}

			$modul_str = ($query_modul->num_rows() > 0) ? '<small class="text-muted">'.html_escape($query_modul->row()->modul_nama).'</small><br>' : '';
			$record[] = $modul_str.'<strong>'.html_escape($query_topik->topik_nama).'</strong> '.$tipe_lbl.'<br><small>'.$temp->tset_jumlah.' soal &bull; '.$ket_acak.'</small>';
            $record[] = '<button type="button" onclick="hapus_soal(\''.$temp->tset_id.'\')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Hapus</button>';

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
		$rows = 10;
		if (isset($_GET['iDisplayLength'])) {
			$rows = intval($_GET['iDisplayLength']);
			if ($rows < 5 || $rows > 500) $rows = 10;
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
