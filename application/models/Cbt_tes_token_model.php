<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cbt_tes_token_model extends CI_Model{
	public $table = 'cbt_tes_token';
	
	
    function save($data){
        $this->db->insert($this->table, $data);
    }
    
    function delete($kolom, $isi){
        $this->db->where($kolom, $isi)
                 ->delete($this->table);
    }

    function delete_by_user_date($user_id){
		$now = date('Y-m-d H:i:s');
        $this->db->where('DATE(token_ts)<DATE('.$this->db->escape($now).')', null, false)
                 ->where('token_user_id', (int)$user_id)
                 ->delete($this->table);
    }
    
    function update($kolom, $isi, $data){
        $this->db->where($kolom, $isi)
                 ->update($this->table, $data);
    }
    
    function count_by_kolom($kolom, $isi){
        $this->db->select('COUNT(*) AS hasil')
                 ->where($kolom, $isi)
                 ->from($this->table);
        return $this->db->get();
    }

    function count_by_isi($isi){
		$now = date('Y-m-d H:i:s');
        $this->db->select('COUNT(*) AS hasil')
                 ->where('token_isi', (string)$isi)
                 ->where('DATE(token_ts)=DATE('.$this->db->escape($now).')', null, false)
                 ->from($this->table);
        return $this->db->get();
    }
	
	function get_by_kolom($kolom, $isi){
        $this->db->select('token_id,token_isi,token_user_id,token_ts,token_aktif')
                 ->where($kolom, $isi)
                 ->from($this->table);
        return $this->db->get();
    }

    /**
     * Mendapatkan token berdasarkan token dan tanggal hari ini
     */
    function get_by_token_now_limit($token, $limit){
		$now = date('Y-m-d H:i:s');
        $this->db->select('token_id,token_isi,token_user_id,token_ts,token_aktif,token_tes_id')
                 ->where('token_isi', (string)$token)
                 ->where('DATE(token_ts)=DATE('.$this->db->escape($now).')', null, false)
                 ->from($this->table)
				 ->limit((int)$limit);
        return $this->db->get();
    }

    // Mendapatkan jumlah token berdasarkan lifetime token yang sudah di generate
    function count_by_token_lifetime($token, $lifetime){
		$now = date('Y-m-d H:i:s');
        $this->db->select('COUNT(*) AS hasil')
                 ->where('token_isi', (string)$token)
                 ->where('token_ts>TIMESTAMPADD(MINUTE,-'.(int)$lifetime.','.$this->db->escape($now).')', null, false)
                 ->from($this->table);
        return $this->db->get();
    }
	
	function get_by_kolom_limit($kolom, $isi, $limit){
        $this->db->select('token_id,token_isi,token_user_id,token_ts,token_aktif,token_tes_id')
                 ->where($kolom, $isi)
                 ->from($this->table)
				 ->limit((int)$limit);
        return $this->db->get();
    }

    function get_by_user_now($user_id){
		$now = date('Y-m-d H:i:s');
        $sql = 'SELECT GROUP_CONCAT("\'",token_isi,"\'") AS token FROM cbt_tes_token WHERE token_user_id=? AND DATE(token_ts)=DATE(?) ORDER BY token_id ASC';

        return $this->db->query($sql, array((int)$user_id, $now));
    }
	
	function get_datatable($start, $rows, $kolom, $isi, $username){
		$this->db->like($kolom, $isi)
                 ->where('username', (string)$username)
                 ->from($this->table)
				 ->join('user', 'cbt_tes_token.token_user_id = user.id')
				 ->order_by('token_id', 'DESC')
                 ->limit((int)$rows, (int)$start);
        return $this->db->get();
	}
    
    function get_datatable_count($kolom, $isi, $username){
		$this->db->select('COUNT(*) AS hasil')
                 ->like($kolom, $isi)
                 ->where('username', (string)$username)
                 ->from($this->table)
				 ->join('user', 'cbt_tes_token.token_user_id = user.id');
        return $this->db->get();
	}
}