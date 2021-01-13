<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['admin']                 = 'dashboard';
$route['my-account']    = 'my_account/myAccount';
$route['adb-login']    = 'my_account/admin_login';

// my account route
$route['my-account']    = 'my_account/myAccount';
$route['my-account/profile']    = 'my_account/profile';
$route['my-account/profile/update'] = 'my_account/profile_update';
$route['my-account/profile/password'] = 'my_account/password';
$route['my-account/profile/update_password'] = 'my_account/update_password';

$route['facebook-login']    = 'auth/facebook_login';
$route['google-login']    = 'auth/google_login';
$route['twitter-login']    = 'auth/twitter_login';
//$route['admin/login']           = 'auth/login_form';
$route['auth/forget-password']           = 'my_account/forget_password_form';
$route['admin/logout']          = 'auth/logout';

$route['translate_uri_dashes']  = FALSE;


define('ModuleRoutePrefix', APPPATH . '/modules/');
define('ModuleRouteSuffix', '/config/routes.php');


require_once ( ModuleRoutePrefix . 'posts' . ModuleRouteSuffix);

require_once ( ModuleRoutePrefix . 'users' . ModuleRouteSuffix);

require_once ( ModuleRoutePrefix . 'module' . ModuleRouteSuffix);
require_once ( ModuleRoutePrefix . 'profile' . ModuleRouteSuffix);




$route['test'] = "Sitemap/index";
$route['sitemap\.xml'] = "Sitemap/index";
$route['sitemap/(:any)'] = "Sitemap/sitemapView";
$route['rss/(:any)/feed.xml'] = "Sitemap/rss_feed/$1";

include_once 'api_routes.php';
