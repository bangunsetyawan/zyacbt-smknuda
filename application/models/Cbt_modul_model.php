<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cbt_modul_model extends CI_Model{
	public $table = 'cbt_modul';
	
	
    function save($data){
        $this->db->insert($this->table, $data);
    }
    
    function delete($kolom, $isi){
        $this->db->where($kolom, $isi)
                 ->delete($this->table);
    }

    function empty_table(){
        $this->db->empty_table($this->table);
    }
    
    function update($kolom, $isi, $data){
        $this->db->where($kolom, $isi)
                 ->update($this->table, $data);
    }
    
    function count_all(){
        $this->db->select('COUNT(*) AS hasil')
                 ->from($this->table);
        return $this->db->get();
    }

    function count_by_kolom($kolom, $isi){
        $this->db->select('COUNT(*) AS hasil')
                 ->where($kolom, $isi)
                 ->from($this->table);
        return $this->db->get();
    }
	
	function get_by_kolom($kolom, $isi){
        $this->db->where($kolom, $isi)
                 ->from($this->table);
        return $this->db->get();
    }
	
	function get_by_kolom_limit($kolom, $isi, $limit){
        $this->db->where($kolom, $isi)
                 ->from($this->table)
				 ->limit($limit);
        return $this->db->get();
    }

    function get_modul(){
        $this->ensure_single_aktif();
        $this->db->where('(modul_aktif=1)')
                 ->from($this->table)
                 ->order_by('modul_id', 'DESC');
        return $this->db->get();
    }

    function get_all(){
        $this->db->from($this->table)
                 ->order_by('modul_id', 'DESC');
        return $this->db->get();
    }

    function get_all_ordered(){
        $this->ensure_single_aktif();
        $this->db->from($this->table)
                 ->order_by('modul_aktif', 'DESC')
                 ->order_by('modul_id', 'DESC');
        return $this->db->get();
    }

    function count_aktif(){
        $this->db->select('COUNT(*) AS hasil')
                 ->where('modul_aktif', 1)
                 ->from($this->table);
        return $this->db->get();
    }

    function ensure_single_aktif(){
        $aktif_rows = $this->db->select('modul_id')
                               ->from($this->table)
                               ->where('modul_aktif', 1)
                               ->order_by('modul_id', 'DESC')
                               ->get()->result();
        $cnt = count($aktif_rows);
        if($cnt > 1){
            $keep_id = (int)$aktif_rows[0]->modul_id;
            $this->set_single_aktif($keep_id);
        }elseif($cnt === 0){
            $latest = $this->db->select('modul_id')
                               ->from($this->table)
                               ->order_by('modul_id', 'DESC')
                               ->limit(1)
                               ->get()->row();
            if($latest){
                $this->set_single_aktif((int)$latest->modul_id);
            }
        }
    }

    function set_single_aktif($modul_id){
        $this->db->trans_start();
        $this->db->update($this->table, array('modul_aktif' => 0));
        $this->db->where('modul_id', (int)$modul_id)
                 ->update($this->table, array('modul_aktif' => 1));
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
	
	function get_datatable($start, $rows, $kolom, $isi){
		$this->db->like($kolom, $isi)
                 ->from($this->table)
				 ->order_by('modul_id', 'DESC')
                 ->limit((int)$rows, (int)$start);
        return $this->db->get();
	}
    
    function get_datatable_count($kolom, $isi){
		$this->db->select('COUNT(*) AS hasil')
                 ->like($kolom, $isi)
                 ->from($this->table);
        return $this->db->get();
	}
}