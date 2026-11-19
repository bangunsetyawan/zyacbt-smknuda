<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modul_daftar extends Member_Controller {
	private $kode_menu = 'modul-daftar';
	private $kelompok = 'modul';
	private $url = 'manager/modul_daftar';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_modul_model');
		$this->load->model('cbt_topik_model');
		$this->load->model('cbt_jawaban_model');
		$this->load->model('cbt_soal_model');

		parent::cek_akses($this->kode_menu);
	}
	
    public function index(){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        $query_user = $this->users_model->get_user_by_username($this->access->get_username());
        $allowed_topik_ids = array();
        $is_restricted = false;

        if($query_user->num_rows()>0){
            $user_row = $query_user->row();
            if(!empty($user_row->opsi1)){
                $is_restricted = true;
                $parts = explode(',', $user_row->opsi1);
                foreach ($parts as $tid) {
                    $tid = intval(trim($tid));
                    if($tid > 0){
                        $allowed_topik_ids[] = $tid;
                    }
                }
            }
        }

        // Ambil semua modul (modul aktif di paling atas)
        $modul_rows = $this->cbt_modul_model->get_all_ordered()->result();
        $modul_map = array();
        $active_modul_id = 0;
        foreach ($modul_rows as $m) {
            $mid = intval($m->modul_id);
            if($active_modul_id === 0 && intval($m->modul_aktif) === 1){
                $active_modul_id = $mid;
            }
            $modul_map[$mid] = array(
                'modul_id' => $mid,
                'modul_nama' => $m->modul_nama,
                'modul_aktif' => intval($m->modul_aktif),
                'jml_topik' => 0,
                'jml_soal' => 0
            );
        }
        if($active_modul_id === 0 && !empty($modul_rows)){
            $active_modul_id = intval($modul_rows[0]->modul_id);
        }

        // Ambil semua topik beserta ringkasan jumlah soal (PG, Esai, Jawaban Singkat) dalam 1 query
        $this->db->select(
            't.topik_id, t.topik_nama, t.topik_modul_id AS modul_id, m.modul_nama, m.modul_aktif, '.
            'COUNT(s.soal_id) AS jml_soal, '.
            'SUM(CASE WHEN s.soal_tipe = 1 THEN 1 ELSE 0 END) AS jml_pg, '.
            'SUM(CASE WHEN s.soal_tipe = 2 THEN 1 ELSE 0 END) AS jml_esai, '.
            'SUM(CASE WHEN s.soal_tipe = 3 THEN 1 ELSE 0 END) AS jml_singkat',
            FALSE
        );
        $this->db->from('cbt_topik t');
        $this->db->join('cbt_modul m', 'm.modul_id = t.topik_modul_id', 'inner');
        $this->db->join('cbt_soal s', 's.soal_topik_id = t.topik_id', 'left');
        if($is_restricted){
            if(!empty($allowed_topik_ids)){
                $this->db->where_in('t.topik_id', $allowed_topik_ids);
            }else{
                $this->db->where('1 = 0', NULL, FALSE);
            }
        }
        $this->db->group_by('t.topik_id');
        $this->db->order_by('m.modul_aktif', 'DESC');
        $this->db->order_by('m.modul_id', 'DESC');
        $this->db->order_by('t.topik_nama', 'ASC');
        $topik_rows = $this->db->get()->result();

        $topik_list = array();
        foreach ($topik_rows as $t) {
            $mid = intval($t->modul_id);
            $jml_soal = intval($t->jml_soal);
            $topik_list[] = array(
                'topik_id' => intval($t->topik_id),
                'topik_nama' => $t->topik_nama,
                'modul_id' => $mid,
                'modul_nama' => $t->modul_nama,
                'modul_aktif' => intval($t->modul_aktif),
                'jml_soal' => $jml_soal,
                'jml_pg' => intval($t->jml_pg),
                'jml_esai' => intval($t->jml_esai),
                'jml_singkat' => intval($t->jml_singkat)
            );
            if(isset($modul_map[$mid])){
                $modul_map[$mid]['jml_topik']++;
                $modul_map[$mid]['jml_soal'] += $jml_soal;
            }
        }

        // Jika user dibatasi (opsi1), hanya tampilkan modul yang punya topik miliknya
        $modul_list = array();
        foreach ($modul_map as $m) {
            if($is_restricted && $m['jml_topik'] === 0){
                continue;
            }
            $modul_list[] = $m;
        }
        if($is_restricted && !empty($modul_list)){
            $found_active = false;
            foreach ($modul_list as $m) {
                if($m['modul_id'] === $active_modul_id){
                    $found_active = true;
                    break;
                }
            }
            if(!$found_active){
                $active_modul_id = $modul_list[0]['modul_id'];
            }
        }

        $data['modul_list'] = $modul_list;
        $data['topik_list'] = $topik_list;
        $data['active_modul_id'] = $active_modul_id;
        $data['is_restricted_user'] = $is_restricted;

        $this->template->display_admin($this->kelompok.'/modul_daftar_view', 'Daftar Soal', $data);
	}
	
	function cetak_soal($topik_id=null){
		if(!empty($topik_id)){
			$query_soal = $this->cbt_soal_model->get_by_kolom('soal_topik_id', $topik_id);
			if($query_soal->num_rows()>0){
				$query_soal = $query_soal->result();

				$query_topik = $this->cbt_topik_model->get_by_kolom_limit('topik_id', $topik_id, 1)->row();

				$soal_table = '
					<style>body{font-family:Arial,sans-serif;font-size:13px;} img{max-width:100%;height:auto;} table{width:100%;border-collapse:collapse;} td{vertical-align:top;padding:4px 6px;}</style>
					<h3>Daftar Soal</h3>
					<b>Topik (Mata Pelajaran) = '.htmlspecialchars($query_topik->topik_nama, ENT_QUOTES, 'UTF-8').'</b>
					<hr />
					<table class="table" border="0">';

				$a = 1;

				foreach($query_soal as $temp){
					$posisi = $this->config->item('upload_path').'/topik_'.$temp->soal_topik_id;

					$soal = $temp->soal_detail;
					$soal = str_replace("[base_url]", base_url(), $soal);

					if($temp->soal_tipe==1){
						$tipe_soal = 'Pilihan Ganda';
					}else if($temp->soal_tipe==2){
						$tipe_soal = 'Essay';
					}

					if(!empty($temp->soal_audio)){
						$posisi = $this->config->item('upload_path').'/topik_'.$temp->soal_topik_id;
						$soal = $soal.'<br />
							<audio controls>
							<source src="'.base_url().$posisi.'/'.$temp->soal_audio.'" type="audio/mpeg">
							Your browser does not support the audio element.
							</audio>
						';
					}

					$soal_table = $soal_table.'
							<tr>
							<td>'.$a++.'</td>
							<td colspan="2">'.$soal.'</td>
							<td width="15%"></td>
							</tr>
					';

					$query_jawaban = $this->cbt_jawaban_model->get_by_soal($temp->soal_id);
					if($query_jawaban->num_rows()>0){
						$query_jawaban = $query_jawaban->result();
						foreach ($query_jawaban as $jawaban) {
							$temp_jawaban = $jawaban->jawaban_detail;
							$temp_jawaban = str_replace("[base_url]", base_url(), $temp_jawaban);

							$temp_benar = '';
							if($jawaban->jawaban_benar==1){
								$temp_benar = '<b>Benar</b>';
							}

							$soal_table = $soal_table.'
								<tr>
									<td width="5%"> </td>
									<td width="5%">'.$temp_benar.'</td>
									<td width="75%">'.$temp_jawaban.'</td>
									<td width="15%"></td>
								</tr>
							';
						}
					}

					$soal_table = $soal_table.'
								<tr>
									<td colspan="4"> ---------------------------------------------------------------------------- </td>
								</tr>
							';
				}

				$soal_table = $soal_table.'</table>
				
					<script lang="javascript">
    				window.print();
					</script>

				';

				echo $soal_table;
			}
		}
	}
	
	function get_topik_by_modul($modul_id=null){
		$data['data'] = 0;
		if(!empty($modul_id)){
			$query = $this->cbt_topik_model->get_by_kolom('topik_modul_id', $modul_id);
			if($query->num_rows()>0){
				$query = $query->result();
				$data['data'] = 1;
				$data['select'] = '<select name="topik" id="topik" class="form-control input-sm" onchange="refresh_table()">';
				foreach ($query as $temp) {
					$data['select'] = $data['select'].'<option value="'.$temp->topik_id.'">'.$temp->topik_nama.'</option>';	
				}
				$data['select'] = $data['select'].'</select>';
			}
		}
		echo json_encode($data);
	}
    
    function get_by_id($id=null){
    	$data['data'] = 0;
		if(!empty($id)){
			$query = $this->cbt_topik_model->get_by_kolom('topik_id', $id);
			if($query->num_rows()>0){
				$query = $query->row();
				$data['data'] = 1;
				$data['id'] = $query->topik_id;
				$data['topik'] = $query->topik_nama;
				$data['deskripsi'] = $query->topik_detail;
				$data['status'] = $query->topik_aktif;
			}
		}
		echo json_encode($data);
    }
    
    function get_datatable(){
		// variable initialization
		$topik = $this->input->get('topik');

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
		$query = $this->cbt_soal_model->get_datatable($start, $rows, 'soal_detail', $search, $topik);
		$iFilteredTotal = $query->num_rows();
		
		$iTotal= $this->cbt_soal_model->get_datatable_count('soal_detail', $search, $topik)->row()->hasil;
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

	    // get result after running query and put it in array
		$i=$start;
		$query = $query->result();
	    foreach ($query as $temp) {			
			$record = array();
            
			$record[] = ++$i;

			if($temp->soal_tipe==1){
				$record[] = '<span class="label label-primary">Pilihan Ganda</span>';
			}else if($temp->soal_tipe==2){
				$record[] = '<span class="label label-info">Esai / Uraian</span>';
			}else if($temp->soal_tipe==3){
				$record[] = '<span class="label label-warning">Jawaban Singkat</span>';
			}else{
				$record[] = '-';
			}

			$soal = $temp->soal_detail;
			$soal = str_replace("[base_url]", base_url(), $soal);
			if(!empty($temp->soal_audio)){
				$posisi = $this->config->item('upload_path').'/topik_'.$temp->soal_topik_id;
				$soal = $soal.'<br />
					<audio controls>
					  <source src="'.base_url().$posisi.'/'.$temp->soal_audio.'" type="audio/mpeg">
					Your browser does not support the audio element.
					</audio>
				';
			}

            $jawaban_table = '
            	<table class="table table-condensed soal-detail-table" border="0" style="margin-bottom:0;">
            		<tr>
                      <td colspan="3" class="soal-preview-content">'.$soal.'</td>
                      <td width="115" class="text-right"><a href="'.site_url().'/manager/modul_soal/index/'.$temp->soal_id.'" class="btn btn-xs btn-default" title="Edit Soal"><i class="fa fa-edit"></i> Edit Soal</a></td>
                    </tr>
            ';


            if($temp->soal_tipe==1){
            	$query_jawaban = $this->cbt_jawaban_model->get_by_soal($temp->soal_id);
	            if($query_jawaban->num_rows()>0){
	            	$query_jawaban = $query_jawaban->result();
	            	$a = 0;
	            	foreach ($query_jawaban as $jawaban) {
	            		$temp_jawaban = $jawaban->jawaban_detail;
						$temp_jawaban = str_replace("[base_url]", base_url(), $temp_jawaban);

						$row_style = '';
						$temp_benar = '<span class="text-muted">Salah</span>';
						if($jawaban->jawaban_benar==1){
							$row_style = ' style="background-color:#eafaf1;"';
							$temp_benar = '<span class="label label-success"><i class="fa fa-check"></i> Benar</span>';
						}

	            		$jawaban_table = $jawaban_table.'
	            			<tr'.$row_style.'>
		                      	<td width="32">'.++$a.'.</td>
		                      	<td width="72">'.$temp_benar.'</td>
		                      	<td class="soal-preview-content">'.$temp_jawaban.'</td>
		                      	<td width="115" class="text-right"><a href="'.site_url().'/manager/modul_jawaban/index/'.$temp->soal_id.'/'.$jawaban->jawaban_id.'" class="btn btn-xs btn-default" title="Edit Jawaban"><i class="fa fa-pencil"></i> Edit Opsi</a></td>
		                    </tr>
	            		';
	            	}
	            }
            }else if($temp->soal_tipe==3){
            	$jawaban_table = $jawaban_table.'
	            			<tr style="background-color:#eafaf1;">
		                      	<td width="120"><b>Kunci Jawaban</b></td>
		                      	<td colspan="3">'.htmlspecialchars($temp->soal_kunci, ENT_QUOTES, 'UTF-8').'</td>
		                    </tr>
	            		';
            }
			
			$jawaban_table = $jawaban_table.'</table>';

            $record[] = $jawaban_table;

			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
        
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