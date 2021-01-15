<?php

use Carbon\Carbon;

defined('BASEPATH') or exit('No direct script access allowed');

function getShortContent($long_text = '', $show = 100)
{
    $filtered_text = strip_tags($long_text);
    if ($show < strlen($filtered_text)) {
        return substr($filtered_text, 0, $show) . '...';
    } else {
        return $filtered_text;
    }
}

function getShortContentAltTag($long_text = '', $show = 100)
{
    $filtered_text = strip_tags($long_text);
    if ($show < strlen($filtered_text)) {
        return substr($filtered_text, 0, $show);
    } else {
        return $filtered_text;
    }
}


function ddd($data)
{
    echo '<pre style="padding:10px;">';
    dd($data);
    echo '</pre>';
}

function pp($data)
{
    echo '<pre style="padding:10px;">';
    print_r($data);
    echo '</pre>';
    exit();
}

function build_pagination_url($link = 'search', $page = 'page')
{
    $array = $_GET;
    $url = $link . '?';

    unset($array[$page]);
    unset($array['_']);

    if ($array) {
        $url .= \http_build_query($array);
    }
    $url .= "&{$page}";
    return $url;
}

function getUsers()
{
    $ci = &get_instance();
    $ci->db->select('id, email, first_name, last_name, role_id');
    $ci->db->where('status', 'Active');
    $users = $ci->db->get('users')->result();

    $options = '';
    foreach ($users as $user) {
        $options .= '<option value="' . $user->email . '" ';
        $options .= '>' . $user->first_name . ' ' . $user->last_name . ' (' . getRoleName($user->role_id) . ')</option>';
    }
    return $options;
}

function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // trim
    $text = trim($text, '-');
    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}

function removeSpaces($input = null)
{
    return preg_replace('!\s+!', ' ', $input);
}

function getLoginUserData($key = '')
{
    //key: user_id, user_mail, role_id, name, photo
    $data = &get_instance();
    $global = json_decode(base64_decode($data->input->cookie('fm_login_data', true)));
    return isset($global->$key) ? $global->$key : null;
}

function getSessionUserData($key = '')
{
    if (!empty($_SESSION) && !empty($_SESSION['value'])) {
        $session_value = $_SESSION['value'];
    } else {
        $session_value = '';
    }
    $data = &get_instance();
    $global = json_decode(base64_decode($session_value));
    return isset($global->$key) ? $global->$key : null;
}

function numericDropDown($i = 0, $end = 12, $incr = 1, $selected = 0)
{
    $option = '';
    for ($i; $i <= $end; $i += $incr) {
        $option .= '<option value="' . $i . '"';
        $option .= ($selected == $i) ? ' selected' : '';
        $option .= '>' . sprintf('%02d', $i) . '</option>';
    }
    return $option;
}

function htmlRadio($name = 'input_radio', $selected = '', $array = ['Male' => 'Male', 'Female' => 'Female'], $attr = null)
{
    $radio = '';
    $id = 0;

    if (count($array)) {
        foreach ($array as $key => $value) {
            $id++;
            $radio .= '<label>';
            $radio .= '<input type="radio" name="' . $name . '" id="' . $name . '_' . $id . '"';
            $radio .= (trim($selected) == $key) ? ' checked ' : '';
            $radio .= ($attr) ? ' ' . $attr . ' ' : '';
            $radio .= 'value="' . $key . '" /> ' . $value;
            $radio .= '&nbsp;&nbsp;&nbsp;</label>';
        }
    }
    return $radio;
}

/*
 * We will use it into header.php or footer.php or any view page
 * to load module wise css or js file
 */

function load_module_asset($module = null, $type = 'css', $script = null)
{
    $file = ($type == 'css') ? 'style.css.php' : 'script.js.php';
    if ($script) {
        $file = $script;
    }

    $path = APPPATH . '/modules/' . $module . '/assets/' . $file;
    if ($module && file_exists($path)) {
        include($path);
    }
}

function startPointOfPagination($limit = 25, $page = 0)
{
    return ($page) ? ($page - 1) * $limit : 0;
}

function getPaginator($total_row = 100, $currentPage = 2, $targetpath = '#&p', $limit = 25)
{
    $stages = 2;
    $page = intval($currentPage);
    $start = ($page) ? ($page - 1) * $limit : 0;

    // Initial page num setup
    $page = ($page == 0) ? 1 : $page;
    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total_row / $limit);
    $LastPagem1 = $lastpage - 1;
    $paginate = '';

    if ($lastpage > 1) {
        $paginate .= '<div class="row">';
        $paginate .= '<div class="col-md-12">';
        $paginate .= '<ul class="pagination low-margin">';
        $paginate .= '<li class="disabled"><a>Total: ' . $total_row . '</a></li>';
        // Previous
        $paginate .= ($page > 1) ? "<li><a href='$targetpath=$prev'>&lt; Pre</a></li>" : "<li class='disabled'><a> &lt; Pre</a></li>";
        // Pages
        if ($lastpage < 7 + ($stages * 2)) {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                $paginate .= ($counter == $page) ? "<li class='active'><a>$counter</a></li>" : "<li><a href='$targetpath=$counter'>$counter</a></li>";
            }
        } elseif ($lastpage > 5 + ($stages * 2)) {
            // Beginning only hide later pages
            if ($page < 1 + ($stages * 2)) {
                for ($counter = 1; $counter < 4 + ($stages * 2); $counter++) {
                    $paginate .= ($counter == $page) ? "<li class='active'><a>$counter</a></li>" : "<li><a href='$targetpath=$counter'>$counter</a></li>";
                }
                $paginate .= "<li class='disabled'><a>...</a></li>";
                $paginate .= "<li><a href='$targetpath=$LastPagem1'>$LastPagem1</a></li>";
                $paginate .= "<li><a href='$targetpath=$lastpage'>$lastpage</a></li>";
            } // Middle hide some front and some back
            elseif ($lastpage - ($stages * 2) > $page && $page > ($stages * 2)) {
                $paginate .= "<li><a href='$targetpath=1'>1</a></li>";
                $paginate .= "<li><a href='$targetpath=2'>2</a></li>";
                $paginate .= "<li><a>...</a></li>";
                for ($counter = $page - $stages; $counter <= $page + $stages; $counter++) {
                    $paginate .= ($counter == $page) ? "<li class='active'><a>$counter</a></li>" : "<li><a href='$targetpath=$counter'>$counter</a></li>";
                }
                $paginate .= "<li><a>...</a></li>";
                $paginate .= "<li><a href='$targetpath=$LastPagem1'>$LastPagem1</a></li>";
                $paginate .= "<li><a href='$targetpath=$lastpage'>$lastpage</a><li>";
            } else {
                // End only hide early pages
                $paginate .= "<li><a href='$targetpath=1'>1</a></li>";
                $paginate .= "<li><a href='$targetpath=2'>2</a></li>";
                $paginate .= "<li><a>...</a></li>";

                for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++) {
                    $paginate .= ($counter == $page) ? "<li class='active'><a>$counter</a></li>" : "<li><a href='$targetpath=$counter'>$counter</a></li>";
                }
            }
        }

        // Next
        $paginate .= ($page < $counter - 1) ? "<li><a href='$targetpath=$next'>Next &gt;</a></li>" : "<li class='disabled'><a>Next &gt;</a></li>";

        $paginate .= "</ul>";
        $paginate .= "<div class='clearfix'></div>";
        $paginate .= "</div>";
        $paginate .= "</div>";
    }

    return $paginate;
}

