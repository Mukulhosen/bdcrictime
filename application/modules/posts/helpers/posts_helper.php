<?php
if (!defined('BASEPATH')){ exit('No direct script access allowed'); }

function getHomePageSectionCategoryList($select = 0) {
    $ci = & get_instance();
    $query = $ci->db->get('home_section_category')->result();  
    $options = '<option value="0">No Show</option>';
    foreach ($query as $row) {
        $options .= '<option value="' . $row->id . '" ';
        $options .= ($row->id == $select ) ? 'selected="selected"' : '';
        $options .= '>' . $row->name . '</option>';
    }
    return $options;
}

function getPostsCategoryList( $select = 0, $label = '-- Root Category --' ) {
    $ci = & get_instance();
    $query = $ci->db->get_where('post_category', ['parent_id' => 0]);
    
    $options = '<option value="0">'.$label.'</option>';
    foreach ($query->result() as $row) {
        $options .= '<option value="' . $row->id . '" ';
        $options .= ($row->id == $select ) ? 'selected="selected"' : '';
        $options .= '>' . $row->name . '</option>';
    }
    return $options;
}

function getPostParentCategoryOption( $parent_id = 0, $cat_id = 0 ) {
    $options = '<option value="0">--- Select Sub Category ---</option>';

    if ($parent_id) {
        $ci = & get_instance();
        $ci->db->from('post_category');
        $ci->db->where('parent_id', $parent_id);
        $ci->db->where('sub_category_id', 0);
        $ci->db->where_not_in('template_design', ['39', '44', '46']);
        $query = $ci->db->get();

        foreach ($query->result() as $row) {
            $options .= '<option value="' . $row->id . '" ';
            $options .= ($row->id == $cat_id ) ? 'selected="selected"' : '';
            $options .= '>' . $row->name . '</option>';
        }
    }

    return $options;
}

function getPostChildCategoryOption( $parent_id = 0, $cat_id = 0 ) {
    $options = '<option value="0">--- Select Child Category ---</option>';

    if ($parent_id) {
        $ci = & get_instance();
        $ci->db->where('sub_category_id', $parent_id);
        $ci->db->where_not_in('template_design', ['28']);
        $categories = $ci->db->get('post_category')->result();

        foreach ($categories as $row) {
            $options .= '<option value="' . $row->id . '" ';
            $options .= ($row->id == $cat_id ) ? 'selected="selected"' : '';
            $options .= '>' . $row->name . '</option>';
        }
    }

    return $options;
}

function getMovieOption($selected = "") {
    $ci = & get_instance();
    $query = $ci->db->get_where('movies', ['status' => "Publish"]);

    $options = '<option value="0">--- Select Movie ---</option>';
    foreach ($query->result() as $row) {
        $options .= '<option value="' . $row->id . '" ';
        $options .= ($row->id == $selected ) ? 'selected="selected"' : '';
        $options .= '>' . $row->name . '</option>';
    }
    return $options;
}

function makeTabPost() {
    $ci = & get_instance();
    $role_id = getLoginUserData('role_id');
    
    if(in_array($role_id, [6,7,8,9,10,11,12,13,14])){
        return false;
    } elseif($role_id == 5) {
        $tabs = [
            'my'         => 'My Post',
            'individual' => 'Individual Post',
            'rejected'   => 'My Rejected News',
            'draft'      => 'Draft News',
            'approved'   => 'Approved News',
        ];
    } elseif($role_id == 3) {
        $tabs = [
            'my'         => 'My Post',
            'individual' => 'Individual Post',
            'journalist' => 'Journalist Post',
            'guest'      => 'Guest Blogger Post',
            'trash'      => 'Trash News',
            'approved'   => 'Approved News',
            'draft'      => 'Draft News',
        ];
    } else {
        $tabs = [
            'my'         => 'My Post',
            'individual' => 'Individual Post',
            'journalist' => 'Journalist Post',
            'guest'      => 'Guest Blogger Post',
            'trash'      => 'Trash News',
            'approved'   => 'Approved News',
            'draft'      => 'Draft News',
        ];
    }
    
    if($ci->input->get('post_type')){
        $active_tab = $ci->input->get('post_type');
    } else {
        $active_tab = 'my';
    }
    
    $html = '<ul class="tabsmenu">';
    
    foreach ($tabs as $link => $tab) {
        $html .= '<li><a href="' . Backend_URL . 'posts?post_type=' . $link .'"';
        $html .= ($link === $active_tab ) ? ' class="active"' : '';
        $html .= '> ' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}