<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_rekap_hasil extends Member_Controller {
	private $kode_menu = 'laporan-rekap';
	private $kelompok = 'laporan';
	private $url = 'manager/laporan_rekap_hasil';
	
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
		
		$tanggal_awal = date('Y-m-d', strtotime('- 1 days'));
        $tanggal_akhir = date('Y-m-d', strtotime('+ 1 days'));
        
        $data['rentang_waktu'] = $tanggal_awal.' - '.$tanggal_akhir;

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
        
        $this->template->display_admin($this->kelompok.'/laporan_rekap_hasil_tes_view', 'Rekapitulasi Hasil Tes', $data);        
    }

    private function _build_rekap_data($grup, $nama_grup, $tanggal){
        $query_user = $this->cbt_user_model->get_by_kolom('user_grup_id', $grup);
        $query_tes = $this->cbt_tesgrup_model->get_by_tanggal_and_grup($tanggal[0], $tanggal[1], $grup);

        $tests = array();
        $students = array();

        if($query_user->num_rows() > 0 && $query_tes->num_rows() > 0){
            $tes_rows = $query_tes->result();
            $user_rows = $query_user->result();

            foreach($tes_rows as $tes){
                $tests[] = array(
                    'tes_id' => (int)$tes->tes_id,
                    'tes_nama' => (string)$tes->tes_nama
                );
            }

            $no = 1;
            foreach($user_rows as $user){
                $nilai_tes = array();
                $nilai_ikut = array();

                foreach($tes_rows as $tes){
                    $query_nilai = $this->cbt_tes_user_model->get_nilai_by_tes_user($tes->tes_id, $user->user_id);
                    if($query_nilai->num_rows() > 0){
                        $row_nilai = $query_nilai->row();
                        if($row_nilai && $row_nilai->nilai !== null){
                            $val = round((float)$row_nilai->nilai, 2);
                            $nilai_tes[] = $val;
                            $nilai_ikut[] = $val;
                        }else{
                            $nilai_tes[] = 'N/A';
                        }
                    }else{
                        $nilai_tes[] = 'N/A';
                    }
                }

                $rata_rata = (count($nilai_ikut) > 0) ? round(array_sum($nilai_ikut) / count($nilai_ikut), 2) : 'N/A';

                $students[] = array(
                    'no' => $no++,
                    'nama' => stripslashes((string)$user->user_firstname),
                    'grup' => (string)$nama_grup,
                    'rata_rata' => $rata_rata,
                    'nilai_tes' => $nilai_tes
                );
            }
        }

        return array(
            'tests' => $tests,
            'students' => $students
        );
    }

    public function preview(){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('pilih-grup', 'Grup', 'required|strip_tags');
        $this->form_validation->set_rules('nama-grup', 'Grup', 'required|strip_tags');
        $this->form_validation->set_rules('pilih-rentang-waktu', 'Rentang Waktu', 'required|strip_tags');

        $this->load->library('tools');

        if($this->form_validation->run() == TRUE){
            $rentang_waktu = $this->input->post('pilih-rentang-waktu', true);
            $tanggal = explode(" - ", $rentang_waktu);
            if(count($tanggal) < 2){
                echo json_encode(array('status' => 0, 'pesan' => 'Format rentang waktu tidak valid.'));
                return;
            }
            $grup = $this->input->post('pilih-grup', true);
            $nama_grup = $this->input->post('nama-grup', true);

            $rekap = $this->_build_rekap_data($grup, $nama_grup, $tanggal);
            $periode = $this->tools->indonesian_date($tanggal[0], 'j F Y', '').' - '.$this->tools->indonesian_date($tanggal[1], 'j F Y', '');

            $status = array(
                'status' => 1,
                'nama_grup' => $nama_grup,
                'periode' => $periode,
                'jumlah_tes' => count($rekap['tests']),
                'jumlah_siswa' => count($rekap['students']),
                'tests' => $rekap['tests'],
                'students' => $rekap['students']
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

    public function export(){
    	$this->load->library('form_validation');
        
        $this->form_validation->set_rules('pilih-grup', 'Grup','required|strip_tags');
        $this->form_validation->set_rules('nama-grup', 'Grup','required|strip_tags');
        $this->form_validation->set_rules('pilih-rentang-waktu', 'Rentang Waktu','required|strip_tags');

        $this->load->library('excel');
        $this->load->library('tools');
            
        $inputFileName = './public/form/form-data-rekap-hasil-tes.xlsx';
        $excel = PHPExcel_IOFactory::load($inputFileName);
        $worksheet = $excel->getSheet(0);
        
        $nama_grup = '';

        if($this->form_validation->run() == TRUE){
            $rentang_waktu = $this->input->post('pilih-rentang-waktu', true);
            $tanggal = explode(" - ", $rentang_waktu);
            $grup = $this->input->post('pilih-grup', true);
            $nama_grup = $this->input->post('nama-grup', true);

            $rekap = $this->_build_rekap_data($grup, $nama_grup, $tanggal);
            
            $worksheet->setCellValueByColumnAndRow(2, 3, $nama_grup);
            $worksheet->setCellValueByColumnAndRow(2, 4, $this->tools->indonesian_date($tanggal[0], 'j F Y', '').' - '.$this->tools->indonesian_date($tanggal[1], 'j F Y', ''));
            $worksheet->setCellValueByColumnAndRow(2, 5, count($rekap['tests']));

            if(!empty($rekap['students']) && !empty($rekap['tests'])){
            	$kolom = 4;
                foreach ($rekap['tests'] as $tes) {
                	$worksheet->setCellValueByColumnAndRow($kolom, 8, $tes['tes_nama']);
                    $kolom++;
                }

            	$row = 9;
            	foreach ($rekap['students'] as $st) {
            		$worksheet->setCellValueByColumnAndRow(0, $row, $st['no']);
                    $worksheet->setCellValueByColumnAndRow(1, $row, $st['nama']);
                    $worksheet->setCellValueByColumnAndRow(2, $row, $st['grup']);
                    $worksheet->setCellValueByColumnAndRow(3, $row, $st['rata_rata']);

                    $kolom = 4;
	                foreach ($st['nilai_tes'] as $nilai) {
                        $worksheet->setCellValueByColumnAndRow($kolom, $row, $nilai);
	                    $kolom++;
	                }

                    $row++;
            	}
            }
        }

        if(ob_get_length()){
            ob_end_clean();
        }
        $safe_grup = preg_replace('/[\\\\\/:\*\?"<>\|]/', '-', (string)$nama_grup);
        $filename = 'Data Rekap Hasil Tes - '.$safe_grup.'.xlsx'; //save our workbook as this file name
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