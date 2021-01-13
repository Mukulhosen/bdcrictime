<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Formula1_League_Team_model extends Fm_model {

    public $table = 'formula1_standing_table';
    public $id = 'id';
    public $order = 'DESC';

    function __construct() {
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
    
    // get total rows
    function total_rows($q = NULL) {
        if ($q) {
            $this->db->like('id', $q);
            $this->db->or_like('name', $q);
        }
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        if ($q) {
            $this->db->like('id', $q);
            $this->db->or_like('name', $q);
        }
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

}
