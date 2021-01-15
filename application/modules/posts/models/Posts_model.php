<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


class Posts_model extends Fm_model {

    public $table = 'posts';
    public $id = 'id';
    public $status = 'status';
    public $post_type = 'post_type';
    public $order = 'ASC';

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
    
    function get_data_for_post($limit, $start = 0, $q = null, $manage_all_posts = false, $status = null, $category = 0, $post_type = false) {
        $this->db->select('posts.*, , sub_category.template_design as sub_cat_tem_desgin');
        $this->sql_posts($q, $manage_all_posts, $status, $category, $post_type);
        $this->db->where('(sub_category.template_design NOT IN (44, 46) OR sub_category.template_design IS NULL)');
        $this->db->order_by('modified', 'DESC');  
        $this->db->limit($limit, $start);
        $this->db->join('post_category as sub_category', 'sub_category.id = posts.sub_category_id', 'LEFT');
        return $this->db->get('posts')->result();
    }
    
    function total_rows_post($q = null, $manage_all_posts = false, $status = null, $category = 0, $post_type = false) {
        $this->sql_posts($q, $manage_all_posts, $status, $category, $post_type);
        $this->db->where('(sub_category.template_design NOT IN (44, 46) OR sub_category.template_design IS NULL)');
        $this->db->join('post_category as sub_category', 'sub_category.id = posts.sub_category_id', 'LEFT');
	$this->db->from('posts');
        return $this->db->count_all_results();        
    }
    
    private function sql_posts($q, $manage_all_posts, $status, $category, $post_type) {
        if($status){ $this->db->where('posts.status', $status); }
        if($category){ $this->db->where('posts.category_id', $category); }

        if ($post_type == 'trash') {
            $this->db->where('posts.status', 'Trash');
        } elseif ($post_type == 'draft') {
            $this->db->where('posts.user_id', $this->user_id);
            $this->db->where('posts.status', 'Draft');
        } elseif ($post_type == 'rejected') {
            $this->db->where('posts.user_id', $this->user_id);
            $this->db->where('posts.status', 'Rejected');
        } elseif ($post_type == 'approved') {
            if($this->role_id == 5){
                $this->db->where('posts.user_id', $this->user_id);
            }
            $this->db->where('posts.post_show', 'Frontend');
            $this->db->where('posts.status', 'Publish');
        } else {
            $this->db->where('posts.status !=', 'Trash');
            $this->db->where('posts.status !=', 'Draft');
            if(!in_array($this->role_id, [1,2])){
                if($this->role_id == 5){
                    $this->db->where('posts.post_show', 'Journalist');
                }
            }

            if($post_type == 'individual'){
                $this->db->where('posts.role_id', 6);
            } elseif($post_type == 'journalist'){
                $this->db->where_in('posts.role_id', [5, 15]);
            } elseif($post_type == 'guest'){
                $this->db->where('posts.role_id', 7);
            } elseif($post_type == 'scrap'){
                $this->db->where('posts.role_id', 0);
            } else {
                $this->db->where('posts.user_id', $this->user_id);
//                $this->db->or_where_in('posts.post_show',['Frontend','Journalist']);
//                $this->db->where_in('posts.status',['Schedule','Schedule_Publish']);
            }
        }
        
        if($q){
            $this->db->group_start();
            $this->db->like('posts.id', $q);
            $this->db->or_like('posts.title', $q);
            $this->db->or_like('posts.post_url', $q);
            $this->db->group_end();
        }
    }


    // get data with limit and search
    function get_data_for_category($limit, $start = 0, $q = NULL) {
        
        if($q != NULL){
            $this->db->order_by($this->id, $this->order);
            $this->db->like('title', $q);
        }
        
        $this->db->limit($limit, $start);
        return $this->db->get('categories')->result();
    }
    
    // get total rows in post
    function total_rows_category($q = NULL) {
        //$this->db->where('post_type', 'post'); 
	$this->db->like('title', $q);
	$this->db->from('categories');
        return $this->db->count_all_results();        
    }
    
    // get data with limit and search
    function get_data_frontend($limit, $start = 0, $q = NULL) {
        $this->db->where('post_type', 'post')->where('status', 'Publish'); 
        if($q != NULL){
            $this->db->order_by($this->id, $this->order);      
            $this->db->like('post_title', $q);
            $this->db->limit($limit, $start);        
        }
       
        return $this->db->get($this->table)->result();
    }
    
    // get total rows in post
    function total_rows_frontend($q = NULL) {
       $this->db->where('post_type', 'post')->where('status', 'Publish'); 
	$this->db->like('post_title', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }
    
}