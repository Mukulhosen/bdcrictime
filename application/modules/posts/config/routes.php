<?php

$route['admin/posts/new_post'] = 'posts/new_post';

$route['admin/posts/fb-callback'] = 'posts/fbCallback';
$route['admin/posts'] = 'posts';
$route['admin/posts/create_action_post'] = 'posts/create_action_post';
$route['admin/posts/create_form_validation'] = 'posts/create_form_validation';
$route['admin/posts/update_action_post'] = 'posts/update_action_post';
$route['admin/posts/update_post/(:any)'] = 'posts/update_post/$1';
$route['admin/posts/post_delete/(:num)'] = 'posts/post_delete/$1';
$route['admin/posts/comments/(:num)'] = 'posts/post_comments/$1';
$route['admin/posts/comments/reply/(:num)'] = 'posts/post_comment_replies/$1';
$route['admin/posts/update_reply/(:num)'] = 'posts/update_post_comment/$1';
$route['admin/posts/update_action_post_comments'] = 'posts/update_action_post_comments';
$route['admin/posts/update_comment/(:num)'] = 'posts/update_post_comment/$1';
$route['admin/posts/delete_comment/(:num)'] = 'posts/delete_post_comment/$1';

$route['admin/posts/cronjob'] = 'posts/post_cron';
$route['admin/posts/scrapping'] = 'posts/scrapping';
$route['admin/posts/scrapping_bbc'] = 'posts/scrapping_bbc';

$route['admin/posts/category']                  = 'posts/category';
$route['admin/posts/category/create']           = 'posts/category/create';
$route['admin/posts/category/update/(:num)']    = 'posts/category/update/$1';
$route['admin/posts/category/read/(:num)']      = 'posts/category/read/$1';
$route['admin/posts/category/delete/(:num)']    = 'posts/category/delete/$1';
$route['admin/posts/category/create_action']    = 'posts/category/create_action';
$route['admin/posts/category/update_action']    = 'posts/category/update_action';
$route['admin/posts/category/delete_action/(:num)']    = 'posts/category/delete_action/$1';
$route['admin/posts/category/category_by_parent_id']    = 'posts/category/category_by_parent_id';
$route['admin/posts/category/posts_category_by_parent_id']    = 'posts/category/posts_category_by_parent_id';
$route['admin/posts/category/child_category_by_sub_category_id']    = 'posts/category/child_category_by_sub_category_id';
$route['admin/posts/category/posts_category_by_parent_id_from_tech']    = 'posts/category/posts_category_by_parent_id_from_tech';

$route['admin/posts/update_status']   = 'posts/update_status';
$route['admin/posts/post_show_status_update']   = 'posts/post_show_status_update';


$route['admin/posts/cropping/scrapping']  = 'posts/cropping/scrapping';

$route['admin/posts/cropping']  = 'posts/cropping';
$route['admin/posts/cropping/category_by_parent_id']    = 'posts/cropping/category_by_parent_id';
$route['admin/posts/cropping/posts_category_by_parent_id']    = 'posts/cropping/posts_category_by_parent_id';


$route['admin/posts/tags']                  = 'posts/tags';
$route['admin/posts/tags/create']           = 'posts/tags/create';
$route['admin/posts/tags/update/(:num)']    = 'posts/tags/update/$1';
$route['admin/posts/tags/read/(:num)']      = 'posts/tags/read/$1';
$route['admin/posts/tags/delete/(:num)']    = 'posts/tags/delete/$1';
$route['admin/posts/tags/create_action']    = 'posts/tags/create_action';
$route['admin/posts/tags/update_action']    = 'posts/tags/update_action';
$route['admin/posts/tags/delete_action/(:num)']    = 'posts/tags/delete_action/$1';
$route['admin/get-sub-categories/(:num)']    = 'posts/category/get_sub_categories/$1';


$route['admin/posts/states']                  = 'posts/states';
$route['admin/posts/states/create']           = 'posts/states/create';
$route['admin/posts/states/update/(:num)']    = 'posts/states/update/$1';
$route['admin/posts/states/create_action']    = 'posts/states/create_action';
$route['admin/posts/states/update_action']    = 'posts/states/update_action';
$route['admin/posts/states/delete_action/(:num)']    = 'posts/states/delete_action/$1';

$route['admin/posts/sports']                  = 'posts/sports';

$route['admin/posts/sports/league/(:num)']    = 'posts/sports/league/$1';
$route['admin/posts/sports/create_league/(:num)']           = 'posts/sports/create_league/$1';
$route['admin/posts/sports/create_league_action']    = 'posts/sports/create_league_action';
$route['admin/posts/sports/update_league/(:num)']    = 'posts/sports/update_league/$1';
$route['admin/posts/sports/update_league_action']    = 'posts/sports/update_league_action';
$route['admin/posts/sports/league_delete/(:num)']    = 'posts/sports/league_delete/$1';

$route['admin/posts/sports/league_teams/(:num)']    = 'posts/sports/league_teams/$1';
$route['admin/posts/sports/create_team/(:num)']           = 'posts/sports/create_team/$1';
$route['admin/posts/sports/create_team_action']    = 'posts/sports/create_team_action';
$route['admin/posts/sports/update_team/(:num)']    = 'posts/sports/update_team/$1';
$route['admin/posts/sports/update_team_action']    = 'posts/sports/update_team_action';
$route['admin/posts/sports/team_delete/(:num)']    = 'posts/sports/team_delete/$1';

