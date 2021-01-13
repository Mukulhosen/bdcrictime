<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_helper {
    
    public static function userMetaValue($meta_key = '', $array = []) {
        //return $array;
        $user_id = getLoginUserData('user_id');
        $CI = & get_instance();
        $data = $CI->db->select('*')
                ->get_where('user_meta', ['user_id' => $user_id, 'meta_key' => $meta_key])
                ->row();
        return @$data->meta_value;
    }

    public static function sellerSocialLinks($json = null, $key = '') {
        if ($json) {
            $data = json_decode($json, true);
            return ($data[$key]) ? $data[$key] : '';
        }
    }
    
    public static function makeTab($active_tab = '') {
        $role_id = getLoginUserData('role_id');
        $html = '<ul class="tabsmenu">';
        $tabs['#'] = '<i class="fa fa-user"></i> &nbsp;My Profile';        
        $tabs['password'] = '<i class="fa fa-random"></i> &nbsp;Change Password';
        foreach ($tabs as $link => $tab) {
            $html .= '<li><a href="' . Backend_URL . 'profile/' . $link . '"';
            $html .= ($link == $active_tab ) ? ' class="active"' : '';
            $html .= '> ' . $tab . '</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }
}
