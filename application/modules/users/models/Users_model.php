<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


class Users_model extends Fm_model {    
    public $table = 'users';
    public $id = 'id';
    public $order = 'DESC';

    function __construct(){
        parent::__construct();
    }

    // get all
    function get_all(){
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id){
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($q = NULL , $status = NULL , $role_id = 0) {        
        if($q){                        
            $this->db->where("(`first_name` LIKE '%{$q}%' ESCAPE '!' "
                . "OR `last_name` LIKE '%{$q}%' ESCAPE '!' "
                . "OR `email` LIKE '%{$q}%' ESCAPE '!' "
                . "OR `contact` LIKE '%{$q}%' ESCAPE '!' "
                . "OR `city` LIKE '%{$q}%' ESCAPE '!' "
                . "OR `postcode` LIKE '%{$q}%' ESCAPE '!')");
        }
        
        if($role_id != 0){
            $this->db->where('role_id', $role_id );
        }        
        if($status){
            $this->db->where('status', $status);
        }
        
        if(getLoginUserData('role_id') != 1){
            $this->db->where_not_in('role_id', [1,2]);
        }
        
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL , $status = NULL , $role_id = 0) {
        $this->db->order_by($this->id, $this->order);
        
        if($q){            
            $this->db->where("(`first_name` LIKE '%{$q}%' ESCAPE '!' "
            . "OR `last_name` LIKE '%{$q}%' ESCAPE '!' "
            . "OR `email` LIKE '%{$q}%' ESCAPE '!' "
            . "OR `contact` LIKE '%{$q}%' ESCAPE '!' "
            . "OR `city` LIKE '%{$q}%' ESCAPE '!' "
            . "OR `postcode` LIKE '%{$q}%' ESCAPE '!')");
        }
        
        if($role_id != 0){
            $this->db->where('role_id', $role_id );
        }
        
        if($status){
            $this->db->where('status', $status);
        }
        
        if(getLoginUserData('role_id') != 1){
            $this->db->where_not_in('role_id', [1,2,3]);
        }
        
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data){
        $this->db->insert($this->table, $data);
    }

    // update data
    function update($id, $data){
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id){
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }    
    
    // get user name by ID
    function get_name_by_id($id){
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

}