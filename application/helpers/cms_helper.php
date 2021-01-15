<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as DB;

function getPostWidgetByCategoryID($category_id = 0, $limit = 3) {
    //$ci =& get_instance();
    //$posts = $ci->db->get_where('cms', ['post_type' => 'post', 'status' => 'Publish' ],  3,0 )->result();    
    $posts = DB::table('cms')
            ->where('parent_id', '=', $category_id)
            ->where('post_type', '=', 'post')
            ->where('status', '=', 'Publish')
            ->take($limit)
            ->get();
    //return $posts;
    $html = '<ul>';

    foreach ($posts as $post) {
        $html .= '<li  class="clearfix">';
        $html .= '<a href="blog/' . $post->post_url . '">';
        $html .= '<div class="col-md-4 no-padding">';
        $html .= getCMSFeaturedThumb($post->thumb, 'tiny');
        $html .= '</div>';

        $html .= '<div class="text col-md-8">';
        $html .= '<p><b>' . getShortContent($post->post_title, 15) . '</b></p>';
        $html .= '<p>' . getShortContent($post->content, 30) . '</p>';
        $html .= '</div>';
        $html .= '</a>';
        $html .= ' </li>';
    }
    $html .= '</ul>';

    return $html;
}

function getCMSFeaturedThumb($thumb = null, $size = 'small', $class = '') {
    switch ($size) {
        case 'tiny':
            $size = 'width="100" height="80"';
            break;
        case 'small':
            $size = 'width="220" height="180"';
            break;
        case 'medium':
            $size = 'width="350" height="300"';
            break;
        case 'large':
            $size = ''; // Full 
            break;
        default :
            $size = 'width="350" height="300"';
    }

    $filepath = dirname(BASEPATH) . '/uploads/cms_photos/' . $thumb;
    if ($thumb && file_exists($filepath)) {
        return '<img src="uploads/cms_photos/' . $thumb . '" ' . $size . ' alt="Thumb" class="' . $class . '"/>';
    } else {
        return '<img src="assets/images/no-photo.jpg" ' . $size . '  alt="No Photo" class="' . $class . '"/>';
    }
}

function getPostFeaturedThumb($thumb, $size = 'small', $class = '') {
    switch ($size) {
        case 'tiny':
            $size = 'width="100" height="80"';
            break;
        case 'small':
            $size = 'width="220" height="180"';
            break;
        case 'medium':
            $size = 'width="350" height="300"';
            break;
        case 'large':
            $size = ''; // Full 
            break;
        case 'EXlarge':
            $size = 'width="286" height="427"';
            break;
        default :
            $size = 'width="350" height="300"';
    }

    $filepath = dirname( BASEPATH ) . '/' . $thumb;
    if($thumb && file_exists($filepath)){
        return '<img src="' . $thumb . '" '. $size .' alt="Thumb" class="'.$class.'"/>';
    } else {
        return '<img src="assets/images/no-photo.jpg" '. $size .'  alt="No Photo" class="'.$class.'"/>';
    }
}

function getUserProfilePhoto($thumb, $size = 'small', $class = '') {
    switch ($size) {
        case 'tiny':
            $size = 'width="80" height="80"';
            break;
        case 'small':
            $size = 'width="220" height="180"';
            break;
        case 'medium':
            $size = 'width="350" height="300"';
            break;
        case 'large':
            $size = ''; // Full 
            break;
        case 'EXlarge':
            $size = 'width="286" height="427"';
            break;
        default :
            $size = 'width="350" height="300"';
    }

    $filepath = dirname( BASEPATH ) . '/' . $thumb;
    if($thumb && file_exists($filepath)){
        return '<img src="' . $thumb . '" '. $size .' alt="Thumb" class="'.$class.'"/>';
    } else {
        return '<img src="assets/images/author.png" '. $size .'  alt="No Photo" class="'.$class.'"/>';
    }
}

function getCMSStatus($status = 'Active', $id = 0) {
    switch ($status) {
        case 'Publish':
            $class = 'btn-success';
            $icon = '<i class="fa fa-check-square-o"></i> ';
            break;
        case 'Draft':
            $class = 'btn-default';
            $icon = '<i class="fa fa-file-o" ></i> ';
            break;
        case 'Trash':
            $class = 'btn-danger';
            $icon = '<i class="fa fa-trash-o"></i> ';
            break;
        default :
            $class = 'btn-default';
            $icon = '<i class="fa fa-info"></i> ';
    }
    return '<button class="btn ' . $class . ' btn-xs" id="active_status_' . $id . '" type="button" data-toggle="dropdown">
            ' . $icon . $status . ' &nbsp; <i class="fa fa-angle-down"></i>
        </button>';
}

