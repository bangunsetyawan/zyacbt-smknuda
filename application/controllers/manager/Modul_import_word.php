<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modul_import_word extends Member_Controller {
	private $kode_menu = 'modul-import-word';
	private $kelompok = 'modul';
	private $url = 'manager/modul_import_word';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_modul_model');
		$this->load->model('cbt_topik_model');
		$this->load->model('cbt_jawaban_model');
		$this->load->model('cbt_soal_model');
		$this->load->helper('directory');
		$this->load->helper('file');

        parent::cek_akses($this->kode_menu);
	}
	
    public function index(){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;
		

        $query_user = $this->users_model->get_user_by_username($this->access->get_username());
        $select = '';
        $counter = 0;
        if($query_user->num_rows()>0){
            $query_user = $query_user->row();

            // Mengecek apakah user dibatasi hanya mengentry beberapa topik
            if(!empty($query_user->opsi1)){
                $user_topik = explode(',', $query_user->opsi1);
                foreach ($user_topik as $topik_id) {
                    $query_topik = $this->cbt_topik_model->get_by_kolom_join_modul('topik_id', $topik_id);
                    if($query_topik->num_rows()>0){
                        $topik = $query_topik->row();
                        $counter++;

                        $jml_soal = $this->cbt_soal_model->count_by_kolom('soal_topik_id', $topik->topik_id)->row()->hasil;

                        $select = $select.'<option value="'.$topik->topik_id.'">'.$topik->modul_nama.' - '.$topik->topik_nama.' ['.$jml_soal.']</option>';
                    }
                }
            }else{
                // Jika user tidak dibatasi mengedit soal sesuai topik
                $query_modul = $this->cbt_modul_model->get_modul();
                if($query_modul->num_rows()>0){
                    $select = '';
                    $query_modul = $query_modul->result();
                    foreach ($query_modul as $temp) {
                        $query_topik = $this->cbt_topik_model->get_by_kolom_join_modul('topik_modul_id', $temp->modul_id);
                        if($query_topik->num_rows()){
                            $select = $select.'<optgroup label="Modul '.$temp->modul_nama.'">';

                            $query_topik = $query_topik->result();
                            foreach ($query_topik as $topik) {
                                $counter++;

                                $jml_soal = $this->cbt_soal_model->count_by_kolom('soal_topik_id', $topik->topik_id)->row()->hasil;
                                $select = $select.'<option value="'.$topik->topik_id.'">'.$topik->modul_nama.' - '.$topik->topik_nama.' ['.$jml_soal.']</option>';
                            }

                            $select = $select.'</optgroup>';
                        }
                    }
                }
            }
        }

        if($counter==0){
        	$select = '<option value="kosong">Tidak Ada Data Topik</option>';
        }
        
        $data['select_topik'] = $select;

        $batch_ctx = $this->_get_batch_context();
        $data['active_modul_id'] = $batch_ctx['modul_id'];
        $data['active_modul_nama'] = $batch_ctx['modul_nama'];
        $data['can_create_topik'] = $batch_ctx['can_create_topik'];
        $data['php_max_file_uploads'] = intval(ini_get('max_file_uploads'));
        $data['php_upload_max'] = ini_get('upload_max_filesize');
        
        $this->template->display_admin($this->kelompok.'/modul_import_word_view', 'Mengimport Soal dari Word', $data);
    }

    function import(){
    	$this->load->library('form_validation');
        
        $this->form_validation->set_rules('topik', 'Topik','required');
		$this->form_validation->set_rules('import-soal', 'Daftar Soal','required');

        if($this->form_validation->run() == TRUE){
        	$id_topik = $this->input->post('topik', true);
			$daftar_soal = $this->input->post('import-soal', false);
			
			$doc = new DOMDocument();
			$doc->loadHTML($daftar_soal);
			$tables = $doc->getElementsByTagName('table');
			if(!empty($tables->item(0)->nodeValue)){
				$rows = $tables->item(0)->getElementsByTagName('tr');
				$counter = 1;
				$soal = '<table border="0" width="100%" class="no-padding" class="table-condensed">';
				foreach ($rows as $row) {
					/*** get each column by tag name ***/ 
					$cols = $row->getElementsByTagName('td'); 
					$kosong = 0;
					
					if(empty($cols->item(1)->nodeValue)){
						$kosong++;
					}
					
					if(empty($cols->item(2)->nodeValue)){
						$kosong++;
					}
					
					if(empty($cols->item(3)->nodeValue)){
						$kosong++;
					}
					if($kosong==0){
						$jenis = strtoupper(preg_replace('/\s+/', '',$cols->item(1)->nodeValue));
						$kunci = strtoupper(preg_replace('/\s+/', '',$cols->item(3)->nodeValue));
						$kunci = intval($kunci);
						if($kunci>1){
							$kunci=1;
						}
						if($jenis=='SOAL'){
							$soal = $soal.'
								<tr>
								<td valign="top"><p>'.$counter.'</p></td>
								<td colspan="2">'.$this->_sanitize_question_html($this->innerHTML($cols->item(2))).'</td>
								<td width="15%"></td>
								</tr>
							';
							$counter++;
						}else if($jenis=='JAWABAN'){
							$soal = $soal.'
									<tr>
										<td width="5%"> </td>
										<td width="5%" valign="top">'.$kunci.'</td>
										<td width="75%">'.$this->_sanitize_question_html($this->innerHTML($cols->item(2))).'</td>
										<td width="15%"></td>
									</tr>
								';
						}
					}else{
						// menghentikan loop ketika ada yang kosong
						break;
					}
			   }
			   $soal = $soal.'</table>';
			   
			   if($counter>1){
				   $status['topik'] = $id_topik;
				   $status['soal'] = $soal;
				   $status['status'] = 1;
				   $status['pesan'] = '';
			   }else{
				   $status['status'] = 0;
				   $status['pesan'] = 'Silahkan cek format soal terlebih dahulu';
			   }
			}else{
				$status['status'] = 0;
				$status['pesan'] = 'Terjadi kesalahan format soal. Silahkan cek format soal terlebih dahulu';
			}
        }else{
        	$status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        echo json_encode($status);
    }
	
	function konfirmasi(){
		$this->load->library('form_validation');
        
        $this->form_validation->set_rules('konfirmasi-topik', 'Topik','required');
		$this->form_validation->set_rules('konfirmasi-import-soal', 'Daftar Soal','required');
		if($this->form_validation->run() == TRUE){
			$id_topik = (int)$this->input->post('konfirmasi-topik', true);
			$daftar_soal = $this->input->post('konfirmasi-import-soal', false);
	    	$posisi = $this->config->item('upload_path').'/topik_'.$id_topik.'';
			
			$doc = new DOMDocument();
			$prev_err = libxml_use_internal_errors(true);
			$doc->loadHTML('<?xml encoding="UTF-8">' . $daftar_soal, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
			libxml_clear_errors();
			libxml_use_internal_errors($prev_err);
			$tables = $doc->getElementsByTagName('table');
			if(!empty($tables->item(0)->nodeValue)){
				$rows = $tables->item(0)->getElementsByTagName('tr');
				$counter_soal = 0;
				$counter_jawaban = 0;
				$soal_id = 0;
				$saved_imgs = 0;
				foreach ($rows as $row) {
					/*** get each column by tag name ***/ 
					$cols = $row->getElementsByTagName('td'); 
					$kosong = 0;
					
					if(empty($cols->item(1)->nodeValue)){
						$kosong++;
					}
					if(empty($cols->item(2)->nodeValue)){
						$kosong++;
					}
					if(empty($cols->item(3)->nodeValue)){
						$kosong++;
					}
					if($kosong==0){
						$jenis = strtoupper(preg_replace('/\s+/', '',$cols->item(1)->nodeValue));
						$kunci = strtoupper(preg_replace('/\s+/', '',$cols->item(3)->nodeValue));
						$kunci = intval($kunci);
						if($kunci>1){
							$kunci=1;
						}
						if($jenis=='SOAL'){
							// Menyimpan image base64 menjadi file dengan whitelist ekstensi aman
							$soal = $this->_sanitize_question_html($this->innerHTML($cols->item(2)));
							$soal = $this->_extract_base64_images_in_html($soal, $posisi, $saved_imgs);
							$soal = str_replace(base_url(),"[base_url]", $soal);
							
							// Simpan soal
							$question['soal_topik_id'] = $id_topik;
							$question['soal_detail'] = $soal;
							$question['soal_tipe'] = '1';
							$question['soal_difficulty'] = '1';
							$question['soal_aktif'] = 1;
							
							$soal_id = $this->cbt_soal_model->save($question);
							$counter_soal++;
						}else if($jenis=='JAWABAN'){
							if($soal_id!=0){
								$jawaban = $this->_sanitize_question_html($this->innerHTML($cols->item(2)));
								$jawaban = $this->_extract_base64_images_in_html($jawaban, $posisi, $saved_imgs);
								$jawaban = str_replace(base_url(),"[base_url]", $jawaban);
								
								$data['jawaban_soal_id'] = $soal_id;
								$data['jawaban_benar'] = $kunci;
								$data['jawaban_aktif'] = 1;
								$data['jawaban_detail'] = $jawaban;
								
								$this->cbt_jawaban_model->save($data);
								$counter_jawaban++;
							}
						}
					}else{
						// menghentikan loop ketika ada yang kosong
						break;
					}
			   }
			   $soal = $soal.'</table>';
			   
			   if($counter_soal>0){
					$status['counter_soal'] = $counter_soal;
					$status['counter_jawaban'] = $counter_jawaban;
					$status['status'] = 1;
					$status['pesan'] = 'Daftar soal berhasil di import';
			   }else{
					$status['status'] = 0;
					$status['pesan'] = 'Silahkan cek format soal terlebih dahulu';
			   }
			}else{
				$status['status'] = 0;
				$status['pesan'] = 'Terjadi kesalahan format soal. Silahkan cek format soal terlebih dahulu';
			}
		}else{
			$status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        echo json_encode($status);
	}
	
	function innerHTML(DOMNode $n, $include_target_tag = false ) {
		$doc = new DOMDocument();
		$doc->appendChild( $doc->importNode( $n, true ) );
		$html = trim( $doc->saveHTML() );
		if ( $include_target_tag ) {
			return $html;
		}
		return preg_replace( '@^<' . $n->nodeName .'[^>]*>|</'. $n->nodeName .'>$@', '', $html );
	}

	/**
	 * Sanitasi HTML soal/jawaban agar aman dari Stored XSS (membuang tag berbahaya, event handler on*, dan URI javascript:)
	 */
	private function _sanitize_question_html($html) {
		$html = (string) $html;
		if ($html === '') {
			return '';
		}
		// Hapus blok tag berbahaya beserta isinya
		$html = preg_replace('#<(script|iframe|object|embed|applet|form|input|button|select|textarea|meta|link|base|style)[^>]*>.*?</\1>#is', '', $html);
		$html = preg_replace('#<(script|iframe|object|embed|applet|form|input|button|select|textarea|meta|link|base|style)[^>]*/?>#is', '', $html);
		// Hapus atribut event handler on* (onclick, onerror, onload, dll.)
		$html = preg_replace('/\s+on[a-z]+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
		// Netralisir javascript:/vbscript:/data:text/html pada href/src
		$html = preg_replace('/\s+(href|src|action|xlink:href)\s*=\s*(["\']?)\s*(?:javascript|vbscript|data\s*:\s*text\/html)[^"\'>\s]*\2/i', '', $html);
		return trim($html);
	}

    function import_file($inputfile, $id_topik){
        $this->load->library('excel');
        $inputFileName = './public/uploads/'.$inputfile;

        $excel = PHPExcel_IOFactory::load($inputFileName);
        $worksheet = $excel->getSheet(0);
        $highestRow = $worksheet->getHighestRow();
        $pesan='<div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-info"></i> Informasi!</h4>';
        
        if($highestRow>10){
            $jmlsoalsukses=0;
            $jmlsoalerror=0;
            $row=6;
            $kosong=0;
            while($kosong<2){
                $kosong=0;
                $kolom1 = $worksheet->getCellByColumnAndRow(2, $row)->getValue();//jenis, soal atau jawaban
                $kolom2 = $worksheet->getCellByColumnAndRow(3, $row)->getValue();//isi 
                $kolom3 = $worksheet->getCellByColumnAndRow(4, $row)->getValue();//jawaban benar atau salah
                $kolom4 = $worksheet->getCellByColumnAndRow(5, $row)->getValue();//tingkat kesulitan
                
                if(empty($kolom1)){ $kosong=+2; }
                if(empty($kolom2)){ $kosong=+2; }
                if(empty($kolom4) and $kolom1=='Q'){ $kosong++; }
                
                if($kosong==0){
                	// Merubah html special char menjadi kode
                	$kolom2 = htmlspecialchars($kolom2);

                	// Menambah tag br untuk baris baru
                	$kolom2 = str_replace("\r","<br />",$kolom2);
                	$kolom2 = str_replace("\n","<br />",$kolom2);
                	/**
                	 * Jika tipe adalah Question
                	 */
                	if($kolom1=='Q'){
                		$question['soal_topik_id'] = $id_topik;
			        	$question['soal_detail'] = $kolom2;
			        	$question['soal_tipe'] = '1';
			        	$question['soal_difficulty'] = $kolom4;
			        	$question['soal_aktif'] = 1;

                		$soal_id = $this->cbt_soal_model->save($question);
                		$jmlsoalsukses++;


                	/**
                	 * Jika tipe adalah Answer
                	 */
                	}else if($kolom1=='A'){
				        $answer['jawaban_detail'] = $kolom2;
				        if(!empty($kolom3)){
				        	$answer['jawaban_benar'] = $kolom3;
				        }else{
				        	$answer['jawaban_benar'] = '0';
				        }
				        $answer['jawaban_aktif'] = 1;
				        $answer['jawaban_soal_id'] = $soal_id;

				        $this->cbt_jawaban_model->save($answer);
                	}

                }else{
                	if($kosong<2){
                		$pesan=$pesan.'Baris ke  '.$row.' GAGAL di simpan : '.$kolom2.'<br>';
                    	$jmlsoalerror++;
                	}
                }
                
                $row++;
            }
            $pesan = $pesan.'<br>Jumlah soal yang berhasil diimport adalah '.$jmlsoalsukses.'<br>
                            Jumlah soal yang gagal di dimport adalah '.$jmlsoalerror.'<br>
                            Jumlah total baris yang diproses adalah '.($row-6).'<br>';
        }else{
            $pesan = $pesan.'Tidak Ada Yang Berhasil Di IMPORT. Cek kembali file excel yang dikirim';
        }
        $pesan = $pesan.'</div>';
        
        return $pesan;
    }
	
	
	function upload_file(){
    	$this->load->library('form_validation');
        
        $this->form_validation->set_rules('image-topik-id', 'Topik','required');

        if($this->form_validation->run() == TRUE){
        	$id_topik = $this->input->post('image-topik-id', true);
	    	$posisi = $this->config->item('upload_path').'/topik_'.$id_topik;

	    	if(!is_dir($posisi)){
	        	mkdir($posisi);
	        }

	    	$field_name = 'image-file';
	        if(!empty($_FILES[$field_name]['name'])){
		    	$config['upload_path'] = $posisi;
			    $config['allowed_types'] = 'jpg|png|jpeg|gif';
			    $config['max_size']	= '0';
			    $config['overwrite'] = true;
			    $config['file_name'] = strtolower($_FILES[$field_name]['name']);

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
		                $status['image'] = '<img src="'.base_url().$posisi.'/'.$upload_data['file_name'].'" style="max-height: 110px;" />';
		                $status['image_isi'] = '<img src="'.base_url().$posisi.'/'.$upload_data['file_name'].'" style="max-width: 600px;" />';
		            }   	
		    	}     
	        }else{
	        	$status['status'] = 0;
	            $status['pesan'] = 'Pilih terlebih dahulu file yang akan di upload';
	        }
        }else{
        	$status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        echo json_encode($status);
    }
	
	function get_datatable_image(){
		$topik = $this->input->get('topik');
		if(!empty($topik)){
			$posisi = $this->config->item('upload_path').'/topik_'.$topik;
		}else{
			$posisi = $this->config->item('upload_path');
		}

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
		if(!is_dir($posisi)){
			mkdir($posisi);
	    }
		$query = directory_map($posisi, 1);

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

			if(!is_dir($posisi.'/'.$temp)){
            	if($info['extension']=='jpg' or $info['extension']=='png' or $info['extension']=='jpeg' or $info['extension']=='gif'){
            		$file_info = get_file_info($posisi.'/'.$temp);

            		$record[] = '<a style="cursor:pointer;" onclick="image_preview(\''.$posisi.'\',\''.$temp.'\')">'.$posisi.'/'.$temp.'</a>';
            		$record[] = '<a style="cursor:pointer;" onclick="image_preview(\''.$posisi.'\',\''.$temp.'\')"><img src="'.base_url().$posisi.'/'.$temp.'" height="40" /></a>';
            		$record[] = date('Y-m-d H:i:s', $file_info['date']);
            		$record[] = '<a onclick="image_preview(\''.$posisi.'\',\''.$temp.'\')" style="cursor: pointer;" class="btn btn-default btn-xs">Pilih</a>';
            		$output['aaData'][] = $record;

					$iTotal++;
            	}
        	}
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

	/**
	 * Batch Upload Soal (.docx / .zip) — Tahap 1: Upload & Analisis Preview
	 */
	public function batch_preview() {
		$status = array('status' => 0, 'pesan' => '');

		if (!class_exists('ZipArchive') || !class_exists('DOMDocument')) {
			$status['pesan'] = 'Ekstensi PHP ZipArchive / DOMDocument tidak tersedia di server.';
			echo json_encode($status);
			return;
		}

		$ctx = $this->_get_batch_context();
		if (empty($ctx['modul_id']) && empty($ctx['topics'])) {
			$status['pesan'] = 'Tidak ada Modul Aktif atau Topik yang dapat diakses. Aktifkan modul terlebih dahulu di menu Topik.';
			echo json_encode($status);
			return;
		}

		if (empty($_FILES['files']) || empty($_FILES['files']['name'])) {
			$status['pesan'] = 'Pilih minimal satu file .docx atau .zip terlebih dahulu.';
			echo json_encode($status);
			return;
		}

		$base_tmp = './public/uploads/batch_tmp';
		if (!is_dir($base_tmp)) {
			@mkdir($base_tmp, 0777, true);
		}
		if (!is_dir($base_tmp) || !is_writable($base_tmp)) {
			$status['pesan'] = 'Folder sementara public/uploads/batch_tmp tidak dapat ditulis.';
			echo json_encode($status);
			return;
		}

		$this->_cleanup_old_batch_tmp($base_tmp, 7200);

		try {
			$batch_token = bin2hex(random_bytes(16));
		} catch (Exception $e) {
			$batch_token = md5(uniqid('batch_', true));
		}

		$batch_dir = $base_tmp . '/' . $batch_token;
		if (!@mkdir($batch_dir, 0777, true)) {
			$status['pesan'] = 'Gagal membuat direktori kerja batch.';
			echo json_encode($status);
			return;
		}

		$names = is_array($_FILES['files']['name']) ? $_FILES['files']['name'] : array($_FILES['files']['name']);
		$tmps = is_array($_FILES['files']['tmp_name']) ? $_FILES['files']['tmp_name'] : array($_FILES['files']['tmp_name']);
		$errors = is_array($_FILES['files']['error']) ? $_FILES['files']['error'] : array($_FILES['files']['error']);
		$sizes = isset($_FILES['files']['size'])
			? (is_array($_FILES['files']['size']) ? $_FILES['files']['size'] : array($_FILES['files']['size']))
			: array();

		$max_files_per_batch = 60;
		$max_file_bytes = 25 * 1024 * 1024; // 25 MB per file
		if (count($names) > $max_files_per_batch) {
			$this->_remove_dir_recursive($batch_dir);
			$status['pesan'] = 'Maksimal ' . $max_files_per_batch . ' file dalam satu kali batch upload.';
			echo json_encode($status);
			return;
		}

		$manifest = array();
		$results = array();
		$file_index = 0;
		$skipped_notes = array();

		for ($i = 0; $i < count($names); $i++) {
			$orig_name = trim((string) $names[$i]);
			if ($orig_name === '') {
				continue;
			}
			if ($errors[$i] !== UPLOAD_ERR_OK) {
				$skipped_notes[] = $orig_name . ' (gagal upload kode ' . intval($errors[$i]) . ')';
				continue;
			}
			if (isset($sizes[$i]) && intval($sizes[$i]) > $max_file_bytes) {
				$skipped_notes[] = $orig_name . ' (melebihi batas ukuran 25 MB)';
				continue;
			}

			$ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
			if ($ext === 'docx' || $ext === 'doc') {
				$stored_name = 'file_' . $file_index . '.' . $ext;
				$dest = $batch_dir . '/' . $stored_name;
				if (@move_uploaded_file($tmps[$i], $dest) || @copy($tmps[$i], $dest)) {
					$entry = $this->_analyze_staged_docx($file_index, $orig_name, $stored_name, $dest, $ctx, '');
					$manifest[] = $entry['manifest'];
					$results[] = $entry['preview'];
					$file_index++;
				} else {
					$skipped_notes[] = $orig_name . ' (gagal menyimpan file sementara)';
				}
			} elseif ($ext === 'zip') {
				$zip = new ZipArchive();
				if ($zip->open($tmps[$i]) === TRUE) {
					$found_in_zip = 0;
					for ($zi = 0; $zi < $zip->numFiles; $zi++) {
						$entry_name = $zip->getNameIndex($zi);
						if ($entry_name === false) {
							continue;
						}
						// Skip direktori, __MACOSX, dan file sementara Word (~$...) atau hidden (._...)
						if (substr($entry_name, -1) === '/') {
							continue;
						}
						if (stripos($entry_name, '__MACOSX/') !== false) {
							continue;
						}
						$base_entry = basename($entry_name);
						if (strpos($base_entry, '._') === 0 || strpos($base_entry, '~$') === 0) {
							continue;
						}
						$entry_ext = strtolower(pathinfo($base_entry, PATHINFO_EXTENSION));
						if ($entry_ext !== 'docx' && $entry_ext !== 'doc') {
							continue;
						}

						$content = $zip->getFromIndex($zi);
						if ($content === false) {
							continue;
						}

						$parent_dir = trim((string) dirname($entry_name), './');
						if ($parent_dir === '.' || $parent_dir === '') {
							$parent_dir = '';
						} else {
							$parent_dir = basename($parent_dir);
						}

						$stored_name = 'file_' . $file_index . '.' . $entry_ext;
						$dest = $batch_dir . '/' . $stored_name;
						if (@file_put_contents($dest, $content) !== false) {
							$entry = $this->_analyze_staged_docx($file_index, $base_entry, $stored_name, $dest, $ctx, $parent_dir);
							$manifest[] = $entry['manifest'];
							$results[] = $entry['preview'];
							$file_index++;
							$found_in_zip++;
						}
					}
					$zip->close();
					if ($found_in_zip === 0) {
						$skipped_notes[] = $orig_name . ' (tidak ditemukan file .docx/.doc di dalam arsip ZIP)';
					}
				} else {
					$skipped_notes[] = $orig_name . ' (arsip ZIP rusak atau tidak dapat dibuka)';
				}
			} else {
				$skipped_notes[] = $orig_name . ' (format tidak didukung, gunakan .docx, .doc, atau .zip)';
			}
		}

		if (empty($results)) {
			$this->_remove_dir_recursive($batch_dir);
			$pesan = 'Tidak ada file .docx / .doc valid yang ditemukan.';
			if (!empty($skipped_notes)) {
				$pesan .= ' Catatan: ' . implode('; ', $skipped_notes);
			}
			$status['pesan'] = $pesan;
			echo json_encode($status);
			return;
		}

		// Deteksi duplikat identik (MD5) & bedakan nama file yang sama antar folder
		$this->_disambiguate_batch_results($results, $manifest, $ctx);

		file_put_contents($batch_dir . '/manifest.json', json_encode(array(
			'created_at' => time(),
			'modul_id' => $ctx['modul_id'],
			'files' => $manifest
		)));

		$total_soal = 0;
		$total_esai = 0;
		$total_jawaban = 0;
		$total_gambar = 0;
		$total_siap = 0;
		$total_duplikat = 0;
		$total_file_ada_esai = 0;
		foreach ($results as $r) {
			if (!empty($r['is_duplicate_content'])) {
				$total_duplikat++;
				continue;
			}
			$total_soal += $r['jumlah_soal'];
			$total_esai += $r['jumlah_esai'];
			$total_jawaban += $r['jumlah_jawaban'];
			$total_gambar += $r['jumlah_gambar'];
			if ($r['jumlah_esai'] > 0) {
				$total_file_ada_esai++;
			}
			if ($r['status'] !== 'error') {
				$total_siap++;
			}
		}

		$status['status'] = 1;
		$status['batch_token'] = $batch_token;
		$status['modul_id'] = $ctx['modul_id'];
		$status['modul_nama'] = $ctx['modul_nama'];
		$status['can_create_topik'] = $ctx['can_create_topik'];
		$status['topics'] = $ctx['topics'];
		$status['files'] = $results;
		$status['summary'] = array(
			'total_file' => count($results),
			'total_siap' => $total_siap,
			'total_duplikat' => $total_duplikat,
			'total_file_ada_esai' => $total_file_ada_esai,
			'total_soal' => $total_soal,
			'total_esai' => $total_esai,
			'total_jawaban' => $total_jawaban,
			'total_gambar' => $total_gambar
		);
		$status['skipped_notes'] = $skipped_notes;
		$status['pesan'] = 'Analisis selesai: ' . count($results) . ' file diperiksa' . ($total_duplikat > 0 ? ' (' . $total_duplikat . ' file duplikat identik dilewati otomatis).' : '.');

		echo json_encode($status);
	}

	/**
	 * Batch Upload Soal (.docx / .doc / .zip) — Tahap 2: Eksekusi Simpan ke Bank Soal
	 * Mode:
	 *  - pg_esai (default): PG ke topik utama, Esai otomatis ke topik terpisah berawalan "Esai "
	 *  - pg_only          : Hanya import Pilihan Ganda, abaikan tabel Esai
	 */
	public function batch_simpan() {
		$status = array('status' => 0, 'pesan' => '');

		$batch_token = trim((string) $this->input->post('batch_token', true));
		if (!preg_match('/^[a-f0-9]{32}$/', $batch_token)) {
			$status['pesan'] = 'Sesi batch tidak valid atau sudah kedaluwarsa. Silakan upload ulang.';
			echo json_encode($status);
			return;
		}

		$batch_dir = './public/uploads/batch_tmp/' . $batch_token;
		$manifest_path = $batch_dir . '/manifest.json';
		if (!is_file($manifest_path)) {
			$status['pesan'] = 'Data file batch sudah tidak ditemukan (kedaluwarsa). Silakan upload ulang.';
			echo json_encode($status);
			return;
		}

		$manifest = json_decode(file_get_contents($manifest_path), true);
		if (empty($manifest) || empty($manifest['files'])) {
			$this->_remove_dir_recursive($batch_dir);
			$status['pesan'] = 'Manifest batch kosong.';
			echo json_encode($status);
			return;
		}

		$selected_indices = $this->input->post('include_idx');
		$target_topik_map = $this->input->post('target_topik');
		$new_topik_map = $this->input->post('new_topik_name');

		// Support import_mode ('pg_esai' | 'pg_only') + fallback include_esai
		$import_mode = trim((string) $this->input->post('import_mode', true));
		if ($import_mode === '') {
			$import_mode = ($this->input->post('include_esai') === '0') ? 'pg_only' : 'pg_esai';
		}
		$include_esai = ($import_mode !== 'pg_only');

		if (empty($selected_indices) || !is_array($selected_indices)) {
			$status['pesan'] = 'Pilih minimal satu file yang akan diimport.';
			echo json_encode($status);
			return;
		}

		$ctx = $this->_get_batch_context();
		$allowed_topic_ids = array();
		$topic_names_by_id = array();
		foreach ($ctx['topics'] as $t) {
			$allowed_topic_ids[intval($t['id'])] = true;
			$topic_names_by_id[intval($t['id'])] = $t['nama'];
		}

		$files_by_idx = array();
		foreach ($manifest['files'] as $mf) {
			$files_by_idx[intval($mf['index'])] = $mf;
		}

		$reports = array();
		$grand_pg = 0;
		$grand_esai = 0;
		$grand_jawaban = 0;
		$grand_gambar = 0;
		$grand_topik_baru = 0;
		$berhasil_file = 0;

		foreach ($selected_indices as $raw_idx) {
			$idx = intval($raw_idx);
			if (!isset($files_by_idx[$idx])) {
				continue;
			}
			$mf = $files_by_idx[$idx];
			$filepath = $batch_dir . '/' . basename($mf['stored_file']);
			if (!is_file($filepath)) {
				$reports[] = array(
					'filename' => $mf['original_name'],
					'status' => 'error',
					'pesan' => 'File sementara tidak ditemukan.'
				);
				continue;
			}

			$parsed = $this->_parse_word_file($filepath);
			$pg_items = !empty($parsed['items']) ? $parsed['items'] : array();
			$esai_items = ($include_esai && !empty($parsed['esai_items'])) ? $parsed['esai_items'] : array();

			if (!$parsed['ok']) {
				$reports[] = array(
					'filename' => $mf['original_name'],
					'status' => 'error',
					'pesan' => !empty($parsed['error']) ? $parsed['error'] : 'Gagal membaca file.'
				);
				continue;
			}
			if (empty($pg_items) && empty($esai_items)) {
				$reports[] = array(
					'filename' => $mf['original_name'],
					'status' => 'error',
					'pesan' => (!$include_esai && !empty($parsed['esai_items']))
						? 'Dilewati: file ini hanya berisi soal Esai (mode Pilihan Ganda Saja aktif).'
						: 'Tidak ada soal valid.'
				);
				continue;
			}

			$target_val = isset($target_topik_map[$idx]) ? trim((string) $target_topik_map[$idx]) : '';
			$raw_candidate = isset($new_topik_map[$idx]) ? trim((string) $new_topik_map[$idx]) : '';
			if ($raw_candidate === '') {
				$raw_candidate = $mf['clean_name'];
			}

			// Tentukan base nama topik untuk PG dan Esai
			$base_topic_name = $raw_candidate;
			if ($target_val !== '__NEW__') {
				$tid_check = intval($target_val);
				if ($tid_check > 0 && isset($topic_names_by_id[$tid_check])) {
					$base_topic_name = $topic_names_by_id[$tid_check];
				}
			}

			// 1. Resolve topik PG (hanya dibuat/dipakai jika ada soal PG)
			$pg_topik_id = 0;
			$pg_topik_nama = '';
			$created_pg_topic = false;

			if (!empty($pg_items)) {
				if ($target_val === '__NEW__') {
					if (!$ctx['can_create_topik'] || empty($ctx['modul_id'])) {
						$reports[] = array(
							'filename' => $mf['original_name'],
							'status' => 'error',
							'pesan' => 'Akun ini tidak diizinkan membuat topik baru.'
						);
						continue;
					}
					$res_pg_topic = $this->_find_or_create_topic_in_modul(
						$ctx['modul_id'],
						$raw_candidate,
						'Topik ' . $raw_candidate,
						$allowed_topic_ids,
						$topic_names_by_id,
						$grand_topik_baru
					);
					$pg_topik_id = $res_pg_topic['id'];
					$pg_topik_nama = $res_pg_topic['nama'];
					$created_pg_topic = $res_pg_topic['created'];
					$base_topic_name = $pg_topik_nama;
				} else {
					$pg_topik_id = intval($target_val);
					if ($pg_topik_id <= 0 || !isset($allowed_topic_ids[$pg_topik_id])) {
						$reports[] = array(
							'filename' => $mf['original_name'],
							'status' => 'error',
							'pesan' => 'Topik tujuan belum dipilih atau tidak diizinkan.'
						);
						continue;
					}
					$pg_topik_nama = $topic_names_by_id[$pg_topik_id];
					$base_topic_name = $pg_topik_nama;
				}
			}

			// 2. Resolve topik Esai terpisah (hanya dibuat/dipakai jika ada soal Esai & mode pg_esai)
			$esai_topik_id = 0;
			$esai_topik_nama = '';
			$created_esai_topic = false;

			if (!empty($esai_items)) {
				$esai_candidate = $this->_build_esai_topic_name($base_topic_name);
				if ($ctx['can_create_topik'] && !empty($ctx['modul_id'])) {
					$res_esai_topic = $this->_find_or_create_topic_in_modul(
						$ctx['modul_id'],
						$esai_candidate,
						'Soal Esai ' . $base_topic_name,
						$allowed_topic_ids,
						$topic_names_by_id,
						$grand_topik_baru
					);
					$esai_topik_id = $res_esai_topic['id'];
					$esai_topik_nama = $res_esai_topic['nama'];
					$created_esai_topic = $res_esai_topic['created'];
				} else {
					// User dibatasi (opsi1): cari apakah topik Esai ada di daftar izinnya
					foreach ($topic_names_by_id as $tid_allowed => $tnama_allowed) {
						if (strtolower($tnama_allowed) === strtolower($esai_candidate)) {
							$esai_topik_id = $tid_allowed;
							$esai_topik_nama = $tnama_allowed;
							break;
						}
					}
					if ($esai_topik_id <= 0) {
						if ($pg_topik_id > 0) {
							// Fallback ke topik PG hanya untuk user terbatas yang tak boleh bikin topik baru
							$esai_topik_id = $pg_topik_id;
							$esai_topik_nama = $pg_topik_nama;
						} else {
							$reports[] = array(
								'filename' => $mf['original_name'],
								'status' => 'error',
								'pesan' => 'Akun ini tidak diizinkan membuat topik Esai baru.'
							);
							continue;
						}
					}
				}
			}

			// 3. Ekstrak gambar terpisah ke folder topik masing-masing
			$saved_img_count = 0;
			$pg_upload_path = ($pg_topik_id > 0) ? ($this->config->item('upload_path') . '/topik_' . $pg_topik_id) : '';
			$esai_upload_path = ($esai_topik_id > 0) ? ($this->config->item('upload_path') . '/topik_' . $esai_topik_id) : '';

			$pg_img_map = array();
			if (!empty($pg_items) && !empty($parsed['used_rids'])) {
				$pg_rids = $this->_collect_rids_from_items($pg_items);
				if (!empty($pg_rids)) {
					$ex_pg = $this->_extract_docx_images($filepath, $parsed['rels'], $pg_rids, $pg_upload_path);
					$pg_img_map = $ex_pg['map'];
					$saved_img_count += $ex_pg['count'];
				}
			}

			$esai_img_map = array();
			if (!empty($esai_items) && !empty($parsed['used_rids'])) {
				$esai_rids = $this->_collect_rids_from_items($esai_items);
				if (!empty($esai_rids)) {
					$ex_esai = $this->_extract_docx_images($filepath, $parsed['rels'], $esai_rids, $esai_upload_path);
					$esai_img_map = $ex_esai['map'];
					$saved_img_count += $ex_esai['count'];
				}
			}

			$this->db->trans_start();
			$cnt_soal = 0;
			$cnt_esai = 0;
			$cnt_jawaban = 0;

			foreach ($pg_items as $item) {
				$soal_html = $this->_apply_image_placeholders($item['soal_html'], $pg_img_map);
				$soal_html = $this->_extract_base64_images_in_html($soal_html, $pg_upload_path, $saved_img_count);
				$question = array(
					'soal_topik_id' => $pg_topik_id,
					'soal_detail' => $soal_html,
					'soal_tipe' => '1',
					'soal_difficulty' => '1',
					'soal_aktif' => 1
				);
				$soal_id = $this->cbt_soal_model->save($question);
				$cnt_soal++;

				if (!empty($item['jawaban'])) {
					foreach ($item['jawaban'] as $jwb) {
						$jwb_html = $this->_apply_image_placeholders($jwb['html'], $pg_img_map);
						$jwb_html = $this->_extract_base64_images_in_html($jwb_html, $pg_upload_path, $saved_img_count);
						$data_jwb = array(
							'jawaban_soal_id' => $soal_id,
							'jawaban_benar' => intval($jwb['benar']),
							'jawaban_aktif' => 1,
							'jawaban_detail' => $jwb_html
						);
						$this->cbt_jawaban_model->save($data_jwb);
						$cnt_jawaban++;
					}
				}
			}

			foreach ($esai_items as $eitem) {
				$esai_html = $this->_apply_image_placeholders($eitem['soal_html'], $esai_img_map);
				$esai_html = $this->_extract_base64_images_in_html($esai_html, $esai_upload_path, $saved_img_count);
				$question = array(
					'soal_topik_id' => $esai_topik_id,
					'soal_detail' => $esai_html,
					'soal_tipe' => '2',
					'soal_difficulty' => '1',
					'soal_aktif' => 1
				);
				$this->cbt_soal_model->save($question);
				$cnt_esai++;
			}
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$reports[] = array(
					'filename' => $mf['original_name'],
					'status' => 'error',
					'pesan' => 'Gagal menyimpan ke database (transaksi dibatalkan).'
				);
			} else {
				$berhasil_file++;
				$grand_pg += $cnt_soal;
				$grand_esai += $cnt_esai;
				$grand_jawaban += $cnt_jawaban;
				$grand_gambar += $saved_img_count;
				$reports[] = array(
					'filename' => $mf['original_name'],
					'status' => 'ok',
					'topik_id' => $pg_topik_id,
					'topik_nama' => $pg_topik_nama,
					'created_new_topic' => $created_pg_topic,
					'esai_topik_id' => $esai_topik_id,
					'esai_topik_nama' => $esai_topik_nama,
					'created_esai_topic' => $created_esai_topic,
					'jumlah_soal' => $cnt_soal,
					'jumlah_esai' => $cnt_esai,
					'jumlah_jawaban' => $cnt_jawaban,
					'jumlah_gambar' => $saved_img_count,
					'pesan' => 'Berhasil disimpan'
				);
			}
		}

		// Bersihkan folder staging setelah eksekusi selesai
		$this->_remove_dir_recursive($batch_dir);

		$grand_total_soal = $grand_pg + $grand_esai;
		$status['status'] = ($berhasil_file > 0) ? 1 : 0;
		$status['reports'] = $reports;
		$status['summary'] = array(
			'berhasil_file' => $berhasil_file,
			'total_dipilih' => count($selected_indices),
			'total_soal' => $grand_total_soal,
			'total_pg' => $grand_pg,
			'total_esai' => $grand_esai,
			'total_jawaban' => $grand_jawaban,
			'total_gambar' => $grand_gambar,
			'total_topik_baru' => $grand_topik_baru
		);
		$status['pesan'] = ($berhasil_file > 0)
			? 'Batch import selesai: ' . $berhasil_file . ' file (' . $grand_pg . ' PG' . ($grand_esai > 0 ? ' + ' . $grand_esai . ' Esai terpisah' : '') . ', ' . $grand_jawaban . ' opsi) berhasil masuk ke Bank Soal.'
			: 'Tidak ada file yang berhasil diimport. Cek detail laporan di bawah.';

		echo json_encode($status);
	}

	private function _get_batch_context() {
		$ctx = array(
			'modul_id' => 0,
			'modul_nama' => '',
			'can_create_topik' => false,
			'topics' => array()
		);

		$query_user = $this->users_model->get_user_by_username($this->access->get_username());
		if ($query_user->num_rows() === 0) {
			return $ctx;
		}
		$user = $query_user->row();

		if (!empty($user->opsi1)) {
			$user_topik = explode(',', $user->opsi1);
			foreach ($user_topik as $topik_id) {
				$query_topik = $this->cbt_topik_model->get_by_kolom_join_modul('topik_id', trim($topik_id));
				if ($query_topik->num_rows() > 0) {
					$t = $query_topik->row();
					if ($ctx['modul_id'] === 0) {
						$ctx['modul_id'] = intval($t->topik_modul_id);
						$ctx['modul_nama'] = $t->modul_nama;
					}
					$jml = intval($this->cbt_soal_model->count_by_kolom('soal_topik_id', $t->topik_id)->row()->hasil);
					$ctx['topics'][] = array(
						'id' => intval($t->topik_id),
						'nama' => $t->topik_nama,
						'modul_nama' => $t->modul_nama,
						'jml_soal' => $jml
					);
				}
			}
		} else {
			$ctx['can_create_topik'] = true;
			$query_modul = $this->cbt_modul_model->get_modul();
			if ($query_modul->num_rows() > 0) {
				$moduls = $query_modul->result();
				$ctx['modul_id'] = intval($moduls[0]->modul_id);
				$ctx['modul_nama'] = $moduls[0]->modul_nama;
				foreach ($moduls as $m) {
					$query_topik = $this->cbt_topik_model->get_by_kolom_join_modul('topik_modul_id', $m->modul_id);
					if ($query_topik->num_rows() > 0) {
						foreach ($query_topik->result() as $t) {
							$jml = intval($this->cbt_soal_model->count_by_kolom('soal_topik_id', $t->topik_id)->row()->hasil);
							$ctx['topics'][] = array(
								'id' => intval($t->topik_id),
								'nama' => $t->topik_nama,
								'modul_nama' => $t->modul_nama,
								'jml_soal' => $jml
							);
						}
					}
				}
			}
		}

		return $ctx;
	}

	private function _analyze_staged_docx($index, $orig_name, $stored_name, $filepath, $ctx, $parent_dir = '') {
		$clean_name = $this->_clean_filename_to_topic($orig_name, $parent_dir);
		$display_name = ($parent_dir !== '') ? ('[' . $parent_dir . '] ' . $orig_name) : $orig_name;
		$content_hash = @md5_file($filepath);
		if ($content_hash === false) {
			$content_hash = '';
		}
		$parsed = $this->_parse_word_file($filepath);
		$match = $this->_match_topic($clean_name, $ctx['topics']);

		$suggested_topik = '';
		if ($match['topik_id'] > 0) {
			$suggested_topik = (string) $match['topik_id'];
		} elseif ($ctx['can_create_topik']) {
			$suggested_topik = '__NEW__';
		} elseif (!empty($ctx['topics'])) {
			$suggested_topik = (string) $ctx['topics'][0]['id'];
		}

		$file_status = 'ok';
		$warnings = array();
		$total_all_soal = $parsed['jumlah_soal'] + $parsed['jumlah_esai'];
		if (!$parsed['ok'] || $total_all_soal === 0) {
			$file_status = 'error';
			$warnings[] = !empty($parsed['error']) ? $parsed['error'] : 'Tidak ditemukan baris SOAL sesuai template tabel ZyaCBT.';
		} else {
			if ($parsed['soal_tanpa_opsi'] > 0) {
				$file_status = 'warning';
				$warnings[] = $parsed['soal_tanpa_opsi'] . ' soal PG belum memiliki opsi JAWABAN.';
			}
			if ($parsed['soal_tanpa_kunci'] > 0) {
				$file_status = 'warning';
				$warnings[] = $parsed['soal_tanpa_kunci'] . ' soal PG belum memiliki kunci benar (nilai 1).';
			}
		}

		return array(
			'manifest' => array(
				'index' => $index,
				'original_name' => $display_name,
				'clean_name' => $clean_name,
				'parent_dir' => $parent_dir,
				'content_hash' => $content_hash,
				'stored_file' => $stored_name
			),
			'preview' => array(
				'index' => $index,
				'filename' => $display_name,
				'clean_name' => $clean_name,
				'parent_dir' => $parent_dir,
				'content_hash' => $content_hash,
				'format_label' => !empty($parsed['format_label']) ? $parsed['format_label'] : strtoupper(pathinfo($orig_name, PATHINFO_EXTENSION)),
				'jumlah_soal' => $parsed['jumlah_soal'],
				'jumlah_esai' => $parsed['jumlah_esai'],
				'jumlah_jawaban' => $parsed['jumlah_jawaban'],
				'jumlah_gambar' => $parsed['jumlah_gambar'],
				'soal_tanpa_kunci' => $parsed['soal_tanpa_kunci'],
				'soal_tanpa_opsi' => $parsed['soal_tanpa_opsi'],
				'status' => $file_status,
				'warnings' => $warnings,
				'suggested_topik' => $suggested_topik,
				'match_type' => $match['match_type'],
				'is_duplicate_content' => false,
				'duplicate_of' => '',
				'name_disambiguated' => false,
				'default_checked' => ($file_status !== 'error')
			)
		);
	}

	private function _disambiguate_batch_results(&$results, &$manifest, $ctx) {
		$seen_hashes = array();
		$name_groups = array();

		// Tahap 1: Deteksi duplikat identik (MD5 sama persis)
		foreach ($results as $k => &$r) {
			if ($r['status'] === 'error') {
				$r['default_checked'] = false;
				continue;
			}
			$hash = !empty($r['content_hash']) ? $r['content_hash'] : '';
			if ($hash !== '' && isset($seen_hashes[$hash])) {
				$r['is_duplicate_content'] = true;
				$r['duplicate_of'] = $seen_hashes[$hash];
				$r['default_checked'] = false;
				if ($r['status'] === 'ok') {
					$r['status'] = 'warning';
				}
				array_unshift(
					$r['warnings'],
					'Duplikat identik (100% sama) dengan ' . $seen_hashes[$hash] . ' — dilewati otomatis.'
				);
				continue;
			}
			if ($hash !== '') {
				$seen_hashes[$hash] = $r['filename'];
			}
			$norm = $this->_normalize_name($r['clean_name']);
			if ($norm !== '') {
				if (!isset($name_groups[$norm])) {
					$name_groups[$norm] = array();
				}
				$name_groups[$norm][] = $k;
			}
		}
		unset($r);

		// Tahap 2: Bedakan nama topik otomatis jika ada 2+ file beda isi tapi nama bersihnya sama
		foreach ($name_groups as $norm => $idxs) {
			if (count($idxs) <= 1) {
				continue;
			}
			$used_norms = array();
			foreach ($idxs as $k) {
				$base_clean = $results[$k]['clean_name'];
				$parent_dir = isset($results[$k]['parent_dir']) ? trim((string) $results[$k]['parent_dir']) : '';
				$new_name = $base_clean;

				if ($parent_dir !== '') {
					$dir_clean = trim(preg_replace('/\s+/', ' ', str_replace('_', ' ', $parent_dir)));
					$norm_dir = $this->_normalize_name($dir_clean);
					if ($norm_dir !== '' && strpos($norm_dir, $norm) !== false) {
						// Nama folder sudah memuat nama mapel (mis. "DINIYAH X" memuat "DINIYAH")
						$new_name = $dir_clean;
					} elseif ($dir_clean !== '') {
						$new_name = $base_clean . ' - ' . $dir_clean;
					}
				}

				$new_name = substr(trim($new_name), 0, 240);
				$candidate_norm = $this->_normalize_name($new_name);
				if (isset($used_norms[$candidate_norm])) {
					$used_norms[$candidate_norm]++;
					$new_name = substr($new_name, 0, 234) . ' (' . $used_norms[$candidate_norm] . ')';
					$candidate_norm = $this->_normalize_name($new_name);
				} else {
					$used_norms[$candidate_norm] = 1;
				}

				$results[$k]['clean_name'] = $new_name;
				$manifest[$k]['clean_name'] = $new_name;
				$results[$k]['name_disambiguated'] = true;

				// Cocokkan ulang secara exact agar tidak tertukar ke topik generik
				$exact_match = $this->_match_topic($new_name, $ctx['topics'], true);
				if ($exact_match['topik_id'] > 0) {
					$results[$k]['suggested_topik'] = (string) $exact_match['topik_id'];
					$results[$k]['match_type'] = $exact_match['match_type'];
				} elseif ($ctx['can_create_topik']) {
					$results[$k]['suggested_topik'] = '__NEW__';
					$results[$k]['match_type'] = 'none';
				}
			}
		}
	}

	private function _build_esai_topic_name($base_name) {
		$base_name = trim(preg_replace('/\s+/', ' ', (string) $base_name));
		if ($base_name === '') {
			$base_name = 'Soal';
		}
		if (preg_match('/^esai\b/i', $base_name)) {
			return substr($base_name, 0, 250);
		}
		return substr('Esai ' . $base_name, 0, 250);
	}

	private function _find_or_create_topic_in_modul($modul_id, $candidate_name, $detail, &$allowed_topic_ids, &$topic_names_by_id, &$grand_topik_baru) {
		$candidate_name = substr(trim((string) $candidate_name), 0, 250);
		$existing = $this->db->where('topik_modul_id', $modul_id)
			->where('topik_nama', $candidate_name)
			->limit(1)
			->get('cbt_topik');
		if ($existing->num_rows() > 0) {
			$row_t = $existing->row();
			$tid = intval($row_t->topik_id);
			$allowed_topic_ids[$tid] = true;
			$topic_names_by_id[$tid] = $row_t->topik_nama;
			return array('id' => $tid, 'nama' => $row_t->topik_nama, 'created' => false);
		}

		$data_topik = array(
			'topik_modul_id' => $modul_id,
			'topik_nama' => $candidate_name,
			'topik_detail' => substr($detail, 0, 255),
			'topik_aktif' => 1
		);
		$this->cbt_topik_model->save($data_topik);
		$tid = intval($this->db->insert_id());
		$allowed_topic_ids[$tid] = true;
		$topic_names_by_id[$tid] = $candidate_name;
		$grand_topik_baru++;
		return array('id' => $tid, 'nama' => $candidate_name, 'created' => true);
	}

	private function _collect_rids_from_items($items) {
		$rids = array();
		foreach ($items as $it) {
			if (!empty($it['soal_html']) && preg_match_all('/\[\[IMG_RID:([^\]]+)\]\]/', $it['soal_html'], $m)) {
				foreach ($m[1] as $rid) {
					$rids[$rid] = true;
				}
			}
			if (!empty($it['jawaban'])) {
				foreach ($it['jawaban'] as $j) {
					if (!empty($j['html']) && preg_match_all('/\[\[IMG_RID:([^\]]+)\]\]/', $j['html'], $m)) {
						foreach ($m[1] as $rid) {
							$rids[$rid] = true;
						}
					}
				}
			}
		}
		return array_keys($rids);
	}

	private function _clean_filename_to_topic($filename, $parent_dir = '') {
		$name = pathinfo($filename, PATHINFO_FILENAME);
		// Hilangkan prefix/token ZYA_CBT_ dan suffix duplikat browser seperti " (1)"
		$name = preg_replace('/\bZYA[_\s-]*CBT[_\s-]*/i', ' ', $name);
		$name = preg_replace('/\s*\(\d+\)\s*$/', '', $name);
		$name = str_replace('_', ' ', $name);
		$name = preg_replace('/\s+/', ' ', $name);
		$name = trim($name, " -");
		if ($name === '' && $parent_dir !== '') {
			$name = trim(preg_replace('/\s+/', ' ', str_replace('_', ' ', $parent_dir)));
		}
		return $name;
	}

	private function _normalize_name($str) {
		$str = strtolower(trim((string) $str));
		return preg_replace('/[^a-z0-9]+/', '', $str);
	}

	private function _match_topic($clean_name, $topics, $exact_only = false) {
		$norm_file = $this->_normalize_name($clean_name);
		if ($norm_file === '' || empty($topics)) {
			return array('topik_id' => 0, 'match_type' => 'none');
		}
		$file_is_esai = (preg_match('/^esai\b/i', trim((string) $clean_name)) === 1);

		// 1. Exact normalized match
		foreach ($topics as $t) {
			if ($this->_normalize_name($t['nama']) === $norm_file) {
				return array('topik_id' => intval($t['id']), 'match_type' => 'exact');
			}
		}

		if ($exact_only) {
			return array('topik_id' => 0, 'match_type' => 'none');
		}

		// 2. Partial contains match (minimal 4 karakter; jangan cocokkan file non-Esai ke topik Esai)
		if (strlen($norm_file) >= 4) {
			foreach ($topics as $t) {
				$t_is_esai = (preg_match('/^esai\b/i', trim((string) $t['nama'])) === 1);
				if ($t_is_esai && !$file_is_esai) {
					continue;
				}
				$norm_t = $this->_normalize_name($t['nama']);
				if (strlen($norm_t) >= 4 && (strpos($norm_file, $norm_t) !== false || strpos($norm_t, $norm_file) !== false)) {
					return array('topik_id' => intval($t['id']), 'match_type' => 'partial');
				}
			}
		}

		return array('topik_id' => 0, 'match_type' => 'none');
	}

	private function _parse_word_file($filepath) {
		$head = @file_get_contents($filepath, false, null, 0, 2048);
		if ($head === false || $head === '') {
			return array(
				'ok' => false,
				'error' => 'File kosong atau tidak dapat dibaca.',
				'format_label' => 'UNKNOWN',
				'items' => array(),
				'esai_items' => array(),
				'rels' => array(),
				'used_rids' => array(),
				'jumlah_soal' => 0,
				'jumlah_esai' => 0,
				'jumlah_jawaban' => 0,
				'jumlah_gambar' => 0,
				'soal_tanpa_kunci' => 0,
				'soal_tanpa_opsi' => 0
			);
		}

		// 1. OOXML (.docx) — ZIP signature "PK"
		if (substr($head, 0, 2) === 'PK') {
			return $this->_parse_docx_file($filepath);
		}

		// 2. Binary Word 97-2003 OLE2 signature (D0 CF 11 E0)
		if (substr($head, 0, 4) === "\xD0\xCF\x11\xE0") {
			return array(
				'ok' => false,
				'error' => 'Format .doc Word 97-2003 biner belum didukung. Buka di Word/LibreOffice lalu Save As .docx.',
				'format_label' => 'DOC-BIN',
				'items' => array(),
				'esai_items' => array(),
				'rels' => array(),
				'used_rids' => array(),
				'jumlah_soal' => 0,
				'jumlah_esai' => 0,
				'jumlah_jawaban' => 0,
				'jumlah_gambar' => 0,
				'soal_tanpa_kunci' => 0,
				'soal_tanpa_opsi' => 0
			);
		}

		// 3. HTML-based .doc (ekspor Gemini Canvas / web HTML Word)
		if (stripos($head, '<html') !== false || stripos($head, '<table') !== false || stripos($head, '<!doctype') !== false || stripos($head, '<body') !== false) {
			return $this->_parse_html_doc_file($filepath);
		}

		return array(
			'ok' => false,
			'error' => 'Format file tidak dikenali (bukan .docx maupun .doc HTML ZyaCBT).',
			'format_label' => 'UNKNOWN',
			'items' => array(),
			'esai_items' => array(),
			'rels' => array(),
			'used_rids' => array(),
			'jumlah_soal' => 0,
			'jumlah_esai' => 0,
			'jumlah_jawaban' => 0,
			'jumlah_gambar' => 0,
			'soal_tanpa_kunci' => 0,
			'soal_tanpa_opsi' => 0
		);
	}

	private function _parse_html_doc_file($filepath) {
		$result = array(
			'ok' => false,
			'error' => '',
			'format_label' => 'DOC-HTML',
			'items' => array(),
			'esai_items' => array(),
			'rels' => array(),
			'used_rids' => array(),
			'jumlah_soal' => 0,
			'jumlah_esai' => 0,
			'jumlah_jawaban' => 0,
			'jumlah_gambar' => 0,
			'soal_tanpa_kunci' => 0,
			'soal_tanpa_opsi' => 0
		);

		$raw = @file_get_contents($filepath);
		if ($raw === false || trim($raw) === '') {
			$result['error'] = 'File .doc kosong atau gagal dibaca.';
			return $result;
		}

		// Pastikan UTF-8 encoding terdeteksi benar oleh DOMDocument
		$raw_utf8 = '<?xml encoding="UTF-8">' . $raw;
		$dom = new DOMDocument();
		$prev_err = libxml_use_internal_errors(true);
		$loaded = $dom->loadHTML($raw_utf8, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
		libxml_clear_errors();
		libxml_use_internal_errors($prev_err);

		if (!$loaded) {
			$result['error'] = 'Gagal memparsing dokumen HTML .doc.';
			return $result;
		}

		$xpath = new DOMXPath($dom);
		$tables = $xpath->query('//table[not(ancestor::table)]');
		if (!$tables || $tables->length === 0) {
			$result['error'] = 'Tidak ditemukan tabel format soal di dalam dokumen .doc.';
			return $result;
		}

		$pg_items = array();
		$esai_items = array();
		$img_count = 0;

		foreach ($tables as $tbl) {
			// Ambil tr langsung milik tabel ini (tanpa nested table)
			$direct_rows = array();
			foreach ($tbl->childNodes as $child) {
				if ($child->nodeType !== XML_ELEMENT_NODE) {
					continue;
				}
				$tag = strtolower($child->nodeName);
				if ($tag === 'tr') {
					$direct_rows[] = $child;
				} elseif ($tag === 'tbody' || $tag === 'thead' || $tag === 'tfoot') {
					foreach ($child->childNodes as $tr) {
						if ($tr->nodeType === XML_ELEMENT_NODE && strtolower($tr->nodeName) === 'tr') {
							$direct_rows[] = $tr;
						}
					}
				}
			}

			$tbl_items = array();
			$tbl_has_jawaban = false;
			$current_idx = -1;

			foreach ($direct_rows as $row) {
				$cells = array();
				foreach ($row->childNodes as $c) {
					if ($c->nodeType === XML_ELEMENT_NODE) {
						$ctag = strtolower($c->nodeName);
						if ($ctag === 'td' || $ctag === 'th') {
							$cells[] = $c;
						}
					}
				}
				if (count($cells) < 3) {
					continue;
				}

				$jenis_raw = trim((string) $cells[1]->textContent);
				$jenis = strtoupper(preg_replace('/\s+/', '', $jenis_raw));
				if ($jenis !== 'SOAL' && $jenis !== 'JAWABAN') {
					continue;
				}
				if ($jenis === 'JAWABAN' && count($cells) < 4) {
					continue;
				}

				$html = $this->_sanitize_question_html(trim($this->innerHTML($cells[2])));
				$has_img = (stripos($html, '<img') !== false);
				$plain_check = trim(html_entity_decode(strip_tags(str_replace('&nbsp;', ' ', $html)), ENT_QUOTES, 'UTF-8'));
				$plain_check = preg_replace('/[\x{00A0}\s]+/u', '', $plain_check);

				if ($plain_check === '' && !$has_img) {
					continue;
				}

				if ($has_img) {
					$imgs = $cells[2]->getElementsByTagName('img');
					$img_count += $imgs->length;
				}

				if ($jenis === 'SOAL') {
					$tbl_items[] = array(
						'soal_html' => $html,
						'jawaban' => array(),
						'col_count' => count($cells)
					);
					$current_idx = count($tbl_items) - 1;
				} elseif ($jenis === 'JAWABAN' && $current_idx >= 0) {
					$tbl_has_jawaban = true;
					$kunci_raw = preg_replace('/\s+/', '', (string) $cells[3]->textContent);
					$kunci = intval($kunci_raw);
					if ($kunci > 1) {
						$kunci = 1;
					} elseif ($kunci < 0) {
						$kunci = 0;
					}
					$tbl_items[$current_idx]['jawaban'][] = array(
						'html' => $html,
						'benar' => $kunci
					);
				}
			}

			if (!empty($tbl_items)) {
				if ($tbl_has_jawaban) {
					foreach ($tbl_items as $ti) {
						if (empty($ti['jawaban']) && isset($ti['col_count']) && $ti['col_count'] === 3) {
							$esai_items[] = $ti;
						} else {
							$pg_items[] = $ti;
						}
					}
				} else {
					// Tabel berisi SOAL saja tanpa baris JAWABAN -> Soal Uraian / Esai
					foreach ($tbl_items as $ti) {
						$esai_items[] = $ti;
					}
				}
			}
		}

		$jml_jawaban = 0;
		$soal_tanpa_kunci = 0;
		$soal_tanpa_opsi = 0;

		foreach ($pg_items as $it) {
			$opsi_count = count($it['jawaban']);
			$jml_jawaban += $opsi_count;
			if ($opsi_count === 0) {
				$soal_tanpa_opsi++;
				$soal_tanpa_kunci++;
			} else {
				$has_key = false;
				foreach ($it['jawaban'] as $j) {
					if ($j['benar'] === 1) {
						$has_key = true;
						break;
					}
				}
				if (!$has_key) {
					$soal_tanpa_kunci++;
				}
			}
		}

		$result['ok'] = true;
		$result['items'] = $pg_items;
		$result['esai_items'] = $esai_items;
		$result['jumlah_soal'] = count($pg_items);
		$result['jumlah_esai'] = count($esai_items);
		$result['jumlah_jawaban'] = $jml_jawaban;
		$result['jumlah_gambar'] = $img_count;
		$result['soal_tanpa_kunci'] = $soal_tanpa_kunci;
		$result['soal_tanpa_opsi'] = $soal_tanpa_opsi;

		return $result;
	}

	private function _extract_base64_images_in_html($html, $upload_rel_path, &$saved_img_count) {
		if (stripos($html, 'data:image/') === false) {
			return $html;
		}
		return preg_replace_callback('/src=(["\'])(data:image\/([a-zA-Z0-9+.-]+);base64,([^"\']+))\1/i', function($m) use ($upload_rel_path, &$saved_img_count) {
			$ext = strtolower($m[3]);
			if ($ext === 'jpeg') {
				$ext = 'jpg';
			}
			$allowed = array('png', 'jpg', 'gif', 'webp');
			if (!in_array($ext, $allowed, true)) {
				return $m[0];
			}
			$bytes = base64_decode($m[4], true);
			if ($bytes === false) {
				return $m[0];
			}
			if (!is_dir($upload_rel_path)) {
				@mkdir($upload_rel_path, 0777, true);
			}
			$filename = 'batch_' . uniqid() . '_' . $saved_img_count . '.' . $ext;
			if (@file_put_contents($upload_rel_path . '/' . $filename, $bytes) !== false) {
				$saved_img_count++;
				return 'src="[base_url]' . $upload_rel_path . '/' . $filename . '"';
			}
			return $m[0];
		}, $html);
	}

	private function _parse_docx_file($filepath) {
		$result = array(
			'ok' => false,
			'error' => '',
			'format_label' => 'DOCX',
			'items' => array(),
			'esai_items' => array(),
			'rels' => array(),
			'used_rids' => array(),
			'jumlah_soal' => 0,
			'jumlah_esai' => 0,
			'jumlah_jawaban' => 0,
			'jumlah_gambar' => 0,
			'soal_tanpa_kunci' => 0,
			'soal_tanpa_opsi' => 0
		);

		$zip = new ZipArchive();
		if ($zip->open($filepath) !== TRUE) {
			$result['error'] = 'File .docx tidak dapat dibuka (bukan arsip Word yang valid).';
			return $result;
		}

		$doc_xml = $zip->getFromName('word/document.xml');
		if ($doc_xml === false) {
			$zip->close();
			$result['error'] = 'Struktur word/document.xml tidak ditemukan di dalam file .docx.';
			return $result;
		}

		// Baca relasi gambar dari word/_rels/document.xml.rels
		$rels = array();
		$rels_xml = $zip->getFromName('word/_rels/document.xml.rels');
		if ($rels_xml !== false) {
			$rels_dom = new DOMDocument();
			if (@$rels_dom->loadXML($rels_xml, LIBXML_NONET)) {
				$rel_nodes = $rels_dom->getElementsByTagName('Relationship');
				foreach ($rel_nodes as $rn) {
					$rid = $rn->getAttribute('Id');
					$target = $rn->getAttribute('Target');
					$type = $rn->getAttribute('Type');
					if ($rid !== '' && $target !== '' && stripos($type, '/image') !== false) {
						$zip_path = (strpos($target, 'word/') === 0) ? $target : 'word/' . ltrim($target, '/');
						if ($zip->locateName($zip_path) !== false) {
							$rels[$rid] = $zip_path;
						}
					}
				}
			}
		}
		$zip->close();

		$dom = new DOMDocument();
		$prev_err = libxml_use_internal_errors(true);
		$loaded = $dom->loadXML($doc_xml, LIBXML_NONET);
		libxml_clear_errors();
		libxml_use_internal_errors($prev_err);

		if (!$loaded) {
			$result['error'] = 'XML isi dokumen Word rusak.';
			return $result;
		}

		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
		$xpath->registerNamespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
		$xpath->registerNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
		$xpath->registerNamespace('v', 'urn:schemas-microsoft-com:vml');

		$tables = $xpath->query('//w:body/w:tbl');
		if (!$tables || $tables->length === 0) {
			$tables = $xpath->query('//w:tbl');
		}
		if (!$tables || $tables->length === 0) {
			$result['error'] = 'Tidak ditemukan tabel format soal di dalam dokumen Word.';
			return $result;
		}

		$items = array();
		$esai_items = array();
		$used_rids = array();

		foreach ($tables as $tbl) {
			$rows = $xpath->query('./w:tr', $tbl);
			if (!$rows) {
				continue;
			}
			$tbl_items = array();
			$tbl_has_jawaban = false;
			$current_idx = -1;

			foreach ($rows as $row) {
				$cells = $xpath->query('./w:tc', $row);
				if (!$cells || $cells->length < 3) {
					continue;
				}

				$jenis_raw = trim((string) $cells->item(1)->textContent);
				$jenis = strtoupper(preg_replace('/\s+/', '', $jenis_raw));
				if ($jenis !== 'SOAL' && $jenis !== 'JAWABAN') {
					continue;
				}
				if ($jenis === 'JAWABAN' && $cells->length < 4) {
					continue;
				}

				$cell_rids = array();
				$html = $this->_docx_cell_to_html($xpath, $cells->item(2), $rels, $cell_rids);
				$plain_check = trim(html_entity_decode(strip_tags(str_replace('&nbsp;', ' ', $html)), ENT_QUOTES, 'UTF-8'));
				$plain_check = preg_replace('/[\x{00A0}\s]+/u', '', $plain_check);

				// Lewati baris template yang masih kosong (tidak ada teks dan tidak ada gambar)
				if ($plain_check === '' && empty($cell_rids)) {
					continue;
				}

				foreach ($cell_rids as $rid) {
					$used_rids[$rid] = true;
				}

				if ($jenis === 'SOAL') {
					$tbl_items[] = array(
						'soal_html' => $html,
						'jawaban' => array(),
						'col_count' => $cells->length
					);
					$current_idx = count($tbl_items) - 1;
				} elseif ($jenis === 'JAWABAN' && $current_idx >= 0) {
					$tbl_has_jawaban = true;
					$kunci_raw = preg_replace('/\s+/', '', (string) $cells->item(3)->textContent);
					$kunci = intval($kunci_raw);
					if ($kunci > 1) {
						$kunci = 1;
					} elseif ($kunci < 0) {
						$kunci = 0;
					}
					$tbl_items[$current_idx]['jawaban'][] = array(
						'html' => $html,
						'benar' => $kunci
					);
				}
			}

			if (!empty($tbl_items)) {
				if ($tbl_has_jawaban) {
					foreach ($tbl_items as $ti) {
						if (empty($ti['jawaban']) && isset($ti['col_count']) && $ti['col_count'] === 3) {
							$esai_items[] = $ti;
						} else {
							$items[] = $ti;
						}
					}
				} else {
					foreach ($tbl_items as $ti) {
						$esai_items[] = $ti;
					}
				}
			}
		}

		$jml_jawaban = 0;
		$soal_tanpa_kunci = 0;
		$soal_tanpa_opsi = 0;

		foreach ($items as $it) {
			$opsi_count = count($it['jawaban']);
			$jml_jawaban += $opsi_count;
			if ($opsi_count === 0) {
				$soal_tanpa_opsi++;
				$soal_tanpa_kunci++;
			} else {
				$has_key = false;
				foreach ($it['jawaban'] as $j) {
					if ($j['benar'] === 1) {
						$has_key = true;
						break;
					}
				}
				if (!$has_key) {
					$soal_tanpa_kunci++;
				}
			}
		}

		$result['ok'] = true;
		$result['items'] = $items;
		$result['esai_items'] = $esai_items;
		$result['rels'] = $rels;
		$result['used_rids'] = array_keys($used_rids);
		$result['jumlah_soal'] = count($items);
		$result['jumlah_esai'] = count($esai_items);
		$result['jumlah_jawaban'] = $jml_jawaban;
		$result['jumlah_gambar'] = count($used_rids);
		$result['soal_tanpa_kunci'] = $soal_tanpa_kunci;
		$result['soal_tanpa_opsi'] = $soal_tanpa_opsi;

		return $result;
	}

	private function _docx_cell_to_html($xpath, $tc_node, $rels, &$cell_rids) {
		$paragraphs = $xpath->query('./w:p', $tc_node);
		$p_htmls = array();

		foreach ($paragraphs as $p) {
			$runs = $xpath->query('.//w:r', $p);
			$p_buf = '';
			foreach ($runs as $r) {
				$rPr = $xpath->query('./w:rPr', $r)->item(0);
				$is_bold = false;
				$is_italic = false;
				$is_underline = false;
				$vert_align = '';

				if ($rPr) {
					$b_node = $xpath->query('./w:b', $rPr)->item(0);
					if ($b_node && $b_node->getAttribute('w:val') !== '0' && $b_node->getAttribute('w:val') !== 'false') {
						$is_bold = true;
					}
					$i_node = $xpath->query('./w:i', $rPr)->item(0);
					if ($i_node && $i_node->getAttribute('w:val') !== '0' && $i_node->getAttribute('w:val') !== 'false') {
						$is_italic = true;
					}
					$u_node = $xpath->query('./w:u', $rPr)->item(0);
					if ($u_node && $u_node->getAttribute('w:val') !== 'none') {
						$is_underline = true;
					}
					$va_node = $xpath->query('./w:vertAlign', $rPr)->item(0);
					if ($va_node) {
						$vert_align = $va_node->getAttribute('w:val');
					}
				}

				$r_buf = '';
				foreach ($r->childNodes as $child) {
					if ($child->nodeType !== XML_ELEMENT_NODE) {
						continue;
					}
					$local = $child->localName;
					if ($local === 't') {
						$r_buf .= htmlspecialchars($child->nodeValue, ENT_QUOTES, 'UTF-8');
					} elseif ($local === 'br' || $local === 'cr') {
						$r_buf .= '<br />';
					} elseif ($local === 'tab') {
						$r_buf .= '&nbsp;&nbsp;&nbsp;&nbsp;';
					} elseif ($local === 'drawing' || $local === 'pict') {
						$blips = $xpath->query('.//a:blip', $child);
						foreach ($blips as $blip) {
							$rid = $blip->getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'embed');
							if ($rid !== '' && isset($rels[$rid])) {
								$r_buf .= '[[IMG_RID:' . $rid . ']]';
								$cell_rids[] = $rid;
							}
						}
						$vimgs = $xpath->query('.//v:imagedata', $child);
						foreach ($vimgs as $vimg) {
							$rid = $vimg->getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'id');
							if ($rid !== '' && isset($rels[$rid])) {
								$r_buf .= '[[IMG_RID:' . $rid . ']]';
								$cell_rids[] = $rid;
							}
						}
					}
				}

				if ($r_buf !== '') {
					if ($vert_align === 'superscript') {
						$r_buf = '<sup>' . $r_buf . '</sup>';
					} elseif ($vert_align === 'subscript') {
						$r_buf = '<sub>' . $r_buf . '</sub>';
					}
					if ($is_underline) {
						$r_buf = '<u>' . $r_buf . '</u>';
					}
					if ($is_italic) {
						$r_buf = '<i>' . $r_buf . '</i>';
					}
					if ($is_bold) {
						$r_buf = '<b>' . $r_buf . '</b>';
					}
					$p_buf .= $r_buf;
				}
			}

			if (trim(strip_tags($p_buf)) !== '' || strpos($p_buf, '[[IMG_RID:') !== false) {
				$p_htmls[] = '<p>' . $p_buf . '</p>';
			}
		}

		return implode('', $p_htmls);
	}

	private function _extract_docx_images($filepath, $rels, $used_rids, $upload_rel_path) {
		$map = array();
		$count = 0;
		$allowed_ext = array('png', 'jpg', 'jpeg', 'gif', 'webp');

		$zip = new ZipArchive();
		if ($zip->open($filepath) !== TRUE) {
			return array('map' => $map, 'count' => 0);
		}

		if (!is_dir($upload_rel_path)) {
			@mkdir($upload_rel_path, 0777, true);
		}

		foreach ($used_rids as $rid) {
			if (!isset($rels[$rid])) {
				continue;
			}
			$zip_path = $rels[$rid];
			$ext = strtolower(pathinfo($zip_path, PATHINFO_EXTENSION));
			if (!in_array($ext, $allowed_ext, true)) {
				continue;
			}
			$bytes = $zip->getFromName($zip_path);
			if ($bytes === false) {
				continue;
			}
			$new_filename = 'batch_' . uniqid() . '_' . $count . '.' . $ext;
			$full_dest = $upload_rel_path . '/' . $new_filename;
			if (@file_put_contents($full_dest, $bytes) !== false) {
				$map['[[IMG_RID:' . $rid . ']]'] = '<img src="[base_url]' . $upload_rel_path . '/' . $new_filename . '" style="max-width: 600px;" />';
				$count++;
			}
		}

		$zip->close();
		return array('map' => $map, 'count' => $count);
	}

	private function _apply_image_placeholders($html, $img_map) {
		if (!empty($img_map)) {
			$html = str_replace(array_keys($img_map), array_values($img_map), $html);
		}
		// Hapus placeholder yang gagal diekstrak (misal format .emf/.wmf) agar tidak muncul teks mentah
		return preg_replace('/\[\[IMG_RID:[^\]]+\]\]/', '', $html);
	}

	private function _cleanup_old_batch_tmp($base_dir, $max_age_seconds = 7200) {
		$items = @scandir($base_dir);
		if (!$items) {
			return;
		}
		$now = time();
		foreach ($items as $item) {
			if ($item === '.' || $item === '..') {
				continue;
			}
			$full = $base_dir . '/' . $item;
			if (is_dir($full) && ($now - @filemtime($full)) > $max_age_seconds) {
				$this->_remove_dir_recursive($full);
			}
		}
	}

	private function _remove_dir_recursive($dir) {
		if (!is_dir($dir)) {
			return;
		}
		$items = @scandir($dir);
		if ($items) {
			foreach ($items as $item) {
				if ($item === '.' || $item === '..') {
					continue;
				}
				$path = $dir . '/' . $item;
				if (is_dir($path)) {
					$this->_remove_dir_recursive($path);
				} else {
					@unlink($path);
				}
			}
		}
		@rmdir($dir);
	}
}