function ageCalculator($date = null)
{
    if ($date) {
        $tz = new DateTimeZone('Europe/London');
        $age = DateTime::createFromFormat('Y-m-d', $date, $tz)
            ->diff(new DateTime('now', $tz))
            ->y;
        return $age . ' years';
    } else {
        return 'Unknown';
    }
}

function sinceCalculator($date = null)
{
    if ($date) {
        $date = date('Y-m-d', strtotime($date));
        $tz = new DateTimeZone('Europe/London');
        $age = DateTime::createFromFormat('Y-m-d', $date, $tz)
            ->diff(new DateTime('now', $tz));

        $result = '';
        $result .= ($age->y) ? $age->y . 'y ' : '';
        $result .= ($age->m) ? $age->m . 'm ' : '';
        $result .= ($age->d) ? $age->d . 'd ' : '';
        $result .= ($age->h) ? $age->h . 'h ' : '';
        return $result;
    } else {
        return 'Unknown';
    }
}

function password_encription($string = '')
{
    return password_hash($string, PASSWORD_DEFAULT);
}


function get_admin_email()
{
    return getSettingItem('IncomingEmail');
}

function maintenanceValue($value)
{
    $maintenance_value = (!empty($value)) ? array_filter(explode('|', $value)) : '';

    return $maintenance_value;
}

function getSettingItem($setting_key = null)
{
    $ci = &get_instance();
    $setting = $ci->db->get_where('settings', ['label' => $setting_key])->row();
    return isset($setting->value) ? $setting->value : false;
}

function userStatus($selected = null)
{
    $status = ['Pending', 'Active', 'Inactive'];
    $options = '';
    foreach ($status as $row) {
        $options .= '<option value="' . $row . '" ';
        $options .= ($row == $selected) ? 'selected="selected"' : '';
        $options .= '>' . $row . '</option>';
    }
    return $options;
}

// Geting Role name from role ID
function getRoleName($role_id = 0)
{
    $ci = &get_instance();
    $role = $ci->db
        ->select('role_name')
        ->get_where('roles', ['id' => $role_id])
        ->row_array();

    if ($role) {
        return $role['role_name'];
    } else {
        return '<span class="text-red">Unknown</span>';
    }
}

function getCountryName($country_id = 0)
{
    $ci = &get_instance();
    $country = $ci->db
        ->select('name')
        ->get_where('countries', ['id' => $country_id])
        ->result();
    $country = (array)current($country);
    return isset($country['name']) ? $country['name'] : null;
}

function getDropDownCountries($country_id = 0)
{
    $ci = &get_instance();
    $query = $ci->db->get_where('countries', ['type' => '1', 'parent_id' => '0']);

    $options = '<option>--Select Country--</option>';
    foreach ($query->result() as $row) {
        $options .= '<option value="' . $row->id . '" ';
        $options .= ($row->id == $country_id) ? 'selected="selected"' : '';
        $options .= '>' . $row->name . '</option>';
    }
    return $options;
}

function bdDateFormat($data = '0000-00-00')
{
    return ($data == '0000-00-00') ? 'Unknown' : date('d/m/y', strtotime($data));
}

function isCheck($checked = 0, $match = 1)
{
    $checked = ($checked);
    return ($checked == $match) ? 'checked="checked"' : '';
}

function getCurrency($selected = '&pound')
{
    $codes = [
        '&pound' => "&pound; GBP",
        '&dollar' => "&dollar; USD",
        '&nira' => "&#x20A6; NGN"
    ];

    $row = '<select name="data[Setting][Currency]" class="form-control">';
    foreach ($codes as $key => $option) {
        $row .= '<option value="' . htmlentities($key) . '"';
        $row .= ($selected == $key) ? ' selected' : '';
        $row .= '>' . $option . '</option>';
    }
    $row .= '</select>';
    return $row;
}

function globalDateTimeFormat($datetime = '0000-00-00 00:00:00')
{
    if ($datetime == '0000-00-00 00:00:00' or $datetime == '0000-00-00') {
        return 'Unknown';
    }
    return date('h:i a d/m/y', strtotime($datetime));
}

function globalTimeFormat($datetime = '0000-00-00 00:00:00')
{
    if ($datetime == '0000-00-00 00:00:00' or $datetime == '0000-00-00') {
        return 'Unknown';
    }
    return date('h:i a', strtotime($datetime));
}

function globalDateFormat($datetime = '0000-00-00 00:00:00')
{
    if ($datetime == '0000-00-00 00:00:00' or $datetime == '0000-00-00' or $datetime == null) {
        return 'Unknown';
    }
    return date('d M y', strtotime($datetime));
}

function dateFormatEntertainment($datetime = '0000-00-00 00:00:00')
{
    if ($datetime == '0000-00-00 00:00:00' or $datetime == '0000-00-00' or $datetime == null) {
        return 'Unknown';
    }
    return date('F j, Y', strtotime($datetime));
}

function dateFormatEntertainmentHome($datetime = '0000-00-00 00:00:00')
{
    if ($datetime == '0000-00-00 00:00:00' or $datetime == '0000-00-00' or $datetime == null) {
        return 'Unknown';
    }
    return date('M d, yy', strtotime($datetime));
}

function returnJSON($array = [])
{
    return json_encode($array);
}

function ajaxRespond($status = 'FAIL', $msg = 'Fail! Something went wrong')
{
    return returnJSON(['Status' => strtoupper($status), 'Msg' => $msg]);
}

function ajaxAuthorized()
{
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return true;
    } else {
        $html = '';
        $html .= '<center>';
        $html .= '<h2 style="color:red;">Access Denied !</h2>';
        $html .= '<hr>';
        $html .= '<p>It seems that you might come here via an unauthorised way</p>';
        $html .= '</center>';
        die($html);
    }
}

function getLatLongByLocation($location = '')
{
    $lat_long = array();
    if (!empty($location)) {
        $requestedLocation = urlencode($location);
        $request_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=" . $requestedLocation . "&sensor=true";
        $urlLoadXml = simplexml_load_file($request_url) or die("url not loading");
        $loadStatus = $urlLoadXml->status;
        if ($loadStatus == "OK") {
            $lat = $urlLoadXml->result->geometry->location->lat;
            $long = $urlLoadXml->result->geometry->location->lng;
        }
        $lat_long['latitude'] = strip_tags($lat);
        $lat_long['longitude'] = strip_tags($long);
    }
    return $lat_long;
}

function getTimeDropDown($selected = 'PM')
{
    $times = [
        'AM' => 'AM',
        'PM' => 'PM',
    ];

    $row = '';
    foreach ($times as $key => $option) {
        $row .= '<option value="' . $key . '"';
        $row .= ($selected === $key) ? ' selected' : '';
        $row .= '>' . $option . '</option>' . "\r\n";
    }
    return $row;
}

function getUserRoleById($id = null)
{
    $CI = &get_instance();
    $user = $CI->db->select('role_id')->from('users')->where('id', $id)->get()->row();
    return isset($user->role_id) ? $user->role_id : false;
}

