<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* ZYA CBT
* Achmad Lutfi
* achmdlutfi@gmail.com
* achmadlutfi.wordpress.com
*/
#[AllowDynamicProperties]
class Access_tes{
	function __construct(){
		$this->CI =& get_instance();
		
		$this->CI->load->helper('cookie');
		$this->CI->load->model('cbt_user_model');
		
		$this->users_model =& $this->CI->cbt_user_model;
	}
	
	
	/**
	 * proses login
	 * 0 = username tak ada
	 * 1 = sukses
	 * 2 = password salah
	 * @param unknown_type $username
	 * @param unknown_type $password
	 * @return boolean
	 */
	function login($username, $password){
		$result = $this->users_model->get_by_username($username);
		if($result){
			if($password === $result->user_password){
				/*
				$this->CI->session->set_userdata('cbt_tes_user_id',$result->user_name);
                $this->CI->session->set_userdata('cbt_tes_nama',stripslashes($result->user_firstname));
                $this->CI->session->set_userdata('cbt_tes_group',$result->grup_nama);
                $this->CI->session->set_userdata('cbt_tes_group_id',$result->grup_id);
				*/
				return 1;
			}else{
				return 2;
			}
		}
		return 0;
	}
	
	/**
	 * cek apakah sudah login
	 * @return boolean
	 */
	function is_login(){
		return (($this->CI->session->userdata('cbt_tes_user_id')) ? TRUE : FALSE);
	}
	
	function get_username(){
		return $this->CI->session->userdata('cbt_tes_user_id');
	}
    
    function get_nama(){
		return $this->CI->session->userdata('cbt_tes_nama');
	}
    
    function get_group(){
		return $this->CI->session->userdata('cbt_tes_group');
	}
    
    function get_group_id(){
		return $this->CI->session->userdata('cbt_tes_group_id');
	}
	
	function is_token(){
		return (($this->CI->session->userdata('cbt_tes_token')) ? TRUE : FALSE);
	}
	
	function remove_token(){
		$this->CI->session->unset_userdata('cbt_tes_token');
	}
	
	/**
	 * Deteksi IP & Jalur Akses Peserta (LAN vs Publik / Paket Data via Cloudflare)
	 * Mengembalikan array:
	 *  - 'mode' : 'PUB' | 'LAN'
	 *  - 'ip'   : IP asli peserta (maks 35 karakter)
	 *  - 'tag'  : string ringkas maks 39 karakter untuk kolom user_ip / tessoal_user_ip (mis. "LAN|10.10.11.45" atau "PUB|114.122.10.5")
	 *  - 'host' : HTTP_HOST yang diakses
	 */
	function detect_network_info(){
		$host = isset($_SERVER['HTTP_HOST']) ? strtolower(trim((string)$_SERVER['HTTP_HOST'])) : '';
		$cf_ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? trim((string)$_SERVER['HTTP_CF_CONNECTING_IP']) : '';
		$cf_ray = isset($_SERVER['HTTP_CF_RAY']) ? trim((string)$_SERVER['HTTP_CF_RAY']) : '';
		$xff = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? trim((string)$_SERVER['HTTP_X_FORWARDED_FOR']) : '';
		$remote = isset($_SERVER['REMOTE_ADDR']) ? trim((string)$_SERVER['REMOTE_ADDR']) : '';

		$is_public_domain = (
			strpos($host, 'smknuda.my.id') !== false ||
			strpos($host, 'smknuda.qzz.io') !== false
		);

		if($cf_ip !== '' || $cf_ray !== '' || $is_public_domain){
			$mode = 'PUB';
			if($cf_ip !== ''){
				$ip = $cf_ip;
			}elseif($xff !== ''){
				$parts = explode(',', $xff);
				$ip = trim($parts[0]);
			}else{
				$ip = ($remote !== '') ? $remote : '0.0.0.0';
			}
		}else{
			$mode = 'LAN';
			if($xff !== ''){
				$parts = explode(',', $xff);
				$ip = trim($parts[0]);
			}else{
				$ip = ($remote !== '') ? $remote : '10.10.10.10';
			}
		}

		// Bersihkan karakter aneh dan pastikan muat di varchar(39) dengan prefix "LAN|" atau "PUB|" (4 + 35 = 39)
		$ip_clean = preg_replace('/[^0-9a-fA-F:\.]/', '', (string)$ip);
		if($ip_clean === ''){
			$ip_clean = '0.0.0.0';
		}
		$ip_short = substr($ip_clean, 0, 35);
		$tag = substr($mode.'|'.$ip_short, 0, 39);

		return array(
			'mode' => $mode,
			'ip'   => $ip_short,
			'tag'  => $tag,
			'host' => substr($host, 0, 60)
		);
	}

