<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cbt_tes_soal_model extends CI_Model{
	public $table = 'cbt_tes_soal';
	
	
    function save($data){
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    function save_batch($data){
        //$this->db->query($sql);
        $this->db->insert_batch($this->table, $data);
    }
    
    function delete($kolom, $isi){
        $this->db->where($kolom, $isi)
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

    function count_by_tesuser_dijawab($tesuser_id){
        $this->db->select('COUNT(*) AS hasil')
                 ->where('tessoal_tesuser_id="'.$tesuser_id.'" AND tessoal_change_time IS NOT NULL')
                 ->from($this->table);
        return $this->db->get();
    }

    function count_by_tesuser_blum_dijawab($tesuser_id){
        $this->db->select('COUNT(*) AS hasil')
                 ->where('tessoal_tesuser_id', (int)$tesuser_id)
                 ->where('tessoal_change_time IS NULL', NULL, FALSE)
                 ->from($this->table);
        return $this->db->get();
    }

    function get_nomor_blum_dijawab($tesuser_id){
        $rows = $this->db->select('tessoal_order')
                         ->where('tessoal_tesuser_id', (int)$tesuser_id)
                         ->where('tessoal_change_time IS NULL', NULL, FALSE)
                         ->from($this->table)
                         ->order_by('tessoal_order', 'ASC')
                         ->get()
                         ->result();
        $nomor = array();
        foreach($rows as $r){
            $nomor[] = (int)$r->tessoal_order;
        }
        return $nomor;
    }
	
	function get_by_kolom($kolom, $isi){
        $this->db->where($kolom, $isi)
                 ->from($this->table);
        return $this->db->get();
    }

    function get_by_testuser($tesuser_id){
        $this->db->where('tessoal_tesuser_id="'.$tesuser_id.'"')
                 ->join('cbt_soal', 'cbt_tes_soal.tessoal_soal_id = cbt_soal.soal_id')
                 ->from($this->table)
                 ->order_by('tessoal_order', 'ASC');
        return $this->db->get();
    }
	
	function get_by_testuser_order_soal($tesuser_id, $soal_tipe = null){
        $this->db->where('tessoal_tesuser_id', (int)$tesuser_id)
                 ->join('cbt_soal', 'cbt_tes_soal.tessoal_soal_id = cbt_soal.soal_id')
                 ->from($this->table);
        if($soal_tipe !== null){
            $this->db->where('cbt_soal.soal_tipe', (int)$soal_tipe);
        }
        $this->db->order_by('soal_id', 'ASC');
        return $this->db->get();
    }

    function get_by_testuser_select($tesuser_id, $topik, $select){
        $this->db->select($select)
                 ->where('tessoal_tesuser_id="'.$tesuser_id.'" AND soal_topik_id="'.$topik.'"')
                 ->join('cbt_soal', 'cbt_tes_soal.tessoal_soal_id = cbt_soal.soal_id')
                 ->from($this->table)
                 ->order_by('tessoal_order', 'ASC');
        return $this->db->get();
    }

    function get_by_testuser_limit($tesuser_id, $limit){
        $this->db->where('tessoal_tesuser_id="'.$tesuser_id.'"')
                 ->join('cbt_soal', 'cbt_tes_soal.tessoal_soal_id = cbt_soal.soal_id')
                 ->from($this->table)
                 ->order_by('tessoal_order', 'ASC')
                 ->limit($limit);
        return $this->db->get();
    }

    function get_by_tessoal_limit($tessoal_id, $limit){
        $this->db->select('tessoal_id,tessoal_tesuser_id,tessoal_user_ip,tessoal_soal_id,tessoal_jawaban_text,tessoal_nilai,tessoal_ragu,tessoal_creation_time,tessoal_display_time,tessoal_change_time,tessoal_reaction_time,tessoal_order,tessoal_num_answers,tessoal_comment,tessoal_audio_play,soal_id,soal_topik_id,soal_detail,soal_tipe,soal_kunci,soal_difficulty,soal_aktif,soal_audio,soal_audio_play,soal_timer,soal_inline_answers,soal_auto_next')
                 ->where('tessoal_id', (int)$tessoal_id)
                 ->join('cbt_soal', 'cbt_tes_soal.tessoal_soal_id = cbt_soal.soal_id')
                 ->from($this->table)
                 ->limit($limit);
        return $this->db->get();
    }
	
	function get_by_kolom_limit($kolom, $isi, $limit){
        $this->db->where($kolom, $isi)
                 ->from($this->table)
				 ->limit($limit);
        return $this->db->get();
    }

    function get_nilai($tessoal_id){
        $sql = 'SELECT
                    CASE
                        WHEN COUNT(*) > 0 AND SUM(CASE WHEN s.soal_tipe != 1 THEN 1 ELSE 0 END) = 0
                        THEN ROUND((COUNT(CASE WHEN ts.tessoal_nilai > 0 THEN 1 END) * 100.0) / COUNT(*), 2)
                        ELSE ROUND(COALESCE(SUM(ts.tessoal_nilai), 0), 2)
                    END AS hasil,
                    COUNT(CASE WHEN ts.tessoal_nilai <= 0 THEN 1 END) AS jawaban_salah,
                    COUNT(*) AS total_soal,
                    SUM(CASE WHEN s.soal_tipe != 1 THEN 1 ELSE 0 END) AS non_pg_count
                FROM cbt_tes_soal ts
                JOIN cbt_soal s ON s.soal_id = ts.tessoal_soal_id
                WHERE ts.tessoal_tesuser_id=?';
        return $this->db->query($sql, array((int)$tessoal_id));
    }
	
    /**
     * Datatable untuk hasil tes detail setiap user
     *
     * @param      <type>  $start  The start
     * @param      <type>  $rows   The rows
     * @param      string  $kolom  The kolom
     * @param      string  $isi    The isi
     *
     * @return     <type>  The datatable.
     */
	function get_datatable($start, $rows, $kolom, $isi, $tesuser_id){
		$this->db->like($kolom, $isi)
                 ->where('tessoal_tesuser_id', (int)$tesuser_id)
                 ->from($this->table)
                 ->join('cbt_soal', 'cbt_tes_soal.tessoal_soal_id = cbt_soal.soal_id')
				 ->order_by('tessoal_order', 'ASC')
                 ->limit((int)$rows, (int)$start);
        return $this->db->get();
	}
    
    function get_datatable_count($kolom, $isi, $tesuser_id){
		$this->db->select('COUNT(*) AS hasil')
                 ->like($kolom, $isi)
                 ->where('tessoal_tesuser_id', (int)$tesuser_id)
                 ->join('cbt_soal', 'cbt_tes_soal.tessoal_soal_id = cbt_soal.soal_id')
                 ->from($this->table);
        return $this->db->get();
	}
}