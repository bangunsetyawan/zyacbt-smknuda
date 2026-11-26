<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modul_topik extends Member_Controller {
	private $kode_menu = 'modul-topik';
	private $kelompok = 'modul';
	private $url = 'manager/modul_topik';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_modul_model');
		$this->load->model('cbt_topik_model');
		$this->load->model('cbt_soal_model');
		$this->load->model('cbt_tes_topik_set_model');

		parent::cek_akses($this->kode_menu);
	}
	
    public function index($page=null, $id=null){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        // Jika modul kosong, ditambah default
		if($this->cbt_modul_model->count_all()->row()->hasil==0){
			$data_modul['modul_nama'] = 'Default';
	        $data_modul['modul_aktif'] = 1;
	        $this->cbt_modul_model->save($data_modul);
		}

		$data['select_modul'] = $this->_build_select_modul();
        
        $this->template->display_admin($this->kelompok.'/topik_view', 'Daftar Topik', $data);
    }

    private function _build_select_modul($selected_id = null){
        $query_all = $this->cbt_modul_model->get_all_ordered();
        if($query_all->num_rows() == 0){
            return '<option value="10000000">KOSONG</option>';
        }
        $aktif_opts = '';
        $arsip_opts = '';
        foreach($query_all->result() as $temp){
            $sel = (!empty($selected_id) && $selected_id == $temp->modul_id) ? ' selected="selected"' : '';
            $nama = htmlspecialchars($temp->modul_nama, ENT_QUOTES, 'UTF-8');
            if((int)$temp->modul_aktif === 1){
                $aktif_opts .= '<option value="'.$temp->modul_id.'"'.$sel.'>'.$nama.'</option>';
            }else{
                $arsip_opts .= '<option value="'.$temp->modul_id.'"'.$sel.'>[Arsip] '.$nama.'</option>';
            }
        }
        if(!empty($arsip_opts)){
            return '<optgroup label="Modul Aktif">'.$aktif_opts.'</optgroup><optgroup label="Modul Arsip (Disembunyikan di Ujian)">'.$arsip_opts.'</optgroup>';
        }
        return $aktif_opts;
    }

    function get_daftar_modul(){
        $selected_id = $this->input->get('selected_id', TRUE);
        $query = $this->cbt_modul_model->get_all_ordered();
        $list = array();
        if($query->num_rows() > 0){
            foreach($query->result() as $m){
                $jml_topik = (int)$this->cbt_topik_model->count_by_kolom('topik_modul_id', $m->modul_id)->row()->hasil;
                $list[] = array(
                    'modul_id' => $m->modul_id,
                    'modul_nama' => $m->modul_nama,
                    'modul_aktif' => (int)$m->modul_aktif,
                    'jml_topik' => $jml_topik
                );
            }
        }
        echo json_encode(array(
            'status' => 1,
            'data' => $list,
            'select_modul' => $this->_build_select_modul($selected_id)
        ));
    }

    function tambah_modul(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('modul-nama', 'Nama Modul', 'required|trim|strip_tags|max_length[255]');

        if($this->form_validation->run() == TRUE){
            $nama = trim((string)$this->input->post('modul-nama', TRUE));
            if($this->cbt_modul_model->count_by_kolom('modul_nama', $nama)->row()->hasil > 0){
                $status['status'] = 0;
                $status['pesan'] = 'Nama Modul sudah ada. Gunakan nama lain.';
            }else{
                $this->cbt_modul_model->save(array(
                    'modul_nama' => $nama,
                    'modul_aktif' => 1
                ));
                $new_id = $this->db->insert_id();
                $this->cbt_modul_model->set_single_aktif($new_id);
                $status['status'] = 1;
                $status['modul_id'] = $new_id;
                $status['pesan'] = 'Modul "'.$nama.'" berhasil ditambahkan dan diaktifkan (modul lain otomatis diarsipkan).';
            }
        }else{
            $status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        echo json_encode($status);
    }

    function edit_modul(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('modul-id', 'ID Modul', 'required|integer');
        $this->form_validation->set_rules('modul-nama', 'Nama Modul', 'required|trim|strip_tags|max_length[255]');

        if($this->form_validation->run() == TRUE){
            $id = (int)$this->input->post('modul-id', TRUE);
            $nama = trim((string)$this->input->post('modul-nama', TRUE));
            $existing = $this->cbt_modul_model->get_by_kolom_limit('modul_id', $id, 1);
            if($existing->num_rows() == 0){
                $status['status'] = 0;
                $status['pesan'] = 'Modul tidak ditemukan.';
            }else{
                $row = $existing->row();
                if(strcasecmp($row->modul_nama, $nama) !== 0 && $this->cbt_modul_model->count_by_kolom('modul_nama', $nama)->row()->hasil > 0){
                    $status['status'] = 0;
                    $status['pesan'] = 'Nama Modul sudah digunakan modul lain.';
                }else{
                    $this->cbt_modul_model->update('modul_id', $id, array('modul_nama' => $nama));
                    $status['status'] = 1;
                    $status['pesan'] = 'Nama Modul berhasil diperbarui.';
                }
            }
        }else{
            $status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        echo json_encode($status);
    }

    function toggle_modul(){
        $id = (int)$this->input->post('modul-id', TRUE);
        $existing = $this->cbt_modul_model->get_by_kolom_limit('modul_id', $id, 1);
        if($existing->num_rows() == 0){
            echo json_encode(array('status' => 0, 'pesan' => 'Modul tidak ditemukan.'));
            return;
        }
        $row = $existing->row();
        $jml_aktif = (int)$this->cbt_modul_model->count_aktif()->row()->hasil;
        if((int)$row->modul_aktif === 1 && $jml_aktif === 1){
            echo json_encode(array('status' => 0, 'pesan' => 'Modul "'.$row->modul_nama.'" sudah menjadi satu-satunya Modul Aktif saat ini.'));
            return;
        }
        $this->cbt_modul_model->set_single_aktif($id);
        echo json_encode(array('status' => 1, 'modul_id' => $id, 'pesan' => 'Modul "'.$row->modul_nama.'" kini menjadi Modul Aktif (modul lainnya otomatis dipindahkan ke Arsip).'));
    }

    function hapus_modul(){
        $id = (int)$this->input->post('modul-id', TRUE);
        $existing = $this->cbt_modul_model->get_by_kolom_limit('modul_id', $id, 1);
        if($existing->num_rows() == 0){
            echo json_encode(array('status' => 0, 'pesan' => 'Modul tidak ditemukan.'));
            return;
        }
        $row = $existing->row();
        if((int)$row->modul_aktif === 1){
            echo json_encode(array('status' => 0, 'pesan' => 'Modul yang sedang Aktif tidak dapat dihapus. Aktifkan modul lain terlebih dahulu sebelum menghapus modul ini.'));
            return;
        }
        $jml_topik = (int)$this->cbt_topik_model->count_by_kolom('topik_modul_id', $id)->row()->hasil;
        if($jml_topik > 0){
            echo json_encode(array('status' => 0, 'pesan' => 'Modul masih berisi '.$jml_topik.' Topik. Kosongkan topiknya terlebih dahulu jika ingin menghapus modul ini.'));
            return;
        }
        if((int)$this->cbt_modul_model->count_all()->row()->hasil <= 1){
            echo json_encode(array('status' => 0, 'pesan' => 'Minimal harus ada 1 Modul tersisa di sistem.'));
            return;
        }
        }
        $this->cbt_modul_model->delete('modul_id', $id);
        echo json_encode(array('status' => 1, 'pesan' => 'Modul kosong "'.$row->modul_nama.'" berhasil dihapus.'));
    }

    function download_template(){
        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('Template Topik');

        $sheet->setCellValue('A1', 'No')
              ->setCellValue('B1', 'Mata Pelajaran')
              ->setCellValue('C1', 'Kelas / Tingkat')
              ->setCellValue('D1', 'Deskripsi (Opsional)');

        // Contoh baris siap timpa oleh staf TU
        $contoh = array(
            array(1, 'Bahasa Indonesia', 'XII TKJ', 'SAS Ganjil - Mapel Umum'),
            array(2, 'Administrasi Infrastruktur Jaringan', 'XI TKJ', 'SAS Ganjil - Produktif TKJ'),
            array(3, 'Matematika', 'X Semua', 'SAS Ganjil - Lintas Jurusan Kelas X')
        );
        $rowNum = 2;
        foreach($contoh as $c){
            $sheet->setCellValue('A'.$rowNum, $c[0])
                  ->setCellValue('B'.$rowNum, $c[1])
                  ->setCellValue('C'.$rowNum, $c[2])
                  ->setCellValue('D'.$rowNum, $c[3]);
            $rowNum++;
        }

        // Styling header & lebar kolom
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
              ->getStartColor()->setRGB('D1FAE5');
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(36);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(38);

        if(ob_get_length()){
            ob_end_clean();
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_import_topik_zyacbt.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

    function import_excel(){
        $modul_id = (int)$this->input->post('import-modul-id', TRUE);
        if($modul_id <= 0 || $this->cbt_modul_model->count_by_kolom('modul_id', $modul_id)->row()->hasil == 0){
            echo json_encode(array('status' => 0, 'pesan' => 'Modul tujuan tidak valid.'));
            return;
        }

        if(empty($_FILES['file-excel']['name'])){
            echo json_encode(array('status' => 0, 'pesan' => 'Pilih file Excel (.xlsx / .xls) terlebih dahulu.'));
            return;
        }

        $config['upload_path'] = './public/uploads/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = 5120;
        $config['overwrite'] = true;
        $config['file_name'] = 'import_topik_'.time();

        $this->load->library('upload', $config);
        if(!$this->upload->do_upload('file-excel')){
            echo json_encode(array('status' => 0, 'pesan' => strip_tags($this->upload->display_errors())));
            return;
        }

        $upload_data = $this->upload->data();
        $file_path = $upload_data['full_path'];

        try {
            $this->load->library('excel');
            $excel = PHPExcel_IOFactory::load($file_path);
            $worksheet = $excel->getSheet(0);
            $highestRow = $worksheet->getHighestRow();

            $sukses = 0;
            $duplikat = 0;
            $kosong = 0;
            $daftar_duplikat = array();

            for($row = 2; $row <= $highestRow; $row++){
                $mapel = trim(strip_tags((string)$worksheet->getCellByColumnAndRow(1, $row)->getValue()));
                $kelas = trim(strip_tags((string)$worksheet->getCellByColumnAndRow(2, $row)->getValue()));
                $deskripsi = trim(strip_tags((string)$worksheet->getCellByColumnAndRow(3, $row)->getValue()));

                if($mapel === ''){
                    $kosong++;
                    continue;
                }

                $topik_nama = ($kelas !== '') ? ($mapel.' - '.$kelas) : $mapel;
                if(strlen($topik_nama) > 255){
                    $topik_nama = substr($topik_nama, 0, 255);
                }
                if($deskripsi === ''){
                    $deskripsi = ($kelas !== '') ? ('Mata Pelajaran '.$mapel.' ('.$kelas.')') : ('Mata Pelajaran '.$mapel);
                }

                // Pengaman duplikat per modul
                if($this->cbt_topik_model->count_by_topik_modul($topik_nama, $modul_id)->row()->hasil > 0){
                    $duplikat++;
                    if(count($daftar_duplikat) < 8){
                        $daftar_duplikat[] = $topik_nama;
                    }
                    continue;
                }

                $this->cbt_topik_model->save(array(
                    'topik_modul_id' => $modul_id,
                    'topik_nama' => $topik_nama,
                    'topik_detail' => $deskripsi,
                    'topik_aktif' => 1
                ));
                $sukses++;
            }

            if(file_exists($file_path)){
                @unlink($file_path);
            }

            $pesan = 'Import selesai: <strong>'.$sukses.'</strong> topik ditambahkan';
            if($duplikat > 0){
                $pesan .= ', <strong>'.$duplikat.'</strong> dilewati (sudah ada di modul ini)';
                if(!empty($daftar_duplikat)){
                    $pesan .= '<br /><small>Duplikat: '.htmlspecialchars(implode(', ', $daftar_duplikat), ENT_QUOTES, 'UTF-8').($duplikat > count($daftar_duplikat) ? ', ...' : '').'</small>';
                }
            }
            if($sukses == 0 && $duplikat == 0){
                echo json_encode(array('status' => 0, 'pesan' => 'Tidak ada data topik yang terbaca. Pastikan kolom B (Mata Pelajaran) mulai baris 2 sudah diisi sesuai template.'));
                return;
            }
            echo json_encode(array(
                'status' => 1,
                'sukses' => $sukses,
                'duplikat' => $duplikat,
                'kosong' => $kosong,
                'pesan' => $pesan
            ));
        } catch (Exception $e) {
            if(file_exists($file_path)){
                @unlink($file_path);
            }
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal membaca file Excel: '.$e->getMessage()));
        }
    }

    function salin_topik(){
        $sumber_id = (int)$this->input->post('sumber-modul-id', TRUE);
        $tujuan_id = (int)$this->input->post('tujuan-modul-id', TRUE);

        if($sumber_id <= 0 || $tujuan_id <= 0){
            echo json_encode(array('status' => 0, 'pesan' => 'Pilih Modul sumber dan tujuan yang valid.'));
            return;
        }
        if($sumber_id === $tujuan_id){
            echo json_encode(array('status' => 0, 'pesan' => 'Modul sumber dan tujuan tidak boleh sama.'));
            return;
        }

        $q_sumber = $this->cbt_topik_model->get_by_kolom('topik_modul_id', $sumber_id);
        if($q_sumber->num_rows() == 0){
            echo json_encode(array('status' => 0, 'pesan' => 'Modul sumber belum memiliki Topik untuk disalin.'));
            return;
        }

        $sukses = 0;
        $duplikat = 0;
        foreach($q_sumber->result() as $t){
            if($this->cbt_topik_model->count_by_topik_modul($t->topik_nama, $tujuan_id)->row()->hasil > 0){
                $duplikat++;
                continue;
            }
            $this->cbt_topik_model->save(array(
                'topik_modul_id' => $tujuan_id,
                'topik_nama' => $t->topik_nama,
                'topik_detail' => $t->topik_detail,
                'topik_aktif' => 1
            ));
            $sukses++;
        }

        $pesan = 'Berhasil menyalin <strong>'.$sukses.'</strong> nama topik ke modul aktif (0 soal).';
        if($duplikat > 0){
            $pesan .= ' <strong>'.$duplikat.'</strong> topik dilewati karena sudah ada.';
        }
        echo json_encode(array(
            'status' => 1,
            'sukses' => $sukses,
            'duplikat' => $duplikat,
            'pesan' => $pesan
        ));
    }

    function tambah(){
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('tambah-topik', 'Nama Topik','required|strip_tags');
        $this->form_validation->set_rules('tambah-modul-id', 'ID Modul','required|strip_tags');
        $this->form_validation->set_rules('tambah-deskripsi', 'Deskripsi','required|strip_tags');
        $this->form_validation->set_rules('tambah-status', 'Status','required|strip_tags');
        
        if($this->form_validation->run() == TRUE){
        	$data['topik_modul_id'] = $this->input->post('tambah-modul-id', true);
            $data['topik_nama'] = $this->input->post('tambah-topik', true);
            $data['topik_detail'] = $this->input->post('tambah-deskripsi', true);
            $data['topik_aktif'] = 1;

            //if($this->cbt_topik_model->count_by_kolom('topik_nama', $data['topik_nama'])->row()->hasil>0){
            if($this->cbt_topik_model->count_by_topik_modul($data['topik_nama'], $data['topik_modul_id'])->row()->hasil>0){
                $status['status'] = 0;
                $status['pesan'] = 'Nama Topik sudah terpakai !';
            }else{
				$this->cbt_topik_model->save($data);
                
                $status['status'] = 1;
                $status['pesan'] = 'Topik berhasil disimpan ';
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

    /**
     * Menghapus topik yang dipilih
     * @return [type] [description]
     */
    function hapus_daftar_topik(){
    	$this->load->library('form_validation');
        
		$this->form_validation->set_rules('edit-topik-id[]', 'Topik','required|strip_tags');
		if($this->form_validation->run() == TRUE){
			$topik_id = $this->input->post('edit-topik-id', TRUE);
			$error_hapus = 0;
			foreach( $topik_id as $kunci => $isi ) {
				if($isi=="on"){
					if($this->cbt_tes_topik_set_model->count_by_kolom('tset_topik_id', $kunci)->row()->hasil>0){
	            		$error_hapus++;
	            	}else{
	            		// Memulai transaction mysql
						$this->db->trans_start();

	            		// hapus topik di database
	            		$this->cbt_topik_model->delete('topik_id', $kunci);

	            		// Menutup transaction mysql
						$this->db->trans_complete();

	            		// hapus file topik
	            		$this->load->helper('directory');
						$this->load->helper('file');
	            		
	            		$folder = $this->config->item('upload_path').'/topik_'.(int)$kunci;
	            		if(is_dir($folder)){
	            			@delete_files($folder, TRUE);
	            			@rmdir($folder);
	            		}
	            	}
            	}
            }
            $status['status'] = 1;
            if($error_hapus>0){
            	$status['pesan'] = 'Daftar topik sebagian tidak dapat dihapus karena masih digunakan Tes !';
            }else{
            	$status['pesan'] = 'Daftar Topik berhasil dihapus';
            }
		}else{
			$status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        
        echo json_encode($status);
    }

    function edit(){
        $this->load->library('form_validation');
        
		$this->form_validation->set_rules('edit-id', 'ID','required|strip_tags');
		$this->form_validation->set_rules('edit-modul-id', 'ID Modul','required|strip_tags');
		$this->form_validation->set_rules('edit-topik', 'Nama Topik','required|strip_tags');
		$this->form_validation->set_rules('edit-deskripsi', 'Deskripsi','required|strip_tags');
        $this->form_validation->set_rules('edit-pilihan', 'Pilihan','required|strip_tags');
        $this->form_validation->set_rules('edit-topik-asli', 'Nama Topik','required|strip_tags');
        
        if($this->form_validation->run() == TRUE){
            $pilihan = $this->input->post('edit-pilihan', true);
            $id = $this->input->post('edit-id', true);
			$modul_id = $this->input->post('edit-modul-id', true);
            
            if($pilihan=='hapus'){//hapus
            	if($this->cbt_tes_topik_set_model->count_by_kolom('tset_topik_id', $id)->row()->hasil>0){
            		$status['status'] = 0;
                	$status['pesan'] = 'Topik masih dipakai pada Tes, tidak bisa dihapus.';
            	}else{
            		// hapus topik di database
            		$this->cbt_topik_model->delete('topik_id', $id);

            		// hapus file topik
            		$this->load->helper('directory');
					$this->load->helper('file');
            		
            		$folder = $this->config->item('upload_path').'/topik_'.(int)$id;
            		if(is_dir($folder)){
            			@delete_files($folder, TRUE);
            			@rmdir($folder);
            		}

					$status['status'] = 1;
					$status['pesan'] = 'Topik berhasil dihapus !';
            	}
            }else if($pilihan=='simpan'){//simpan
				$topik_asli = $this->input->post('edit-topik-asli', true);
                $data['topik_nama'] = $this->input->post('edit-topik', true);
                $data['topik_detail'] = $this->input->post('edit-deskripsi', true);

                if($topik_asli!=$data['topik_nama']){
                	//if($this->cbt_topik_model->count_by_kolom('topik_nama', $data['topik_nama'])->row()->hasil>0){
					if($this->cbt_topik_model->count_by_topik_modul($data['topik_nama'], $modul_id)->row()->hasil>0){
		                $status['status'] = 0;
		                $status['pesan'] = 'Nama Topik sudah terpakai !';
		            }else{
						$this->cbt_topik_model->update('topik_id', $id, $data);
		                
		                $status['status'] = 1;
		                $status['pesan'] = 'Topik berhasil disimpan ';
		            }
                }else{
                	$this->cbt_topik_model->update('topik_id', $id, $data);
                	$status['status'] = 1;
                	$status['pesan'] = 'Topik Berhasil disimpan';
                }
            }
            
        }else{
            $status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        
        echo json_encode($status);
    }
    
    function get_datatable(){
		// variable initialization
		$modul = $this->input->get('modul');

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
		$query = $this->cbt_topik_model->get_datatable($start, $rows, 'topik_nama', $search, $modul);
		$iFilteredTotal = $query->num_rows();
		
		$iTotal= $this->cbt_topik_model->get_datatable_count('topik_nama', $search, $modul)->row()->hasil;
	    
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

			$jml_soal = $this->cbt_soal_model->count_by_kolom('soal_topik_id', $temp->topik_id)->row()->hasil;

            $record[] = $temp->topik_nama;
            $record[] = $temp->topik_detail;
            $record[] = $jml_soal;
            if($temp->topik_aktif==1){
            	$record[] = 'Aktif';
            }else{
            	$record[] = 'Tidak Aktif';
            }
            $record[] = '<a onclick="edit(\''.$temp->topik_id.'\')" style="cursor: pointer;" class="btn btn-default btn-xs">Edit</a>';
            $record[] = '<input type="checkbox" name="edit-topik-id['.$temp->topik_id.']" >';

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