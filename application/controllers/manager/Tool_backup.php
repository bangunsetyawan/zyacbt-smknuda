<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* ZYA CBT
* Achmad Lutfi
* achmdlutfi@gmail.com
* achmadlutfi.wordpress.com
*/
class Tool_backup extends Member_Controller {
	private $kode_menu = 'tool-backup';
	private $kelompok = 'tool';
	private $url = 'manager/tool_backup';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_user_model');

		parent::cek_akses($this->kode_menu);
		if(strtolower((string)$this->access->get_level()) !== 'admin'){
			show_error('Akses menu Backup Data hanya diizinkan untuk Administrator.', 403);
		}
	}
	
    public function index(){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;
        
        $this->template->display_admin($this->kelompok.'/tool_backup_view', 'Backup Data', $data);
    }

    public function database(){
    	ini_set("memory_limit","-1");
		ini_set('max_execution_time', 200);

    	// Load the DB utility class
		$this->load->dbutil();

		// Backup your entire database and assign it to a variable
		$backup = $this->dbutil->backup();

		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
		$tanggal = date('Y-m-d_H-i-s');
		force_download('backup_database_zya_cbt_'.$tanggal.'_.gz', $backup);
    }

    public function data_upload(){
    	ini_set("memory_limit","-1");
		ini_set('max_execution_time', 200);
    	
    	$this->load->library('zip');

    	$path = $this->config->item('upload_path');

		$this->zip->read_dir($path);

		// Download the file to your desktop. Name it "my_backup.zip"
		$this->zip->download('backup_data_upload_zya_cbt.zip');
    }
	
	public function clear_session(){
		$this->load->model('cbt_sessions_model');
		
		$this->cbt_sessions_model->empty_table();
		
		$status['status'] = 1;
		$status['pesan'] = 'Sessions berhasil dihapus';
		echo json_encode($status);
	}

	/**
	 * Membersihkan folder uploads/topik_X yatim yang topiknya sudah tidak ada di cbt_topik
	 */
	public function clean_orphan_uploads(){
		$this->load->helper('file');
		$upload_base = rtrim((string)$this->config->item('upload_path'), '/');
		if(empty($upload_base) || !is_dir($upload_base)){
			echo json_encode(array('status' => 0, 'pesan' => 'Folder uploads tidak ditemukan.'));
			return;
		}
		$valid_ids = array();
		$q_topik = $this->db->select('topik_id')->from('cbt_topik')->get()->result();
		foreach($q_topik as $t){
			$valid_ids[(int)$t->topik_id] = true;
		}
		$removed = array();
		$entries = scandir($upload_base);
		if(is_array($entries)){
			foreach($entries as $entry){
				if(preg_match('/^topik_(\d+)$/', $entry, $m)){
					$tid = (int)$m[1];
					if(!isset($valid_ids[$tid])){
						$dir_path = $upload_base.'/'.$entry;
						if(is_dir($dir_path)){
							@delete_files($dir_path, TRUE);
							if(@rmdir($dir_path)){
								$removed[] = $entry;
							}
						}
					}
				}
			}
		}
		$jml = count($removed);
		$msg = ($jml > 0)
			? 'Berhasil membersihkan '.$jml.' folder media yatim: '.implode(', ', $removed)
			: 'Tidak ada folder media yatim (semua folder uploads/topik_X sinkron dengan database).';
		echo json_encode(array('status' => 1, 'pesan' => $msg, 'removed' => $removed));
	}

	/**
	 * Reset Data Dummy / Uji Coba sebelum ujian real:
	 * - Opsi 1 (reset_hasil_dummy): Hapus seluruh sesi/hasil tes & peserta dummy NISN 999%
	 * - Opsi 2 (reset_total_ujian): Kosongkan seluruh hasil tes, jadwal tes, peserta, & bank soal untuk mulai bersih
	 */
	public function reset_data_dummy(){
		$konfirmasi = trim((string)$this->input->post('konfirmasi', TRUE));
		$mode = trim((string)$this->input->post('mode', TRUE));
		if(strtoupper($konfirmasi) !== 'RESET DUMMY'){
			echo json_encode(array('status' => 0, 'pesan' => 'Ketik teks konfirmasi "RESET DUMMY" dengan benar.'));
			return;
		}

		$this->db->trans_start();
		if($mode === 'full_clean'){
			// Hapus semua hasil tes, jadwal tes, peserta, grup, soal, topik, modul (sisakan 1 modul default)
			$this->db->query('DELETE FROM cbt_tes_soal_jawaban');
			$this->db->query('DELETE FROM cbt_tes_soal');
			$this->db->query('DELETE FROM cbt_tes_user');
			$this->db->query('DELETE FROM cbt_tes_token');
			$this->db->query('DELETE FROM cbt_tesgrup');
			$this->db->query('DELETE FROM cbt_tes_topik_set');
			$this->db->query('DELETE FROM cbt_tes');
			$this->db->query('DELETE FROM cbt_user');
			$this->db->query('DELETE FROM cbt_user_grup');
			$this->db->query('DELETE FROM cbt_jawaban');
			$this->db->query('DELETE FROM cbt_soal');
			$this->db->query('DELETE FROM cbt_topik');
			$this->db->query('DELETE FROM cbt_modul');
			$this->db->insert('cbt_modul', array('modul_nama' => 'ASAS Ganjil 2026/2027', 'modul_aktif' => 1));
			$pesan = 'Seluruh data dummy (Peserta, Kelas, Jadwal Tes, Hasil Tes, dan Bank Soal Dummy) berhasil dibersihkan. Sistem siap diisi data ujian real.';
		}else{
			// Default: Hapus peserta dummy 999% beserta sesi tesnya, dan hapus hasil tes uji coba
			$this->db->query('DELETE FROM cbt_user WHERE user_name LIKE "999%"');
			$this->db->query('DELETE FROM cbt_tes_user WHERE tesuser_user_id NOT IN (SELECT user_id FROM cbt_user)');
			$pesan = 'Seluruh peserta dummy (NISN 999...) beserta riwayat hasil tesnya berhasil dihapus.';
		}
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			echo json_encode(array('status' => 0, 'pesan' => 'Gagal mereset data dummy di database.'));
			return;
		}
		echo json_encode(array('status' => 1, 'pesan' => $pesan));
	}
}