<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* ZYA CBT — Halaman Pantau Ruang & Pengawas (Read-Only, Mobile-First)
*/
class Tes_hasil_operator extends Member_Controller {
	private $kode_menu = 'tes-hasil-operator';
	private $kelompok = 'tes';
	private $url = 'manager/tes_hasil_operator';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('cbt_user_model');
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_tes_model');
		$this->load->model('cbt_tes_user_model');
		$this->load->model('cbt_tes_soal_model');

		parent::cek_akses($this->kode_menu);
	}
	
    public function index($page=null, $id=null){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        $is_admin = (strtolower(trim((string)$this->access->get_level())) === 'admin');

        // 1. Daftar Ruang untuk Kartu Ruang (dari cbt_user.user_detail / Keterangan)
        $ruang_list = array();
        $q_ruang = $this->db->select('user_detail, COUNT(*) AS jml')
                            ->from('cbt_user')
                            ->where('user_detail IS NOT NULL', null, false)
                            ->where("TRIM(user_detail) !=", '')
                            ->group_by('user_detail')
                            ->get();
        if($q_ruang->num_rows() > 0){
            $raw_rooms = $q_ruang->result();
            usort($raw_rooms, function($a, $b){
                return strnatcasecmp(trim((string)$a->user_detail), trim((string)$b->user_detail));
            });
            foreach($raw_rooms as $r){
                $r_val = trim((string)$r->user_detail);
                if($r_val !== ''){
                    $ruang_list[] = array(
                        'kode'   => $r_val,
                        'label'  => $r_val,
                        'jumlah' => (int)$r->jml
                    );
                }
            }
        }
        $jml_tanpa_ruang = (int)$this->db->where("(user_detail IS NULL OR TRIM(user_detail) = '')", null, false)
                                         ->count_all_results('cbt_user');
        if($jml_tanpa_ruang > 0){
            $ruang_list[] = array(
                'kode'   => '__tanpa_ruang__',
                'label'  => 'Belum Diatur Ruang',
                'jumlah' => $jml_tanpa_ruang
            );
        }

        $data['is_admin'] = $is_admin;
        $data['ruang_list'] = $ruang_list;
        
        $this->template->display_admin($this->kelompok.'/tes_hasil_operator_view', 'Pantau Ruang Pengawas', $data);
    }

    /**
     * Endpoint JSON ringan untuk polling auto-refresh tiap 10 detik dari HP Pengawas
     * Read-only: Tanpa skor/nilai dan tanpa aksi proktor.
     */
    public function get_pantau_data(){
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $ruang  = trim((string)$this->input->get('ruang', TRUE));
        $group  = trim((string)$this->input->get('group', TRUE));
        $tes_id = trim((string)$this->input->get('tes', TRUE));
        $filter_warna = trim((string)$this->input->get('warna', TRUE)); // masalah | semua | merah | kuning | hijau | belum
        if($filter_warna === ''){
            $filter_warna = 'masalah';
        }
        $search = trim((string)$this->input->get('q', TRUE));

        // Jika ruang belum dipilih, jangan muat/tampilkan data siswa
        if($ruang === ''){
            echo json_encode(array(
                'status' => 1,
                'empty_room' => true,
                'waktu_server' => date('H:i:s'),
                'summary' => array('total' => 0, 'merah' => 0, 'kuning' => 0, 'hijau' => 0, 'belum' => 0, 'masalah' => 0),
                'items' => array()
            ));
            return;
        }

        $is_admin = (strtolower(trim((string)$this->access->get_level())) === 'admin');
        if($ruang === 'semua_masalah'){
            if(!$is_admin){
                echo json_encode(array(
                    'status' => 1,
                    'empty_room' => true,
                    'waktu_server' => date('H:i:s'),
                    'summary' => array('total' => 0, 'merah' => 0, 'kuning' => 0, 'hijau' => 0, 'belum' => 0, 'masalah' => 0),
                    'items' => array()
                ));
                return;
            }
            // Mode lintas-ruang admin: izinkan filter 'masalah' (Merah+Kuning), 'merah', atau 'kuning' saja
            if(!in_array($filter_warna, array('masalah', 'merah', 'kuning'), true)){
                $filter_warna = 'masalah';
            }
        }

        // Ambil daftar siswa sesuai filter ruang
        $this->db->select('u.user_id, u.user_name, u.user_firstname, u.user_detail, u.user_ip, u.user_login, u.user_login_date, g.grup_nama')
                 ->from('cbt_user u')
                 ->join('cbt_user_grup g', 'g.grup_id = u.user_grup_id', 'left');

        if($ruang === '__tanpa_ruang__'){
            $this->db->where("(u.user_detail IS NULL OR TRIM(u.user_detail) = '')", null, false);
        }elseif($ruang !== 'semua' && $ruang !== 'semua_masalah'){
            $this->db->where('u.user_detail', $ruang);
        }
        if($group !== '' && $group !== 'semua' && is_numeric($group)){
            $this->db->where('u.user_grup_id', (int)$group);
        }
        if($search !== ''){
            $s_like = $this->db->escape_like_str($search);
            $this->db->where("(u.user_firstname LIKE '%{$s_like}%' ESCAPE '!' OR u.user_name LIKE '%{$s_like}%' ESCAPE '!' OR u.user_detail LIKE '%{$s_like}%' ESCAPE '!')", null, false);
        }

        $this->db->order_by('u.user_firstname', 'ASC')
                 ->limit(500);
        $users = $this->db->get()->result();

        if(empty($users)){
            echo json_encode(array(
                'status' => 1,
                'waktu_server' => date('H:i:s'),
                'summary' => array('total' => 0, 'merah' => 0, 'kuning' => 0, 'hijau' => 0, 'belum' => 0),
                'items' => array()
            ));
            return;
        }

        $user_ids = array();
        $usernames = array();
        foreach($users as $u){
            $user_ids[] = (int)$u->user_id;
            $usernames[] = (string)$u->user_name;
        }

        $today_date = date('Y-m-d');

        // Ambil sesi ujian HARI INI saja per siswa (beserta progres soal terjawab / total soal & IP soal terakhir)
        $tes_cond = '';
        if($tes_id !== '' && $tes_id !== 'semua' && is_numeric($tes_id)){
            $tes_cond = ' AND tu.tesuser_tes_id = '.(int)$tes_id;
        }

        $ids_csv = implode(',', $user_ids);
        $sql_sesi = "
            SELECT 
                tu.tesuser_id,
                tu.tesuser_user_id,
                tu.tesuser_tes_id,
                tu.tesuser_status,
                tu.tesuser_creation_time,
                t.tes_nama,
                t.tes_duration_time,
                COUNT(ts.tessoal_id) AS total_soal,
                SUM(CASE WHEN ts.tessoal_change_time IS NOT NULL THEN 1 ELSE 0 END) AS terjawab,
                MAX(ts.tessoal_change_time) AS last_answer_time,
                SUBSTRING_INDEX(
                    GROUP_CONCAT(
                        CASE WHEN ts.tessoal_user_ip IS NOT NULL AND ts.tessoal_user_ip != '' THEN ts.tessoal_user_ip ELSE NULL END
                        ORDER BY COALESCE(ts.tessoal_change_time, ts.tessoal_creation_time) DESC, ts.tessoal_id DESC
                        SEPARATOR ';;'
                    ),
                    ';;', 1
                ) AS last_soal_ip,
                SUM(CASE WHEN ts.tessoal_user_ip LIKE 'PUB|%' THEN 1 ELSE 0 END) AS soal_pub_count
            FROM cbt_tes_user tu
            INNER JOIN cbt_tes t ON t.tes_id = tu.tesuser_tes_id
            LEFT JOIN cbt_tes_soal ts ON ts.tessoal_tesuser_id = tu.tesuser_id
            WHERE tu.tesuser_user_id IN ({$ids_csv})
              AND DATE(tu.tesuser_creation_time) = ".$this->db->escape($today_date)." {$tes_cond}
            GROUP BY tu.tesuser_id
            ORDER BY tu.tesuser_id ASC
        ";
        $sesi_rows = $this->db->query($sql_sesi)->result();
        $sesi_by_user = array();
        foreach($sesi_rows as $sr){
            // Simpan sesi paling baru untuk tiap user_id hari ini
            $sesi_by_user[(int)$sr->tesuser_user_id] = $sr;
        }

        // Ambil riwayat pelanggaran / pindah jaringan di user_log HARI INI saja
        $log_by_username = array();
        if(!empty($usernames)){
            $this->db->select('username, log, ts')
                     ->from('user_log')
                     ->where_in('username', $usernames)
                     ->where('DATE(ts)', $today_date)
                     ->order_by('id', 'DESC');
            $q_logs = $this->db->get();
            if($q_logs && $q_logs->num_rows() > 0){
                foreach($q_logs->result() as $lg){
                    $uname = (string)$lg->username;
                    if(!isset($log_by_username[$uname])){
                        $log_by_username[$uname] = array(
                            'count' => 0,
                            'pub_count' => 0,
                            'kembali_count' => 0,
                            'has_pub' => false,
                            'last_log' => $lg->log,
                            'last_ts' => date('H:i:s', strtotime($lg->ts)),
                            'last_ts_raw' => $lg->ts
                        );
                    }
                    $log_by_username[$uname]['count']++;
                    if(strpos($lg->log, 'KEMBALI KE LAN') === 0){
                        $log_by_username[$uname]['kembali_count']++;
                    }elseif(strpos($lg->log, 'PAKET DATA') !== false || strpos($lg->log, 'PUB|') !== false){
                        $log_by_username[$uname]['has_pub'] = true;
                        $log_by_username[$uname]['pub_count']++;
                    }
                }
            }
        }

        $now_dt = new DateTime();
        $items = array();
        $summary = array('total' => 0, 'merah' => 0, 'kuning' => 0, 'hijau' => 0, 'belum' => 0, 'masalah' => 0);

        foreach($users as $u){
            $uid = (int)$u->user_id;
            $uname = (string)$u->user_name;
            $sesi = isset($sesi_by_user[$uid]) ? $sesi_by_user[$uid] : null;
            $ulog = isset($log_by_username[$uname]) ? $log_by_username[$uname] : null;

            // Tentukan tag IP aktif HARI INI saja (jika user_login_date != hari ini, dianggap belum login hari ini)
            $is_logged_today = (!empty($u->user_login_date) && substr((string)$u->user_login_date, 0, 10) === $today_date);
            $raw_tag = $is_logged_today ? trim((string)$u->user_ip) : '';
            if($raw_tag === '' && $sesi && !empty($sesi->last_soal_ip)){
                $raw_tag = trim((string)$sesi->last_soal_ip);
            }
            if($raw_tag === '' && $ulog && !empty($ulog['last_log'])){
                if(preg_match('/((?:PUB|LAN)\|[^\s\)]+)/', (string)$ulog['last_log'], $m_tag)){
                    $raw_tag = $m_tag[1];
                }
            }

            $cur_mode = '';
            $cur_ip = '-';
            if(strpos($raw_tag, 'PUB|') === 0){
                $cur_mode = 'PUB';
                $cur_ip = substr($raw_tag, 4);
            }elseif(strpos($raw_tag, 'LAN|') === 0){
                $cur_mode = 'LAN';
                $cur_ip = substr($raw_tag, 4);
            }elseif($raw_tag !== ''){
                // Format lama tanpa prefix
                $cur_ip = $raw_tag;
                if(preg_match('/^(10\.|172\.(1[6-9]|2[0-9]|3[0-1])\.|192\.168\.|127\.)/', $raw_tag)){
                    $cur_mode = 'LAN';
                }else{
                    $cur_mode = 'PUB';
                }
            }

            // Perjelas tampilan apabila IP LAN tercatat sebagai Docker bridge gateway 172.18.0.1
            $ip_display = $cur_ip;
            if($cur_mode === 'LAN' && strpos($cur_ip, '172.18.') === 0){
                $ip_display = '10.10.10.10 (LAN Sekolah)';
            }

            $pernah_pub = false;
            if(($ulog && $ulog['has_pub']) || ($sesi && (int)$sesi->soal_pub_count > 0)){
                $pernah_pub = true;
            }

            // Status & Progres Ujian (Tanpa menampilkan Nilai/Skor)
            $status_ujian = 'Belum Mulai';
            $status_kode = 'belum'; // berjalan | selesai | belum
            $tes_nama = '-';
            $progres_teks = 'Belum membuka soal';
            $waktu_mulai = '-';
            $waktu_aktivitas = '-';

            if($sesi){
                $tes_nama = (string)$sesi->tes_nama;
                $terjawab = (int)$sesi->terjawab;
                $total_soal = (int)$sesi->total_soal;
                $waktu_mulai = date('H:i', strtotime($sesi->tesuser_creation_time));
                $waktu_aktivitas = !empty($sesi->last_answer_time)
                    ? date('H:i:s', strtotime($sesi->last_answer_time))
                    : date('H:i:s', strtotime($sesi->tesuser_creation_time));

                $batas_dt = new DateTime($sesi->tesuser_creation_time);
                $batas_dt->modify('+'.(int)$sesi->tes_duration_time.' minutes');

                if((int)$sesi->tesuser_status === 1 && $now_dt < $batas_dt){
                    $diff = $batas_dt->diff($now_dt);
                    $sisa_mnt = ($diff->h * 60) + $diff->i;
                    $status_ujian = 'Berjalan (Sisa '.$sisa_mnt.' mnt)';
                    $status_kode = 'berjalan';
                }else{
                    $status_ujian = 'Selesai';
                    $status_kode = 'selesai';
                }
                $progres_teks = 'Terjawab '.$terjawab.' / '.$total_soal.' soal';
            }elseif($ulog){
                $waktu_aktivitas = $ulog['last_ts'];
            }

            // Tentukan Warna Indikator (Merah / Kuning / Hijau / Belum)
            // Prioritas pengurutan: 1 = MERAH, 2 = KUNING, 3 = HIJAU (Berjalan), 4 = HIJAU (Selesai/Login), 5 = BELUM
            if($cur_mode === 'PUB'){
                $warna = 'merah';
                $badge_label = 'PAKET DATA / PUBLIK';
                $jalur_teks = 'Internet / Paket Data (Cloudflare)';
                $sort_priority = 1;
            }elseif($cur_mode === 'LAN' && $pernah_pub){
                $warna = 'kuning';
                $badge_label = 'PERNAH PINDAH JARINGAN';
                $jalur_teks = 'Saat ini LAN, tapi tercatat sempat ke Paket Data';
                $sort_priority = 2;
            }elseif($cur_mode === 'LAN'){
                $warna = 'hijau';
                $badge_label = 'LAN UJIAN (AMAN)';
                $jalur_teks = 'Jaringan Lokal Sekolah (LAN)';
                $sort_priority = ($status_kode === 'berjalan') ? 3 : 4;
            }elseif($sesi){
                // Sesi lama yang dibuat sebelum fitur pencatatan IP aktif
                $warna = 'hijau';
                $badge_label = 'SESI TERCATAT';
                $jalur_teks = 'Sesi sebelum log IP aktif';
                $sort_priority = 4;
            }else{
                $warna = 'belum';
                $badge_label = 'BELUM LOGIN / MULAI';
                $jalur_teks = 'Belum ada koneksi tercatat';
                $sort_priority = 5;
            }

            $summary['total']++;
            if(isset($summary[$warna])){
                $summary[$warna]++;
            }
            if($warna === 'merah' || $warna === 'kuning'){
                $summary['masalah']++;
            }

            // Default: utamakan Kuning & Merah saja (Hijau & Belum cukup angka ringkasan).
            // Jika pengawas sedang mengetik pencarian nama/NISN, tampilkan siswa yang dicari walaupun Hijau/Belum.
            if($filter_warna === 'masalah'){
                if($search === '' && $warna !== 'merah' && $warna !== 'kuning'){
                    continue;
                }
            }elseif($filter_warna !== '' && $filter_warna !== 'semua' && $filter_warna !== $warna){
                continue;
            }

            // Tentukan Jam Kejadian Besar & Aksi Terakhir yang Jelas (Login / Mulai Tes / Simpan Jawaban / Selesai / Logout)
            $latest_ts_int = 0;
            $jam_kejadian = '-';
            $aksi_terakhir = 'Belum ada aktivitas tercatat';

            if($sesi){
                $t_mulai_int = strtotime($sesi->tesuser_creation_time);
                if($t_mulai_int > $latest_ts_int){
                    $latest_ts_int = $t_mulai_int;
                    $jam_kejadian = date('H:i:s', $t_mulai_int);
                    $aksi_terakhir = 'Mulai Tes ('.$tes_nama.')';
                }
                if(!empty($sesi->last_answer_time)){
                    $t_ans_int = strtotime($sesi->last_answer_time);
                    if($t_ans_int >= $latest_ts_int){
                        $latest_ts_int = $t_ans_int;
                        $jam_kejadian = date('H:i:s', $t_ans_int);
                        $aksi_terakhir = ($status_kode === 'selesai')
                            ? 'Selesai / Kirim Jawaban Terakhir ('.$terjawab.'/'.$total_soal.' soal)'
                            : 'Simpan Jawaban ('.$terjawab.'/'.$total_soal.' soal terjawab)';
                    }
                }
                if((int)$sesi->tesuser_status === 4 && strpos($aksi_terakhir, 'Selesai') === false){
                    $aksi_terakhir .= ' • Status: Selesai Dikerjakan';
                }
            }

            if($ulog && !empty($ulog['last_ts_raw'])){
                $t_log_int = strtotime($ulog['last_ts_raw']);
                if($t_log_int >= $latest_ts_int){
                    $latest_ts_int = $t_log_int;
                    $jam_kejadian = date('H:i:s', $t_log_int);
                    $log_str = (string)$ulog['last_log'];
                    $aksi_kode = '';
                    if(preg_match('/\[([A-Z_]+)\]/', $log_str, $m_aksi)){
                        $aksi_kode = $m_aksi[1];
                    }
                    $jalur_label = (strpos($log_str, 'KEMBALI KE LAN') === 0) ? 'Kembali ke LAN' : 'via Paket Data / Publik';
                    if($aksi_kode === 'LOGIN'){
                        $aksi_terakhir = 'Login '.$jalur_label;
                    }elseif($aksi_kode === 'MULAI_TES'){
                        $aksi_terakhir = 'Mulai Tes '.$jalur_label;
                    }elseif($aksi_kode === 'JAWAB'){
                        $aksi_terakhir = 'Simpan Jawaban '.$jalur_label;
                    }elseif($aksi_kode === 'SELESAI'){
                        $aksi_terakhir = 'Klik Selesai Ujian '.$jalur_label;
                    }elseif($aksi_kode === 'LOGOUT'){
                        $aksi_terakhir = 'Logout '.$jalur_label;
                    }else{
                        $aksi_terakhir = 'Aktivitas Jaringan ('.$jalur_label.')';
                    }
                    if($sesi && $status_kode === 'selesai' && $aksi_kode !== 'SELESAI'){
                        $aksi_terakhir .= ' • Sesi Selesai';
                    }
                }
            }

            $anomali_count = $ulog ? (int)$ulog['count'] : 0;
            $anomali_pub = $ulog ? (int)$ulog['pub_count'] : 0;
            $anomali_kembali = $ulog ? (int)$ulog['kembali_count'] : 0;
            $anomali_summary = '';
            if($anomali_count > 0){
                $rincian = array();
                if($anomali_pub > 0){
                    $rincian[] = $anomali_pub.'x Paket Data';
                }
                if($anomali_kembali > 0){
                    $rincian[] = $anomali_kembali.'x Kembali LAN';
                }
                $anomali_summary = $anomali_count.'x Pindah/Keluar Jalur'.(!empty($rincian) ? ' ('.implode(', ', $rincian).')' : '');
            }

            $catatan_log = '';
            if($ulog && !empty($ulog['last_log'])){
                $catatan_log = $ulog['last_ts'].' — '.$ulog['last_log'];
            }elseif($sesi && (int)$sesi->soal_pub_count > 0){
                $catatan_log = 'Tercatat '.(int)$sesi->soal_pub_count.' jawaban dikirim lewat jalur Paket Data/Publik.';
            }

            $items[] = array(
                'user_id'         => $uid,
                'username'        => $uname,
                'nama'            => stripslashes((string)$u->user_firstname),
                'kelas'           => !empty($u->grup_nama) ? (string)$u->grup_nama : '-',
                'ruang'           => !empty($u->user_detail) ? (string)$u->user_detail : '-',
                'warna'           => $warna,
                'badge_label'     => $badge_label,
                'ip'              => $ip_display,
                'jalur'           => $jalur_teks,
                'status_ujian'    => $status_ujian,
                'status_kode'     => $status_kode,
                'tes_nama'        => $tes_nama,
                'progres'         => $progres_teks,
                'waktu_mulai'     => $waktu_mulai,
                'waktu_aktivitas' => $waktu_aktivitas,
                'jam_kejadian'    => $jam_kejadian,
                'aksi_terakhir'   => $aksi_terakhir,
                'anomali_count'   => $anomali_count,
                'anomali_summary' => $anomali_summary,
                'catatan_log'     => $catatan_log,
                'sort_priority'   => $sort_priority
            );
        }

        // Urutkan: Merah (1) -> Kuning (2) -> Hijau Berjalan (3) -> Hijau Selesai (4) -> Belum (5), lalu Abjad Nama
        usort($items, function($a, $b){
            if($a['sort_priority'] !== $b['sort_priority']){
                return $a['sort_priority'] - $b['sort_priority'];
            }
            return strcasecmp($a['nama'], $b['nama']);
        });

        echo json_encode(array(
            'status'       => 1,
            'waktu_server' => date('H:i:s'),
            'summary'      => $summary,
            'items'        => $items
        ));
    }
}