function getUserRoleNameById($id = null)
{
    $CI = &get_instance();
    $user = $CI->db->select('role_name')->from('roles')->where('id', $id)->get()->row();
    return isset($user->role_name) ? $user->role_name : false;
}

function getTableData($id, $table, $field)
{
    $ci = &get_instance();
    $html = '';
    $result = $ci->db->get_where($table, ['id' => $id])->row();
    if ($result) {
        $html = $result->$field;
    }
    return $html;
}

function getUserNameById($id = 0)
{
    $CI = &get_instance();
    $user_id = intval($id);
    $user = $CI->db->select('title, first_name, last_name')->from('users')->where('id', $id)->get()->row();
    return isset($user) ? $user->first_name . ' ' . $user->last_name : 'Unknown user';
}

function countPostLikeFromTable($post_id)
{
    $html = 0;
    $CI = &get_instance();
    $count = $CI->db->where(['post_id' => $post_id, 'like_unlike' => '1'])->group_by('user_id')->count_all_results('post_like_unlike');
    if ($count) {
        $html = $count;
    }
    return $html;
}

function countPostUnlikeFromTable($post_id)
{
    $html = 0;
    $CI = &get_instance();
    $count = $CI->db->where(['post_id' => $post_id, 'like_unlike' => '0'])->group_by('user_id')->count_all_results('post_like_unlike');
    if ($count) {
        $html = $count;
    }
    return $html;
}

function countPostLikeUnlikeWithCulture($post_id)
{
    $html = 0;
    $CI = &get_instance();
    $count = $CI->db->where(['post_id' => $post_id])->group_by('user_id')->count_all_results('post_like_unlike');
    if ($count) {
        $html = $count;
    }
    return $html;
}

function commentLikeCount($comment_id = 0)
{
    $html = 0;
    $CI = &get_instance();
    $count = $CI->db->where(['comment_id' => $comment_id, 'like_unlike' => '1'])->count_all_results('post_comments_like_unlike');
    if ($count) {
        $html = $count;
    }
    return $html;
}

function commentUnlikeCount($comment_id = 0)
{
    $html = 0;
    $CI = &get_instance();
    $count = $CI->db->where(['comment_id' => $comment_id, 'like_unlike' => '0'])->count_all_results('post_comments_like_unlike');
    if ($count) {
        $html = $count;
    }
    return $html;
}

function getCategories($cat = 0)
{
    $html = '';
    $ci = &get_instance();
    $categories = $ci->db->get_where('categories', ['status' => 'Active'])->result();
    if ($categories) {
        foreach ($categories as $category) {
            $html .= '<option value="' . $category->id . '"';
            $html .= ($category->id == $cat) ? ' selected' : '';
            $html .= '>' . $category->title . '</option>';
        }
    }
    return $html;
}


function timePassed($post_date)
{
    $html = '';
    $timestamp = (int)strtotime($post_date);
    $current_time = time();
    $diff = $current_time - $timestamp;

    $intervals = array(
        'year' => 31556926, 'month' => 2629744, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute' => 60
    );

    if ($diff == 0) {
        $return = 'just now';
    }

    if ($diff < 60) {
        $return = $diff == 1 ? $diff . ' second ago' : $diff . ' seconds ago';
    }

    if ($diff >= 60 && $diff < $intervals['hour']) {
        $diff = floor($diff / $intervals['minute']);
        $return = $diff == 1 ? $diff . ' minute ago' : $diff . ' minutes ago';
    }

    if ($diff >= $intervals['hour'] && $diff < $intervals['day']) {
        $diff = floor($diff / $intervals['hour']);
        $return = $diff == 1 ? $diff . ' hour ago' : $diff . ' hours ago';
    }

    if ($diff >= $intervals['day'] && $diff < $intervals['week']) {
        $diff = floor($diff / $intervals['day']);
        $return = $diff == 1 ? $diff . ' day ago' : $diff . ' days ago';
    }

    if ($diff >= $intervals['week'] && $diff < $intervals['month']) {
        $diff = floor($diff / $intervals['week']);
        $return = $diff == 1 ? $diff . ' week ago' : $diff . ' weeks ago';
    }

    if ($diff >= $intervals['month'] && $diff < $intervals['year']) {
        $diff = floor($diff / $intervals['month']);
        $return = $diff == 1 ? $diff . ' month ago' : $diff . ' months ago';
    }

    if ($diff >= $intervals['year']) {
        $diff = floor($diff / $intervals['year']);
        $return = $diff == 1 ? $diff . ' year ago' : $diff . ' years ago';
    }

    if ($return) {
        $html = '<i class="fa fa-clock-o"></i> ' . $return;
    }

    return $html;
}

function removeImage($photo = null, $folder = null)
{
    $filename = dirname(APPPATH) . '/uploads/' . $folder . '/' . $photo;
    if ($photo && file_exists($filename) && is_file($filename)) {
        unlink($filename);
    }
    return TRUE;
}

function getUserData($user_id = 0, $filde_name = 'id')
{
    $CI = &get_instance();
    if ($user = $CI->db->select($filde_name)->from('users')->where('id', $user_id)->get()->row()) {
        return $user->$filde_name;
    } else {
        return $id = 0;
    }
}

function selectOptions($selected = '', $array = null)
{
    $options = '';
    if (count($array)) {
        foreach ($array as $key => $value) {
            $options .= '<option value="' . $key . '" ';
            $options .= ($key == $selected) ? ' selected="selected"' : '';
            $options .= '>' . $value . '</option>';
        }
    }
    return $options;
}


function getDateRange($range = '')
{
    $status = array(
        '0' => '--Any--',
        date('Y-m-d') => 'Today',
        date('Y-m-d', strtotime('+1 Day')) => 'Next 2 Days',
        date('Y-m-d', strtotime('+3 Day')) => 'Next 3 Days',
        date('Y-m-d', strtotime('+7 Day')) => 'Next 7 Days',
        date('Y-m-d', strtotime('+1 Month')) => 'Next 1 Month',
        'Custom' => 'Custom'
    );
    $row = '';
    foreach ($status as $key => $option) {
        $row .= '<option value="' . $key . '"';
        if ($range == $key) {
            $row .= ' selected';
        }
        $row .= '>' . $option . '</option>';
    }
    return $row;
}

function getUserProfilePhotoByID($user_id = 0, $class = 'img-circle', $style = '')
{
    $CI = &get_instance();
    $user_info = $CI->db->get_where('users', ['id' => $user_id])->row();
    $photo = @$user_info->profile_photo;
    if ($photo) {
        $photofile = dirname(BASEPATH) . '/uploads/users_profile/' . $photo;
        if ($photo && file_exists($photofile) && is_file($photofile)) {
            return "<img class='" . $class . "' style='" . $style . "'  alt='" . $photo . "' src='uploads/users_profile/" . $photo . "'/>";
        } else {
            return "<img class='" . $class . "' style='" . $style . "'  alt='" . $photo . "' src='assets/images/avatar.svg'/>";
        }
    } else {
        return "<img class='" . $class . "' style='" . $style . "'  alt='" . $photo . "' src='assets/images/avatar.svg'/>";
    }
}

