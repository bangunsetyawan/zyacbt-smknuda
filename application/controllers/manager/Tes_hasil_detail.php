<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* ZYA CBT
* Achmad Lutfi
* achmdlutfi@gmail.com
* achmadlutfi.wordpress.com
*/
class Tes_hasil_detail extends Member_Controller {
	private $kode_menu = 'tes-hasil';
	private $kelompok = 'tes';
	private $url = 'manager/tes_hasil_detail';
	
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
	
    public function index($tesuser_id=null){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        if(!empty($tesuser_id)){
        	$query_testuser = $this->cbt_tes_user_model->get_by_kolom_limit('tesuser_id', $tesuser_id, 1);
        	if($query_testuser->num_rows()>0){
        		$query_testuser = $query_testuser->row();

        		$query_test = $this->cbt_tes_model->get_by_kolom_limit('tes_id', $query_testuser->tesuser_tes_id, 1)->row();
        		$query_user = $this->cbt_user_model->get_by_kolom_limit('user_id', $query_testuser->tesuser_user_id, 1)->row();

        		$data['tes_user_id'] = $tesuser_id;
        		$data['tes_nama'] = $query_test->tes_nama;
        		$data['tes_mulai'] = $query_testuser->tesuser_creation_time;
        		$data['user_nama'] = $query_user->user_firstname;

        		$nilai = $this->cbt_tes_soal_model->get_nilai($tesuser_id)->row();
        		$max_tampil = ((int)$nilai->non_pg_count === 0 && (int)$nilai->total_soal > 0) ? '100' : rtrim(rtrim(number_format((float)$query_test->tes_max_score, 2, '.', ''), '0'), '.');
        		$hasil_tampil = rtrim(rtrim(number_format((float)$nilai->hasil, 2, '.', ''), '0'), '.');
        		$data['nilai'] = $hasil_tampil.'  /  '.$max_tampil.'  (nilai / nilai maksimal) ';

        		$data['benar'] = ($nilai->total_soal-$nilai->jawaban_salah).'  /  '.$nilai->total_soal.'  (jawaban benar / total soal)';

        		$this->template->display_admin($this->kelompok.'/tes_hasil_detail_view', 'Hasil Tes Detail', $data);
        	}else{
        		redirect('manager/tes_hasil');	
        	}
        }else{
        	redirect('manager/tes_hasil');
        }
    }
    
