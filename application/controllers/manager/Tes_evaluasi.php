<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* ZYA CBT
* Achmad Lutfi
* achmdlutfi@gmail.com
* achmadlutfi.wordpress.com
*/
class Tes_evaluasi extends Member_Controller {
	private $kode_menu = 'tes-evaluasi';
	private $kelompok = 'tes';
	private $url = 'manager/tes_evaluasi';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_user_model');
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_tes_model');
		$this->load->model('cbt_tes_token_model');
		$this->load->model('cbt_tes_topik_set_model');
		$this->load->model('cbt_tes_user_model');
		$this->load->model('cbt_tesgrup_model');
		$this->load->model('cbt_soal_model');
		$this->load->model('cbt_jawaban_model');
		$this->load->model('cbt_tes_soal_model');
		$this->load->model('cbt_tes_soal_jawaban_model');

		parent::cek_akses($this->kode_menu);
	}

    /**
     * Hitung batas nilai minimal & maksimal per soal esai pada suatu tes
     * (mendukung tes Esai murni maupun tes Campuran PG + Esai)
     */
    private function _get_essay_score_bounds($tes_row, $tes_id){
        $min = 0.0;
        $max = (float)$tes_row->tes_score_right;
        if($max <= 0){
            $max = 20.0;
        }
        // Cek apakah tes ini campuran PG + Esai
        $q_tset = $this->cbt_tes_topik_set_model->get_by_kolom('tset_tes_id', (int)$tes_id)->result();
        $jml_pg = 0;
        $jml_esai = 0;
        foreach($q_tset as $ts){
            if((int)$ts->tset_tipe === 1){
                $jml_pg += (int)$ts->tset_jumlah;
            }elseif((int)$ts->tset_tipe === 2){
                $jml_esai += (int)$ts->tset_jumlah;
            }
        }
        if($jml_pg > 0 && $jml_esai > 0){
            // Pada tes campuran, izinkan korektor memberi nilai sampai batas wajar (minimal 20 atau 100/jml_esai)
            $alt_max = round(100 / $jml_esai, 2);
            if($alt_max > $max){
                $max = $alt_max;
            }
        }
        return array('min' => $min, 'max' => round($max, 2));
    }

    /**
     * Ambil daftar seluruh tes yang memiliki paket soal Esai (tipe 2) atau memiliki jawaban esai siswa
     */
    private function _get_essay_tests(){
        $sql = "SELECT t.tes_id, t.tes_nama, t.tes_begin_time, t.tes_score_right, t.tes_max_score,
                    COUNT(DISTINCT tu.tesuser_id) AS jml_peserta,
                    SUM(CASE WHEN s.soal_tipe = 2 AND ts.tessoal_jawaban_text IS NOT NULL AND TRIM(ts.tessoal_jawaban_text) != '' THEN 1 ELSE 0 END) AS jml_terjawab,
                    SUM(CASE WHEN s.soal_tipe = 2 AND ts.tessoal_jawaban_text IS NOT NULL AND TRIM(ts.tessoal_jawaban_text) != '' AND ts.tessoal_comment IS NULL THEN 1 ELSE 0 END) AS jml_belum,
                    SUM(CASE WHEN s.soal_tipe = 2 AND ts.tessoal_comment IS NOT NULL THEN 1 ELSE 0 END) AS jml_sudah
                FROM cbt_tes t
                LEFT JOIN cbt_tes_user tu ON tu.tesuser_tes_id = t.tes_id
                LEFT JOIN cbt_tes_soal ts ON ts.tessoal_tesuser_id = tu.tesuser_id
                LEFT JOIN cbt_soal s ON s.soal_id = ts.tessoal_soal_id
                WHERE EXISTS (
                    SELECT 1 FROM cbt_tes_topik_set tset
                    WHERE tset.tset_tes_id = t.tes_id AND tset.tset_tipe IN (0, 2)
                ) OR EXISTS (
                    SELECT 1 FROM cbt_tes_user tu2
                    JOIN cbt_tes_soal ts2 ON ts2.tessoal_tesuser_id = tu2.tesuser_id
                    JOIN cbt_soal s2 ON s2.soal_id = ts2.tessoal_soal_id
                    WHERE tu2.tesuser_tes_id = t.tes_id AND s2.soal_tipe = 2
                )
                GROUP BY t.tes_id, t.tes_nama, t.tes_begin_time, t.tes_score_right, t.tes_max_score
                ORDER BY t.tes_begin_time DESC, t.tes_id DESC";
        return $this->db->query($sql)->result();
    }
	
    public function index($page=null, $id=null){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        $selected_tes_id = (int)$this->input->get('tes_id', TRUE);
        $essay_tests = $this->_get_essay_tests();

        // Jika belum ada pilihan tes di URL, pilih tes pertama yang punya antrean belum dikoreksi (atau tes pertama)
        if($selected_tes_id <= 0 && !empty($essay_tests)){
            foreach($essay_tests as $et){
                if((int)$et->jml_belum > 0){
                    $selected_tes_id = (int)$et->tes_id;
                    break;
                }
            }
            if($selected_tes_id <= 0){
                $selected_tes_id = (int)$essay_tests[0]->tes_id;
            }
        }

        $select_tes = '<option value="semua"'.($selected_tes_id <= 0 ? ' selected' : '').'>-- Semua Tes Esai --</option>';
        if(!empty($essay_tests)){
        	foreach ($essay_tests as $temp) {
                $sel = ($selected_tes_id === (int)$temp->tes_id) ? ' selected="selected"' : '';
                $belum = (int)$temp->jml_belum;
                $sudah = (int)$temp->jml_sudah;
                $info_tag = '';
                if($belum > 0){
                    $info_tag = ' ['.$belum.' belum dikoreksi]';
                }elseif($sudah > 0){
                    $info_tag = ' [Selesai: '.$sudah.' dikoreksi]';
                }
        		$select_tes .= '<option value="'.(int)$temp->tes_id.'"'.$sel.'>'.html_escape($temp->tes_nama.$info_tag).'</option>';
        	}
        }
        $data['select_tes'] = $select_tes;
        $data['selected_tes_id'] = $selected_tes_id;

        // Daftar Kelas / Group
        $query_group = $this->cbt_user_grup_model->get_group();
        $select_group = '<option value="semua">Semua Kelas</option>';
        if($query_group->num_rows() > 0){
            foreach($query_group->result() as $g){
                $select_group .= '<option value="'.(int)$g->grup_id.'">'.html_escape($g->grup_nama).'</option>';
            }
        }
        $data['select_group'] = $select_group;
        
        $this->template->display_admin($this->kelompok.'/tes_evaluasi_view', 'Evaluasi Jawaban Esai', $data);
    }

    /**
     * JSON ringkasan statistik progres koreksi + daftar Kelas & daftar Soal Esai untuk filter dinamis
     */
    function get_summary(){
        $tes_id  = trim((string)$this->input->get('tes', TRUE));
        $grup_id = trim((string)$this->input->get('grup', TRUE));

        $where = "s.soal_tipe = 2";
        $params = array();
        if($tes_id !== '' && $tes_id !== 'semua' && is_numeric($tes_id)){
            $where .= " AND tu.tesuser_tes_id = ?";
            $params[] = (int)$tes_id;
        }
        if($grup_id !== '' && $grup_id !== 'semua' && is_numeric($grup_id)){
            $where .= " AND u.user_grup_id = ?";
            $params[] = (int)$grup_id;
        }

        $sql_stat = "SELECT
                        COUNT(ts.tessoal_id) AS total_lembar,
                        SUM(CASE WHEN ts.tessoal_jawaban_text IS NOT NULL AND TRIM(ts.tessoal_jawaban_text) != '' AND ts.tessoal_comment IS NULL THEN 1 ELSE 0 END) AS belum_dikoreksi,
                        SUM(CASE WHEN ts.tessoal_comment IS NOT NULL THEN 1 ELSE 0 END) AS sudah_dikoreksi,
                        SUM(CASE WHEN (ts.tessoal_jawaban_text IS NULL OR TRIM(ts.tessoal_jawaban_text) = '') AND ts.tessoal_comment IS NULL THEN 1 ELSE 0 END) AS jawaban_kosong,
                        COUNT(DISTINCT tu.tesuser_user_id) AS total_siswa
                     FROM cbt_tes_user tu
                     JOIN cbt_user u ON u.user_id = tu.tesuser_user_id
                     JOIN cbt_tes_soal ts ON ts.tessoal_tesuser_id = tu.tesuser_id
                     JOIN cbt_soal s ON s.soal_id = ts.tessoal_soal_id
                     WHERE $where";
        $stat = $this->db->query($sql_stat, $params)->row();

        // Daftar butir soal esai pada tes yang dipilih (agar guru bisa koreksi fokus per nomor soal)
        $soal_list = array();
        $where_soal = "s.soal_tipe = 2";
        $params_soal = array();
        if($tes_id !== '' && $tes_id !== 'semua' && is_numeric($tes_id)){
            $where_soal .= " AND tu.tesuser_tes_id = ?";
            $params_soal[] = (int)$tes_id;
        }
        $sql_soal = "SELECT s.soal_id, s.soal_detail,
                        COUNT(ts.tessoal_id) AS total,
                        SUM(CASE WHEN ts.tessoal_jawaban_text IS NOT NULL AND TRIM(ts.tessoal_jawaban_text) != '' AND ts.tessoal_comment IS NULL THEN 1 ELSE 0 END) AS belum
                     FROM cbt_tes_user tu
                     JOIN cbt_tes_soal ts ON ts.tessoal_tesuser_id = tu.tesuser_id
                     JOIN cbt_soal s ON s.soal_id = ts.tessoal_soal_id
                     WHERE $where_soal
                     GROUP BY s.soal_id, s.soal_detail
                     ORDER BY s.soal_id ASC";
        $q_soal = $this->db->query($sql_soal, $params_soal)->result();
        $idx = 1;
        foreach($q_soal as $sr){
            $plain = trim(preg_replace('/\s+/', ' ', strip_tags($sr->soal_detail)));
            if(function_exists('mb_substr') && mb_strlen($plain) > 65){
                $plain = mb_substr($plain, 0, 65).'...';
            }elseif(strlen($plain) > 65){
                $plain = substr($plain, 0, 65).'...';
            }
            $soal_list[] = array(
                'soal_id' => (int)$sr->soal_id,
                'nomor'   => $idx++,
                'cuplikan'=> $plain,
                'total'   => (int)$sr->total,
                'belum'   => (int)$sr->belum
            );
        }

        // Info batas nilai tes jika 1 tes dipilih
        $score_info = array('min' => 0, 'max' => 20);
        if($tes_id !== '' && $tes_id !== 'semua' && is_numeric($tes_id)){
            $qt = $this->cbt_tes_model->get_by_kolom_limit('tes_id', (int)$tes_id, 1);
            if($qt->num_rows() > 0){
                $score_info = $this->_get_essay_score_bounds($qt->row(), (int)$tes_id);
            }
        }

        echo json_encode(array(
            'status' => 1,
            'stat' => array(
                'total_lembar'    => (int)($stat ? $stat->total_lembar : 0),
                'belum_dikoreksi' => (int)($stat ? $stat->belum_dikoreksi : 0),
                'sudah_dikoreksi' => (int)($stat ? $stat->sudah_dikoreksi : 0),
                'jawaban_kosong'  => (int)($stat ? $stat->jawaban_kosong : 0),
                'total_siswa'     => (int)($stat ? $stat->total_siswa : 0)
            ),
            'soal_list'  => $soal_list,
            'score_info' => $score_info
        ));
    }

    /**
     * Simpan nilai 1 jawaban esai (Inline / Modal)
     */
    /**
     * Hitung batas nilai esai (min & max) dari server berdasarkan tessoal_id (tidak mempercayai input klien)
     */
    private function _get_bounds_by_tessoal($tessoal_id, &$bounds_cache = array(), &$tesuser_id_out = 0){
        $tesuser_id_out = 0;
        $row = $this->db->select('ts.tessoal_tesuser_id, t.tes_id, t.tes_score_right, t.tes_max_score')
                        ->from('cbt_tes_soal ts')
                        ->join('cbt_tes_user tu', 'tu.tesuser_id = ts.tessoal_tesuser_id')
                        ->join('cbt_tes t', 't.tes_id = tu.tesuser_tes_id')
                        ->where('ts.tessoal_id', (int)$tessoal_id)
                        ->limit(1)
                        ->get()->row();
        if(!$row){
            return null;
        }
        $tesuser_id_out = (int)$row->tessoal_tesuser_id;
        $tid = (int)$row->tes_id;
        if(!isset($bounds_cache[$tid])){
            $bounds_cache[$tid] = $this->_get_essay_score_bounds($tid, $row->tes_score_right, $row->tes_max_score);
        }
        return $bounds_cache[$tid];
    }

    function simpan_nilai(){
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('evaluasi-testlog-id', 'Soal','required|integer');
        $this->form_validation->set_rules('evaluasi-nilai', 'Nilai','required|numeric');
        
        if($this->form_validation->run() == TRUE){
            $nilai = round((float)$this->input->post('evaluasi-nilai', TRUE), 2);
            $tessoal_id = (int)$this->input->post('evaluasi-testlog-id', TRUE);
            $bounds_cache = array();
            $tu_id = 0;
            $bounds = $this->_get_bounds_by_tessoal($tessoal_id, $bounds_cache, $tu_id);
            if(!$bounds){
                echo json_encode(array('status' => 0, 'pesan' => 'Data jawaban soal tidak ditemukan.'));
                return;
            }
            $nilai_min = (float)$bounds['min'];
            $nilai_max = (float)$bounds['max'];

            if($nilai >= $nilai_min && $nilai <= $nilai_max){
                $korektor = $this->access->get_username();
                $data['tessoal_nilai'] = $nilai;
                $data['tessoal_comment'] = 'Sudah di koreksi '.$korektor;

                $this->cbt_tes_soal_model->update('tessoal_id', $tessoal_id, $data);

                // Hitung total nilai terbaru siswa pada tes ini
                $total_nilai_siswa = null;
                if($tu_id > 0){
                    $q_tot = $this->cbt_tes_soal_model->get_nilai($tu_id);
                    if($q_tot->num_rows() > 0){
                        $total_nilai_siswa = round((float)$q_tot->row()->hasil, 2);
                    }
                }

                $status['status'] = 1;
                $status['tessoal_id'] = $tessoal_id;
                $status['nilai'] = $nilai;
                $status['korektor'] = $korektor;
                $status['total_nilai_siswa'] = $total_nilai_siswa;
                $status['pesan'] = 'Nilai '.$nilai.' berhasil disimpan.';
            }else{
                $status['status'] = 0;
                $status['pesan'] = 'Nilai harus berada di antara '.$nilai_min.' s/d '.$nilai_max.'!';
            }
        }else{
            $status['status'] = 0;
            $status['pesan'] = strip_tags(validation_errors());
        }
        
        echo json_encode($status);
    }

    /**
     * Simpan banyak nilai esai sekaligus dalam 1 transaksi database (Batch Save 1-Klik)
     */
    function simpan_nilai_batch(){
        $raw = $this->input->post('items_json', FALSE);
        $items = json_decode((string)$raw, true);
        if(!is_array($items) || empty($items)){
            echo json_encode(array('status' => 0, 'pesan' => 'Tidak ada data nilai yang dikirim untuk disimpan.'));
            return;
        }

        $korektor = $this->access->get_username();
        $comment = 'Sudah di koreksi '.$korektor;
        $saved = 0;
        $bounds_cache = array();

        $this->db->trans_begin();
        foreach($items as $it){
            $tessoal_id = isset($it['tessoal_id']) ? (int)$it['tessoal_id'] : 0;
            $nilai_raw  = isset($it['nilai']) ? trim((string)$it['nilai']) : '';

            if($tessoal_id <= 0 || $nilai_raw === '' || !is_numeric($nilai_raw)){
                continue;
            }
            $tu_id = 0;
            $bounds = $this->_get_bounds_by_tessoal($tessoal_id, $bounds_cache, $tu_id);
            if(!$bounds){
                continue;
            }
            $min_val = (float)$bounds['min'];
            $max_val = (float)$bounds['max'];
            $nilai = round((float)$nilai_raw, 2);
            if($nilai < $min_val || $nilai > $max_val){
                $this->db->trans_rollback();
                echo json_encode(array(
                    'status' => 0,
                    'pesan'  => 'Terdapat nilai ('.$nilai.') di luar batas yang diizinkan ('.$min_val.' s/d '.$max_val.').'
                ));
                return;
            }

            $this->cbt_tes_soal_model->update('tessoal_id', $tessoal_id, array(
                'tessoal_nilai'   => $nilai,
                'tessoal_comment' => $comment
            ));
            $saved++;
        }

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal menyimpan nilai batch ke database.'));
            return;
        }
        $this->db->trans_commit();

        echo json_encode(array(
            'status' => 1,
            'saved'  => $saved,
            'pesan'  => 'Berhasil menyimpan '.$saved.' nilai esai sekaligus!'
        ));
    }

    function get_datatable(){
		$tes_id        = trim((string)$this->input->get('tes', TRUE));
		$grup_id       = trim((string)$this->input->get('grup', TRUE));
		$status_filter = trim((string)$this->input->get('status_koreksi', TRUE)); // belum | sudah | semua | kosong
		$soal_filter   = trim((string)$this->input->get('soal_id', TRUE));
		$urutkan       = trim((string)$this->input->get('urutkan', TRUE));
		$search        = trim((string)$this->input->get('cari', TRUE));
		if($search === '' && isset($_GET['sSearch']) && $_GET['sSearch'] !== ''){
			$search = trim($_GET['sSearch']);
		}

		$start = $this->get_start();
		$rows  = $this->get_rows();

		$this->db->start_cache();
		$this->db->from('cbt_tes_user tu')
		         ->join('cbt_tes t', 't.tes_id = tu.tesuser_tes_id')
		         ->join('cbt_user u', 'u.user_id = tu.tesuser_user_id')
		         ->join('cbt_user_grup g', 'g.grup_id = u.user_grup_id', 'left')
		         ->join('cbt_tes_soal ts', 'ts.tessoal_tesuser_id = tu.tesuser_id')
		         ->join('cbt_soal s', 's.soal_id = ts.tessoal_soal_id')
		         ->where('s.soal_tipe', 2);

		if($tes_id !== '' && $tes_id !== 'semua' && is_numeric($tes_id)){
			$this->db->where('tu.tesuser_tes_id', (int)$tes_id);
		}
		if($grup_id !== '' && $grup_id !== 'semua' && is_numeric($grup_id)){
			$this->db->where('u.user_grup_id', (int)$grup_id);
		}
		if($soal_filter !== '' && $soal_filter !== 'semua' && is_numeric($soal_filter)){
			$this->db->where('ts.tessoal_soal_id', (int)$soal_filter);
		}

		// Filter status koreksi
		if($status_filter === 'sudah'){
			$this->db->where('ts.tessoal_comment IS NOT NULL', NULL, FALSE);
		}elseif($status_filter === 'kosong'){
			$this->db->where("(ts.tessoal_jawaban_text IS NULL OR TRIM(ts.tessoal_jawaban_text) = '')", NULL, FALSE);
		}elseif($status_filter === 'semua'){
			// tampilkan semua jawaban yang sudah diisi ataupun sudah dikoreksi
			$this->db->where("((ts.tessoal_jawaban_text IS NOT NULL AND TRIM(ts.tessoal_jawaban_text) != '') OR ts.tessoal_comment IS NOT NULL)", NULL, FALSE);
		}elseif($status_filter === 'semua_termasuk_kosong'){
			// tampilkan mutlak seluruh baris termasuk yang belum dijawab
		}else{
			// Default: 'belum' -> sudah dijawab siswa tapi belum dikoreksi
			$this->db->where("ts.tessoal_jawaban_text IS NOT NULL AND TRIM(ts.tessoal_jawaban_text) != ''", NULL, FALSE);
			$this->db->where('ts.tessoal_comment IS NULL', NULL, FALSE);
		}

		if($search !== ''){
			$like = $this->db->escape_like_str($search);
			$this->db->where("(u.user_firstname LIKE '%".$like."%' ESCAPE '!' OR u.user_name LIKE '%".$like."%' ESCAPE '!' OR ts.tessoal_jawaban_text LIKE '%".$like."%' ESCAPE '!' OR s.soal_detail LIKE '%".$like."%' ESCAPE '!')", NULL, FALSE);
		}

		$this->db->stop_cache();
		$iTotal = $this->db->count_all_results();

		$this->db->select('ts.tessoal_id, ts.tessoal_tesuser_id, ts.tessoal_jawaban_text, ts.tessoal_nilai, ts.tessoal_comment, ts.tessoal_order,
		                   t.tes_id, t.tes_nama, t.tes_score_right, t.tes_score_wrong, t.tes_max_score,
		                   u.user_id, u.user_name, u.user_firstname, u.user_detail,
		                   g.grup_nama,
		                   s.soal_id, s.soal_detail, s.soal_kunci');

		if($urutkan === 'user' || $urutkan === 'siswa'){
			$this->db->order_by('g.grup_nama', 'ASC')
			         ->order_by('u.user_firstname', 'ASC')
			         ->order_by('ts.tessoal_order', 'ASC');
		}elseif($urutkan === 'kelas'){
			$this->db->order_by('g.grup_nama', 'ASC')
			         ->order_by('s.soal_id', 'ASC')
			         ->order_by('u.user_firstname', 'ASC');
		}elseif($urutkan === 'nilai_tinggi'){
			$this->db->order_by('ts.tessoal_nilai', 'DESC')
			         ->order_by('u.user_firstname', 'ASC');
		}else{
			// Default: urut per Butir Soal lalu Kelas & Nama Siswa (paling enak untuk koreksi konsisten per nomor)
			$this->db->order_by('s.soal_id', 'ASC')
			         ->order_by('g.grup_nama', 'ASC')
			         ->order_by('u.user_firstname', 'ASC');
		}

		$this->db->limit($rows, $start);
		$query_rows = $this->db->get()->result();
		$this->db->flush_cache();

		// Cache batas nilai per tes_id
		$bounds_cache = array();
		foreach($query_rows as $r){
			$tid = (int)$r->tes_id;
			if(!isset($bounds_cache[$tid])){
				$bounds_cache[$tid] = $this->_get_essay_score_bounds($r, $tid);
			}
		}

		$output = array(
			"sEcho" => isset($_GET['sEcho']) ? intval($_GET['sEcho']) : 1,
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

		$i = $start;
	    foreach ($query_rows as $temp) {			
			$record = array();
			$ts_id = (int)$temp->tessoal_id;
			$tid   = (int)$temp->tes_id;
			$bounds = isset($bounds_cache[$tid]) ? $bounds_cache[$tid] : array('min' => 0, 'max' => 20);
			$min_v = $bounds['min'];
			$max_v = $bounds['max'];

			$is_graded = !empty($temp->tessoal_comment);
			$curr_val  = ($temp->tessoal_nilai !== null) ? round((float)$temp->tessoal_nilai, 2) : 0;
			$val_str   = $is_graded ? rtrim(rtrim(number_format($curr_val, 2, '.', ''), '0'), '.') : '';
			if($is_graded && $val_str === '') $val_str = '0';

			// 1. Kolom No
			$record[] = '<div class="text-center"><strong>'.(++$i).'</strong></div>';

			// 2. Kolom Peserta & Kelas
			$nama_siswa = html_escape(stripslashes($temp->user_firstname));
			$nis_siswa  = html_escape($temp->user_name);
			$kelas_str  = !empty($temp->grup_nama) ? html_escape($temp->grup_nama) : '-';
			$ruang_str  = !empty($temp->user_detail) ? ' &bull; '.html_escape($temp->user_detail) : '';
			$tes_badge  = ($tes_id === 'semua' || $tes_id === '')
				? '<div style="margin-top:4px;"><span class="label label-default" style="font-weight:500;">'.html_escape($temp->tes_nama).'</span></div>'
				: '';

			$status_badge = $is_graded
				? '<span id="badge-status-'.$ts_id.'" class="label label-success" style="display:inline-block;margin-top:4px;"><i class="fa fa-check-circle"></i> Sudah Dikoreksi ('.rtrim(rtrim(number_format($curr_val, 2, '.', ''), '0'), '.').')</span>'
				: '<span id="badge-status-'.$ts_id.'" class="label label-warning" style="display:inline-block;margin-top:4px;"><i class="fa fa-clock-o"></i> Belum Dikoreksi</span>';

			$record[] = '
				<div class="ev-student-box">
					<div style="font-weight:700; font-size:13.5px; color:#1e293b;">'.$nama_siswa.'</div>
					<div class="text-muted" style="font-size:12px;"><code>'.$nis_siswa.'</code></div>
					<div style="margin-top:3px;">
						<span class="label label-info" style="font-size:11px;">'.$kelas_str.$ruang_str.'</span>
					</div>
					'.$tes_badge.'
					<div>'.$status_badge.'</div>
				</div>';

			// 3. Kolom Soal & Jawaban Siswa
			$soal_html = str_replace("[base_url]", base_url(), $temp->soal_detail);
			$kunci_box = '';
			if(!empty($temp->soal_kunci) && trim($temp->soal_kunci) !== ''){
				$kunci_box = '<div class="ev-key-box"><i class="fa fa-key"></i> <strong>Kunci / Rubrik:</strong> '.nl2br(html_escape($temp->soal_kunci)).'</div>';
			}

			$raw_jwb = (string)$temp->tessoal_jawaban_text;
			if(trim($raw_jwb) === ''){
				$jwb_box = '<div class="ev-answer-box is-empty"><i class="fa fa-exclamation-circle"></i> <em>(Siswa tidak mengisi jawaban uraian)</em></div>';
			}else{
				// Escape HTML dengan aman lalu ubah newline jadi <br> agar jawaban siswa berisi < > tetap tampil utuh!
				$safe_jwb = nl2br(htmlspecialchars($raw_jwb, ENT_QUOTES, 'UTF-8'));
				$jwb_box = '<div class="ev-answer-box">'.$safe_jwb.'</div>';
			}

			$record[] = '
				<div class="ev-qa-wrap">
					<div class="ev-question-box">
						<div class="ev-q-header"><span class="badge bg-purple">Soal #'.(int)$temp->tessoal_order.'</span></div>
						<div class="ev-q-body">'.$soal_html.'</div>
						'.$kunci_box.'
					</div>
					<div class="ev-answer-label"><i class="fa fa-pencil-square-o"></i> Jawaban Siswa:</div>
					'.$jwb_box.'
				</div>';

			// 4. Kolom Input Nilai Cepat (Inline + Preset Persentase + Enter/Simpan)
			$p25  = round($max_v * 0.25, 2);
			$p50  = round($max_v * 0.50, 2);
			$p75  = round($max_v * 0.75, 2);
			$p100 = round($max_v, 2);

			$max_disp = rtrim(rtrim(number_format($max_v, 2, '.', ''), '0'), '.');

			$record[] = '
				<div class="ev-grade-box '.($is_graded ? 'is-graded' : 'is-ungraded').'" id="grade-box-'.$ts_id.'" data-id="'.$ts_id.'" data-min="'.$min_v.'" data-max="'.$max_v.'">
					<div class="ev-max-info">Rentang: <b>0 &ndash; '.$max_disp.'</b></div>
					<div class="input-group input-group-sm" style="margin-bottom:6px;">
						<input type="number" step="0.01" min="'.$min_v.'" max="'.$max_v.'"
						       class="form-control ev-score-input"
						       id="input-nilai-'.$ts_id.'"
						       data-id="'.$ts_id.'"
						       data-min="'.$min_v.'"
						       data-max="'.$max_v.'"
						       data-orig="'.html_escape($val_str).'"
						       placeholder="0 - '.$max_disp.'"
						       value="'.html_escape($val_str).'">
						<span class="input-group-btn">
							<button type="button" class="btn btn-success btn-save-inline" id="btn-save-'.$ts_id.'" onclick="simpanInline('.$ts_id.')" title="Simpan Nilai (atau tekan Enter)">
								<i class="fa fa-check"></i> Simpan
							</button>
						</span>
					</div>
					<div class="ev-preset-btns">
						<button type="button" class="btn btn-default btn-xs" onclick="setPresetNilai('.$ts_id.', 0)" title="Kosong / Salah (0)">0</button>
						<button type="button" class="btn btn-default btn-xs" onclick="setPresetNilai('.$ts_id.', '.$p25.')" title="25% ('.$p25.')">25%</button>
						<button type="button" class="btn btn-default btn-xs" onclick="setPresetNilai('.$ts_id.', '.$p50.')" title="Setengah Benar 50% ('.$p50.')">50%</button>
						<button type="button" class="btn btn-default btn-xs" onclick="setPresetNilai('.$ts_id.', '.$p75.')" title="75% ('.$p75.')">75%</button>
						<button type="button" class="btn btn-primary btn-xs" onclick="setPresetNilai('.$ts_id.', '.$p100.')" title=" Nilai Penuh 100% ('.$p100.')">Max ('.$max_disp.')</button>
					</div>
					<div class="ev-save-feedback" id="feedback-'.$ts_id.'">'.($is_graded ? '<small class="text-success"><i class="fa fa-check"></i> '.html_escape($temp->tessoal_comment).'</small>' : '<small class="text-muted">Tekan <b>Enter</b> untuk simpan &amp; lanjut</small>').'</div>
				</div>';

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
