<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_M extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
    }
    
}