$route['admin/posts/sports/update_tennis_team/(:num)']    = 'posts/sports/update_tennis_team/$1';
$route['admin/posts/sports/update_tennis_team_action']    = 'posts/sports/update_tennis_team_action';
$route['admin/posts/sports/tennis_team_delete/(:num)']    = 'posts/sports/tennis_team_delete/$1';

$route['admin/posts/sports/update_formula1_team/(:num)']    = 'posts/sports/update_formula1_team/$1';
$route['admin/posts/sports/update_formula1_team_action']    = 'posts/sports/update_formula1_team_action';
$route['admin/posts/sports/formula1_team_delete/(:num)']    = 'posts/sports/formula1_team_delete/$1';
$route['admin/posts/sports/formula1-drivers/(:num)']    = 'posts/sports/formula1_drivers/$1';
$route['admin/posts/sports/create_formula1_driver/(:num)']    = 'posts/sports/create_formula1_driver/$1';
$route['admin/posts/sports/create_formula1_driver_action']    = 'posts/sports/create_formula1_driver_action';
$route['admin/posts/sports/update_formula1_driver/(:num)']    = 'posts/sports/update_formula1_driver/$1';
$route['admin/posts/sports/update_formula1_driver_action']    = 'posts/sports/update_formula1_driver_action';
$route['admin/posts/sports/formula1_driver_delete/(:num)']    = 'posts/sports/formula1_driver_delete/$1';

$route['admin/posts/sports/update_basketball_team/(:num)']    = 'posts/sports/update_basketball_team/$1';
$route['admin/posts/sports/update_basketball_team_action']    = 'posts/sports/update_basketball_team_action';
$route['admin/posts/sports/basketball_team_delete/(:num)']    = 'posts/sports/basketball_team_delete/$1';

$route['admin/posts/sports/update_boxing_team/(:num)']    = 'posts/sports/update_boxing_team/$1';
$route['admin/posts/sports/update_boxing_team_action']    = 'posts/sports/update_boxing_team_action';
$route['admin/posts/sports/boxing_team_delete/(:num)']    = 'posts/sports/boxing_team_delete/$1';

$route['admin/posts/sports/league_features/(:num)']    = 'posts/sports/league_features/$1';
$route['admin/posts/sports/create_feature/(:num)']           = 'posts/sports/create_feature/$1';
$route['admin/posts/sports/create_feature_action']    = 'posts/sports/create_feature_action';
$route['admin/posts/sports/update_feature/(:num)']    = 'posts/sports/update_feature/$1';
$route['admin/posts/sports/update_feature_action']    = 'posts/sports/update_feature_action';
$route['admin/posts/sports/feature_delete/(:num)']    = 'posts/sports/feature_delete/$1';


$route['admin/posts/sports/update_tennis_feature/(:num)']    = 'posts/sports/update_tennis_feature/$1';
$route['admin/posts/sports/update_tennis_feature_action']    = 'posts/sports/update_tennis_feature_action';
$route['admin/posts/sports/tennis_feature_delete/(:num)']    = 'posts/sports/tennis_feature_delete/$1';


$route['admin/posts/sports/update_formula1_feature/(:num)']    = 'posts/sports/update_formula1_feature/$1';
$route['admin/posts/sports/update_formula1_feature_action']    = 'posts/sports/update_formula1_feature_action';
$route['admin/posts/sports/formula1_feature_delete/(:num)']    = 'posts/sports/formula1_feature_delete/$1';


$route['admin/posts/sports/update_basketball_feature/(:num)']    = 'posts/sports/update_basketball_feature/$1';
$route['admin/posts/sports/update_basketball_feature_action']    = 'posts/sports/update_basketball_feature_action';
$route['admin/posts/sports/basketball_feature_delete/(:num)']    = 'posts/sports/basketball_feature_delete/$1';

$route['admin/posts/sports/update_boxing_feature/(:num)']    = 'posts/sports/update_boxing_feature/$1';
$route['admin/posts/sports/update_boxing_feature_action']    = 'posts/sports/update_boxing_feature_action';
$route['admin/posts/sports/boxing_feature_delete/(:num)']    = 'posts/sports/boxing_feature_delete/$1';





$route['admin/posts/sports/national-team/(:num)']    = 'posts/sports/national_team/$1';
$route['admin/posts/sports/create-country/(:num)']    = 'posts/sports/create_country/$1';
$route['admin/posts/sports/create-country-action']    = 'posts/sports/create_country_action';
$route['admin/posts/sports/update-country/(:num)']    = 'posts/sports/update_country/$1';
$route['admin/posts/sports/update-country-action']    = 'posts/sports/update_country_action';
$route['admin/posts/sports/country-delete/(:num)']    = 'posts/sports/country_delete/$1';

$route['admin/posts/sports/country-teams/(:num)']    = 'posts/sports/country_teams/$1';
$route['admin/posts/sports/create-country-team/(:num)']           = 'posts/sports/create_country_team/$1';
$route['admin/posts/sports/create-country-team-action']    = 'posts/sports/create_country_team_action';
$route['admin/posts/sports/update-country-team/(:num)']    = 'posts/sports/update_country_team/$1';
$route['admin/posts/sports/update-country-team-action']    = 'posts/sports/update_country_team_action';
$route['admin/posts/sports/country-team-delete/(:num)']    = 'posts/sports/country_team_delete/$1';


$route['admin/posts/sports/make-default-league/(:num)']    = 'posts/sports/make_default_league/$1';

$route['admin/posts/approve_post/(:num)'] = 'posts/posts/approve_post/$1';

