<?php

use Carbon\Carbon;

defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Mukul Hosen
 * Date : 2016-10-13
 */

class Post_api extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('api_helper');
    }

    function category_list()
    {
        if ($this->input->method() != 'get') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => []
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));

        $data = $this->db->query("SELECT post_category.name, post_category.slug, post_category.id FROM post_category")->result();

        // Finally send the final result
        return apiResponse([
            'status' => true,
            'message' => "",
            'data' => $data
        ]);
    }

    function sub_cate_with_child($main_cate)
    {
        if ($this->input->method() != 'get') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));

        if (empty($main_cate)) return (apiResponse([
            'status' => false,
            'message' => 'All Field Is Required',
            'data' => new stdClass()
        ]));
        $rowData = $this->db->query("
                SELECT
                    sub_cate.`name` AS sub_cate_name, 
                    sub_cate.slug AS sub_cate_slug, 
                    sub_cate.id AS sub_cate_id, 
                    GROUP_CONCAT(child_cate.`name` SEPARATOR '|') as child_cat_name, 
                    GROUP_CONCAT(child_cate.id SEPARATOR '|') as child_cat_id, 
                    GROUP_CONCAT(child_cate.slug SEPARATOR '|') as child_cat_slug
                FROM
                    post_category AS sub_cate
                    LEFT JOIN
                    post_category AS child_cate
                    ON 
                        sub_cate.id = child_cate.sub_category_id
                WHERE
                    sub_cate.parent_id = '$main_cate' AND 
                    sub_cate.sub_category_id = 0
                GROUP BY
                    sub_cate.id
                ORDER BY
                    sub_cate.id ASC
            ")->result();
        $data = [];
        foreach ($rowData as $key => $d) {
            $data[$key] = [
                'sub_cate_id' => $d->sub_cate_id,
                'sub_cate_name' => $d->sub_cate_name,
                'sub_cate_slug' => $d->sub_cate_slug,
            ];
            $child_cat_id = explode('|', $d->child_cat_id);
            $child_cat_name = explode('|', $d->child_cat_name);
            $child_cat_slug = explode('|', $d->child_cat_slug);
            $data[$key]['child'] = [];

            if (!empty($child_cat_id[0])) {
                for ($i = 0; $i < count($child_cat_id); $i++) {
                    $data[$key]['child'][$i] = [
                        'id' => $child_cat_id[$i],
                        'name' => $child_cat_name[$i],
                        'slug' => $child_cat_slug[$i]
                    ];
                }
            }

        }
        if (!empty($data)) {
            return apiResponse([
                'status' => true,
                'message' => "",
                'data' => $data
            ]);
        }
        return apiResponse([
            'status' => true,
            'message' => "No Sub Category Found",
            'data' => []
        ]);
    }


    function getSubChild($id)
    {
        if ($this->input->method() != 'get') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));


        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));

        $this->db->select("name, slug, id");
        $this->db->where('sub_category_id', $id);
        $data = $this->db->get('post_category')->result();

        if (!empty($data)) {
            return apiResponse([
                'status' => true,
                'message' => "",
                'data' => $data
            ]);
        }
        return apiResponse([
            'status' => false,
            'message' => "No Child Category Found",
            'data' => []
        ]);
    }

    function news_list()
    {
        if ($this->input->method() != 'get') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));

        $category_id = $this->input->get('category');


        $sub_category_id = $this->input->get('sub_category');
        $child_category_id = $this->input->get('child_category');
        $limit = !empty($this->input->get('limit')) ? $this->input->get('limit') : 10;
        $offset = !empty($this->input->get('offset')) ? $this->input->get('offset') : 0;

        $this->db->select("main_category.name as category_name, main_category.id as category_id, main_category.slug as category_slug ,posts.title, posts.description, posts.id,posts.post_url, posts.created, CONCAT(users.first_name, ' ', users.last_name) as author_name");
        $this->db->select("IF(posts.post_image IS null OR posts.post_image = '' , '', CONCAT('" . base_url() . "',posts.post_image)) as post_image");
        $this->db->from('posts');
        $this->db->join('users', "posts.user_id = users.id", 'LEFT');
        if (!empty($child_category_id)) {
            $this->db->join('post_category AS child_category', "posts.child_category_id = child_category.id", 'INNER');
            $this->db->where('child_category.id', $child_category_id);
        }
        if (!empty($sub_category_id)) {
            $this->db->join('post_category AS sub_category', "posts.sub_category_id = sub_category.id", 'INNER');
            $this->db->where('sub_category.id', $sub_category_id);
        }
        if (!empty($category_id)) {
            $this->db->where('main_category.id', $category_id);
        }
		$this->db->join('post_category AS main_category', "posts.category_id = main_category.id", 'INNER');
        $this->db->where_in('posts.post_show', ['Frontend']);
        $this->db->where('posts.status', 'Publish');

        $this->db->order_by('posts.created', 'DESC');
        $this->db->limit($limit, $offset);

        $data['news'] = $this->db->get()->result();
        if (empty($offset)){
            $data['trending'] = $this->_mostRead($category_id, $sub_category_id, $child_category_id);
        }
        // Finally send the final result
        return apiResponse([
            'status' => true,
            'message' => "",
            'data' => $data
        ]);

    }

    public function news_details($slug)
    {
        if ($this->input->method() != 'get') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => []
        ], 405));


        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));

        $data = [];

        $post = $this->db->select('posts.*')
            ->get_where('posts', ['post_url' => $slug])->row();

        if (empty($post)) return apiResponse([
            'status' => false,
            'message' => "No data Found",
            'data' => []
        ]);
        if ($post->post_show != 'Frontend') {
            if (!in_array($this->role_id, [1, 2, 3, 4, 5, 6, 15])) return apiResponse([
                'status' => false,
                'message' => "Access Forbidden",
                'data' => []
            ]);
        }

        if ($post->status == 'Trash') {
            if (!in_array($this->role_id, [2, 3, 4])) return apiResponse([
                'status' => false,
                'message' => "Access Forbidden",
                'data' => []
            ]);
        } elseif ($post->status == 'Draft') {
            if ($this->user_id != $post->user_id) return apiResponse([
                'status' => false,
                'message' => "Access Forbidden",
                'data' => []
            ]);
        } elseif ($post->status == 'Pending' || $post->status == 'Rejected') {
            if (!in_array($this->role_id, [1, 2, 3, 4, 5, 15])) return apiResponse([
                'status' => false,
                'message' => "Access Forbidden",
                'data' => []
            ]);
        }

        $post->post_image = !empty($post->post_image) ? base_url() . $post->post_image : '';
        $categoriesName = '';
        $main_category = '';
        $sub_category = '';
        $child_category = '';
        $template = 0;
        if ($post->category_id) {
            $main_category = $this->db->select('*')
                ->get_where('post_category', array('id' => $post->category_id))
                ->row();
            if (!empty($main_category)) {
                $categoriesName .= $main_category->name;
                $template = $main_category->template_design;
            }
        }
        if ($post->sub_category_id) {
            $sub_category = $this->db->select('*')
                ->get_where('post_category', array('id' => $post->sub_category_id))
                ->row();
            if (!empty($sub_category)) {
                $categoriesName .= " - " . $sub_category->name;
                $template = $sub_category->template_design;
            }
        }
        if ($post->child_category_id) {
            $child_category = $this->db->select('*')
                ->get_where('post_category', array('id' => $post->child_category_id))
                ->row();
            if (!empty($child_category)) {
                $categoriesName .= " - " . $child_category->name;
                $template = $child_category->template_design;
            }
        }
        $data['post'] = $post;

        $this->db->where('pt.post_id', $post->id);
        $this->db->join('tags as t', 't.id = pt.tag_id');
        $this->db->from('post_tags as pt');
        $data['tags'] = $this->db->get()->result_array();

        $this->db->set('hit_count', $post->hit_count + 1);
        $this->db->where('id', $post->id);
        $this->db->update('posts');

