<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modul_filemanager extends Member_Controller {
	private $kode_menu = 'modul-filemanager';
	private $kelompok = 'modul';
	private $url = 'manager/modul_filemanager';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_modul_model');
		$this->load->helper('directory');
		$this->load->helper('file');

		parent::cek_akses($this->kode_menu);
	}
	
    public function index(){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;
        $data['upload_path'] = $this->config->item('upload_path');
        
        $this->template->display_admin($this->kelompok.'/modul_filemanager_view', 'File Manager', $data);
    }

    /**
     * Validasi dan kunci path agar tidak pernah keluar dari base upload_path (mencegah Path Traversal ../)
     */
    private function _resolve_safe_upload_path($rel_posisi = '') {
        $base = rtrim((string)$this->config->item('upload_path'), '/\\');
        $real_base = realpath($base);
        if ($real_base === false) {
            return false;
        }
        $rel = trim(str_replace('\\', '/', (string)$rel_posisi), '/');
        if ($rel === '') {
            return $base;
        }
        // Tolak segmen .. atau karakter berbahaya
        $parts = explode('/', $rel);
        $clean_parts = array();
        foreach ($parts as $p) {
            $p = trim($p);
            if ($p === '' || $p === '.' || $p === '..' || !preg_match('/^[a-zA-Z0-9._-]+$/', $p)) {
                return false;
            }
            $clean_parts[] = $p;
        }
        $candidate = $base . '/' . implode('/', $clean_parts);
        if (file_exists($candidate)) {
            $real_cand = realpath($candidate);
            if ($real_cand === false || strpos($real_cand, $real_base) !== 0) {
                return false;
            }
        }
        return $candidate;
    }

    private function _sanitize_single_name($name) {
        $name = basename(str_replace('\\', '/', trim((string)$name)));
        if ($name === '' || $name === '.' || $name === '..' || strpos($name, '..') !== false || !preg_match('/^[a-zA-Z0-9._-]+$/', $name)) {
            return false;
        }
        return $name;
    }

    function tambah_dir(){
    	$this->load->library('form_validation');
        
		$this->form_validation->set_rules('tambah-dir', 'Direktori','required|strip_tags');
        
        if($this->form_validation->run() == TRUE){
            $dir = $this->_sanitize_single_name($this->input->post('tambah-dir', true));
            $posisi = $this->_resolve_safe_upload_path($this->input->post('tambah-posisi', true));

            if($dir === false || $posisi === false || !is_dir($posisi)){
                $status['status'] = 0;
                $status['pesan'] = 'Nama direktori atau path tidak valid.';
            }else if(is_dir($posisi.'/'.$dir)){
            	$status['status'] = 0;
            	$status['pesan'] = 'Direktori sudah ada, silahkan cek kembali';
            }else{
            	mkdir($posisi.'/'.$dir, 0755);

            	$status['status'] = 1;
            	$status['pesan'] = 'Direktori berhasil dibuat';
            }
            
        }else{
            $status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        
        echo json_encode($status);
    }

    function upload_file(){
    	$posisi = $this->_resolve_safe_upload_path($this->input->post('upload-posisi', true));
    	if($posisi === false || !is_dir($posisi)){
    		echo json_encode(array('status' => 0, 'pesan' => 'Direktori tujuan upload tidak valid.'));
    		return;
    	}

    	$field_name = 'upload-file';
        if(!empty($_FILES[$field_name]['name'])){
	    	$config['upload_path'] = $posisi;
		    $config['allowed_types'] = 'jpg|png|jpeg|mp3';
		    $config['max_size']	= '10240';
		    $config['overwrite'] = true;
		    $safe_name = $this->_sanitize_single_name(strtolower($_FILES[$field_name]['name']));
		    if($safe_name === false){
		    	echo json_encode(array('status' => 0, 'pesan' => 'Nama file tidak valid.'));
		    	return;
		    }
		    $config['file_name'] = $safe_name;

		    if(file_exists($posisi.'/'.$config['file_name'])){
        		$status['status'] = 0;
            	$status['pesan'] = 'Nama file sudah terdapat pada direktori, silahkan ubah nama file yang akan di upload';
	    	}else{
		        $this->load->library('upload', $config);
	            if (!$this->upload->do_upload($field_name)){
	            	$status['status'] = 0;
	            	$status['pesan'] = $this->upload->display_errors();
	            }else{
	            	$upload_data = $this->upload->data();

	            	$status['status'] = 1;
	                $status['pesan'] = 'File '.$upload_data['file_name'].' BERHASIL di IMPORT';
	            }   	
	    	}     
        }else{
        	$status['status'] = 0;
            $status['pesan'] = 'Pilih terlebih dahulu file yang akan di upload';
        }
        echo json_encode($status);
    }

    function hapus_file(){
    	$this->load->library('form_validation');
        
		$this->form_validation->set_rules('hapus-file', 'File','required|strip_tags');
        
        if($this->form_validation->run() == TRUE){
            $file = $this->_sanitize_single_name($this->input->post('hapus-file', true));
            $posisi = $this->_resolve_safe_upload_path($this->input->post('hapus-posisi', true));

            if($file === false || $posisi === false || $file === '.htaccess' || $file === '.gitkeep' || !file_exists($posisi.'/'.$file)){
                $status['status'] = 0;
                $status['pesan'] = 'File atau direktori tidak valid.';
            }else if(is_dir($posisi.'/'.$file)){
            	delete_files($posisi.'/'.$file, TRUE);
            	rmdir($posisi.'/'.$file);

            	$status['status'] = 1;
            	$status['pesan'] = 'Direktori berhasil dihapus ';
            }else{
            	unlink($posisi.'/'.$file);

            	$status['status'] = 1;
            	$status['pesan'] = 'File berhasil dihapus ';
            }
            
        }else{
            $status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        
        echo json_encode($status);
    }

    function get_datatable(){
    	$posisi_raw = $this->input->get('posisi');

		// variable initialization
		$search = "";
		$start = 0;
		$rows = 10;

		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}

		// limit
		$start = $this->get_start();
		$rows = $this->get_rows();

		// run query to get user listing
		$posisi = $this->_resolve_safe_upload_path($posisi_raw);
		$query = ($posisi !== false && is_dir($posisi)) ? directory_map($posisi, 1) : array();
		if(!is_array($query)){
			$query = array();
		}

	    // get result after running query and put it in array
	    $iTotal = 0;
		$i=$start;

		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );
		
	    foreach ($query as $temp) {			
			$record = array();

			$temp = str_replace("\\","", $temp);
            
			$record[] = ++$i;
			$is_dir=0;
			$is_image=0;
			$info = pathinfo($temp);

			if(is_dir($posisi.'/'.$temp)){
            	$record[] = '<a style="cursor:pointer;" onclick="open_dir(\''.$temp.'\')"><b>'.$temp.'</b></a>';
            	$is_dir=1;
        	}else{
        		if($info['extension']=='jpg' or $info['extension']=='png' or $info['extension']=='jpeg'){
            		$record[] = '<a style="cursor:pointer;" onclick="open_image(\''.$temp.'\')">'.$temp.'</a>';
            		$is_image=1;
            	}else{
            		$record[] = $temp;
            	}
        	}

            $file_info = get_file_info($posisi.'/'.$temp);

            if($is_dir==1){
            	$record[] = 'Direktori';
            }else{
            	if($is_image==1){
            		$record[] = '<a style="cursor:pointer;" onclick="open_image(\''.$temp.'\')"><img src="'.base_url().$posisi.'/'.$temp.'" height="50" /></a>';
            	}else{
            		$record[] = 'File bukan gambar';
            	}
            }

            $record[] = date('Y-m-d H:i:s', $file_info['date']);
            $record[] = '<div style="text-align:right;">'.number_format($file_info['size']).' B</div>';
            $record[] = '<a onclick="hapus_file(\''.$temp.'\')" style="cursor: pointer;" class="btn btn-default btn-xs">Hapus</a>';

			$output['aaData'][] = $record;

			$iTotal++;
		}

		$output['iTotalRecords'] = $iTotal;
		$output['iTotalDisplayRecords'] = $iTotal;
        
		echo json_encode($output);
	}
	
	/**
	* funsi tambahan 
	* 
	* 
*/
	
	function get_start() {
		$start = 0;
		if (isset($_GET['iDisplayStart'])) {
			$start = intval($_GET['iDisplayStart']);

			if ($start < 0)
				$start = 0;
		}

		return $start;
	}

	function get_rows() {
		$rows = 10;
		if (isset($_GET['iDisplayLength'])) {
			$rows = intval($_GET['iDisplayLength']);
			if ($rows < 5 || $rows > 500) {
				$rows = 10;
			}
		}

		return $rows;
	}

	function get_sort_dir() {
		$sort_dir = "ASC";
		$sdir = strip_tags($_GET['sSortDir_0']);
		if (isset($sdir)) {
			if ($sdir != "asc" ) {
				$sort_dir = "DESC";
			}
		}

		return $sort_dir;
	}
}