<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends Fm_model{

    public $table 	= 'users';
    public $id 		= 'id';
    public $order 	= 'DESC';

    function __construct(){
        parent::__construct();
    }
   

    // get data by id
    function get_by_id($id){
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
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
    

}