//        $data['main_category'] = $main_category;
//        $data['sub_category'] = $sub_category;
//        $data['child_category'] = $child_category;
        $post->category_name = $categoriesName;

//        $CI = &get_instance();
//        $CI->db->from('posts');
//        $CI->db->select('id, post_image, post_url, title, modified, created');
//        $this->db->select("IF(posts.post_image IS null OR posts.post_image = '' , '', CONCAT('" . base_url() . "',posts.post_image)) as post_image");
//        $CI->db->where_not_in('id', $post->id);
//        $CI->db->where_in('post_show', ['Frontend']);
//        $CI->db->where('status', 'Publish');
//        if ($post->category_id) $CI->db->where('category_id', $post->category_id);
//        if ($post->sub_category_id) $CI->db->where('sub_category_id', $post->sub_category_id);
//        $CI->db->order_by('created', 'DESC');
//        $CI->db->limit(5);
//        $data['related_post'] = $CI->db->get()->result();


        $data['all_comment'] = [];
        $comments = getAllComments($post->id);
        $child_comment = $comments;
        usort($child_comment, function ($a, $b){return $a['id'] - $b['id'];});
        $parent_count = 0;
        foreach ($comments as $k => $comment){
            if(empty($comment['parent_id'])){
                $data['all_comment'][$parent_count] = $comment;
                $data['all_comment'][$parent_count]['profile_photo'] = !empty($comment['profile_photo']) ? ($comment['oauth_provider'] == 'web' ? base_url().'uploads/users_profile/'.$comment['profile_photo'] : $comment['profile_photo']) : '';
                $data['all_comment'][$parent_count]['child'] = [];
                $child_count = 0;
                foreach ($child_comment as $key => $cc){
                    if ($cc['parent_id'] == $comment['id']){
                        $data['all_comment'][$parent_count]['child'][$child_count] = $cc;
                        $data['all_comment'][$parent_count]['child'][$child_count]['profile_photo'] = !empty($cc['profile_photo']) ? ($cc['oauth_provider'] == 'web' ? base_url().'uploads/users_profile/'.$cc['profile_photo'] : $cc['profile_photo']) : '';
                        $child_count++;
                    }
                }
                $parent_count++;
            }

        }

        $author = getAuthorDetails($post->user_id);
        if (!empty($author)){
            $post->author_name = $author->first_name . ' ' . $author->last_name;
            $post->auther_slug = $author->profile_slug;
            $post->author_image = empty($author->profile_photo) ? '' : $author->oauth_provider == 'web' ? base_url().'uploads/users_profile/'. $author->profile_photo : $author->profile_photo;
            $post->author_facebook_link = $author->facebook_link;
            $post->author_twitter_link = $author->twitter_link;
            $post->author_instagram_link = $author->instagram_link;
            $post->author_biography = $author->biography;
        } else {
            $post->author_name = 'Unknown user';
            $post->auther_slug = '';
            $post->author_image = '';
            $post->author_facebook_link = '';
            $post->author_twitter_link = '';
            $post->author_instagram_link = '';
            $post->author_biography = '';
        }

        return apiResponse([
            'status' => true,
            'message' => "",
            'data' => $data
        ]);

