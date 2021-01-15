<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Frontend_controller extends MX_Controller {

    public $user_id;
    public $role_id;

    public function __construct() {
        date_default_timezone_set("Europe/London");
        parent::__construct();

        $this->load->library('user_agent');                              
        $this->load->helper('security');
        $this->load->helper('acl_helper');
        $this->load->model('module/Acl_model', 'acls');

        /* @var $user_id type */
        $this->user_id = intval(getLoginUserData('user_id'));
        $this->role_id = intval(getLoginUserData('role_id'));
    }

    public function index() {
        $PageSlug = empty($this->uri->segment(1)) ? 'home' : $this->uri->segment(1);
        if(!in_array($this->role_id, [1,2])){
            $this->db->where('status', 'Publish');
        }        
        $this->db->where('post_url', $PageSlug);
        $cms = $this->db->get('cms')->row_array();

        $post_type = isset($cms['post_type']) ? $cms['post_type'] : '';
        if ($post_type == 'page') {
            $this->getCmsPage($cms, $PageSlug);
        } else {
            $this->viewFrontContent('frontend/404');
        }
    }
    
    private function getCmsPage($cms, $PageSlug = '') {
        $cms_page                       = $cms;
        $cms_page['meta_title']         = ($cms['seo_title']) ? $cms['seo_title'] : getSettingItem('ComName');
        $cms_page['meta_description']   = getShortContent($cms['seo_description'], 120);
        $cms_page['meta_keywords']      = $cms['seo_keyword'];
        
        $viewTeamplatePath = APPPATH . '/views/frontend/template/' . $PageSlug . '.php';
        if (file_exists($viewTeamplatePath)) {
            $viewPath = 'template/' . $PageSlug;
            $this->viewFrontContent('frontend/' . $viewPath, $cms_page);
        } else {
            $this->viewFrontContent('frontend/404');
        }
    }

    public function viewMemberContent($view, $data = []) {
        if( $this->input->is_ajax_request() ){
            $this->load->view($view, $data);        
        } else {
            
            $this->load->view('backend/layout/header');
            $this->load->view('backend/layout/sidebar'); 
            
            if( $this->check_access( $view ) ){
                $this->load->view($view, $data);  
            } else {
                $this->load->view('backend/restrict');    
            }
            $this->load->view('backend/layout/footer');
        }  	
    }

    public function check_access( $string = 'dashboard'){
        
        // $backend_uri = 'admin'; // prefix no need to touch        
        $controller = empty($this->uri->segment(2)) ? $string : $this->uri->segment(2);       
        $method     = empty($this->uri->segment(3)) ? '' : '/'.$this->uri->segment(3);        
        $access_key = $controller . $method;        
        return $this->acls->checkPermission($access_key, $this->role_id);
    }

    public function viewFrontContent($view, $data = []) {

            $this->load->view($view, $data);

    }

    public function insertRecords($table, $records) {
        $sql = $this->db->insert($table, $records);
        return ($sql) ? true : false;
    }

    public function updateRecords($table, $data, $where) {
        if (!empty($where))
            $this->db->where($where);
        $sql = $this->db->update($table, $data);
        return ($sql) ? 1 : 0;
    }

    public function getRecords($table, $where, $option) {
        if (!empty($where))
            $sql = $this->db->get_where($table, $where);
        else
            $sql = $this->db->get($table);
        if ($sql->num_rows() > 0) {
            if ($option == "all") {
                foreach ($sql->result() as $rows) {
                    $data[] = $rows;
                }
            } else {
                $data = $sql->row_array();
            }
            return $data;
        } else
            return false;
    }

    public function insertBatch($table, $records) {
        $sql = $this->db->insert_batch($table, $records);
        return ($sql) ? true : false;
    }

    public function deleteRecords($table, $where = '') {
        if (empty($where))
            $del = $this->db->empty_table($table);
        else
            $del = $this->db->delete($table, $where);
        return ($del) ? 1 : 0;
    }


    function receiving_datetime($time)
    {
         $date = date("Y-m-d h:i:s", $time);  // Format this however you want the data
         return $date;
    }
    
    
    function sending_datatime($time)
    {
        $date = strtotime($time);
        return $date;
    }




}
