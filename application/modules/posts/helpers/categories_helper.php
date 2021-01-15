<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function categoryTabs($id, $active_tab) {
    $html = '<ul class="tabsmenu">';
    $tabs = [
        'read' => 'Details',
        'update' => 'Update',
        'delete' => 'Delete',
    ];

    foreach ($tabs as $link => $tab) {
        $html .= '<li> <a href="' . Backend_URL . 'posts/category/' . $link . '/' . $id . '"';
        $html .= ($link === $active_tab ) ? ' class="active"' : '';
        $html .= '> ' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function getCategoryList( $select = 0, $label = '-- Root Category -- ' ) {
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

function getSubCategoryList( $parent_id, $select = 0, $label = '-- Sub Category -- ' ) {
    $ci = & get_instance();
    $ci->db->from('post_category');
    $ci->db->where('parent_id', $parent_id);
    $ci->db->where('sub_category_id', 0);

    $query = $ci->db->get();

    $options = '<option value="0">'.$label.'</option>';
    foreach ($query->result() as $row) {
        $options .= '<option value="' . $row->id . '" ';
        $options .= ($row->id == $select ) ? 'selected="selected"' : '';
        $options .= '>' . $row->name . '</option>';
    }
    return $options;
}

function getParentCategoryOption( $category_id = 0, $parent_id = 0 ) {
    $ci = & get_instance();
    $query = $ci->db->get_where('post_category', ['parent_id' => $category_id]);
    
    $options = '';
    $options = '<option value="">--- Select Sub Category ---</option>';
    foreach ($query->result() as $row) {
        $options .= '<option value="' . $row->id . '" ';
        $options .= ($row->id == $parent_id ) ? 'selected="selected"' : '';
        $options .= '>' . $row->name . '</option>';
    }
    return $options;
}

function getCategoryNameById( $id ){
    $ci = & get_instance();
    $result = $ci->db->get_where('post_category', ['id' => $id])->row();
    if($result){
        return $result->name;
    } else {
        return 'No Parent';
    }
}