//            if ($template == 1) {
//                $this->viewFrontContent('frontend/template/news-details', $data);
//            } elseif (in_array($template, [2, 11, 21, 22, 23, 24, 25, 26])) {
//                $this->db->select('*');
//                $this->db->where('parent_id', $main_category->id)->where('sub_category_id', 0);
//                $this->db->from('post_category');
//                $data['sub_category_menu'] = $this->db->get()->result();
//                $data['next'] = $this->_next_prev_latest($post, 'next');
//                $data['prev'] = $this->_next_prev_latest($post, 'prev');
//                $data['latest'] = $this->_next_prev_latest($post, 'latest', 4);
//                //print_r($data['prev']);die;
//                $this->viewFrontContent('frontend/template/video/video_details', $data);
//            } else {
//                $this->viewFrontContent('frontend/template/news-details', $data);
//            }

    }


    function tag_post()
    {
        if ($this->input->method() != 'get') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => []
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));

        $slug = $this->input->get('slug');
        $limit = $this->input->get('limit');
        $offset = $this->input->get('offset');

        $tag_id = getTagIdBySlug($slug);
        if (empty($tag_id)) {
            $tag_id = -1;
        }


        $this->db->select("c.name as category_name, c.id as category_id, c.slug as category_slug , p.title, p.post_url, p.modified, p.created, p.id");
        $this->db->select("CONCAT(u.first_name, ' ', u.last_name) AS author_name");
        $this->db->select("IF(p.post_image IS null OR p.post_image = '' , '', CONCAT('" . base_url() . "',p.post_image)) as post_image");
        $this->db->where_in('p.post_show', ['Frontend']);
        $this->db->where('p.status', 'Publish');
        $this->db->where('pt.tag_id', $tag_id);
        $this->db->from('post_tags as pt');
        $this->db->join('posts as p', 'p.id = pt.post_id');
        $this->db->join('post_category as c', 'p.category_id = c.id', 'INNER');
        $this->db->join('users as u', 'p.user_id = u.id', 'LEFT');
        $this->db->order_by('created', 'DESC');
        $this->db->limit($limit, $offset);
        $tag_posts = $this->db->get()->result();
        $pin_posts = [];


        if (empty($offset)) {
            $pinPost = getTagPinPostsBySlug($slug);
            if (!empty($pinPost)) {
                if (!empty($pinPost->post_1)) {
                    $pin_posts[] = [
                        'category_name' => $pinPost->category_name_1,
                        'title' => $pinPost->title_1,
                        'post_url' => $pinPost->post_url_1,
                        'modified' => $pinPost->modified_1,
                        'created' => $pinPost->created_1,
                        'description' => $pinPost->description_1,
                        'author_name' => $pinPost->author_name_1,
                        'post_image' => !empty($pinPost->post_image_1) ? base_url() . $pinPost->post_image_1 : ''
                    ];
                }
                if (!empty($pinPost->post_2)) {
                    $pin_posts[] = [
                        'category_name' => $pinPost->category_name_2,
                        'title' => $pinPost->title_2,
                        'post_url' => $pinPost->post_url_2,
                        'modified' => $pinPost->modified_2,
                        'created' => $pinPost->created_2,
                        'description' => $pinPost->description_2,
                        'author_name' => $pinPost->author_name_2,
                        'post_image' => !empty($pinPost->post_image_2) ? base_url() . $pinPost->post_image_2 : ''
                    ];
                }
            }
        }


        // Finally send the final result
        return apiResponse([
            'status' => true,
            'message' => "",
            'data' => [
                'pin_posts' => $pin_posts,
                'tag_posts' => $tag_posts
            ]
        ]);

    }

    public function news_search()
    {
        if ($this->input->method() != 'get') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));

        $limit = empty($this->input->get('limit')) ? 0 : $this->input->get('limit');
        $offset = empty($this->input->get('offset')) ? 0 : $this->input->get('offset');
        $type = $this->input->get('type');
        $search_text = $this->input->get('search', TRUE);


        $cleanStr = trim(preg_replace('/\s\s+/', ' ', str_replace("'", "", str_replace("\n", " ", $search_text))));
        $searchString = urlencode($cleanStr);
        $queryStr = "(";
        $sq = $this->db->escape('[[:<:]]' . strtolower(urldecode($searchString)) . '[[:>:]]');
        $queryStr = $queryStr . "(LOWER(p.title) REGEXP $sq AND LOWER(p.seo_title) REGEXP $sq AND LOWER(p.seo_keyword) REGEXP $sq)";
        $search = explode('+', $searchString);
        $searchCombinations = $this->getSearchCombinations($search);
        foreach ($searchCombinations as $string) {
            if (!empty($string)) {
                $sq = $this->db->escape('[[:<:]]' . strtolower($string) . '[[:>:]]');
                $queryStr = $queryStr . " OR (LOWER(p.title) REGEXP $sq AND LOWER(p.seo_title) REGEXP $sq AND LOWER(p.seo_keyword) REGEXP $sq)";
                $explodedStrings = explode(" ", $string);
                $queryStr = $queryStr . " OR (";
                foreach ($explodedStrings as $explodedString) {
                    $sq = $this->db->escape('[[:<:]]' . strtolower($explodedString) . '[[:>:]]');
                    $queryStr = $queryStr . "LOWER(p.title) REGEXP $sq AND ";
                }
                $queryStr = substr($queryStr, 0, -4);
                $queryStr = $queryStr . ")";
            }
        }
        $queryStr = $queryStr . ")";
        if ($type == 'autocomplete'){
            $this->db->select("p.title, p.post_url");
        } else {
            $this->db->select("c.name as category_name ,c.id as category_id, c.slug as category_slug , p.title, p.post_url ,  p.modified, p.created, p.id");
            $this->db->select("CONCAT(u.first_name, ' ', u.last_name) AS author_name");
            $this->db->select("IF(p.post_image IS null OR p.post_image = '' , '', CONCAT('" . base_url() . "',p.post_image)) as post_image");
            $this->db->join('users as u', 'u.id = p.user_id', 'LEFT');
            $this->db->join('post_category as c', 'c.id = p.category_id');
        }
        $this->db->where($queryStr, NULL, FALSE);
        $this->db->from('posts as p');
        $this->db->where('p.post_show', 'Frontend');
        $this->db->where('p.status', 'Publish');
        $this->db->order_by("CASE WHEN p.title = '" . urldecode($searchString) . "'THEN 0  
              WHEN p.title LIKE '" . urldecode($searchString) . "%' THEN 1  
              WHEN p.title LIKE '%" . urldecode($searchString) . "%' THEN 2  
              WHEN p.title LIKE '%" . urldecode($searchString) . "' THEN 3  
              ELSE 4
         END, p.created DESC");
        $this->db->limit($limit, $offset);
        $posts = $this->db->get()->result();
        // Finally send the final result
        return apiResponse([
            'status' => true,
            'message' => "",
            'data' => $posts
        ]);
    }

    private function getSearchCombinations($array)
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

    public  function add_comment(){
        if ($this->input->method() != 'post') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));

        $user = get_user_by_token(@$this->input->request_headers()['token']);

        if (empty($user)) return apiResponse([
            'status' => false,
            'message' => "Invalid User",
            'data' => new stdClass()
        ]);

        $post_id = intval($this->input->post('post_id', TRUE));
        $insert_data = array(
            'user_id' => $user->id,
            'post_id' => $post_id,
            'parent_id' => $this->input->post('parent_id', TRUE),
            'description' => $this->input->post('comment', TRUE),
            'reply_to' => $this->input->post('reply_to', TRUE),
            'status' => 'Approved',
            'created' => date('Y-m-d H:i:s')
        );

        $this->db->insert('post_comments', $insert_data);
        $comment_id = $this->db->insert_id();
        $this->db->update('posts', ['comment_count' => postCommentsCount($post_id)], ['id' => $post_id]);

        $comments = getAllComments($post_id);
        $child_comment = $comments;
        usort($child_comment, function ($a, $b){return $a['id'] - $b['id'];});
        $parent_count = 0;
        $data = [];
        foreach ($comments as $k => $comment){
            if(empty($comment['parent_id'])){
                $data[$parent_count] = $comment;
                $data[$parent_count]['profile_photo'] = !empty($comment['profile_photo']) ? ($comment['oauth_provider'] == 'web' ? base_url().'uploads/users_profile/'.$comment['profile_photo'] : $comment['profile_photo']) : '';
                $data[$parent_count]['child'] = [];
                $child_count = 0;
                foreach ($child_comment as $key => $cc){
                    if ($cc['parent_id'] == $comment['id']){
                        $data[$parent_count]['child'][$child_count] = $cc;
                        $data[$parent_count]['child'][$child_count]['profile_photo'] = !empty($cc['profile_photo']) ? ($cc['oauth_provider'] == 'web' ? base_url().'uploads/users_profile/'.$cc['profile_photo'] : $cc['profile_photo']) : '';
                        $child_count++;
                    }
                }
                $parent_count++;
            }

        }


        return apiResponse([
            'status' => true,
            'message' => "",
            'data' => $data
        ]);
    }

    private function _mostRead($category_id = null, $sub_category = null, $child_category = null,$days = 1, $limit = 6)
    {

        $ci = &get_instance();
        $ci->db->select("c.name as category_name, c.id as category_id, c.slug as category_slug , p.title, p.post_url, p.modified, p.created, p.id");
        $ci->db->select("CONCAT(u.first_name, ' ', u.last_name) AS author_name");
        $ci->db->select("IF(p.post_image IS null OR p.post_image = '' , '', CONCAT('" . base_url() . "',p.post_image)) as post_image");
        //$ci->db->select("CONCAT(u.first_name, ' ', u.last_name) AS name");
        $ci->db->from('posts as p');
        if (!empty($category_id)){
            $ci->db->where('p.category_id', $category_id);
        }
        if (!empty($sub_category)){
            $ci->db->where('p.sub_category_id', $sub_category);
        }
        if (!empty($child_category)){
            $ci->db->where('p.child_category_id', $child_category);
        }
        $ci->db->where('p.post_show', 'Frontend');
        $ci->db->where('p.created >=', Carbon::now()->subDays($days)->format('Y-m-d H:i'));
        $ci->db->join('users as u', 'u.id = p.user_id', 'LEFT');
        $ci->db->join('post_category as c', 'c.id = p.category_id', 'LEFT');
        $ci->db->limit($limit, 0);

        return $ci->db->order_by('hit_count', 'DESC')->get()->result();
    }

    public function popular_news(){
		if ($this->input->method() != 'get') return (apiResponse([
			'status' => false,
			'message' => 'Invalid Request',
			'data' => []
		], 405));

		if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
			'status' => false,
			'message' => 'Bad Request',
			'data' => new stdClass()
		], 405));
		$days = $this->input->get('days', TRUE);
		$limit = $this->input->get('limit', TRUE);
		return apiResponse([
			'status' => true,
			'message' => "",
			'data' => $this->_mostRead(null, null, null,$days, $limit)
		]);
	}


	public function home_page_news(){
		if ($this->input->method() != 'get') return (apiResponse([
			'status' => false,
			'message' => 'Invalid Request',
			'data' => []
		], 405));

		if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
			'status' => false,
			'message' => 'Bad Request',
			'data' => new stdClass()
		], 405));

		$limit = empty($this->input->get('limit')) ? 0 : $this->input->get('limit');
		$offset = empty($this->input->get('offset')) ? 0 : $this->input->get('offset');

		return apiResponse([
			'status' => true,
			'message' => "",
			'data' => $this->getPostByHomeCatData(1, $limit, [], $offset)
		]);

	}


	private function getPostByHomeCatData($section_id, $limit = 5, $special_category = [], $offset = 0)
	{
		$ci = &get_instance();
		$ci->db->select("c.name as category_name, c.id as category_id, c.slug as category_slug , p.title, p.post_url, p.modified, p.created, p.id, p.description");
		$ci->db->select("CONCAT(u.first_name, ' ', u.last_name) AS author_name");
		$ci->db->select("IF(p.post_image IS null OR p.post_image = '' , '', CONCAT('" . base_url() . "',p.post_image)) as post_image");
		$ci->db->from('posts as p');
		$ci->db->where('p.home_section_id', $section_id);
		$ci->db->where_in('p.post_show', ['Frontend']);
		if (!empty($special_category)){
			$ci->db->where_in('p.category_id', $special_category);
		}
		$ci->db->where('p.status', 'Publish');
		$ci->db->order_by('p.created', 'DESC');
		$ci->db->join('users as u', 'u.id = p.user_id', 'LEFT');
		$ci->db->join('post_category as c', 'c.id = p.category_id');
		$ci->db->limit($limit, $offset);
		return $ci->db->get()->result();
	}


	public function auther_post($slug = ""){
		if ($this->input->method() != 'get') return (apiResponse([
			'status' => false,
			'message' => 'Invalid Request',
			'data' => []
		], 405));

		if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
			'status' => false,
			'message' => 'Bad Request',
			'data' => []
		], 405));

		$limit = empty($this->input->get('limit')) ? 10 : $this->input->get('limit');
		$offset = empty($this->input->get('offset')) ? 0 : $this->input->get('offset');

		$this->db->select('u.*');
		$this->db->select("CONCAT(u.first_name, ' ', u.last_name) As FullName");
		$this->db->where('u.profile_slug', $slug);
		$this->db->where_in('u.status', ['Active', 'Inactive']);
