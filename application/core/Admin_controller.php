<?php

/**
 * Description of Admin_controller
 *
 * @author Kanny
 */
class Admin_controller extends MX_Controller {  
    protected $user_id;
    protected $role_id;
    
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Europe/London');

        $this->load->library('user_agent');                              
        $this->load->helper('security');
        $this->load->helper('acl_helper');
        $this->load->model('module/Acl_model', 'acls');

        /* @var $user_id type */
        $this->user_id = intval(getLoginUserData('user_id'));
        $this->role_id = intval(getLoginUserData('role_id'));

        if( checkPermission('my_account/access_backoffice', $this->role_id ) == false ){
            redirect( base_url('my-account'), 'refresh' );    
        }
        if(in_array($this->role_id,[6,7])) {
            redirect( base_url('my-account'), 'refresh' );  
        }

        if($this->isActiveAccount() == false ){
            redirect( base_url('auth/logout'), 'refresh' );   
        }
        
        if($this->user_id <= 0){
            redirect( site_url('admin/login'));
        }
        $this->set_admin_prefix( $this->uri->uri_string() );
    }
    
    private function isActiveAccount(){
        $this->db->select('status');
        $this->db->where('id', $this->user_id );
        $user = $this->db->get('users')->row();
        
        if($user && $user->status == 'Active'){
            return true;
        } else {
            return false;
        }
    }


    public function check_access( $string = 'dashboard'){
        
        // $backend_uri = 'admin'; // prefix no need to touch        
        $controller = empty($this->uri->segment(2)) ? $string : $this->uri->segment(2);       
        $method     = empty($this->uri->segment(3)) ? '' : '/'.$this->uri->segment(3);        
        $access_key = $controller . $method;        
        return $this->acls->checkPermission($access_key, $this->role_id);
    }
    
    
    private function set_admin_prefix( $string = '/'){
        if($this->uri->segment(1) != 'admin'){
            redirect( site_url('admin') .'/'. $string );  
        };
    }	         
           
    public function viewAdminContent($view, $data = []){				
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

    public function picture_upload($photo,$folder, $name = 0, $image_x = 350, $image_y = 350, $quality = 100) {
        $photo_path  = '';
        $handle = new upload($photo);
        if ($handle->uploaded) {
            $handle->file_overwrite = true;
            $handle->file_new_name_body = $name;
            $handle->image_resize   = true;
            $handle->file_force_extension = true;
            $handle->file_new_name_ext = 'jpg';
            $handle->image_ratio    = true;
            $handle->image_x        = $image_x;
            $handle->image_y        = $image_y;
            $handle->jpeg_quality   = $quality;
            $handle->Process('uploads/'.$folder.'/');
            $photo_path = stripslashes($handle->file_dst_pathname);
            if ( $handle->processed ) {
                $handle->clean();
            }
        }
        return $photo_path;
    }
}
