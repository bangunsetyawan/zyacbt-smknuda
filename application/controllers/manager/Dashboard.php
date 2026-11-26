<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Member_Controller {
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_tes_topik_set_model');
		$this->load->model('cbt_tesgrup_model');
	}
    
    public function index(){
        if($this->access->get_level() === 'operator-tes'){
            redirect('manager/tes_hasil_operator');
            return;
        }
        $this->load->helper('form');
        $data['nama'] = $this->access->get_nama();

        $data['post_max_size'] = ini_get('post_max_size');
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');
        $data['waktu_server'] = date('Y-m-d H:i:s');
		$data['timezone'] = date_default_timezone_get();

        $dir1 = './public/uploads/';
        $dir2 = './uploads/';

        $data['dir_public_uploads'] = 'Not Writeable';
        if(is_writable($dir1)){
        	$data['dir_public_uploads'] = 'Writeable';
        }

        $data['dir_uploads'] = 'Not Writeable';
        if(is_writable($dir2)){
        	$data['dir_uploads'] = 'Writeable';
        }

        // Statistik operasional dashboard
        $data['total_grup'] = $this->db->count_all('cbt_user_grup');
        $data['total_peserta'] = $this->db->count_all('cbt_user');
        $data['total_topik'] = $this->db->count_all('cbt_topik');
        $data['total_soal'] = $this->db->count_all('cbt_soal');

        // Jadwal & Pantauan Ujian Hari Ini
        $today = date('Y-m-d');
        $now_dt = new DateTime();
        $jadwal_hari_ini = array();

        $query_tes_hari_ini = $this->db->where('DATE(tes_begin_time) <=', $today)
                                       ->where('DATE(tes_end_time) >=', $today)
                                       ->order_by('tes_begin_time ASC, tes_id ASC')
                                       ->get('cbt_tes');

        if($query_tes_hari_ini->num_rows() > 0){
            foreach($query_tes_hari_ini->result() as $tes){
                $has_pg = false;
                $has_essay = false;
                $total_soal_tes = 0;

                $q_tset = $this->cbt_tes_topik_set_model->get_by_kolom('tset_tes_id', $tes->tes_id);
                if($q_tset->num_rows() > 0){
                    foreach($q_tset->result() as $tset){
                        $total_soal_tes += (int)$tset->tset_jumlah;
                        if((int)$tset->tset_tipe === 1){
                            $has_pg = true;
                        }elseif((int)$tset->tset_tipe === 2 || (int)$tset->tset_tipe === 3){
                            $has_essay = true;
                        }else{
                            if(preg_match('/\b(esai|essay|uraian)\b/i', $tes->tes_nama)){
                                $has_essay = true;
                            }else{
                                $has_pg = true;
                            }
                        }
                    }
                }

                if($has_essay && !$has_pg){
                    $kategori = 'ESAI';
                    $kategori_class = 'label-primary';
                    $kategori_style = 'background-color:#4f46e5;';
                }elseif($has_essay && $has_pg){
                    $kategori = 'CAMPURAN';
                    $kategori_class = 'label-warning';
                    $kategori_style = '';
                }elseif($has_pg){
                    $kategori = 'PG';
                    $kategori_class = 'label-success';
                    $kategori_style = 'background-color:#059669;';
                }else{
                    $kategori = 'BELUM ADA SOAL';
                    $kategori_class = 'label-default';
                    $kategori_style = '';
                }

                // Daftar grup peserta
                $grup_list = array();
                $q_grup = $this->cbt_tesgrup_model->get_by_tes_id($tes->tes_id);
                if($q_grup->num_rows() > 0){
                    foreach($q_grup->result() as $g){
                        $grup_list[] = $g->grup_nama;
                    }
                }

                // Hitung siswa sedang mengerjakan vs selesai
                $jml_mengerjakan = 0;
                $jml_selesai = 0;
                $q_tuser = $this->db->select('tesuser_status, tesuser_creation_time')
                                    ->where('tesuser_tes_id', $tes->tes_id)
                                    ->get('cbt_tes_user');
                if($q_tuser->num_rows() > 0){
                    foreach($q_tuser->result() as $tu){
                        $batas = new DateTime($tu->tesuser_creation_time);
                        $batas->modify('+'.$tes->tes_duration_time.' minutes');
                        if((int)$tu->tesuser_status === 4 || $now_dt >= $batas){
                            $jml_selesai++;
                        }else{
                            $jml_mengerjakan++;
                        }
                    }
                }

                // Status sesi
                $begin_dt = new DateTime($tes->tes_begin_time);
                $end_dt = new DateTime($tes->tes_end_time);
                if($now_dt < $begin_dt){
                    $status_sesi = 'Menunggu';
                    $status_class = 'label-info';
                }elseif($now_dt > $end_dt){
                    $status_sesi = 'Berakhir';
                    $status_class = 'label-default';
                }else{
                    $status_sesi = 'Berlangsung';
                    $status_class = 'label-success';
                }

                $item = new stdClass();
                $item->tes_id = $tes->tes_id;
                $item->tes_nama = $tes->tes_nama;
                $item->tes_begin_time = $tes->tes_begin_time;
                $item->tes_end_time = $tes->tes_end_time;
                $item->tes_duration_time = $tes->tes_duration_time;
                $item->total_soal = $total_soal_tes;
                $item->kategori = $kategori;
                $item->kategori_class = $kategori_class;
                $item->kategori_style = $kategori_style;
                $item->grup_text = !empty($grup_list) ? implode(', ', $grup_list) : 'Belum ada grup';
                $item->jml_mengerjakan = $jml_mengerjakan;
                $item->jml_selesai = $jml_selesai;
                $item->status_sesi = $status_sesi;
                $item->status_class = $status_class;

                $jadwal_hari_ini[] = $item;
            }
        }

        $data['jadwal_hari_ini'] = $jadwal_hari_ini;
        $data['total_jadwal_hari_ini'] = count($jadwal_hari_ini);

        $this->template->display_admin('manager/dashboard_view', 'Dashboard', $data);
    }
	
	function password(){
        $this->load->library('form_validation');
        
		$this->form_validation->set_rules('password-old', 'Password Lama','required|strip_tags');
		$this->form_validation->set_rules('password-new', 'Password Baru','required|strip_tags');
        $this->form_validation->set_rules('password-confirm', 'Confirm Password','required|strip_tags');
        
        if($this->form_validation->run() == TRUE){
			$old = $this->input->post('password-old', TRUE);
			$new = $this->input->post('password-new', TRUE);
			$confirm = $this->input->post('password-confirm', TRUE);
			
			$username = $this->access->get_username();
			
			if($this->users_model->get_user_count($username, $old)>0){
				if($new==$confirm){
					$this->users_model->change_password($username, $new);
					$status['status'] = 1;
					$status['error'] = '';
				}else{
					$status['status'] = 0;
					$status['error'] = 'Kedua password baru tidak sama';
				}
			}else{
				$status['status'] = 0;
				$status['error'] = 'Password Lama tidak Sesuai';
			}
        }else{
            $status['status'] = 0;
            $status['error'] = validation_errors();
        }
        
        echo json_encode($status);
    }
}