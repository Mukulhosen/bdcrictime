<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Common API

$route['api/latest-news']    = 'api/Common_api/latest_news';

// Auth_api
$route['api/sign-in']    = 'api/Auth_api/login';
$route['api/profile']    = 'api/Auth_api/profile';
$route['api/sign-up']    = 'api/Auth_api/sign_up';
$route['api/sign-out']    = 'api/Auth_api/logout';
$route['api/user-delete']    = 'api/Auth_api/user_delete';
$route['api/role-list']    = 'api/Auth_api/role_list';
$route['api/social-login']    = 'api/Auth_api/social_login';
//$route['api/forget-password']    = 'api/Auth_api/forgot_pass';
$route['api/profile-update']    = 'api/Auth_api/profile_update';
$route['api/change-password']    = 'api/Auth_api/change_password';
$route['api/profile-picture-change']    = 'api/Auth_api/profile_picture_change';
//$route['api/reset-password-action']    = 'api/Auth_api/reset_password_action';
//$route['api/verification-email-action']    = 'api/Auth_api/confirm_email_code';
//$route['api/resend-email-verification-code']    = 'api/Auth_api/resend_email';

// Post_api
$route['api/tag-post']    = 'api/Post_api/tag_post';
$route['api/news-list']    = 'api/Post_api/news_list';
$route['api/news-search']    = 'api/Post_api/news_search';
$route['api/add-comment']    = 'api/Post_api/add_comment';
$route['api/category-list']    = 'api/Post_api/category_list';
$route['api/news-details/(:any)']    = 'api/Post_api/news_details/$1';
$route['api/subcategory-list/(:num)']    = 'api/Post_api/sub_cate_with_child/$1';
$route['api/child-category-list/(:num)']    = 'api/Post_api/getSubChild/$1';
$route['api/auther-post/(:any)']    = 'api/Post_api/auther_post/$1';
$route['api/selected-home-page-news']    = 'api/Post_api/home_page_news';
$route['api/popular_news']    = 'api/Post_api/popular_news';




// micro service API
//$route['api/schedule-post-update']    = 'api/Post_api/schedule_post_update';
//$route['api/compnay-news-alert-info']    = 'api/Post_api/company_news_alert_info';


// Video API
//$route['api/video-category']    = 'api/Video_api/video_category';




//Test Push
$route['api/test-push']    = 'api/Common_api/test_push';