// meat value from meta key
function getCategoryDropDown($selected = null) {
    $CI = & get_instance();
    $categories = $CI->db->select('*')->get_where('cms_options', ['type' => 'category'])->result();

    $row = '';
    $row = '<option value="0">--- None ---</option>';
    foreach ($categories as $category) {
        $row .= '<option value="' . $category->id . '"';
        $row .= ($selected == $category->id) ? ' selected' : '';
        $row .= '>' . $category->name . '</option>';
    }
    return $row;
}

function caretoryParentIdByName($parent_id) {
    $CI = & get_instance();
    $catName = $CI->db->select('*')->get_where('cms_options', ['id' => $parent_id, 'type' => 'category'])->row();
    $count = $CI->db->where('id', $parent_id)->where('id', $parent_id)->count_all_results('cms_options');

    if ($count > 0) {
        return $catName->name;
    } else {
        echo 'Default';
    }
}

function getCMSPhoto($photo = null, $size = 'midium', $class = 'img-responsive') {
    switch ($size) {
        case 'small':
            $width_height = 'width="120"';
            break;
        case 'midium':
            $width_height = 'width="200"';
            break;
        default :
            $width_height = '';
    }

    $filename = dirname(APPPATH) . '/uploads/cms/' . $photo;
    if ($photo && file_exists($filename)) {
        return '<img class="' . $class . '" src="uploads/cms/' . $photo . '" ' . $width_height . '>';
    } else {
        return '<img class="' . $class . '" src="assets/images/no-photo.jpg" ' . $width_height . '>';
    }
}

function getNavigationMenu($menu_id = 0, $class = 'menu', $id = 'myNavbar') {
    $CI = & get_instance();
    $CI->db->select('rel.id, rel.parent, cms.post_url as url, cms.menu_name as title');
    $CI->db->from('cms_relations as rel');
    $CI->db->join('cms', 'rel.obj_id = cms.id', 'LEFT');    
    $CI->db->where('rel.opt_id', $menu_id);
    $CI->db->order_by('rel.order', 'ASC');
    $pages = $CI->db->get()->result_array();
    
    $active = $CI->uri->segment(1);
    $items = array();
    foreach($pages as $page){
        $items[$page['parent']][] = $page;        
    }
   
    $nav = "<div class=\"collapse navbar-collapse\" id=\"{$id}\">";
    $nav .= '<ul class="nav navbar-nav">';    
    $nav .= navigationBuilder($items, 0, 0, $active);        
    $nav .= '</ul>';
    $nav .= '</div>';
    return $nav;
}

function navigationBuilder($items, $parentID = 0, $level = 0, $active = 0) {
    $output = '';    
    foreach ($items[$parentID] as $root) {
        $output .= '<li ';
        if (empty($items[$root['id']])) {
            if($root['url'] == 'home'){
                $output .= ($active == $root['url']) ? ' class="active"' : '';
                $output .= '><a href="' . base_url(). '">' . $root['title'];
                $output .= '</a>';
            } else {
                $output .= ($active == $root['url']) ? ' class="active"' : '';
                $output .= '><a href="' . $root['url'] . '">' . $root['title'];
                $output .= '</a>';
            }
        } else {
            $output .= ' class="dropdown"';
            $output .= '<span class="caret"></span> ';
            $output .= '<a class="dropdown-toggle" data-toggle="dropdown" href="#">';
            $output .= $root['title'];
            $output .= '<span class="caret"></span></a>';            
            $output .= '<ul class="dropdown-menu">';
            $output .= navigationBuilder($items, $root['id'], $level + 1, $active);
            $output .= '</ul>';
            $output .= '</a>';            
        }
        
        $output .= '</li>';
    }    
    return $output;
}

function getPageFooterMenu($menu_id = 0, $class = 'menu', $id = '') {
    $CI = & get_instance();
    $CI->db->select('rel.id, rel.parent, cms.post_url as url, cms.menu_name as title');
    $CI->db->from('cms_relations as rel');
    $CI->db->join('cms', 'rel.obj_id = cms.id', 'LEFT');    
    $CI->db->where('rel.opt_id', $menu_id);
    $CI->db->order_by('rel.order', 'ASC');
    $pages = $CI->db->get()->result_array();
    
    $active = $CI->uri->segment(1);
    $items = array();
    foreach($pages as $page){
        $items[$page['parent']][] = $page;        
    }
   

    $nav = '<ul class="footer-nav">';    
    $nav .= navigationBuilder($items, 0, 0, $active);        
    $nav .= '</ul>';

    return $nav;
}