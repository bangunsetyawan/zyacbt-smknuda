<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Peserta_daftar extends Member_Controller {
	private $kode_menu = 'peserta-daftar';
	private $kelompok = 'peserta';
	private $url = 'manager/peserta_daftar';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_user_model');
		$this->load->model('cbt_tesgrup_model');
		$this->load->library('peserta_importer');

		parent::cek_akses($this->kode_menu);
	}

    private function _get_groups_summary(){
        $sql = "SELECT g.grup_id, g.grup_nama,
                    COUNT(DISTINCT u.user_id) AS jml_peserta,
                    COUNT(DISTINCT tg.tstgrp_tes_id) AS jml_tes
                FROM cbt_user_grup g
                LEFT JOIN cbt_user u ON u.user_grup_id = g.grup_id
                LEFT JOIN cbt_tesgrup tg ON tg.tstgrp_grup_id = g.grup_id
                GROUP BY g.grup_id, g.grup_nama
                ORDER BY g.grup_nama ASC";
        $rows = $this->db->query($sql)->result();
        $total_peserta = 0;
        $groups = array();
        foreach($rows as $r){
            $jp = (int)$r->jml_peserta;
            $jt = (int)$r->jml_tes;
            $total_peserta += $jp;
            $groups[] = array(
                'grup_id' => (int)$r->grup_id,
                'grup_nama' => $r->grup_nama,
                'jml_peserta' => $jp,
                'jml_tes' => $jt
            );
        }
        return array(
            'groups' => $groups,
            'total_peserta' => $total_peserta
        );
    }
	
    public function index(){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        $summary = $this->_get_groups_summary();
        $data['groups'] = $summary['groups'];
        $data['total_peserta'] = $summary['total_peserta'];

        if(!empty($summary['groups'])){
        	$select = '';
        	foreach ($summary['groups'] as $temp) {
        		$select .= '<option value="'.$temp['grup_id'].'">'.html_escape($temp['grup_nama']).'</option>';
        	}
        }else{
        	$select = '<option value="">Belum ada kelas</option>';
        }
        $data['select_group'] = $select;
        
        $this->template->display_admin($this->kelompok.'/peserta_daftar_view', 'Peserta & Kelas', $data);
    }

    /**
     * JSON daftar kelas + jumlah peserta & tes (untuk refresh realtime UI)
     */
    function group_list(){
        $summary = $this->_get_groups_summary();
        echo json_encode(array(
            'status' => 1,
            'groups' => $summary['groups'],
            'total_peserta' => $summary['total_peserta']
        ));
    }

    function group_tambah(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tambah-group', 'Nama Kelas (Group)', 'required|trim|strip_tags|max_length[255]');

        if($this->form_validation->run() == TRUE){
            $nama = preg_replace('/\s+/', ' ', trim((string)$this->input->post('tambah-group', true)));
            if($nama === ''){
                echo json_encode(array('status' => 0, 'pesan' => 'Nama Kelas tidak boleh kosong.'));
                return;
            }
            $cek = $this->db->select('COUNT(*) AS hasil')
                            ->from('cbt_user_grup')
                            ->where('LOWER(grup_nama)', strtolower($nama))
                            ->get()->row()->hasil;
            if($cek > 0){
                $status['status'] = 0;
                $status['pesan'] = 'Nama Kelas "'.html_escape($nama).'" sudah ada!';
            }else{
                $this->cbt_user_grup_model->save(array('grup_nama' => $nama));
                $status['status'] = 1;
                $status['pesan'] = 'Kelas "'.html_escape($nama).'" berhasil ditambahkan.';
            }
        }else{
            $status['status'] = 0;
            $status['pesan'] = strip_tags(validation_errors());
        }
        echo json_encode($status);
    }

    function group_edit(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('edit-grup-id', 'ID Kelas', 'required|integer');
        $this->form_validation->set_rules('edit-grup-nama', 'Nama Kelas (Group)', 'required|trim|strip_tags|max_length[255]');

        if($this->form_validation->run() == TRUE){
            $id = (int)$this->input->post('edit-grup-id', true);
            $nama = preg_replace('/\s+/', ' ', trim((string)$this->input->post('edit-grup-nama', true)));

            $existing = $this->cbt_user_grup_model->get_by_kolom_limit('grup_id', $id, 1);
            if($existing->num_rows() == 0){
                echo json_encode(array('status' => 0, 'pesan' => 'Kelas tidak ditemukan.'));
                return;
            }

            $cek = $this->db->select('COUNT(*) AS hasil')
                            ->from('cbt_user_grup')
                            ->where('LOWER(grup_nama)', strtolower($nama))
                            ->where('grup_id !=', $id)
                            ->get()->row()->hasil;
            if($cek > 0){
                $status['status'] = 0;
                $status['pesan'] = 'Nama Kelas "'.html_escape($nama).'" sudah digunakan kelas lain!';
            }else{
                $this->cbt_user_grup_model->update('grup_id', $id, array('grup_nama' => $nama));
                $status['status'] = 1;
                $status['pesan'] = 'Nama Kelas berhasil diperbarui.';
            }
        }else{
            $status['status'] = 0;
            $status['pesan'] = strip_tags(validation_errors());
        }
        echo json_encode($status);
    }

    function group_hapus(){
        $id = (int)$this->input->post('hapus-grup-id', true);
        $confirm_cascade = $this->input->post('confirm-cascade', true);

        if($id <= 0){
            echo json_encode(array('status' => 0, 'pesan' => 'ID Kelas tidak valid.'));
            return;
        }

        $q = $this->cbt_user_grup_model->get_by_kolom_limit('grup_id', $id, 1);
        if($q->num_rows() == 0){
            echo json_encode(array('status' => 0, 'pesan' => 'Kelas tidak ditemukan.'));
            return;
        }
        $grup_nama = $q->row()->grup_nama;

        // Proteksi 1: Kelas yang dipakai jadwal tes tidak boleh dihapus
        $jml_tes = (int)$this->cbt_tesgrup_model->count_by_kolom('tstgrp_grup_id', $id)->row()->hasil;
        if($jml_tes > 0){
            echo json_encode(array(
                'status' => 0,
                'pesan' => 'Kelas "'.html_escape($grup_nama).'" tidak dapat dihapus karena masih terhubung dengan '.$jml_tes.' jadwal Tes!'
            ));
            return;
        }

        // Proteksi 2: DB memakai ON DELETE CASCADE ke cbt_user, cegah penghapusan tidak sengaja!
        $jml_peserta = (int)$this->cbt_user_model->count_by_kolom('user_grup_id', $id)->row()->hasil;
        if($jml_peserta > 0 && $confirm_cascade !== '1'){
            echo json_encode(array(
                'status' => 0,
                'need_confirm' => 1,
                'jml_peserta' => $jml_peserta,
                'pesan' => 'Kelas "'.html_escape($grup_nama).'" masih memiliki '.$jml_peserta.' peserta. Menghapus kelas ini akan ikut menghapus seluruh peserta & hasil tes di dalamnya.'
            ));
            return;
        }

        $this->cbt_user_grup_model->delete('grup_id', $id);
        echo json_encode(array(
            'status' => 1,
            'pesan' => 'Kelas "'.html_escape($grup_nama).'" berhasil dihapus.'
        ));
    }

    function download_template(){
        $this->peserta_importer->download_template();
    }

    function import_preview(){
        $res = $this->peserta_importer->parse_upload('file-excel');
        echo json_encode($res);
    }

    function import_simpan(){
        $token = $this->input->post('token', true);
        $duplicate_mode = $this->input->post('duplicate_mode', true);
        $res = $this->peserta_importer->commit_import($token, $duplicate_mode);
        echo json_encode($res);
    }

    function tambah(){
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('tambah-username', 'Username','required|trim|strip_tags|max_length[255]');
        $this->form_validation->set_rules('tambah-password', 'Password','required|trim|strip_tags|max_length[255]');
        $this->form_validation->set_rules('tambah-nama', 'Nama Lengkap','required|trim|strip_tags|max_length[255]');
        $this->form_validation->set_rules('tambah-email', 'Email','trim|strip_tags|max_length[255]');
		$this->form_validation->set_rules('tambah-detail', 'Keterangan','trim|strip_tags|max_length[25]');
        $this->form_validation->set_rules('tambah-group', 'Kelas (Group)','required|integer');
        
        if($this->form_validation->run() == TRUE){
        	$data['user_name'] = trim((string)$this->input->post('tambah-username', true));
            $data['user_password'] = trim((string)$this->input->post('tambah-password', true));
            $data['user_email'] = trim((string)$this->input->post('tambah-email', true));
            $data['user_firstname'] = trim((string)$this->input->post('tambah-nama', true));
			$data['user_detail'] = substr(trim((string)$this->input->post('tambah-detail', true)), 0, 25);
            $data['user_grup_id'] = (int)$this->input->post('tambah-group', true);

            if($this->cbt_user_grup_model->count_by_kolom('grup_id', $data['user_grup_id'])->row()->hasil>0){
                $cek_user = $this->db->select('COUNT(*) AS hasil')
                                     ->from('cbt_user')
                                     ->where('LOWER(user_name)', strtolower($data['user_name']))
                                     ->get()->row()->hasil;
            	if($cek_user > 0){
	                $status['status'] = 0;
	                $status['pesan'] = 'Username sudah terpakai !';
	            }else{
					$this->cbt_user_model->save($data);
	                
	                $status['status'] = 1;
	                $status['pesan'] = 'Data Peserta berhasil disimpan ';
	            }
            }else{
            	$status['status'] = 0;
                $status['pesan'] = 'Data Kelas (Group) tidak tersedia, silakan tambah Kelas terlebih dahulu';
            }
        }else{
            $status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        
        echo json_encode($status);
    }
    
    function get_by_id($id=null){
    	$data['data'] = 0;
		if(!empty($id)){
			$query = $this->cbt_user_model->get_by_kolom('user_id', (int)$id);
			if($query->num_rows()>0){
				$query = $query->row();
				$data['data'] = 1;
				$data['id'] = $query->user_id;
				$data['username'] = $query->user_name;
				$data['password'] = $query->user_password;
				$data['nama'] = stripslashes((string)$query->user_firstname);
				$data['email'] = $query->user_email;
				$data['detail'] = $query->user_detail;
				$data['group'] = $query->user_grup_id;
			}
		}
		echo json_encode($data);
    }

    /**
     * Menghapus siswa yang dipilih
     */
    function hapus_daftar_siswa(){
    	$this->load->library('form_validation');
        
		$this->form_validation->set_rules('edit-user-id[]', 'Siswa','required|strip_tags');
		if($this->form_validation->run() == TRUE){
			$user_id = $this->input->post('edit-user-id', TRUE);
			$deleted = 0;
			foreach( $user_id as $kunci => $isi ) {
				if($isi=="on"){
					$this->cbt_user_model->delete('user_id', (int)$kunci);
					$deleted++;
            	}
            }
            $status['status'] = 1;
            $status['pesan'] = $deleted.' data peserta berhasil dihapus';
		}else{
			$status['status'] = 0;
            $status['pesan'] = 'Pilih minimal satu peserta terlebih dahulu.';
        }
        
        echo json_encode($status);
    }

    function edit(){
        $this->load->library('form_validation');
        
		$this->form_validation->set_rules('edit-id', 'ID','required|integer');
        $this->form_validation->set_rules('edit-pilihan', 'Pilihan','required|strip_tags');
        $this->form_validation->set_rules('edit-username', 'Username','required|trim|strip_tags');
        $this->form_validation->set_rules('edit-password', 'Password','required|trim|strip_tags|max_length[255]');
        $this->form_validation->set_rules('edit-nama', 'Nama Lengkap','required|trim|strip_tags|max_length[255]');
        $this->form_validation->set_rules('edit-email', 'Email','trim|strip_tags|max_length[255]');
		$this->form_validation->set_rules('edit-detail', 'Keterangan','trim|strip_tags|max_length[25]');
        $this->form_validation->set_rules('edit-group', 'Kelas (Group)','required|integer');
        
        if($this->form_validation->run() == TRUE){
            $pilihan = $this->input->post('edit-pilihan', true);
            $id = (int)$this->input->post('edit-id', true);
            
            if($pilihan=='hapus'){
            	$this->cbt_user_model->delete('user_id', $id);
				$status['status'] = 1;
				$status['pesan'] = 'Data Peserta berhasil dihapus !';

            }else if($pilihan=='simpan'){
                $data['user_password'] = trim((string)$this->input->post('edit-password', true));
                $data['user_firstname'] = trim((string)$this->input->post('edit-nama', true));
                $data['user_email'] = trim((string)$this->input->post('edit-email', true));
                $data['user_grup_id'] = (int)$this->input->post('edit-group', true);
				$data['user_detail'] = substr(trim((string)$this->input->post('edit-detail', true)), 0, 25);

                $this->cbt_user_model->update('user_id', $id, $data);

                $status['status'] = 1;
		        $status['pesan'] = 'Data Peserta berhasil disimpan ';
            }
            
        }else{
            $status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        
        echo json_encode($status);
    }
    
    function get_datatable(){
		$group = $this->input->get('group', TRUE);
		if(empty($group)){
			$group = 'semua';
		}

		$search = "";
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = trim($_GET['sSearch']);
		}

		$start = $this->get_start();
		$rows = $this->get_rows();

		// Build query sekali jalan dengan LEFT JOIN (tanpa N+1 query) + pencarian lintas kolom
		$this->db->start_cache();
		$this->db->from('cbt_user u')
		         ->join('cbt_user_grup g', 'u.user_grup_id = g.grup_id', 'left');
		if($group !== 'semua' && is_numeric($group)){
			$this->db->where('u.user_grup_id', (int)$group);
		}
		if($search !== ''){
			$like = $this->db->escape_like_str($search);
			$this->db->where("(u.user_firstname LIKE '%".$like."%' ESCAPE '!' OR u.user_name LIKE '%".$like."%' ESCAPE '!' OR u.user_detail LIKE '%".$like."%' ESCAPE '!' OR g.grup_nama LIKE '%".$like."%' ESCAPE '!')", NULL, FALSE);
		}
		$this->db->stop_cache();

		$iTotal = $this->db->count_all_results();

		$this->db->select('u.user_id, u.user_name, u.user_firstname, u.user_detail, u.user_grup_id, g.grup_nama')
		         ->order_by('g.grup_nama', 'ASC')
		         ->order_by('u.user_firstname', 'ASC')
		         ->limit($rows, $start);
		$query = $this->db->get()->result();
		$this->db->flush_cache();

		$output = array(
			"sEcho" => isset($_GET['sEcho']) ? intval($_GET['sEcho']) : 1,
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

		$i = $start;
	    foreach ($query as $temp) {			
			$record = array();
            
			$record[] = ++$i;
            $record[] = '<code>'.html_escape($temp->user_name).'</code>';
            $record[] = '<strong>'.html_escape(stripslashes((string)$temp->user_firstname)).'</strong>';
            $record[] = '<span class="label label-primary" style="font-size:11.5px;">'.html_escape($temp->grup_nama ? $temp->grup_nama : '-').'</span>';
			$record[] = !empty($temp->user_detail) ? html_escape($temp->user_detail) : '<span class="text-muted">-</span>';
            $record[] = '<button type="button" onclick="edit(\''.(int)$temp->user_id.'\')" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Edit</button>';
            $record[] = '<input type="checkbox" class="chk-peserta" name="edit-user-id['.(int)$temp->user_id.']" >';

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
			if ($rows < 5 || $rows > 500) {
				$rows = 25;
			}
		}
		return $rows;
	}

	function get_sort_dir() {
		$sort_dir = "ASC";
		if (isset($_GET['sSortDir_0'])) {
			$sdir = strip_tags($_GET['sSortDir_0']);
			if ($sdir != "asc" ) {
				$sort_dir = "DESC";
			}
		}
		return $sort_dir;
	}
}