//        $this->db->where_not_in('u.role_id', [1,2,3]);
		$this->db->from('users as u');
		$this->db->join('roles as r', 'u.role_id = r.id', 'left');
		$user_data = $this->db->get()->row_array();

		$this->db->from('posts');
		$this->db->select('id');
		$this->db->where_in('post_show', ['Frontend']);
		if ($user_data['id']) {
			$this->db->where('user_id', $user_data['id']);
		}
		$this->db->where('status', 'Publish');
		$total = $this->db->count_all_results();

		$this->db->from('posts');
		$this->db->select('id, comment_count, post_url, title, modified, created, description');
		$this->db->select("IF(post_image IS null OR post_image = '' , '', CONCAT('" . base_url() . "',post_image)) as post_image");
		$this->db->where_in('post_show', ['Frontend']);
		if ($user_data['id']) {
			$this->db->where('user_id', $user_data['id']);
		}
		$this->db->where('status', 'Publish');
		$this->db->order_by('id', 'DESC');
		$this->db->limit($limit, $offset);
		$posts = $this->db->get()->result();

		$data = [
			'user_data' => $user_data,
			'posts' => $posts,
			'total' => $total
		];

		return apiResponse([
			'status' => true,
			'message' => "",
			'data' => $data
		]);

	}
}
