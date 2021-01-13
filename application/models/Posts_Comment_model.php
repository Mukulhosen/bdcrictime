<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


class Posts_Comment_model extends Fm_model {

    public $table = 'post_comments';
    public $id = 'id';
    public $status = 'status';
    public $post_type = 'post_type';
    public $order = 'ASC';

    function __construct(){
        parent::__construct();
    }

    // get all
    function get_all_comment(){
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_comment_by_id($id){
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    // insert data
    function insert_comment($data){
        $this->db->insert($this->table, $data);
    }

    // update data
    function update_comment($id, $data){
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete_comment($id){
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
    

    /**
     * get post comment data
     *  
     * 
    */ 
    function get_data_for_post_comment($post_id = null, $parent_id = null, $limit, $start = 0, $q = null, $manage_all_posts = false, $status = null, $post_type = false) {

        $this->sql_post_comments($q, $manage_all_posts, $status, $post_type);
        
        $this->db->select("post_comments.*, CONCAT(users.first_name, ' ', users.last_name) as name, users.profile_photo, users.oauth_provider, reply.first_name as reply_name");
        if($post_id) {
            $this->db->where(['post_id' => $post_id]);
            $this->db->where(['parent_id' => '']);
        }
        if($parent_id) $this->db->where(['parent_id' => $parent_id]);
        if($status) $this->db->where(['post_comments.status' => $status]);
        $this->db->order_by('id', 'DESC');
        $this->db->join('users', "users.id = post_comments.user_id");
        $this->db->join('users as reply', "reply.id = post_comments.reply_to", 'LEFT');
        $this->db->from('post_comments');
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }
    
    /**
     * get post comment data
     *  
     * 
    */ 
    function total_rows_post_comment($q = null, $post_id, $parent_id, $manage_all_posts = false, $status = null, $post_type = false) {
        $this->sql_post_comments($q, $manage_all_posts, $status, $post_type);

        if($post_id) {
            $this->db->where(['post_id' => $post_id]);
            $this->db->where(['parent_id' => '']);
        }
        if($parent_id) $this->db->where(['parent_id' => $parent_id]);
        if($status) $this->db->where(['post_comments.status' => $status]);
        $this->db->join('users', "users.id = post_comments.user_id");
	    $this->db->from('post_comments');
        return $this->db->count_all_results();        
    }

    // sql post comments
    function sql_post_comments($q, $manage_all_posts, $status, $post_type) {
        if($status){ $this->db->where('status', $status); }

        if($q){
            $this->db->like('id', $q);
            $this->db->or_like('title', $q);
            $this->db->or_like('post_url', $q);
        }
    }

}