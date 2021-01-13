<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//ini_set('memory_limit', '-1');
//error_reporting(0);

$config['base_url'] = 'http://local.bdcrictime.com/';
$config['index_page']               = '';
$config['uri_protocol']             = 'REQUEST_URI';
$config['url_suffix']               = '';
$config['language']                 = 'english';
$config['charset']                  = 'UTF-8';
$config['enable_hooks']             = TRUE;
$config['subclass_prefix']          = 'MY_';
$config['composer_autoload']        = 'vendor/autoload.php';
$config['permitted_uri_chars']      = 'a-z 0-9~%.:_\-';
$config['allow_get_array']          = TRUE;
$config['enable_query_strings']     = FALSE;
$config['controller_trigger']       = 'c';
$config['function_trigger']         = 'm';
$config['directory_trigger']        = 'd';
$config['log_threshold']            = 0; // 1,2,3,4
$config['log_path']                 = '';
$config['log_file_extension']       = '';
$config['log_file_permissions']     = 0644;
$config['log_date_format']          = 'Y-m-d H:i:s';
$config['error_views_path']         = '';
$config['cache_path']               = '';
$config['cache_query_string']       = FALSE;
$config['encryption_key']           = '';
$config['sess_driver']              = 'files';
$config['sess_cookie_name']         = 'ci_session_';
$config['sess_expiration']          = 7200;
$config['sess_save_path']           = NULL;
$config['sess_match_ip']            = FALSE;
$config['sess_time_to_update']      = 7200; //60*60*24*7;
$config['sess_regenerate_destroy']  = FALSE;
$config['cookie_prefix']    = 'fm_';
$config['cookie_domain']    = 'local.bdcrictime.com';
$config['cookie_path']      = '/';
$config['cookie_secure']    = FALSE;
$config['cookie_httponly']  = FALSE;
$config['standardize_newlines'] = FALSE;
$config['global_xss_filtering'] = true;
$config['csrf_protection']      = false;
$config['csrf_token_name']      = '_token';
$config['csrf_cookie_name']     = 'csrf_cookie_name';
$config['csrf_expire']          = 60 * 60; // 1 hour
$config['csrf_regenerate']      = true;
$config['csrf_exclude_uris']    = array();
$config['compress_output']      = FALSE;
$config['time_reference']       = 'local';
$config['rewrite_short_tags']   = FALSE;
$config['proxy_ips']            = '';

$config['encrypt_key']           = 'abcdef';


$config['modules_locations'] = array(
	APPPATH.'modules/' => '../modules/',
);
