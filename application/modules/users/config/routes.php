<?php

/* Users Management [ Admin, Editor, Vendor, Customer etc everyone ] */
$route['admin/users']                   = 'users';

$route['admin/users/profile/(:num)']    = 'users/profile/$1';
$route['admin/users/create']            = 'users/create';
$route['admin/users/create_action']     = 'users/create_action';
$route['admin/users/mails/(:num)']      = 'users/mails/$1';
$route['admin/users/update/(:num)']     = 'users/update/$1';
$route['admin/users/update_action']     = 'users/update_action';
$route['admin/users/delete/(:num)']     = 'users/delete/$1';
$route['admin/users/confirm_delete/(:num)']     = 'users/confirm_delete/$1';

$route['admin/users/change_vendor_type']     = 'users/change_vendor_type';
$route['admin/users/vendor_update_status']     = 'users/vendor_update_status';
$route['admin/users/user_update_status']     = 'users/user_update_status';

/* Roles Controller */
$route['admin/users/roles']         = 'users/roles';
$route['admin/users/roles/create']  = 'users/roles/create';
$route['admin/users/roles/rename']  = 'users/roles/rename';
$route['admin/users/roles/delete']  = 'users/roles/delete';
$route['admin/users/roles/update']  = 'users/roles/update';
$route['admin/users/roles/getAcl']  = 'users/roles/getAcl';
$route['admin/users/countUser']     = 'users/countUser';


$route['admin/role_permissions']          = 'role_permissions';
$route['admin/users/roles/update_acl']    = 'users/roles/update_acl';
$route['admin/users/seller_status']    = 'users/seller_status';
$route['admin/users/vendor_bills']    = 'users/vendor_bills';
$route['admin/users/bill_update/(:num)']     = 'users/bill_update/$1';

$route['admin/users/force_logout/(:num)']     = 'users/force_logout/$1';