<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Peserta_online extends Member_Controller {
	private $kode_menu = 'peserta-online';
	private $kelompok = 'peserta';
	private $url = 'manager/peserta_online';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_user_model');
		$this->load->model('cbt_tes_user_model');
		$this->load->model('cbt_tes_model');

		parent::cek_akses($this->kode_menu);
	}
	
    public function index($id_soal=null){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;
		
		$query_group = $this->cbt_user_grup_model->get_group();
        if($query_group->num_rows()>0){
        	$select = '<option value="semua">Semua</option>';
        	$query_group = $query_group->result();
        	foreach ($query_group as $temp) {
        		$select = $select.'<option value="'.$temp->grup_id.'">'.$temp->grup_nama.'</option>';
        	}

        }else{
        	$select = '<option value="0">KOSONG</option>';
        }
        $data['select_group'] = $select;
		
		$tanggal_awal = date('Y-m-d H:i', strtotime('- 1 hours'));
        $tanggal_akhir = date('Y-m-d H:i');
		$data['rentang_waktu'] = $tanggal_awal.' - '.$tanggal_akhir;
        
        $this->template->display_admin($this->kelompok.'/peserta_online_view', 'Status Online', $data);
    }
    
    function get_datatable(){
		$group      = trim((string)$this->input->get('group', TRUE));
		$keterangan = trim((string)$this->input->get('keterangan', TRUE));
		$waktu      = trim((string)$this->input->get('waktu', TRUE));
		$status_flt = trim((string)$this->input->get('status', TRUE)); // semua | online | offline

		$search = "";
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = trim((string)$_GET['sSearch']);
		}

		$start = $this->get_start();
		$rows  = $this->get_rows();

		$t_awal  = date('Y-m-d H:i:s', strtotime('-1 hours'));
		$t_akhir = date('Y-m-d H:i:s');
		if (!empty($waktu) && strpos($waktu, ' - ') !== false) {
			$parts = explode(' - ', $waktu);
			if (!empty($parts[0]) && !empty($parts[1])) {
				$t_awal  = date('Y-m-d H:i:s', strtotime(trim($parts[0])));
				$t_akhir = date('Y-m-d H:i:s', strtotime(trim($parts[1])) + 59);
			}
		}

		$esc_awal  = $this->db->escape($t_awal);
		$esc_akhir = $this->db->escape($t_akhir);
		$online_cond = "EXISTS (SELECT 1 FROM cbt_tes_user tu WHERE tu.tesuser_user_id = u.user_id AND tu.tesuser_creation_time >= {$esc_awal} AND tu.tesuser_creation_time <= {$esc_akhir})";

		$this->db->start_cache();
		$this->db->from('cbt_user u')
		         ->join('cbt_user_grup g', 'g.grup_id = u.user_grup_id', 'left');

		if ($group !== '' && $group !== 'semua' && is_numeric($group)) {
			$this->db->where('u.user_grup_id', (int)$group);
		}
		if ($keterangan !== '') {
			$this->db->like('u.user_detail', $keterangan);
		}
		if ($search !== '') {
			$s_like = $this->db->escape_like_str($search);
			$this->db->where("(u.user_firstname LIKE '%{$s_like}%' OR u.user_name LIKE '%{$s_like}%' OR u.user_detail LIKE '%{$s_like}%')", null, false);
		}
		if ($status_flt === 'online') {
			$this->db->where($online_cond, null, false);
		} elseif ($status_flt === 'offline') {
			$this->db->where("NOT ({$online_cond})", null, false);
		}
		$this->db->stop_cache();

		$iTotal = (int)$this->db->count_all_results();

		$this->db->select("u.user_id, u.user_name, u.user_firstname, u.user_detail, g.grup_nama, (CASE WHEN {$online_cond} THEN 1 ELSE 0 END) AS is_online", false)
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
			$nama_html = html_escape((string)$temp->user_firstname);
			if (!empty($temp->grup_nama)) {
				$nama_html .= ' <span class="label label-default">'.html_escape((string)$temp->grup_nama).'</span>';
			}
			$record[] = $nama_html;
			$record[] = html_escape((string)$temp->user_name);
			$record[] = html_escape((string)$temp->user_detail);
			if ((int)$temp->is_online === 1) {
				$record[] = '<span class="label label-success">Online</span>';
			} else {
				$record[] = '<span class="label label-danger">Offline</span>';
			}
			$output['aaData'][] = $record;
		}

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
		if (isset($_GET['sSortDir_0'])) {
			$sdir = strip_tags($_GET['sSortDir_0']);
			if ($sdir != "asc" ) {
				$sort_dir = "DESC";
			}
		}

		return $sort_dir;
	}
}