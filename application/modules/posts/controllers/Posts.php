<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends Admin_controller {

    function __construct() {
		ini_set('post_max_size','99500M');
		ini_set('upload_max_size','100000M');
		ini_set('memory_limit','128M');
		ini_set('max_execution_time','5000');
        parent::__construct();
        $this->load->model('Posts_model');
        $this->load->model('Posts_Comment_model');
        $this->load->helper('cms');
        $this->load->helper('posts');
//        $this->load->library('Simple_html_dom');
        $this->load->helper('api_helper');
    }

    public function index() {
        $q                = urldecode($this->input->get('q', TRUE));
        $start            = intval($this->input->get('start'));
        $status           = urldecode($this->input->get('status', TRUE));
        $show             = urldecode($this->input->get('show', TRUE));
        $category         = intval($this->input->get('category'));
        $post_type        = ($this->input->get('post_type', TRUE)) ? $this->input->get('post_type', TRUE) : 'my';
        $manage_all_posts = checkPermission('posts/manage_all', $this->role_id);
        $paginator              = build_pagination_url('posts', 'start');
        $config['base_url']     = Backend_URL . $paginator;
        $config['first_url']    = Backend_URL . $paginator;
        $config['per_page']     = 25;
        $config['page_query_string'] = TRUE;

        $config['total_rows'] = $this->Posts_model->total_rows_post($q, $manage_all_posts, $status, $category, $post_type);
        $posts = $this->Posts_model->get_data_for_post($config['per_page'], $start, $q, $manage_all_posts, $status, $category, $post_type);

//        echo $this->db->last_query();

        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data = array(
            'post_data'         => $posts,
            'pagination'        => $this->pagination->create_links(),
            'total_rows'        => $config['total_rows'],
            'start'             => $start,
            'add_permission'    => checkMenuPermission('posts/new_post', $this->role_id),
            'manage_all_posts'  => $manage_all_posts,
            'q'                 => $q,
            'status'            => $status,
            'category'          => $category,
            'post_type'         => $post_type,
            'show'              => $show,
        );
        $this->viewAdminContent('posts/posts/posts', $data);
    }

    public function new_post() {

        $data = array(
            'button' => 'Create',
            'action' => site_url(Backend_URL . 'posts/create_action_post'),
            'id' => set_value('id'),
            'user_id' => set_value('user_id'),
            'category_id' => set_value('category_id'),
            'sub_category_id' => set_value('sub_category_id'),
            'child_category_id' => set_value('child_category_id'),
            'title' => set_value('title'),
            'post_url' => set_value('post_url'),
            'description' => set_value('description'),
            'status' => set_value('status'),
            'vimeo_id' => set_value('vimeo_id'),
            'youtube_json' => set_value('youtube_json'),
            'post_image' => set_value('post_image'),
            'seo_title' => set_value('seo_title'),
            'seo_keyword' => set_value('seo_keyword'),
            'seo_description' => set_value('seo_description'),
            'created' => set_value('created'),
            'modified' => set_value('modified'),
            'reject_note' => set_value('reject_note'),
            'photo_caption' => set_value('photo_caption'),
            'home_section_id' => set_value('home_section_id'),
        );
        $this->viewAdminContent('posts/posts/post_form', $data);
    }

    private function isDuplicateSlug( $slug = '' ){
        return $this->db->get_where('posts', ['post_url' => $slug])->num_rows();
    }


    public function create_action_post() {
        $slug = slugify($this->input->post('post_url', TRUE));
    //    $slug = $this->input->post('post_url', TRUE);
				
        if( $this->isDuplicateSlug( $slug ) ){
            $slug = $slug . rand(0000,9999);
        }

        $photo      = uploadPostPhoto($_FILES['post_image']);


        if($this->role_id == 6 || $this->role_id == 5){ // Individual
            $post_show = ['post_show' => 'Journalist'];
        } elseif($this->role_id == 7){ // Journalist or Guest
            $post_show = ['post_show' => 'Editor'];
        } else {
            $post_show = ['post_show' => 'Frontend'];
        }

        $status = $this->input->post('status', TRUE);


        $data1 = array(
            'user_id'           => $this->user_id,
            'role_id'           => $this->role_id,
            'category_id'       => $this->input->post('category_id', TRUE),
            'sub_category_id'   => $this->input->post('sub_category_id', TRUE),
            'child_category_id' => $this->input->post('child_category_id', TRUE),
            'title'             => $this->input->post('title', TRUE),
            'post_url'          => $slug,
            'description'       => $_POST['description'],
            'post_image'        => $photo,
            'status'            => $status,
            'youtube_json'      => $this->input->post('video_id', TRUE),
            'vimeo_id'             => $this->input->post('vimeo_id', TRUE),

            'audio02'           => '',
            'seo_title'         => $this->input->post('seo_title', TRUE),
            'seo_keyword'       => $this->input->post('seo_keyword', TRUE),
            'seo_description'   => $this->input->post('seo_description', TRUE),
            'created'           => date('Y-m-d H:i:s'),
            'modified'          => date('Y-m-d H:i:s'),
            'reject_note'       => $this->input->post('reject_note', TRUE),
            'photo_caption'       => $this->input->post('photo_caption', TRUE),
        );

		if(in_array($this->role_id, [1,2,3])){
			$data1 = array_merge($data1, ['home_section_id' => (int) $this->input->post('home_section_id', TRUE)]);
		}


        $data = array_merge($data1, $post_show);

        //pp($data);
        $this->db->insert('posts', $data);
        $post_id = $this->db->insert_id();


        // Tags
        $tags = $this->input->post('tags');
        $tags_array = [];
        if($tags[0] != ''){
            foreach ($tags as $tag){
                if (!is_numeric($tag)) {
                    $tag = $this->_create_tag($tag);
                }
                $tags_array[] = [
                    'post_id' => $post_id,
                    'tag_id' => $tag
                ];
            }
            $this->db->insert_batch('post_tags', $tags_array);
        } else {
            $tags = [];
        }

//        $share_facebook = false;
//        $share_twiter = false;
        
//        if (($post_show['post_show'] == "Frontend") && ($data1['status'] == "Publish")) {
//
//                send_push_to_topic($post_id);
//
//             $share_twiter = sendTwitterFeed($this->input->post('title', TRUE), $data['post_url']);
//        }

        $message = 'Create Record Success';
//        // schedule post
//        if($post_show['post_show'] == "Frontend" && $schedule_post_time && $status == 'Schedule_Publish') {
//            $this->schedulePost($post_id, $schedule_datetime);
//        }
//
//        if($schedule_post_time) $message .= ', This post will published at '.date('d-m-Y H:i A',strtotime($schedule_datetime)).' UTC time';
//
//        // if($share_facebook) $message .= ', Facebook share';
//         if($share_twiter) $message .= ', Twitter share';

        $this->session->set_flashdata('message', '<p class="ajax_success">'.$message.'</p>');
        redirect(site_url('admin/posts/update_post/' . $post_id));
    }

    public function update_post($id) {
        // check if fb access token in the session
        $row = $this->db->get_where('posts', ['id' => $id])->row();
        if ($row) {
            $tags = $this->db->select('tag_id')->get_where('post_tags', ['post_id' => $id])->result_array();
            $tags_array = array_map('current', $tags);

            $data = array(
                'button'          => 'Update',
                'action'          => site_url(Backend_URL . 'posts/update_action_post'),
                'id'              => set_value('id', $row->id),
                'user_id'         => set_value('user_id', $row->user_id),
                'sub_category_id' => set_value('sub_category_id', $row->sub_category_id),
                'category_id'     => set_value('category_id', $row->category_id),
                'child_category_id'     => set_value('child_category_id', $row->child_category_id),
                'title'           => set_value('post_title', $row->title),
                'post_url'        => set_value('post_url', $row->post_url),
                'photo_caption'        => set_value('photo_caption', $row->photo_caption),
                'status'          => set_value('post_url', $row->status),
                'description'     => $row->description,
                'post_image'      => set_value('post_image', $row->post_image),
                'youtube_json'    => ($row->youtube_json) ? $row->youtube_json : '[]',
                'vimeo_id'         => set_value('vimeo_id', $row->vimeo_id),
                'seo_title'       => set_value('seo_title', $row->seo_title),
                'seo_keyword'     => set_value('seo_keyword', $row->seo_keyword),
                'seo_description' => set_value('seo_description', $row->seo_description),
                'modified'        => set_value('modified', $row->modified),
                'post_show' => set_value('post_show', $row->post_show),
                'tags'            => set_value('tags', $tags_array),
                'reject_note'   => set_value('tags', $row->reject_note),
				'home_section_id' => set_value('home_section_id', $row->home_section_id),
            );

            $this->viewAdminContent('posts/posts/post_form', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_notice">Record Not Found</p>');
            redirect(site_url('admin/posts'));
        }
    }

    public function update_action_post() {
        $post_id = $this->input->post('id');

        $pic = $this->db->get_where('posts', ['id' => $post_id])->row();
        if ($pic->status == 'Publish' && $pic->post_show == 'Frontend'){
            if (!in_array(getLoginUserData('role_id'), [1, 2, 3])){
                $this->session->set_flashdata('message', '<p class="ajax_error">Sorry! You cannot edit now. Please contact the editor</p>');
                redirect(site_url('admin/posts/update_post/' . $post_id));
            }
        }
        $sub_category_id = intval($this->input->post('sub_category_id', true));





        if (empty($_FILES['post_image']['name'])) {
            $photo = $pic->post_image;
        } else {
            removeFile($pic->post_image);
            $photo = uploadPostPhoto($_FILES['post_image']);
        }


        if($this->role_id == 6 || $this->role_id == 5){ // Individual
            $post_show = 'Journalist';
        } elseif($this->role_id == 7){ // Journalist or Guest
            $post_show = 'Editor';
        } else {
            $post_show = 'Frontend';
        }


        $status = $this->input->post('status', TRUE);


//        if($status == 'Publish') $post_show = 'Frontend';

        $data = array(
            'category_id'       => $this->input->post('category_id', TRUE),
            'sub_category_id'   => $this->input->post('sub_category_id', TRUE),
            'child_category_id' => $this->input->post('child_category_id', TRUE),
            'title'             => $this->input->post('title', TRUE),

            'photo_caption'     => $this->input->post('photo_caption', TRUE),
            'description'       => $_POST['description'],

            'post_image'        => $photo,
            'youtube_json'      => $this->input->post('video_id', TRUE),
            'vimeo_id'          => $this->input->post('vimeo_id', TRUE),
            'seo_title'         => $this->input->post('seo_title', TRUE),
            'seo_keyword'       => $this->input->post('seo_keyword', TRUE),
            'seo_description'   => $this->input->post('seo_description', TRUE),
            'reject_note'       => $this->input->post('reject_note', TRUE),
            'modified'          => date('Y-m-d H:i:s')
        );
		if(in_array($this->role_id, [1,2])){
			$data = array_merge($data, ['home_section_id' => (int) $this->input->post('home_section_id', TRUE)]);
		}
        if ($pic->status == 'Publish' && $pic->post_show == 'Frontend'){
            if (in_array(getLoginUserData('role_id'), [1,2])){
                $data = array_merge($data, ['status' => $status,'post_show'  => $post_show,]);
            }
            if(checkPermission('post/post-url-change',getLoginUserData('role_id'))){
                    $data = array_merge($data, ['post_url' => slugify($this->input->post('post_url', TRUE))]);
            }
        } else {
            $data = array_merge($data, ['status' => $status,'post_show'  => $post_show,'post_url' => slugify($this->input->post('post_url', TRUE))]);
        }

        $row = $this->Posts_model->get_by_id($this->input->post('id'));

        if ( $pic->status == 'Draft' && $this->input->post('status', TRUE) == 'Publish') {
            $data = array_merge($data, ['created' => date('Y-m-d H:i:s')]);
        }

        if($pic->user_id == 0 && $this->role_id == 5){
            $data2 = array('user_id' => $this->user_id);
            $data = array_merge($data, $data2);
        }

        $this->db->update('posts', $data, ['id' => $this->input->post('id')]);

        // Tags
        $this->db->delete('post_tags', ['post_id' => $post_id]);
        $tags = array($this->input->post('tags'));

        $tags_array = [];
        if($tags[0] != ''){
            foreach ($tags[0] as $tag){

                if (!is_numeric($tag)) {
                    $tag = $this->_create_tag($tag);
                }
                $tags_array[] = [
                    'post_id' => $post_id,
                    'tag_id' => $tag
                ];

            }

            if(!empty($tags_array)){
                $this->db->insert_batch('post_tags', $tags_array);
            }
        }


        
//        $share_facebook = false;
//        $share_twiter = false;
//        if (($post_show == "Frontend") && ($status == "Publish")) {
//                send_push_to_topic($this->input->post('id'));
//
//
//             if(($pic->status == 'Draft' || $pic->post_show != "Frontend") && ($post_show == "Frontend" && $data['status'] == "Publish")) {
////                 $share_facebook = sendFacebookFeed($this->input->post('title', TRUE), $data['post_url']);
//                 $share_twiter = sendTwitterFeed($this->input->post('title', TRUE), !empty($data['post_url']) ? $data['post_url'] : $row->post_url);
//             }
//        }

        $message = 'Update Record Success';
//        // schedule post
//        if($schedule_post_time && $status == 'Schedule_Publish') {
//            $this->schedulePost($post_id, $schedule_datetime);
//        }
//
//        if($schedule_post_time) $message .= ', This post will published at '.date('d-m-Y H:i A',strtotime($schedule_datetime)).' UTC time';
//
//        // if($share_facebook) $message .= ', Facebook share';
//         if($share_twiter) $message .= ', Twitter share';

        $this->session->set_flashdata('message', '<p class="ajax_success">'.$message.'</p>');
        redirect(site_url('admin/posts/update_post/' . $post_id));
    }

    private function upload_file($name = array(), $type = 'video'){
        $handle = new Verot\Upload\Upload($name);
        $file = '';
        if ($handle->uploaded) {
            $handle->file_new_name_body = $type.'_'.time().rand();
            $handle->process('uploads/post_files');
            if ($handle->processed) {
                $file = $handle->file_dst_pathname;
                $handle->clean();
            }
        }
        return $file;
    }

    public function post_delete($id) {

        if (!$this->check_access()) {
            $this->session->set_flashdata('message', '<p class="ajax_notice">You do not have delete permission</p>');
            redirect(site_url('admin/posts'));
        }

        $row = $this->Posts_model->get_by_id($id);
        if ($row) {
            removeFile($row->post_image);
            $this->db->delete('post_like_unlike', array('post_id' => $id));
            $this->db->delete('post_comments', array('post_id' => $id));
            $this->db->delete('post_comments_like_unlike', array('post_id' => $id));
            $this->db->delete('posts', array('id' => $id));

            $this->session->set_flashdata('message', '<p class="ajax_success">News Delete Success</p>');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Record Not Found</p>');
            redirect(site_url('admin/posts'));
        }
    }

    public function menu() {
        return buildMenuForMoudle([
            'module' => 'News',
            'icon' => 'fa-list',
            'href' => 'posts',
            'children' => [
                [
                    'title' => 'All Post' . count_posts(),
                    'icon' => 'fa fa-circle-o',
                    'href' => 'posts'
                ], [
                    'title' => 'New Post',
                    'icon' => 'fa fa-circle-o',
                    'href' => 'posts/new_post'
                ], [
                    'title' => 'Categoriies',
                    'icon' => 'fa fa-circle-o',
                    'href' => 'posts/category'
                ], [
                    'title' => 'Post Tags',
                    'icon' => 'fa fa-circle-o',
                    'href' => 'posts/tags'
                ]
            ]
        ]);
    }

    private function _create_tag($input)
    {
        $CI = & get_instance();
        $CI->db->from('tags')->where('slug', slugify($input));
        $slug = $this->db->count_all_results();
        if($slug != 0){
            return false;
        }
        $data = array(
            'name' => $input,
            'slug' => slugify($input),
            'heading' => $input . ' News - Latest Breaking news and top headlines',
            'meta_description' => 'catch up with all the latest news , breaking stories, top headlines and opinion about ' . $input
        );
        $CI->db->insert('tags', $data);
        $tag_id = $CI->db->insert_id();

        return $tag_id;
    }


    public function approve_post($postId)
    {
        $post = $this->db->from('posts')->where('id', $postId)->get()->row();
        if (empty($post)) {
            $this->session->set_flashdata('message', '<p class="ajax_error">Invalid News.</p>');
            redirect($_SERVER['HTTP_REFERER']);
        }
        $status = 'Publish';

        $post_show = 'Frontend';

        $update_data = [
            'status' => $status,
            'post_show' => $post_show,
        ];

        $this->db->update('posts', $update_data, ['id' => $postId]);

            $this->db->where('post_id',$postId);
            $this->db->from('post_tags');
            $post_tags = $this->db->get()->result();
    

            //    sendFacebookFeed($post->title, $post->post_url);
//                sendTwitterFeed($post->title, $post->post_url);
//                send_push_to_topic($postId);

        $this->session->set_flashdata('message', '<p class="ajax_success">News is approved successfully.</p>');
        redirect($_SERVER['HTTP_REFERER']);
    }

     /**
     * post_comments
     * @param $post_id
     * @return post comments array
     */
    public function post_comments($post_id) {

        $q                = urldecode($this->input->get('q', TRUE));
        $start            = intval($this->input->get('start'));
        $status           = urldecode($this->input->get('status', TRUE));
        $show             = urldecode($this->input->get('show', TRUE));
        $category         = intval($this->input->get('category'));
        $post_type        = ($this->input->get('post_type', TRUE)) ? $this->input->get('post_type', TRUE) : 'my';
        $manage_all_posts = checkPermission('posts/manage_all', $this->role_id);
        $paginator              = build_pagination_url('posts/comments/'.$post_id, 'start');
        $config['base_url']     = Backend_URL . $paginator;
        $config['first_url']    = Backend_URL . $paginator;
        $config['per_page']     = 25;
        $config['page_query_string'] = TRUE;

        $config['total_rows'] = $this->Posts_Comment_model->total_rows_post_comment($q, $post_id, null, $manage_all_posts, $status, $post_type);
        $post_comments = $this->Posts_Comment_model->get_data_for_post_comment($post_id, null, $config['per_page'], $start, $q, $manage_all_posts, $status, $post_type);

//        echo $this->db->last_query();

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'post_comments_data'=> $post_comments,
            'pagination'        => $this->pagination->create_links(),
            'total_rows'        => $config['total_rows'],
            'start'             => $start,
            'add_permission'    => checkMenuPermission('posts/new_post', $this->role_id),
            'manage_all_posts'  => $manage_all_posts,
            'q'                 => $q,
            'status'            => $status,
            'category'          => $category,
            'post_type'         => $post_type,
            'show'              => $show,
            'post_id'           => '',
            'reply'             => false,
        );

        $this->viewAdminContent('posts/posts/comment/post_comments', $data);
    }

     /**
     * post_comments
     * @param $post_id
     * @return post comments array
     */
    public function post_comment_replies($comment_id) {

        $q                = urldecode($this->input->get('q', TRUE));
        $start            = intval($this->input->get('start'));
        $status           = urldecode($this->input->get('status', TRUE));
        $show             = urldecode($this->input->get('show', TRUE));
        $category         = intval($this->input->get('category'));
        $post_type        = ($this->input->get('post_type', TRUE)) ? $this->input->get('post_type', TRUE) : 'my';
        $manage_all_posts = checkPermission('posts/manage_all', $this->role_id);
        $paginator              = build_pagination_url('posts/comments/reply/'.$comment_id, 'start');
        $config['base_url']     = Backend_URL . $paginator;
        $config['first_url']    = Backend_URL . $paginator;
        $config['per_page']     = 25;
        $config['page_query_string'] = TRUE;

        $row = $this->db->get_where('post_comments', ['id' => $comment_id])->row();

        $config['total_rows'] = $this->Posts_Comment_model->total_rows_post_comment($q, null, $comment_id, $manage_all_posts, $status, $post_type);
        $post_comments = $this->Posts_Comment_model->get_data_for_post_comment(null, $comment_id, $config['per_page'], $start, $q, $manage_all_posts, $status, $post_type);

//        echo $this->db->last_query();

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'post_comments_data'=> $post_comments,
            'pagination'        => $this->pagination->create_links(),
            'total_rows'        => $config['total_rows'],
            'start'             => $start,
            'add_permission'    => checkMenuPermission('posts/new_post', $this->role_id),
            'manage_all_posts'  => $manage_all_posts,
            'q'                 => $q,
            'status'            => $status,
            'category'          => $category,
            'post_type'         => $post_type,
            'show'              => $show,
            'post_id'         => $row->post_id,
            'reply'             => true,
        );

        $this->viewAdminContent('posts/posts/comment/post_comments', $data);
    }

     /**
     * update_post_comment
     * @param comment $id
     * @return comment values
     */
    public function update_post_comment($id) {
        $row = $this->db->get_where('post_comments', ['id' => $id])->row();
        $user =  $this->db->get_where('users', ['id' => $row->user_id])->row();

        $data = [
            'button'          => 'Update',
            'action'          => site_url(Backend_URL . 'posts/update_action_post_comments'),
            'id'              => set_value('post_comments.id', $row->id),
            'parent_id'       => set_value('post_comments.parent_id', $row->parent_id),
            'post_id'              => set_value('post_comments.post_id', $row->post_id),
            'description'     => set_value('description', $row->description),
            'status'          => set_value('status', $row->status),
            'profile_photo'   => getUserProfilePhoto($user->profile_photo, 'tiny'),
            'name'            => ($user->first_name.' '.$user->last_name)
        ];


        $this->viewAdminContent('posts/posts/comment/post_comment_form', $data);
    }

     /**
     * update_action_post_comments
     * @param comment form post request
     * @return update success message
     */
    public function update_action_post_comments()
    {
        $post_id = $this->input->post('id');
        $result = $this->db->get_where('post_comments', ['id' => $post_id])->row();
        $data = [
            'description'       => $_POST['description'],
            'status'            => $this->input->post('status', TRUE),
            'is_edited'         => (($result->description != $_POST['description']) ? true : false)
        ];

        $this->db->update('post_comments', $data, ['id' => $post_id]);

        $redirect_url = 'admin/posts/update_comment/' . $post_id;
        if($result->parent_id) {
            $redirect_url = 'admin/posts/update_reply/' . $post_id;
        }


        $this->session->set_flashdata('message', '<p class="ajax_success">Comment Update Success.</p>');
        redirect(site_url($redirect_url));
    }

    /**
     * delete_post_comment
     * @param comment id
     * @return success message
     */
    public function delete_post_comment($id)
    {
        $row = $this->db->get_where('post_comments', ['id' => $id])->row();

        // delete post comment and post comment replies 
        // and update posts table comment_count decrement
        $this->db->trans_start();
        $this->db->delete('post_comments', ['parent_id' => $id]);
        $this->db->delete('post_comments', ['id' => $id]);

        $this->db->set('comment_count', 'comment_count-1', FALSE);
        $this->db->where('id', $row->post_id);
        $this->db->update('posts');

        $this->db->trans_complete();

        $redirect_url = 'admin/posts/comments/' . $row->post_id;
        if($row->parent_id) {
            $redirect_url = 'admin/posts/comments/reply/' . $row->parent_id;
        }

        if ($this->db->trans_status() === FALSE)
        {
            $this->session->set_flashdata('message', '<p class="ajax_success">Comment Delete Unsuccess.</p>');
            redirect(site_url($redirect_url));
        }

        $this->session->set_flashdata('message', '<p class="ajax_success">Comment Delete Success.</p>');
        redirect(site_url($redirect_url));
    }
}
