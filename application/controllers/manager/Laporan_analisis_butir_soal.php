<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_analisis_butir_soal extends Member_Controller {
	private $kode_menu = 'laporan-analisis-butir-soal';
	private $kelompok = 'laporan';
	private $url = 'manager/laporan_analisis_butir_soal';
	
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
	
    public function index(){
		$data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        $username = $this->access->get_username();
		$user_id = $this->users_model->get_login_info($username)->id;

        $query_group = $this->cbt_user_grup_model->get_group();
        $select = '';
        if($query_group->num_rows()>0){
        	$query_group = $query_group->result();
        	foreach ($query_group as $temp) {
        		$select = $select.'<option value="'.$temp->grup_id.'">'.$temp->grup_nama.'</option>';
        	}

        }else{
        	$select = '<option value="0">Tidak Ada Group</option>';
        }
        $data['select_group'] = $select;
		
		$query_tes = $this->cbt_tes_user_model->get_by_group();
        $select = '';
        if($query_tes->num_rows()>0){
        	$query_tes = $query_tes->result();
        	foreach ($query_tes as $temp) {
        		$select = $select.'<option value="'.$temp->tes_id.'">'.$temp->tes_nama.'</option>';
        	}
        }else{
			$select = '<option value="kosong">Belum Ada Tes yang Dilakukan</option>';
		}
        $data['select_tes'] = $select;
        
        $this->template->display_admin($this->kelompok.'/laporan_analisis_butir_soal_view', 'Analisis Butir Soal', $data);
    }

    private function _kategori_kesulitan($p){
        if($p <= 30){
            return array('label' => 'Sukar', 'badge' => 'danger');
        }
        if($p <= 70){
            return array('label' => 'Sedang', 'badge' => 'warning');
        }
        return array('label' => 'Mudah', 'badge' => 'success');
    }

    private function _kategori_daya_beda($d){
        if($d >= 0.40){
            return array('label' => 'Baik', 'badge' => 'success');
        }
        if($d >= 0.20){
            return array('label' => 'Cukup', 'badge' => 'info');
        }
        if($d >= 0.00){
            return array('label' => 'Buruk', 'badge' => 'warning');
        }
        return array('label' => 'Soal bermasalah', 'badge' => 'danger');
    }

    private function _build_analisis_data($tes, $grup){
        $query_user = $this->cbt_tes_user_model->get_by_tes_group($tes, $grup);
        $students = array();
        $soal_id_map = array();

        if($query_user->num_rows() > 0){
            $users = $query_user->result();
            $no = 1;
            foreach($users as $user){
                // Hanya soal Pilihan Ganda (soal_tipe = 1) untuk analisis butir soal
                $query_soal_tes = $this->cbt_tes_soal_model->get_by_testuser_order_soal($user->tesuser_id, 1);
                $jawaban_map = array();
                if($query_soal_tes->num_rows() > 0){
                    foreach($query_soal_tes->result() as $soal_tes){
                        $sid = (int)$soal_tes->soal_id;
                        $soal_id_map[$sid] = $sid;
                        $jawaban_map[$sid] = ($soal_tes->tessoal_nilai > 0) ? 1 : 0;
                    }
                }

                $nilai_row = $this->cbt_tes_user_model->get_nilai_by_tes_user($tes, $user->user_id)->row();
                $total_nilai = ($nilai_row && $nilai_row->nilai !== null) ? (float)$nilai_row->nilai : 0.0;

                $students[] = array(
                    'no' => $no++,
                    'username' => (string)$user->user_name,
                    'nama' => stripslashes((string)$user->user_firstname),
                    'total_nilai' => $total_nilai,
                    'jawaban_map' => $jawaban_map
                );
            }
        }

        ksort($soal_id_map, SORT_NUMERIC);
        $soal_ids = array_values($soal_id_map);

        // Normalisasi array jawaban per siswa mengikuti urutan soal_ids
        foreach($students as $idx => $st){
            $row_jawaban = array();
            foreach($soal_ids as $sid){
                $row_jawaban[] = isset($st['jawaban_map'][$sid]) ? $st['jawaban_map'][$sid] : 0;
            }
            $students[$idx]['jawaban'] = $row_jawaban;
            unset($students[$idx]['jawaban_map']);
        }

        $n_siswa = count($students);
        $n_soal = count($soal_ids);
        $kesulitan = array();
        $daya_beda = array();

        if($n_siswa > 0 && $n_soal > 0){
            // Urutkan indeks siswa berdasarkan total nilai DESC untuk kelompok atas/bawah 27%
            $sorted_indices = range(0, $n_siswa - 1);
            usort($sorted_indices, function($a, $b) use ($students){
                if($students[$a]['total_nilai'] == $students[$b]['total_nilai']){
                    return $students[$a]['no'] - $students[$b]['no'];
                }
                return ($students[$a]['total_nilai'] < $students[$b]['total_nilai']) ? 1 : -1;
            });

            $k = ($n_siswa >= 2) ? max(1, (int)round($n_siswa * 0.27)) : 0;
            $atas_indices = ($k > 0) ? array_slice($sorted_indices, 0, $k) : array();
            $bawah_indices = ($k > 0) ? array_slice($sorted_indices, -$k) : array();

            for($s = 0; $s < $n_soal; $s++){
                $benar_total = 0;
                for($i = 0; $i < $n_siswa; $i++){
                    $benar_total += $students[$i]['jawaban'][$s];
                }
                $p = round(($benar_total / $n_siswa) * 100, 1);
                $kat_p = $this->_kategori_kesulitan($p);
                $kesulitan[] = array(
                    'nilai' => $p,
                    'kategori' => $kat_p['label'],
                    'badge' => $kat_p['badge'],
                    'text' => number_format($p, 1).'% ('.$kat_p['label'].')'
                );

                if($k > 0){
                    $benar_atas = 0;
                    foreach($atas_indices as $idx){
                        $benar_atas += $students[$idx]['jawaban'][$s];
                    }
                    $benar_bawah = 0;
                    foreach($bawah_indices as $idx){
                        $benar_bawah += $students[$idx]['jawaban'][$s];
                    }
                    $d = round(($benar_atas / $k) - ($benar_bawah / $k), 2);
                }else{
                    $d = 0.00;
                }
                $kat_d = $this->_kategori_daya_beda($d);
                $daya_beda[] = array(
                    'nilai' => $d,
                    'kategori' => $kat_d['label'],
                    'badge' => $kat_d['badge'],
                    'text' => number_format($d, 2).' ('.$kat_d['label'].')'
                );
            }
        }

        return array(
            'soal_ids' => $soal_ids,
            'students' => $students,
            'kesulitan' => $kesulitan,
            'daya_beda' => $daya_beda
        );
    }

    public function preview(){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('pilih-grup', 'Grup', 'required|strip_tags');
        $this->form_validation->set_rules('nama-grup', 'Grup', 'required|strip_tags');
        $this->form_validation->set_rules('pilih-tes', 'Nama Tes', 'required|strip_tags');
        $this->form_validation->set_rules('nama-tes', 'Nama Tes', 'required|strip_tags');

        if($this->form_validation->run() == TRUE){
            $tes = $this->input->post('pilih-tes', true);
            $grup = $this->input->post('pilih-grup', true);
            $nama_grup = $this->input->post('nama-grup', true);
            $nama_tes = $this->input->post('nama-tes', true);

            if($tes === 'kosong' || empty($grup)){
                echo json_encode(array('status' => 0, 'pesan' => 'Pilih grup dan tes yang valid.'));
                return;
            }

            $analisis = $this->_build_analisis_data($tes, $grup);
            $status = array(
                'status' => 1,
                'nama_grup' => $nama_grup,
                'nama_tes' => $nama_tes,
                'jumlah_siswa' => count($analisis['students']),
                'jumlah_soal' => count($analisis['soal_ids']),
                'students' => $analisis['students'],
                'kesulitan' => $analisis['kesulitan'],
                'daya_beda' => $analisis['daya_beda']
            );
        }else{
            $status = array(
                'status' => 0,
                'pesan' => validation_errors()
            );
        }

        header('Content-Type: application/json');
        echo json_encode($status);
    }

    private function _badge_rgb($badge){
        $map = array(
            'success' => '00A65A',
            'info'    => '00C0EF',
            'warning' => 'F39C12',
            'danger'  => 'DD4B39'
        );
        return isset($map[$badge]) ? $map[$badge] : '777777';
    }

    private function _apply_excel_badge_style($worksheet, $col, $row, $badge, $font_size = 8){
        $rgb = $this->_badge_rgb($badge);
        $style = $worksheet->getStyleByColumnAndRow($col, $row);
        $style->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($rgb);
        $style->getFont()->setBold(true)->setSize($font_size)->getColor()->setRGB('FFFFFF');
        $style->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
              ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
              ->setWrapText(true);
        $style->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('DDDDDD');
    }

    public function export(){
    	$this->load->library('form_validation');
        
        $this->form_validation->set_rules('pilih-grup', 'Grup','required|strip_tags');
        $this->form_validation->set_rules('nama-grup', 'Grup','required|strip_tags');
        $this->form_validation->set_rules('pilih-tes', 'Nama Tes','required|strip_tags');
		$this->form_validation->set_rules('nama-tes', 'Nama Tes','required|strip_tags');

        $this->load->library('excel');
            
        $inputFileName = './public/form/form-data-analisis-butir-soal.xlsx';
        $excel = PHPExcel_IOFactory::load($inputFileName);
        $worksheet = $excel->getSheet(0);
		
		$nama_grup = '';
        
        if($this->form_validation->run() == TRUE){
            $tes = $this->input->post('pilih-tes', true);
            $grup = $this->input->post('pilih-grup', true);
            $nama_grup = $this->input->post('nama-grup', true);
			$nama_tes = $this->input->post('nama-tes', true);

            $worksheet->setCellValueByColumnAndRow(2, 3, $nama_grup);
            $worksheet->setCellValueByColumnAndRow(2, 4, $nama_tes);

            $analisis = $this->_build_analisis_data($tes, $grup);

            if(!empty($analisis['students'])){
            	$row = 8;
            	foreach ($analisis['students'] as $st) {
            		$worksheet->setCellValueByColumnAndRow(0, $row, $st['no']);
					$worksheet->setCellValueByColumnAndRow(1, $row, $st['username']);
                    $worksheet->setCellValueByColumnAndRow(2, $row, $st['nama']);

					$kolom = 3;
					foreach ($st['jawaban'] as $val) {
						$worksheet->setCellValueByColumnAndRow($kolom, $row, (string)$val);
						$kolom++;
					}

                    $row++;
            	}

                // 2 baris ringkasan di bawah matriks: Tingkat Kesulitan dan Daya Beda
                if(!empty($analisis['kesulitan'])){
                    $worksheet->setCellValueByColumnAndRow(2, $row, 'Tingkat Kesulitan');
                    $worksheet->getStyleByColumnAndRow(2, $row)->getFont()->setBold(true);
                    $worksheet->getStyleByColumnAndRow(2, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $worksheet->getRowDimension($row)->setRowHeight(32);
                    $kolom = 3;
                    foreach($analisis['kesulitan'] as $item){
                        $cell_val = number_format($item['nilai'], 1)."%\n".$item['kategori'];
                        $worksheet->setCellValueByColumnAndRow($kolom, $row, $cell_val);
                        $this->_apply_excel_badge_style($worksheet, $kolom, $row, $item['badge'], 7);
                        $kolom++;
                    }

                    $row++;
                    $worksheet->setCellValueByColumnAndRow(2, $row, 'Daya Beda');
                    $worksheet->getStyleByColumnAndRow(2, $row)->getFont()->setBold(true);
                    $worksheet->getStyleByColumnAndRow(2, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $worksheet->getRowDimension($row)->setRowHeight(32);
                    $kolom = 3;
                    foreach($analisis['daya_beda'] as $item){
                        $kat_short = ($item['kategori'] === 'Soal bermasalah') ? 'Bermasalah' : $item['kategori'];
                        $cell_val = number_format($item['nilai'], 2)."\n".$kat_short;
                        $worksheet->setCellValueByColumnAndRow($kolom, $row, $cell_val);
                        $this->_apply_excel_badge_style($worksheet, $kolom, $row, $item['badge'], 7);
                        $kolom++;
                    }

                    // Baris legenda di bawah ringkasan
                    $row += 2;
                    $worksheet->setCellValueByColumnAndRow(2, $row, 'Keterangan Matriks');
                    $worksheet->getStyleByColumnAndRow(2, $row)->getFont()->setBold(true);
                    $worksheet->mergeCellsByColumnAndRow(3, $row, 8, $row);
                    $worksheet->setCellValueByColumnAndRow(3, $row, '1 = Benar, 0 = Salah');
                    $worksheet->getStyleByColumnAndRow(3, $row)->getFont()->setBold(true);
                    $worksheet->getRowDimension($row)->setRowHeight(18);

                    $row++;
                    $worksheet->setCellValueByColumnAndRow(2, $row, 'Kategori Tingkat Kesulitan');
                    $worksheet->getStyleByColumnAndRow(2, $row)->getFont()->setBold(true);
                    $worksheet->getRowDimension($row)->setRowHeight(20);
                    $leg_kesulitan = array(
                        array('0-30% Sukar', 'danger'),
                        array('31-70% Sedang', 'warning'),
                        array('71-100% Mudah', 'success')
                    );
                    $c_start = 3;
                    foreach($leg_kesulitan as $lk){
                        $worksheet->mergeCellsByColumnAndRow($c_start, $row, $c_start + 3, $row);
                        $worksheet->setCellValueByColumnAndRow($c_start, $row, $lk[0]);
                        for($mc = $c_start; $mc <= $c_start + 3; $mc++){
                            $this->_apply_excel_badge_style($worksheet, $mc, $row, $lk[1], 9);
                        }
                        $c_start += 4;
                    }

                    $row++;
                    $worksheet->setCellValueByColumnAndRow(2, $row, 'Kategori Daya Beda');
                    $worksheet->getStyleByColumnAndRow(2, $row)->getFont()->setBold(true);
                    $worksheet->getRowDimension($row)->setRowHeight(20);
                    $leg_daya_beda = array(
                        array('>=0,40 Baik', 'success'),
                        array('0,20-0,39 Cukup', 'info'),
                        array('0,00-0,19 Buruk', 'warning'),
                        array('<0 Soal bermasalah', 'danger')
                    );
                    $c_start = 3;
                    foreach($leg_daya_beda as $ld){
                        $worksheet->mergeCellsByColumnAndRow($c_start, $row, $c_start + 3, $row);
                        $worksheet->setCellValueByColumnAndRow($c_start, $row, $ld[0]);
                        for($mc = $c_start; $mc <= $c_start + 3; $mc++){
                            $this->_apply_excel_badge_style($worksheet, $mc, $row, $ld[1], 9);
                        }
                        $c_start += 4;
                    }
                }
            }
        }

        if(ob_get_length()){
            ob_end_clean();
        }
        $safe_grup = preg_replace('/[\\\\\/:\*\?"<>\|]/', '-', (string)$nama_grup);
        $filename = 'Analisis Butir Soal - '.$safe_grup.'.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                 
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
}