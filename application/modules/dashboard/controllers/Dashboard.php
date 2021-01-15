<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_controller {

    function __construct() {
        parent::__construct();
        $this->role_id = getLoginUserData('role_id');
        $this->load->helper('dashboard');

        if($this->role_id == 6) {
            redirect(site_url('my-account'));
        }
    }

    public function index() {
        $total_user = $this->db->where('status', 'Active')->count_all_results('users');
        $data = [
            'total_users' => $total_user,
        ];
        $data['draftPosts'] = draftPosts($this->user_id);

        if (in_array($this->role_id, [1, 2, 3, 21])) {
            $data['highestViewedPost'] = highestViewedPosts();
        } elseif (in_array($this->role_id, [5, 6, 15, 19])) {
            $data['highestViewedPost'] = highestViewedPosts($this->user_id);
        }

        $this->viewAdminContent('dashboard', $data);
    }

}