function getPhoto($config = [])
{
    $default = [
        'class' => 'img-responsive',
        'attr' => '',
        'folder' => 'posts',
        'photo' => '',
        'no_photo_size' => 'default',
        'no_photo' => 'yes',
    ];
    $setting = $config + $default;

    $filename = dirname(APPPATH) . '/uploads/' . $setting['folder'] . '/' . $setting['photo'];
    if ($setting['photo'] && file_exists($filename) && is_file($filename)) {
        return '<img class="' . $setting['class'] . '"  alt="' . $setting['photo'] . '" src="uploads/' . $setting['folder'] . '/' . $setting['photo'] . '" ' . $setting['attr'] . '>';
    } elseif ($setting['no_photo'] == 'yes') {
        return '<img class="no_photo ' . $setting['class'] . '" alt="no photo" src="uploads/no_photo_' . $setting['no_photo_size'] . '.jpg" ' . $setting['attr'] . '>';
    } else {
        return false;
    }
}

function getPhoto2($config = [], $alt = '')
{
    $default = [
        'class' => 'details-img',
        'attr' => '',
        'photo' => '',
        'no_photo_size' => 'default',
        'no_photo' => 'yes',
    ];
    $setting = $config + $default;

    $filename = dirname(APPPATH) . '/' . $setting['photo'];
    if ($setting['photo'] && file_exists($filename) && is_file($filename)) {
        return '<img class="' . $setting['class'] . '" alt="' . $alt . '" src="' . $setting['photo'] . '" ' . $setting['attr'] . '>';
    } elseif ($setting['no_photo'] == 'yes') {
        return '<img class="no_photo ' . $setting['class'] . '" alt="no photo" src="uploads/no_photo_' . $setting['no_photo_size'] . '.jpg" ' . $setting['attr'] . '>';
    } else {
        return false;
    }
}

function getPhoto3($photo = '', $location = '')
{

    $filename = FCPATH;
    if ($location) $filename .= $location;
    $filename .= '/' . $photo;
    if ($photo && file_exists($filename) && is_file($filename)) {
        return stripslashes($photo);
    } else {
        return 'assets/images/no-photo.jpg';
    }
}

