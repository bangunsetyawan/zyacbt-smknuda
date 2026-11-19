<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Peserta_kartu extends Member_Controller {
	private $kode_menu = 'peserta-kartu';
	private $kelompok = 'peserta';
	private $url = 'manager/peserta_kartu';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_user_model');
		$this->load->model('cbt_konfigurasi_model');

        parent::cek_akses($this->kode_menu);
	}
	
    public function index(){
		$data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        // Ambil semua kelas beserta jumlah pesertanya dalam 1 query
        $sql_grup = "SELECT g.grup_id, g.grup_nama, COUNT(u.user_id) AS jml_peserta
                     FROM cbt_user_grup g
                     LEFT JOIN cbt_user u ON u.user_grup_id = g.grup_id
                     GROUP BY g.grup_id, g.grup_nama
                     ORDER BY g.grup_nama ASC";
        $groups = $this->db->query($sql_grup)->result();

        $total_peserta = 0;
        $select = '';
        foreach($groups as $g){
            $total_peserta += (int)$g->jml_peserta;
        }
        $select .= '<option value="semua">Semua Kelas ('.$total_peserta.' peserta)</option>';
        foreach ($groups as $temp) {
            $select .= '<option value="'.(int)$temp->grup_id.'">'.html_escape($temp->grup_nama).' ('.(int)$temp->jml_peserta.' peserta)</option>';
        }
        $data['select_group'] = $select;
        $data['total_peserta'] = $total_peserta;

        // Ambil daftar distinct Keterangan (Ruang/Sesi) untuk filter opsional
        $details = $this->db->select('DISTINCT(user_detail) AS detail', FALSE)
                            ->from('cbt_user')
                            ->where('user_detail IS NOT NULL', NULL, FALSE)
                            ->where("TRIM(user_detail) !=", '')
                            ->order_by('user_detail', 'ASC')
                            ->get()->result();
        $data['details'] = $details;

        // Ambil nama sekolah & keterangan kegiatan dari cbt_konfigurasi
        $cbt_nama = 'SMK NU Darussalam';
        $cbt_keterangan = 'Ujian Berbasis Komputer (CBT)';
        $q_nama = $this->cbt_konfigurasi_model->get_by_kolom_limit('konfigurasi_kode', 'cbt_nama', 1);
        if($q_nama->num_rows() > 0 && trim($q_nama->row()->konfigurasi_isi) !== ''){
            $cbt_nama = trim($q_nama->row()->konfigurasi_isi);
        }
        $q_ket = $this->cbt_konfigurasi_model->get_by_kolom_limit('konfigurasi_kode', 'cbt_keterangan', 1);
        if($q_ket->num_rows() > 0 && trim($q_ket->row()->konfigurasi_isi) !== ''){
            $cbt_keterangan = trim($q_ket->row()->konfigurasi_isi);
        }
        $data['cbt_nama'] = $cbt_nama;
        $data['cbt_keterangan'] = $cbt_keterangan;
		
        $this->template->display_admin($this->kelompok.'/peserta_kartu_view', 'Cetak Kartu Login', $data);
    }

	/**
	 * Cetak Kartu Login untuk satu kelas (grup_id) atau semua kelas ('semua')
	 * Opsional GET: ?detail=...&judul=...&kertas=a4|f4
	 */
    public function cetak_kartu($grup_id = null){
		$data['kode_menu'] = $this->kode_menu;

        $detail_filter = trim((string)$this->input->get('detail', TRUE));
        $judul_custom  = trim((string)$this->input->get('judul', TRUE));
        $kertas        = strtolower(trim((string)$this->input->get('kertas', TRUE)));
        if($kertas !== 'f4'){
            $kertas = 'a4';
        }

        // Ambil konfigurasi penyelenggara
        $cbt_nama = 'SMK NU Darussalam';
        $q_nama = $this->cbt_konfigurasi_model->get_by_kolom_limit('konfigurasi_kode', 'cbt_nama', 1);
        if($q_nama->num_rows() > 0 && trim($q_nama->row()->konfigurasi_isi) !== ''){
            $cbt_nama = trim($q_nama->row()->konfigurasi_isi);
        }

        $cbt_keterangan = '';
        if($judul_custom !== ''){
            $cbt_keterangan = $judul_custom;
        }else{
            $q_ket = $this->cbt_konfigurasi_model->get_by_kolom_limit('konfigurasi_kode', 'cbt_keterangan', 1);
            if($q_ket->num_rows() > 0 && trim($q_ket->row()->konfigurasi_isi) !== ''){
                $cbt_keterangan = trim($q_ket->row()->konfigurasi_isi);
            }
        }

        $peserta_list = array();
        $label_filter = 'Semua Kelas';

        if(!empty($grup_id)){
            $gid = 0;
            if($grup_id !== 'semua'){
                $gid = (int)$grup_id;
                $qg = $this->cbt_user_grup_model->get_by_kolom_limit('grup_id', $gid, 1);
                if($qg->num_rows() > 0){
                    $label_filter = 'Kelas '.$qg->row()->grup_nama;
                }
            }

            $this->db->select('u.user_id, u.user_name, u.user_password, u.user_firstname, u.user_detail, g.grup_nama')
                     ->from('cbt_user u')
                     ->join('cbt_user_grup g', 'u.user_grup_id = g.grup_id', 'left');

            if($grup_id !== 'semua'){
                $this->db->where('u.user_grup_id', $gid);
            }

            if($detail_filter !== ''){
                $this->db->where('u.user_detail', $detail_filter);
                $label_filter .= ' &bull; '.$detail_filter;
            }

            $this->db->order_by('g.grup_nama', 'ASC')
                     ->order_by('u.user_detail', 'ASC')
                     ->order_by('u.user_firstname', 'ASC');
            $peserta_list = $this->db->get()->result();
        }

        $data['cbt_nama']       = $cbt_nama;
        $data['cbt_keterangan'] = $cbt_keterangan;
        $data['peserta_list']   = $peserta_list;
        $data['label_filter']   = $label_filter;
        $data['kertas']         = $kertas;
		
		$this->load->view($this->kelompok.'/peserta_cetak_kartu_view', $data);
	}
}
