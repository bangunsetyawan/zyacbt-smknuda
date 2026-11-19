<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Peserta_import extends Member_Controller {
	private $kode_menu = 'peserta-import';
	private $kelompok = 'peserta';
	private $url = 'manager/peserta_import';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_user_model');
		$this->load->library('peserta_importer');

        parent::cek_akses($this->kode_menu);
	}
	
    public function index(){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;
        $this->template->display_admin($this->kelompok.'/peserta_import_view', 'Import Data Peserta', $data);
    }

    public function download_template(){
        $this->peserta_importer->download_template();
    }

    public function preview(){
        $res = $this->peserta_importer->parse_upload('userfile');
        echo json_encode($res);
    }

    public function simpan(){
        $token = $this->input->post('token', true);
        $duplicate_mode = $this->input->post('duplicate_mode', true);
        $res = $this->peserta_importer->commit_import($token, $duplicate_mode);
        echo json_encode($res);
    }

    /**
     * Backward-compatible fallback jika form lama di-POST ke /import
     */
    public function import(){
        $this->index();
    }
}