    function get_datatable(){
		// variable initialization
		$tesuser_id = (int)$this->input->get('tes_user_id');

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
		$query = $this->cbt_tes_soal_model->get_datatable($start, $rows, 'soal_detail', $search, $tesuser_id);
		$iFilteredTotal = $query->num_rows();
		
		$iTotal= $this->cbt_tes_soal_model->get_datatable_count('soal_detail', $search, $tesuser_id)->row()->hasil;
	    
		$output = array(
			"sEcho" => isset($_GET['sEcho']) ? intval($_GET['sEcho']) : 1,
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
				$record[] = 'Pilihan Ganda';
			}else if($temp->soal_tipe==2){
				$record[] = 'Essay';
			}else if($temp->soal_tipe==3){
				$record[] = 'Jawaban Singkat';
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
            	<table class="table" border="0">
            		<tr>
                      <td colspan="4">'.$soal.'</td>
                    </tr>
            ';

            // cek tipe soal
            // Jika soal adalah jenis pilihan ganda
            if($temp->soal_tipe==1){
            	$query_jawaban = $this->cbt_tes_soal_jawaban_model->get_by_tessoal($temp->tessoal_id);
	            if($query_jawaban->num_rows()>0){
	            	$query_jawaban = $query_jawaban->result();
	            	$a = 0;
	            	$is_answered = false;
	            	$is_correct = false;
	            	foreach ($query_jawaban as $j_chk) {
	            		if((int)$j_chk->soaljawaban_selected === 1){
	            			$is_answered = true;
	            			if((int)$j_chk->jawaban_benar === 1){
	            				$is_correct = true;
	            			}
	            		}
	            	}
	            	if(!$is_answered){
	            		$status_soal_badge = '<span class="label label-default"><i class="fa fa-minus-circle"></i> Tidak Dijawab (Poin: '.$temp->tessoal_nilai.')</span>';
	            	}elseif($is_correct){
	            		$status_soal_badge = '<span class="label label-success"><i class="fa fa-check-circle"></i> Jawaban Benar (+'.$temp->tessoal_nilai.' Poin)</span>';
	            	}else{
	            		$status_soal_badge = '<span class="label label-danger"><i class="fa fa-times-circle"></i> Jawaban Salah (Poin: '.$temp->tessoal_nilai.')</span>';
	            	}

	            	$jawaban_table = $jawaban_table.'
	            			<tr>
		                      	<td colspan="4" style="padding-top:4px;padding-bottom:6px;">'.$status_soal_badge.'</td>
		                    </tr>
	            			<tr style="background:#f8fafc;font-weight:bold;">
		                      	<td width="5%">No</td>
		                      	<td width="10%" class="text-center">Kunci</td>
		                      	<td width="12%" class="text-center">Pilihan Siswa</td>
		                      	<td width="73%">Opsi Jawaban</td>
		                    </tr>
	            		';
	            	foreach ($query_jawaban as $jawaban) {
	            		$temp_jawaban = $jawaban->jawaban_detail;
						$temp_jawaban = str_replace("[base_url]", base_url(), $temp_jawaban);

						$is_kunci = ((int)$jawaban->jawaban_benar === 1);
						$is_dipilih = ((int)$jawaban->soaljawaban_selected === 1);

						$temp_benar = $is_kunci
							? '<span class="label label-success"><i class="fa fa-key"></i> Kunci</span>'
							: '<span class="text-muted">-</span>';

						if($is_dipilih && $is_kunci){
							$temp_pilihan = '<span class="label label-success"><i class="fa fa-check"></i> Dipilih</span>';
							$row_style = ' style="background-color:#f0fdf4;"';
						}elseif($is_dipilih && !$is_kunci){
							$temp_pilihan = '<span class="label label-danger"><i class="fa fa-times"></i> Dipilih</span>';
							$row_style = ' style="background-color:#fef2f2;"';
						}else{
							$temp_pilihan = '<span class="text-muted">-</span>';
							$row_style = $is_kunci ? ' style="background-color:#f8fafc;"' : '';
						}

	            		$jawaban_table = $jawaban_table.'
	            			<tr'.$row_style.'>
		                      	<td width="5%">'.++$a.'.</td>
		                      	<td width="10%" class="text-center">'.$temp_benar.'</td>
		                      	<td width="12%" class="text-center">'.$temp_pilihan.'</td>
		                      	<td width="73%">'.$temp_jawaban.'</td>
		                    </tr>
	            		';
	            	}
	            }
            }else if($temp->soal_tipe==2){
            	// Jika soal adalah soal essay
            	$jawaban_table = $jawaban_table.'
            		<tr style="background:#f8fafc;font-weight:bold;">
		            	<td width="5%"></td>
		                <td width="10%" class="text-center">Skor</td>
		                <td width="85%" colspan="2">Jawaban Esai Siswa</td>
		            </tr>
	            	<tr>
		            	<td width="5%"></td>
		                <td width="10%" class="text-center"><span class="badge bg-blue">'.$temp->tessoal_nilai.'</span></td>
		                <td width="85%" colspan="2"><div style="width:100%;"><pre style="white-space: pre-wrap;word-wrap: break-word;margin-bottom:0;">'.htmlspecialchars((string)$temp->tessoal_jawaban_text, ENT_QUOTES, 'UTF-8').'</pre></div></td>
		            </tr>
	            ';
            }else if($temp->soal_tipe==3){
            	// Jika soal adalah soal Jawaban Singkat
            	$jawaban_table = $jawaban_table.'
            		<tr style="background:#f8fafc;font-weight:bold;">
		            	<td width="5%"></td>
		                <td width="10%" class="text-center">Skor</td>
		                <td width="85%" colspan="2">Jawaban Singkat Siswa</td>
		            </tr>
	            	<tr>
		            	<td width="5%"></td>
		                <td width="10%" class="text-center"><span class="badge bg-blue">'.$temp->tessoal_nilai.'</span></td>
		                <td width="85%" colspan="2"><div style="width:100%;">'.htmlspecialchars((string)$temp->tessoal_jawaban_text, ENT_QUOTES, 'UTF-8').'</div></td>
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
		if (isset($_GET['sSortDir_0'])) {
			$sdir = strip_tags($_GET['sSortDir_0']);
			if ($sdir != "asc" ) {
				$sort_dir = "DESC";
			}
		}

		return $sort_dir;
	}
}