	/**
	 * Catat IP & jalur akses ke cbt_user.user_ip dan tulis bukti ke user_log
	 * bila terdeteksi akses jalur PUBLIK (Paket Data) atau perpindahan jalur.
	 * Dibuat defensive (try/catch & non-blocking) agar tidak pernah mengganggu ujian.
	 */
	function record_activity_network($username, $aksi = 'AKTIVITAS', $extra_ket = ''){
		$net = $this->detect_network_info();
		$username = trim((string)$username);
		if($username === ''){
			return $net;
		}
		try {
			$prev_tag = '';
			$user_ruang = '';
			$prev_login_date = '';
			$today_date = date('Y-m-d');
			$q_user = $this->CI->db->select('user_ip, user_detail, user_login_date')
			                       ->where('user_name', $username)
			                       ->limit(1)
			                       ->get('cbt_user');
			if($q_user && $q_user->num_rows() > 0){
				$u_row = $q_user->row();
				$prev_tag = trim((string)$u_row->user_ip);
				$user_ruang = trim((string)$u_row->user_detail);
				$prev_login_date = trim((string)$u_row->user_login_date);
			}

			// Anggap status kemarin sudah kedaluwarsa saat berganti hari
			$is_same_day = ($prev_login_date === $today_date);
			$effective_prev_tag = $is_same_day ? $prev_tag : '';

			// Update cbt_user.user_ip dan tanggal login hari ini (untuk reset tampilan harian otomatis tanpa hapus log)
			if($prev_tag !== $net['tag'] || !$is_same_day){
				$this->CI->db->where('user_name', $username)
				             ->update('cbt_user', array(
				                 'user_ip'         => $net['tag'],
				                 'user_login_date' => $today_date
				             ));
			}

			$prev_mode = '';
			if(strpos($effective_prev_tag, 'LAN|') === 0){
				$prev_mode = 'LAN';
			}elseif(strpos($effective_prev_tag, 'PUB|') === 0){
				$prev_mode = 'PUB';
			}

			// Catat ke user_log jika:
			// 1) Akses melalui jalur PUB (paket data / internet) pada saat LOGIN / MULAI_TES, atau
			// 2) Terjadi perpindahan jalur (LAN -> PUB atau PUB -> LAN), atau
			// 3) Selama SIMPAN_JAWABAN di jalur PUB dengan IP berubah
			$should_log = false;
			$log_msg = '';
			$ket_parts = array();
			if($user_ruang !== ''){
				$ket_parts[] = 'Ruang: '.$user_ruang;
			}
			if($extra_ket !== ''){
				$ket_parts[] = $extra_ket;
			}
			$ket_suffix = !empty($ket_parts) ? ' ('.implode(', ', $ket_parts).')' : '';

			if($net['mode'] === 'PUB'){
				if($prev_mode === 'LAN'){
					$should_log = true;
					$log_msg = 'PINDAH KE PAKET DATA/PUBLIK ['.$aksi.']: dari '.$prev_tag.' ke '.$net['tag'].' via '.$net['host'].$ket_suffix;
				}elseif($aksi === 'LOGIN' || $aksi === 'MULAI_TES' || $aksi === 'SELESAI' || $aksi === 'LOGOUT' || $prev_tag !== $net['tag']){
					$should_log = true;
					$log_msg = 'AKSES PAKET DATA/PUBLIK ['.$aksi.']: '.$net['tag'].' via '.$net['host'].$ket_suffix;
				}
			}elseif($net['mode'] === 'LAN' && $prev_mode === 'PUB'){
				$should_log = true;
				$log_msg = 'KEMBALI KE LAN ['.$aksi.']: dari '.$prev_tag.' ke '.$net['tag'].' via '.$net['host'].$ket_suffix;
			}

			if($should_log && $log_msg !== ''){
				$this->CI->db->insert('user_log', array(
					'username' => substr($username, 0, 100),
					'log'      => substr($log_msg, 0, 250)
				));
			}
		} catch (Exception $e) {
			// Defensive: abaikan error logging agar ujian siswa tidak terganggu
		}
		return $net;
	}

	/**
	 * logout
	 */
	function logout(){
		$this->CI->session->unset_userdata('cbt_tes_tanda');
		$this->CI->session->unset_userdata('cbt_tes_user_id');
		$this->CI->session->unset_userdata('cbt_tes_nama');
		$this->CI->session->unset_userdata('cbt_tes_group_id');
		$this->CI->session->unset_userdata('cbt_tes_group');
		$this->CI->session->unset_userdata('cbt_tes_token');
		$this->CI->session->sess_regenerate(TRUE);
	}
}