function getRevenue($url)
{
    $ci = &get_instance();
    $data = null;
    $revenue = [];

    $ci->db->select('adverts.*, advert_pages.url, 
            advert_pages.name as page_name, advert_pages.section_name,
            advert_image_size.height, advert_image_size.width,
            advert_image_size.name as image_name');
    $ci->db->from('adverts');
    $ci->db->join('advert_pages', 'advert_pages.id = adverts.page_id', 'LEFT');
    $ci->db->join('advert_image_size', 'advert_image_size.id = adverts.img_size_id', 'LEFT');
    $ci->db->where('url', $url);
    $ci->db->where('status', 'Publish');
    $ci->db->where('activation_date <=', date('Y-m-d'));
    $ci->db->where('expire_date >', date('Y-m-d'));

    $revenue = $ci->db->get()->result();

    foreach ($revenue as $rev) {
        $data[$rev->section_name] = $rev;
    }

    return $data;
}

function getAddSet($url, $section)
{
    $ci = &get_instance();
    $data = null;
    $revenue = [];

    $ci->db->select('adverts.*, advert_pages.url, 
            advert_pages.name as page_name, advert_pages.section_name,
            advert_image_size.height, advert_image_size.width,
            advert_image_size.name as image_name');
    $ci->db->from('adverts');
    $ci->db->join('advert_pages', 'advert_pages.id = adverts.page_id', 'LEFT');
    $ci->db->join('advert_image_size', 'advert_image_size.id = adverts.img_size_id', 'LEFT');
    $ci->db->where('url', $url);
    $ci->db->where('status', 'Publish');
    $ci->db->like('section_name', $section);
    $ci->db->where('activation_date <=', date('Y-m-d'));
    $ci->db->where('expire_date >', date('Y-m-d'));

    $revenue = $ci->db->get()->result();

    foreach ($revenue as $rev) {
        $data[$rev->section_name] = $rev;
    }

    return $data;
}

function getRevenueImage($section, $data)
{
    $html = "";

    if (isset($data[$section]) && !empty($data[$section])) {
        if ($data[$section]->advert_type != 'adsense') {
            $html .= '<a href="' . $data[$section]->company_url . '" target="_blank" class="reve" data-id="' . $data[$section]->id . '">';
            if ($data[$section]->image) {
                $html .= '<img src="' . $data[$section]->image . '" alt="">';
            } else {
                if ($data[$section]->height == 90 && $data[$section]->width == 970) {
                    $html .= '<img src="assets/images/adds/large-leaderboard.jpg" alt="">';
                } elseif ($data[$section]->height == 250 && $data[$section]->width == 300) {
                    $html .= '<img src="assets/images/adds/rectangle.jpg" alt="">';
                } elseif ($data[$section]->height == 250 && $data[$section]->width == 970) {
                    $html .= '<img src="assets/images/adds/billboard.jpg" alt="">';
                } elseif ($data[$section]->height == 90 && $data[$section]->width == 728) {
                    $html .= '<img src="assets/images/adds/liaderboard.jpg" alt="">';
                } elseif ($data[$section]->height == 280 && $data[$section]->width == 336) {
                    $html .= '<img src="assets/images/adds/large-rectangle.jpg" alt="">';
                } elseif ($data[$section]->height == 1050 && $data[$section]->width == 300) {
                    $html .= '<img src="assets/images/adds/portrait.jpg" alt="">';
                } elseif ($data[$section]->height == 600 && $data[$section]->width == 300) {
                    $html .= '<img src="assets/images/adds/haf-page-skyscraper.jpg" alt="">';
                } elseif ($data[$section]->height == 400 && $data[$section]->width == 300) {
                    $html .= '<img src="assets/images/adds/add-big.jpg" alt="">';
                }
            }

            $html .= '</a>';
        } else {
            $ci = &get_instance();
            $code = $ci->db->get('adsense_code')->row();

            if (isset($code) && !empty($code)) {
                $html .= '<script data-ad-client="' . $code->public_id . '" data-ad-slot="' . $code->ad_slot_id . '" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle" style="display:inline-block;width:' . $data[$section]->width . 'px;height:' . $data[$section]->height . 'px"></ins><script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>';
            }
        }
    }

    return $html;
}

function removeHtmlCss($text = '')
{
    $text = strip_tags($text, "<style>");

    $substring = substr($text, strpos($text, "<style"), strpos($text, "</style>") + 0);

    $text = str_replace($substring, "", $text);
    $text = str_replace(array("\t", "\r", "\n"), " ", $text);
    $text = trim($text);

    return $text;
}

function getLocationList($selected = 0, $type = 0, $parent_id = 0)
{
    $ci = &get_instance();

    $ci->db->where('type', $type);

    if ($parent_id) {
        $ci->db->where('parent_id', $parent_id);
    }

    $results = $ci->db->get('countries')->result();

    $options = '';
    foreach ($results as $row) {
        $options .= '<option value="' . $row->id . '" ';
        $options .= ($row->id == $selected) ? 'selected="selected"' : '';
        $options .= '>' . $row->name . '</option>';
    }
    return $options;
}

function getAdvertisement()
{
    $ci = &get_instance();
    $uri1 = $ci->uri->segment(1);
    $catName = $ci->uri->segment(2);
    $subCatName = $ci->uri->segment(3);
    $html = '';

    if ($uri1 == 'category' && $catName) {
        $sub_cat_id = getCategoryIDBySlug($subCatName);
        $cat_id = getCategoryIDBySlug($catName);

        if ($cat_id && $sub_cat_id) {
            $ci->db->where('category_id', $cat_id);
            $ci->db->where('sub_cat_id', $sub_cat_id);
            $ci->db->where('status', 'Active');
        } else {
            $ci->db->where('category_id', $cat_id);
            $ci->db->where('sub_cat_id', 0);
            $ci->db->where('status', 'Active');
        }

        $ci->db->from('ads');
        $ci->db->order_by('date', 'DESC');
        $rows = $ci->db->get()->result();

        if ($rows) {
            foreach ($rows as $row) {
                if ($row->link) {
                    $html .= '<a target="_blank" href="' . site_url("click?id={$row->id}&url={$row->link}") . '">';
                } else {
                    $html .= '<a href="javascript:void(0)">';
                }

                $html .= '<img class="img-responsive" alt="no photo" src="' . getPhoto3($row->photo) . '"/>';
                $html .= '</a>';
            }
        } else {
            $ci->db->where('status', 'Default');
            $ci->db->from('ads');
            $ci->db->order_by('date', 'DESC');
            $rows = $ci->db->get()->result();
            foreach ($rows as $row) {
                if ($row->link) {
                    $html .= '<a target="_blank" href="' . site_url("click?id={$row->id}&url={$row->link}") . '">';
                } else {
                    $html .= '<a href="javascript:void(0)">';
                }

                $html .= '<img class="img-responsive" alt="photo" src="' . getPhoto3($row->photo) . '"/>';
                $html .= '</a>';
            }
        }
        return $html;
    } else {
        return false;
    }


}

function mostRead($id = 0, $days = 7)
{
    $ci = &get_instance();
    $ci->db->select('p.id, p.category_id, p.sub_category_id, p.title, p.post_url, p.post_image, p.home_section_id, p.modified, p.created, c.name as category_title, sub_category.template_design as sub_cat_tem_desgin');
    $ci->db->select("CONCAT(u.first_name, ' ', u.last_name) AS name");
    $ci->db->from('posts as p');
    $ci->db->where('p.id !=', $id);
    $ci->db->where('p.post_show', 'Frontend');
    $ci->db->where('p.created >=', Carbon::now()->subDays($days)->format('Y-m-d H:i'));
    $ci->db->join('users as u', 'u.id = p.user_id', 'LEFT');
    $ci->db->join('post_category as c', 'c.id = p.category_id', 'LEFT');
    $ci->db->join('post_category as sub_category', 'sub_category.id = p.sub_category_id', 'LEFT');
    $ci->db->limit(5, 0);

    return $ci->db->order_by('hit_count', 'DESC')->get()->result();
}

function getRelatedTagPosts($id = 0)
{
    $ci = &get_instance();
    $ci->db->from('post_tags');
    $ci->db->where('post_id', $id);
    $tags = $ci->db->get()->result();
    if (count($tags) > 0) {
        $ci->db->from('post_tags');
        $ci->db->where('tag_id', $tags[0]->tag_id);
        $ci->db->where('post_id !=', $id);
        foreach ($tags as $key => $tag) {
            if ($key > 0) {
                $ci->db->or_where("tag_id", $tag->tag_id);
            }
        }
        $postTags = $ci->db->get()->result();
        $relatedPosts = [];
        foreach ($postTags as $post) {
            $relatedPosts[] = $post->post_id;
        }

        $ci->db->select('p.id, p.category_id, p.sub_category_id, p.title, p.post_url, p.post_image, p.home_section_id, p.modified, c.name as category_title');
        $ci->db->select("CONCAT(u.first_name, ' ', u.last_name) AS name");
        $ci->db->from('posts as p');
        $ci->db->where('p.id !=', $id);
        if (!empty($relatedPosts)) {
            $ci->db->where_in('p.id', $relatedPosts);
        }
        $ci->db->where('p.post_show', 'Frontend');
        $ci->db->where('p.status', 'Publish');
        $ci->db->order_by('p.created', 'DESC');
        $ci->db->join('users as u', 'u.id = p.user_id', 'LEFT');
        $ci->db->join('post_category as c', 'c.id = p.category_id', 'LEFT');

        return $ci->db->order_by('hit_count', 'DESC')->get()->result();
    }

    return [];
}

function templateDesign($input = null)
{
    $output = [
        1 => 'News for General',
    ];

    if (empty($input) || !array_key_exists($input, $output)) {
        return $output;
    }

    return $output[$input];
}


function getUserBioById($id = 0)
{
    $CI = &get_instance();
    $user_id = intval($id);
    $user = $CI->db->select('biography')->from('users')->where('id', $user_id)->get()->row();
    return isset($user) ? $user->biography : '';
}

function getAdvertPageName($selec = 0)
{
    $CI = &get_instance();
    $pages = $CI->db->order_by('id', 'DESC')->get('advert_pages')->result();

    $html = '';

    foreach ($pages as $page) {
        $selected = intval($page->id) == intval($selec) ? 'selected' : '';
        $html .= '<option value="' . $page->id . '" ' . $selected . ' >' . $page->name . " " . $page->section_name . '</option>';
    }

    return $html;
}


function getPercent($value, $total)
{
    $percent = ($value / $total) * 100;

    return number_format($percent, 2, '.', ',');
}

function getAns($question_id, $gender)
{
    $CI = &get_instance();
    $ans = $CI->db->select('edo_opinion_poll_questions_ans.*')
        ->from('edo_opinion_poll_questions_ans')
        ->where('question_id', $question_id)
        ->where('gender', $gender)
        ->order_by('id', 'ASC')->get()->result();

    return $ans;
}

function arraySort($array)
{
    for ($i = 1; $i < 6; $i++) {
        for ($j = 1; $j < 6; $j++) {
            $result = [];
            if (intval($array[$i]['total']) > intval($array[$j]['total'])) {
                $result = $array[$i];
                $array[$i] = $array[$j];
                $array[$j] = $result;
            }
        }
    }

    return $array;
}

function getAdvertImageName($selec = 0)
{
    $CI = &get_instance();
    $pages = $CI->db->get('advert_image_size')->result();

    $html = '';

    foreach ($pages as $page) {
        $selected = intval($page->id) == intval($selec) ? 'selected' : '';
        $html .= '<option value="' . $page->id . '" ' . $selected . ' >' . $page->name . " " . $page->height . "*" . $page->width . '</option>';
    }

    return $html;
}

function getUserProfileSlugById($id = 0)
{
    $CI = &get_instance();
    $user_id = intval($id);
    $user = $CI->db->select('profile_slug')->from('users')->where('id', $user_id)->get()->row();
    return isset($user) ? $user->profile_slug : '';
}

function getAuthorDetails($id = 0)
{
    $CI = &get_instance();
    $user_id = intval($id);
    $user = $CI->db->from('users')->where('id', $user_id)->get()->row();

    return isset($user) ? $user : '';
}

function checkRecaptcha()
{
    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secret = config_item('secret_key');
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $contents = curl_exec($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
            echo "\n<br />";
            $contents = '';
        } else {
            curl_close($ch);
        }
        //$contents = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
        //$responseData = json_decode($verifyResponse);


        $responseData = json_decode($contents);
        if ($responseData->success) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}


function marketSelect($id = null)
{
    $ci = &get_instance();
    $ci->db->select('id, name');
    $ci->db->order_by('id', 'asc');
    $market_places = $ci->db->get('market_places')->result();
    $html = '';
    $id = empty($id) ? 1 : $id;
    foreach ($market_places as $key => $market_place) {
        $selected = $market_place->id == $id ? 'selected' : '';
        $html .= '<option value="' . $market_place->id . '" ' . $selected . '>' . $market_place->name . '</option>';
    }
    return $html;
}

function currencySelect($isBase = null, $id = null)
{
    $ci = &get_instance();
    $ci->db->select('id, code');
    if (!empty($isBase)) {
        $ci->db->where('is_base', 1);
    }
    $ci->db->where('status', 'Publish');
    if (!empty($isBase)) {
        $ci->db->order_by('code', 'desc');
    } else {
        $ci->db->order_by('id', 'desc');
    }

    $currencies = $ci->db->get('currencies')->result();
    $html = '';
    foreach ($currencies as $key => $currency) {
        $selected = $currency->id == $id ? 'selected' : '';
        $html .= '<option value="' . $currency->id . '" ' . $selected . '>' . $currency->code . '</option>';
    }
    return $html;

}

function age_calculate_by_dob($dob = '')
{
    if (empty($dob)) return false;
    //date in yyyy-mm-dd format; or it can be in other formats as well
    //explode the date to get month, day and year
    $birthDate = explode("-", $dob);
    //get age from date or birthdate
    return (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
        ? ((date("Y") - $birthDate[0]) - 1)
        : (date("Y") - $birthDate[0]));
}


function countryWithSelect($select = null)
{
    $country_array = array(
        "Afghanistan", "Aland Islands", "Albania", "Algeria",
        "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica",
        "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria",
        "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda",
        "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia",
        "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.",
        "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands",
        "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic",
        "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros",
        "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia",
        "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador",
        "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)",
        "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories",
        "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada",
        "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina",
        "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq",
        "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan",
        "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia",
        "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia",
        "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico",
        "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia",
        "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria",
        "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama",
        "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion",
        "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)",
        "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone",
        "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain",
        "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan",
        "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey",
        "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay",
        "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)",
        "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");
    $html = '';
    foreach ($country_array as $country) {
        $selected = $country == $select ? 'selected' : '';
        $html .= '<option value="' . $country . '" ' . $selected . '>' . $country . '</option>';
    }

    return $html;
}

function searchOBJByValue($field, $value, $array)
{
    foreach ($array as $key => $val) {
        if ($val->$field == $value) {
            return $val;
        }
    }
    return [];
}


function getUserIpAddress()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}


function getShortWordWithDot($in, $width = 12)
{
    return mb_strimwidth($in, 0, $width, "..");
}


function aboutUsPageUserSections($input = null)
{
    $output = [
        1 => 'Editorial Team',
        2 => 'Others'
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

function apiResponse($data = [], $code = 200)
{
    $CI = &get_instance();

    return $CI->output
        ->set_content_type('application/json')
        ->set_status_header($code)
        ->set_output(json_encode($data));
}

function getCategoryData($main_category_id, $sub_category_id, $child_category_id, $limit = 5)
{
    $ci = &get_instance();

    $data = $ci->db->select('posts.*')->from('posts')
        ->where_in('post_show', ['Frontend'])
        ->where('posts.status', 'Publish')
        ->where('category_id', $main_category_id)
        ->where('sub_category_id', $sub_category_id)
        ->where('child_category_id', $child_category_id)
        ->order_by('modified', 'DESC')
        ->limit($limit)->get()->result();

    return $data;
}

function getCategoryDataTotal($main_category_id, $sub_category_id, $child_category_id)
{
    $ci = &get_instance();

    $data = $ci->db->select('posts.*')->from('posts')
        ->where_in('post_show', ['Frontend'])
        ->where('posts.status', 'Publish')
        ->where('category_id', $main_category_id)
        ->where('sub_category_id', $sub_category_id)
        ->where('child_category_id', $child_category_id)
        ->order_by('modified', 'DESC')
        ->count_all_results();

    return $data;
}

function getChildCategoryByTemplate($parentId, $subCatId = 0, $template = 1)
{
    $ci = &get_instance();

    $childCat = $ci->db->select('post_category.*')
        ->from('post_category')
        ->where('parent_id', $parentId)
        ->where('sub_category_id', $subCatId)
        ->where('template_design', $template)->get()->row();

    return $childCat ? $childCat : "";
}

function splitDescription($description, $chunk = 15)
{
    $array = array_filter(explode('</p>', $description), function ($value) {
        return !is_null($value) && $value !== '' && $value != PHP_EOL;
    });
    $ch = array_chunk($array, $chunk);

    return $ch;
}

function visualNumberFormat($value)
{
    if (is_integer($value)) {
        return number_format($value, 2, '.', '');
    } elseif (is_string($value)) {
        $value = floatval($value);
    }
    $number = explode('.', number_format($value, 10, '.', ''));
    $intVal = (int)$value;
    if ($value > $intVal || $value < 0) {
        $intPart = $number[0];
        $floatPart = substr($number[1], 0, 8);
        $floatPart = rtrim($floatPart, '0');
        if (strlen($floatPart) < 2) {
            $floatPart = substr($number[1], 0, 2);
        }

        return $intPart . '.' . $floatPart;
    }

    return $number[0] . '.' . substr($number[1], 0, 2);
}

function highestViewedPosts($userId = 0)
{
    $ci = &get_instance();
    if ($userId) {
        $data = $ci->db->select('title, post_url, hit_count, sub_category.template_design as sub_cat_tem_desgin')
            ->where('user_id', $userId)
            ->from('posts')
            ->join('post_category as sub_category', 'sub_category.id = posts.sub_category_id', 'LEFT')
            ->order_by('hit_count', 'DESC')
            ->limit(10)
            ->get()
            ->result();
    } else {
        $data = $ci->db->select('title, post_url, hit_count, sub_category.template_design as sub_cat_tem_desgin')
            ->from('posts')
            ->join('post_category as sub_category', 'sub_category.id = posts.sub_category_id', 'LEFT')
            ->order_by('hit_count', 'DESC')
            ->limit(10)
            ->get()
            ->result();
    }

    return $data;
}

function draftPosts($userId)
{
    $ci = &get_instance();
    $items = $ci->db->select('posts.id, posts.title, posts.post_url, sub_category.template_design as sub_cat_tem_desgin')
        ->where('posts.user_id', $userId)
        ->where('posts.status', 'Draft')
        ->join('post_category as sub_category', 'sub_category.id = posts.sub_category_id', 'LEFT')
        ->from('posts')->order_by('posts.id', 'DESC')->limit(4)->get()->result();
    return $items;
}

function recentlyPublished($userId = 0)
{
    $ci = &get_instance();
    if ($userId) {
        $data = $ci->db->select('posts.title, post_url, hit_count, CONCAT(first_name, \' \', last_name) AS name, modified, sub_category.template_design as sub_cat_tem_desgin')
            ->where('user_id', $userId)
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->join('users', 'users.id = posts.user_id', 'LEFT')
            ->join('post_category as sub_category', 'sub_category.id = posts.sub_category_id', 'LEFT')
            ->from('posts')->order_by('posts.created', 'DESC')->limit(20)->get()->result();
    } else {
        $data = $ci->db->select('posts.title, post_url, hit_count, CONCAT(first_name, \' \', last_name) AS name, modified, sub_category.template_design as sub_cat_tem_desgin')
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->join('users', 'users.id = posts.user_id', 'LEFT')
            ->join('post_category as sub_category', 'sub_category.id = posts.sub_category_id', 'LEFT')
            ->from('posts')->order_by('posts.created', 'DESC')->limit(20)->get()->result();
    }

    return $data;
}

function recentComments($userId = 0)
{
    $ci = &get_instance();
    if ($userId) {
        $data = $ci->db->select('posts.title, post_url, post_comments.description, sub_category.template_design as sub_cat_tem_desgin')
            ->where('posts.user_id', $userId)
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->join('posts', 'posts.id = post_comments.post_id')
            ->join('post_category as sub_category', 'sub_category.id = posts.sub_category_id', 'LEFT')
            ->from('post_comments')->order_by('post_comments.id', 'DESC')->limit(10)->get()->result();
    } else {
        $data = $ci->db->select('posts.title, post_url, post_comments.description, sub_category.template_design as sub_cat_tem_desgin')
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->join('posts', 'posts.id = post_comments.post_id')
            ->join('post_category as sub_category', 'sub_category.id = posts.sub_category_id', 'LEFT')
            ->from('post_comments')->order_by('post_comments.id', 'DESC')->limit(10)->get()->result();
    }

    return $data;
}


function journalistPosts($time = 'today')
{
    $ci = &get_instance();
    if ($time == 'yesterday') {
        $data = $ci->db->select('COUNT(posts.id) as total_posts, SUM(posts.hit_count) as total_views, CONCAT(first_name, \' \', last_name) AS name, profile_slug')
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->where('posts.created >=', Carbon::now()->subDay()->startOfDay()->format('Y-m-d H:i:s'))
            ->where('posts.created <=', Carbon::now()->subDay()->endOfDay()->format('Y-m-d H:i:s'))
            ->join('posts', 'posts.user_id = users.id')
            ->from('users')->order_by('users.first_name', 'ASC')->group_by('users.id')->get()->result();
    } elseif ($time == 'last_7_days') {
        $data = $ci->db->select('COUNT(posts.id) as total_posts, SUM(posts.hit_count) as total_views, CONCAT(first_name, \' \', last_name) AS name, profile_slug')
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->where('posts.created >=', Carbon::now()->subWeek()->format('Y-m-d H:i:s'))
            ->join('posts', 'posts.user_id = users.id')
            ->from('users')->order_by('users.first_name', 'ASC')->group_by('users.id')->get()->result();
    } elseif ($time == 'last_30_days') {
        $data = $ci->db->select('COUNT(posts.id) as total_posts, SUM(posts.hit_count) as total_views, CONCAT(first_name, \' \', last_name) AS name, profile_slug')
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->where('posts.created >=', Carbon::now()->subMonth()->format('Y-m-d H:i:s'))
            ->join('posts', 'posts.user_id = users.id')
            ->from('users')->order_by('users.first_name', 'ASC')->group_by('users.id')->get()->result();
    } else {
        $data = $ci->db->select('COUNT(posts.id) as total_posts, SUM(posts.hit_count) as total_views, CONCAT(first_name, \' \', last_name) AS name, profile_slug')
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->where('posts.created >=', Carbon::now()->startOfDay()->format('Y-m-d H:i:s'))
            ->join('posts', 'posts.user_id = users.id')
            ->from('users')->order_by('users.first_name', 'ASC')->group_by('users.id')->get()->result();
    }

    return $data;
}

function maxJournalistPosts($time = 'today')
{
    $ci = &get_instance();
    if ($time == 'yesterday') {
        $data = $ci->db->select('COUNT(posts.id) as total_posts, SUM(posts.hit_count) as total_views')
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->where('posts.created >=', Carbon::now()->subDay()->startOfDay()->format('Y-m-d H:i:s'))
            ->where('posts.created <=', Carbon::now()->subDay()->endOfDay()->format('Y-m-d H:i:s'))
            ->join('posts', 'posts.user_id = users.id')
            ->from('users')->order_by('COUNT(posts.id)', 'DESC')->group_by('users.id')->get()->row();
    } elseif ($time == 'last_7_days') {
        $data = $ci->db->select('COUNT(posts.id) as total_posts, SUM(posts.hit_count) as total_views')
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->where('posts.created >=', Carbon::now()->subWeek()->format('Y-m-d H:i:s'))
            ->join('posts', 'posts.user_id = users.id')
            ->from('users')->order_by('COUNT(posts.id)', 'DESC')->group_by('users.id')->get()->row();
    } elseif ($time == 'last_30_days') {
        $data = $ci->db->select('COUNT(posts.id) as total_posts, SUM(posts.hit_count) as total_views')
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->where('posts.created >=', Carbon::now()->subMonth()->format('Y-m-d H:i:s'))
            ->join('posts', 'posts.user_id = users.id')
            ->from('users')->order_by('COUNT(posts.id)', 'DESC')->group_by('users.id')->get()->row();
    } else {
        $data = $ci->db->select('COUNT(posts.id) as total_posts, SUM(posts.hit_count) as total_views')
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->where('posts.created >=', Carbon::now()->startOfDay()->format('Y-m-d H:i:s'))
            ->join('posts', 'posts.user_id = users.id')
            ->from('users')->order_by('COUNT(posts.id)', 'DESC')->group_by('users.id')->get()->row();
    }

    return $data;
}

function pendingAndSchedulePosts()
{
    $ci = &get_instance();
    $data = $ci->db->select('posts.id, posts.title, posts.status, post_url, hit_count, CONCAT(first_name, \' \', last_name) AS name, modified , sub_category.template_design as sub_cat_tem_desgin')
        ->where_in('post_show', ['Journalist', 'Editor'])
        ->where_in('posts.status', ['Publish', 'Schedule', 'Schedule_Publish'])
        ->join('users', 'users.id = posts.user_id', 'LEFT')
        ->join('post_category as sub_category', 'sub_category.id = posts.sub_category_id', 'LEFT')
        ->from('posts')->order_by('posts.id', 'DESC')->get()->result();

    return $data;
}

function getYearRange($start = 1960, $selected = 0)
{
    $option = '';
    for ($i = date('Y'); $i >= $start; $i--) {
        $option .= '<option';
        $option .= ($selected == $i) ? ' selected' : '';
        $option .= '>' . sprintf('%02d', $i) . '</option>';
    }
    return $option;
}

function getYea($selec = 0)
{
    $CI = &get_instance();
    $pages = $CI->db->select('states.name, edo_states.state_id')->from('edo_states')->join('states', 'states.id = edo_states.state_id', 'LEFT')->order_by('states.name', 'ASC')->get()->result();

    $html = '<option value="">Select State</option>';

    foreach ($pages as $page) {
        $selected = intval($page->state_id) == intval($selec) ? 'selected' : '';
        $html .= '<option value="' . $page->state_id . '" ' . $selected . ' >' . $page->name . " State" . '</option>';
    }

    return $html;
}


function get_cat_template($template)
{
    $ci = &get_instance();
    return $ci->db->where(['template_design' => $template])->order_by('id', 'ASC')->get('post_category')->row();
}

function get_sub_cat_template($template)
{
    $ci = &get_instance();
    return $ci->db->where(['template_design' => $template, 'sub_category_id' => 0])->get('post_category')->row();
}

function get_profile_img($picture, $authType = 'web')
{
    if ($authType == 'web') {
        return base_url() . 'uploads/users_profile/' . $picture;
    }
    return $picture;
}

function searchOnPostTable($search = '', $countOrResult = '', $category_id = 0, $subcategory_id = 0, $child_category = 0, $limit = 0, $offset = 0)
{
    $ci = &get_instance();
    $cleanStr = trim(preg_replace('/\s\s+/', ' ', str_replace("'", "", str_replace("\n", " ", $search))));
    $searchString = urlencode($cleanStr);
    $queryStr = "(";
    $sq = $ci->db->escape('[[:<:]]' . strtolower(urldecode($searchString)) . '[[:>:]]');
    $queryStr = $queryStr . "(LOWER(posts.title) REGEXP $sq AND LOWER(posts.seo_title) REGEXP $sq AND LOWER(posts.seo_keyword) REGEXP $sq)";
    $search = explode('+', $searchString);
    $searchCombinations = getSearchCombinations($search);
    foreach ($searchCombinations as $string) {
        if (!empty($string)) {
            $sq = $ci->db->escape('[[:<:]]' . strtolower($string) . '[[:>:]]');
            $queryStr = $queryStr . " OR (LOWER(posts.title) REGEXP $sq AND LOWER(posts.seo_title) REGEXP $sq AND LOWER(posts.seo_keyword) REGEXP $sq)";
            $explodedStrings = explode(" ", $string);
            $queryStr = $queryStr . " OR (";
            foreach ($explodedStrings as $explodedString) {
                $sq = $ci->db->escape('[[:<:]]' . strtolower($explodedString) . '[[:>:]]');
                $queryStr = $queryStr . "LOWER(posts.title) REGEXP $sq AND ";
            }
            $queryStr = substr($queryStr, 0, -4);
            $queryStr = $queryStr . ")";
        }
    }
    $queryStr = $queryStr . ")";
//        $ci->db->select('posts.*, sub_category.template_design as sub_cat_tem_desgin');
    $ci->db->where($queryStr, NULL, FALSE);
    $ci->db->from('posts');
//        $ci->db->join('post_category as sub_category', 'sub_category.id = posts.sub_category_id', 'LEFT');
    if (!empty($category_id)) $ci->db->where('posts.category_id', $category_id);
    if (!empty($subcategory_id)) $ci->db->where('posts.sub_category_id', $subcategory_id);
    if (!empty($child_category)) $ci->db->where('posts.child_category_id', $child_category);

    $ci->db->where('posts.post_show', 'Frontend');
    $ci->db->where('posts.status', 'Publish');
    $ci->db->order_by("CASE WHEN posts.title = '" . urldecode($searchString) . "'THEN 0  
              WHEN posts.title LIKE '" . urldecode($searchString) . "%' THEN 1  
              WHEN posts.title LIKE '%" . urldecode($searchString) . "%' THEN 2  
              WHEN posts.title LIKE '%" . urldecode($searchString) . "' THEN 3  
              ELSE 4
         END, posts.created DESC");

    if ($countOrResult == 'count') return $ci->db->get()->num_rows();
    if (!empty($limit)) $ci->db->limit($limit, $offset);
    return $ci->db->get()->result();
}

function getSearchCombinations($array)
{
    $list = array();

    $array_count = count($array);

    if ($array_count > 1) {
        for ($i = 0; $i < $array_count; $i++) {
            $tempArray = $array;
            $list[$i] = "";
            unset($tempArray[$i]);
            foreach ($tempArray as $value) {
                $list[$i] = $list[$i] . $value . " ";
            }
            $list[$i] = rtrim($list[$i]);
        }
    } else {
        $list = $array;
    }

    return $list;
}

function addCommentFromCookie($redirect = 1, $user_id)
{
    if (!empty($_COOKIE['comment'])) {
        $ci = &get_instance();
        $comment_data = json_decode($_COOKIE['comment']);
        setcookie('comment', null, -1, '/');
        if (isset($comment_data->msg) && !empty($comment_data->msg)) {
            $post_id = intval($comment_data->post_id);
            $insert_data = array(
                'user_id' => $user_id,
                'parent_id' => $comment_data->parentId,
                'reply_to' => $comment_data->reply_to,
                'description' => $comment_data->msg,
                'status' => 'Approved',
                'created' => date('Y-m-d H:i:s')
            );

            if ($comment_data->table == 'post_comments') {
                $insert_data['post_id'] = $post_id;
            } elseif ($comment_data->table == 'gallery_comments') {
                $insert_data['gallery_id'] = $post_id;
            }

            $ci->db->insert($comment_data->table, $insert_data);

            $ci->db->update('posts', ['comment_count' => postCommentsCount($post_id)], ['id' => $post_id]);
        }


        if ($redirect) {
            redirect($comment_data->redirect);
        }

    }
}


function getSegmentByTemplate($template)
{
    $segment = 'news';
    if ($template == 34) {
        $segment = 'article';
    } else if ($template == 35) {
        $segment = 'report';
    } else if ($template == 38) {
        $segment = 'energy';
    } else if ($template == 44) {
        $segment = 'review';
    } else if ($template == 46) {
        $segment = 'tips-and-tricks';
    }

    return $segment;
}

/**
 * sendFacebookFeed
 * @param $title , $post_url
 * @return true false
 */
function sendFacebookFeed($title, $post_url)
{
    $fb = new Facebook\Facebook([
        'app_id' => FB_App_ID,
        'app_secret' => FB_App_Secret,
        'default_graph_version' => FB_App_Version
    ]);

    $linkData = [
        'link' => site_url('news/' . $post_url),
        'message' => $title,
    ];

    try {
        // Returns a `Facebook\FacebookResponse` object        

        $response = $fb->post('/me/feed', $linkData, $_SESSION['fb_access_token']);
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        // echo 'Graph returned an error: ' . $e->getMessage();
        // exit;
        // print_r($e->getMessage());
        return false;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        // echo 'Facebook SDK returned an error: ' . $e->getMessage();
        // exit;
        // print_r($e->getMessage());
        return false;
    }

    // $graphNode = $response->getGraphNode();

    // return $graphNode['id'];

    return true;
}

/**
 * sendTwitterFeed
 * @param $title , $post_url
 * @return true false
 */
function sendTwitterFeed($title, $post_url)
{
    if (!config_item('is_production')) {
        return false;
    }
    $CONSUMER_KEY = TW_CONSUMER_KEY;
    $CONSUMER_SECRET = TW_CONSUMER_SECRET;
    $access_token = TW_Access_Token;
    $access_token_secret = TW_Access_Token_Secret;

    $connection = new Abraham\TwitterOAuth\TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $access_token, $access_token_secret);
    $content = $connection->get("account/verify_credentials");

    $statues = $connection->post("statuses/update", ["status" => $title . ' ' . site_url('news/' . $post_url)]);

    if ($connection->getLastHttpCode() == 200) {
        return true;
    } else {
        return false;
    }
}


