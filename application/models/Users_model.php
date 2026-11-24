<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users_model extends CI_Model{	
    function save($data){
        $this->db->insert('user', $data);
    }
    
    function delete($username){
        $this->db->where('username', $username)
                 ->delete('user');
    }
    
    function update($data, $username){
        $this->db->where('username', $username)
                 ->update('user', $data);
    }
    
    function get_user_by_id($id){
        $this->db->where('id', $id)
                 ->from('user')
                 ->limit(1);
        return $this->db->get();
    }

    function get_user_by_username($username){
        $this->db->where('username', $username)
                 ->from('user')
                 ->limit(1);
        return $this->db->get();
    }
	
	function get_login_info($username){
		$this->db->where('username',$username);
		$this->db->limit(1);
		$query = $this->db->get('user');
		return ($query->num_rows() > 0) ? $query->row() : FALSE;
	}
    
    /**
     * Change Password
     * 
     */ 
    function change_password($username, $password){
        $this->db->where('username', $username);
        $this->db->update('user', array('password' => password_hash((string)$password, PASSWORD_BCRYPT)));
	}
    
    function get_user_count($username, $password){
        $user = $this->get_login_info($username);
        if(!$user){
            return 0;
        }
        $stored = (string)$user->password;
        if(strpos($stored, '$2y$') === 0 || strpos($stored, '$2a$') === 0 || strpos($stored, '$argon2') === 0){
            return password_verify((string)$password, $stored) ? 1 : 0;
        }
        return (sha1((string)$password) === $stored) ? 1 : 0;
	}
    
    function get_user_count_by_level($level){
        $this->db->select('COUNT(*) AS hasil')
                 ->where('level', $level)
                 ->from('user');
        return $this->db->get();
    }
    
    /*
	 * mendapatkan hak akses suatu menu
	 */
	function cek_akses($kode_menu, $level){
		$sql='SELECT COUNT(*) AS hasil FROM user_akses WHERE user_akses.kode_menu=? AND user_akses.level=?';
		$hasil=$this->db->query($sql, array((string)$kode_menu, (string)$level))->row()->hasil;
		if($hasil == 0 && $kode_menu === 'modul-import-word'){
			// Fallback: level yang memiliki akses modul-import (spreadsheet lama) otomatis diizinkan akses modul-import-word
			$sql_fb = 'SELECT COUNT(*) AS hasil FROM user_akses WHERE user_akses.kode_menu="modul-import" AND user_akses.level=?';
			$hasil = $this->db->query($sql_fb, array((string)$level))->row()->hasil;
		}
		if($hasil == 0 && $kode_menu === 'peserta-online'){
			$sql_fb = 'SELECT COUNT(*) AS hasil FROM user_akses WHERE user_akses.kode_menu="peserta-daftar" AND user_akses.level=?';
			$hasil = $this->db->query($sql_fb, array((string)$level))->row()->hasil;
		}

		return $hasil;
	}
    
    /*
	 * mendapatkan hak akses crud menu
	 */
	function cek_akses_crud($kode_menu, $level, $tipe){
        if($tipe==0){
            $sql='SELECT COUNT(*) AS hasil FROM user_akses WHERE user_akses.kode_menu=? AND user_akses.level=? AND user_akses.add=1';
        }else{
            $sql='SELECT COUNT(*) AS hasil FROM user_akses WHERE user_akses.kode_menu=? AND user_akses.level=? AND user_akses.edit=1';
        }
		$hasil=$this->db->query($sql, array((string)$kode_menu, (string)$level))->row()->hasil;
		if($hasil == 0 && $kode_menu === 'modul-import-word'){
			$col = ($tipe == 0) ? 'add' : 'edit';
			$sql_fb = 'SELECT COUNT(*) AS hasil FROM user_akses WHERE user_akses.kode_menu="modul-import" AND user_akses.level=? AND user_akses.'.$col.'=1';
			$hasil = $this->db->query($sql_fb, array((string)$level))->row()->hasil;
		}

		return $hasil;
	}
    
    function get_all_user($start, $rows, $search){
        $s_like = $this->db->escape_like_str((string)$search);
        $this->db->where('(username LIKE "%'.$s_like.'%" OR nama LIKE "%'.$s_like.'%")')
                 ->where('username !=','admin')
                 ->from('user')
                 ->limit((int)$rows, (int)$start);    
        
        return $this->db->get();
    }
    
    function get_all_user_count($search){
        $s_like = $this->db->escape_like_str((string)$search);
        $this->db->select('COUNT(*) AS hasil')
                 ->where('(username LIKE "%'.$s_like.'%" OR nama LIKE "%'.$s_like.'%")')
                 ->where('user.username !=','admin')
                 ->from('user');
        return $this->db->get();
    }
    
    function get_parent_menu($child_menu){
        $sql = 'SELECT `user_menu`.`parent` FROM user_menu WHERE `user_menu`.`kode_menu`=?';
        $query = $this->db->query($sql, array((string)$child_menu));
        if($query->num_rows()>0){
            $hasil = $query->row()->parent;
        }else{
            $hasil = 'KOSONG';
        }
        return $hasil;
        
    }
    
    function get_menu_detail($kode_menu){
        $sql = 'SELECT `user_menu`.* FROM user_menu WHERE `user_menu`.`kode_menu`=? LIMIT 1';
        return $this->db->query($sql, array((string)$kode_menu));
    }
    
    /**
     * Mendapatkan menu dashboard secara dynamic 
     */ 
    function get_menu($kode_menu, $level){
        $this->db->select('user_menu.parent')
                 ->where('user_akses.level', (string)$level)
				 ->join('user_menu', 'user_akses.kode_menu = user_menu.kode_menu')
                 ->from('user_akses')
				 ->group_by('user_menu.parent');
		$result = $this->db->get()->result();
		
		// mendapatkan array menu
		$data_menu = array();
		foreach($result as $temp){
			$data_menu[] = $temp->parent;
		}
		if(empty($data_menu)){
			return '';
		}
		
		$this->db->where_in('kode_menu', $data_menu)
                 ->from('user_menu')
				 ->order_by('urutan', 'ASC');
		$result = $this->db->get();
        $parent_kode_menu = $this->get_parent_menu($kode_menu);
        
        $menu = '';
        
        if($result->num_rows()>0){
			// mencari menu berdasarkan menu parent yang didapat
            foreach ($result->result() as $temp){
                $parent_active='';
                if($temp->kode_menu==$parent_kode_menu){
					$parent_active=' active ';
    			}
                
                $sql_child = 'SELECT user_menu.* FROM `user_akses` INNER JOIN `user_level` ON (`user_akses`.`level` = `user_level`.`level`) 
                    INNER JOIN `user_menu` ON (`user_akses`.`kode_menu` = `user_menu`.`kode_menu`) WHERE user_akses.`level`=? 
                    AND user_menu.`tipe`=1 AND user_menu.parent=? ORDER BY user_menu.`urutan` ASC';
                $result_child = $this->db->query($sql_child, array((string)$level, (string)$temp->kode_menu));
                
                $menu_child = '';
                $menu_child_count = 0;
                $hidden_menus = array('modul-import', 'peserta-group', 'tes-tambah'); // Sembunyikan menu redundan dari sidebar (zero DB mod)
                $menu_label_overrides = array(
                    'modul-topik' => 'Topik (Mata Pelajaran)',
                    'peserta-daftar' => 'Peserta & Kelas',
                    'peserta-kartu' => 'Cetak Kartu Login'
                );
                if($result_child->num_rows()>0){
                    $rows_child = $result_child->result();
                    $has_import_word = false;
                    $has_import_old = false;
                    foreach ($rows_child as $rc) {
                        if ($rc->kode_menu === 'modul-import-word') $has_import_word = true;
                        if ($rc->kode_menu === 'modul-import') $has_import_old = true;
                    }
                    if ($has_import_old && !$has_import_word) {
                        $q_iw = $this->get_menu_detail('modul-import-word');
                        if ($q_iw->num_rows() > 0) {
                            $rows_child[] = $q_iw->row();
                        }
                    }
                    $menu_child = $menu_child.'<ul class="treeview-menu">';
                    foreach ($rows_child as $child){
                        if (in_array($child->kode_menu, $hidden_menus, true)) {
                            continue;
                        }
                        $child_active='';
                        if($kode_menu==$child->kode_menu){
                            $child_active='active';
                        }
                        $child_label = isset($menu_label_overrides[$child->kode_menu]) ? $menu_label_overrides[$child->kode_menu] : $child->nama_menu;
                        $menu_child = $menu_child.'
                            <li class="'.$child_active.'"><a href="'.site_url().'/'.$child->url.'"><i class="fa fa-circle-o"></i> '.$child_label.'</a></li>
                        ';
                        
                        $menu_child_count++;
                    }
                    $menu_child = $menu_child.'</ul>';
                }
                
                $menu = $menu.'
                    <li class="treeview '.$parent_active.'">
                        <a href="'.site_url().'/'.$temp->url.'">
        					<i class="'.$temp->icon.'"></i>
        					<span>'.$temp->nama_menu.'</span>';
                if($menu_child_count>0){
                    $menu = $menu.'<i class="fa fa-angle-left pull-right"></i>';
                }
                
                $menu = $menu.'</a>';
                
                $menu = $menu.'
                    '.$menu_child;
                
                $menu = $menu.'</li>';
            }
        }
        
        return $menu;
    }
}