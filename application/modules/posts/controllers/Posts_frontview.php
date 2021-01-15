<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Posts_frontview extends Frontend_controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper('posts');
    }

    public function index()
    {
//        pp('ok');
        $target_path = 'newsroom';
        $category_slug = $this->uri->segment(2);
        if ($category_slug) {
            $cid = getCategoryIDBySlug($category_slug);
            $target_path .= '/' . $category_slug;
        } else {
            $target_path .= '';
        }

        $this->load->library('pagination');

        $limit = 200000;
        $target = 'newsroom?p';
        if ($cid) {
            $target = 'newsroom/' . $category_slug . '?p';
        }
        $currentPage = intval($this->input->get('p'));

        $this->db->select('id');
        if ($cid) {
            $this->db->where('category', $cid);
        }

        $this->db->from('posts');
        $this->db->where('post_show', 'Newsroom');
        $this->db->where('status', 'Publish');
        $this->db->where_not_in('role_id', [6]);
        $this->db->order_by('modified', 'DESC');

        $total = $this->db->get()->num_rows();
        $start = startPointOfPagination($limit, $currentPage);
        $paginator = getPaginator($total, $currentPage, $target, $limit);

        if ($cid) {
            $this->db->where('category', $cid);
        }
        $this->db->from('posts');
        $this->db->where('post_show', 'Newsroom');
        $this->db->where('status', 'Publish');
        $this->db->where_not_in('role_id', [6]);
        $this->db->order_by('modified', 'DESC');
        $this->db->limit($limit, $start);
        $posts = $this->db->get()->result();

        $data = array(
            'pagination' => $paginator,
            'start' => $start,
            'posts_data' => $posts,
        );
        return $data;
    }

    public function posters_post()
    {
        $target_path = 'posters-post';
        $category_slug = $this->uri->segment(2);

        if ($category_slug) {
            $cid = getCategoryIDBySlug($category_slug);
            $target_path .= '/' . $category_slug;
        } else {
            $target_path .= '';
        }

        $this->load->library('pagination');

        $limit = 50;
        $target = 'posters-post?p';
        if ($cid) {
            $target = 'posters-post/' . $category_slug . '?p';
        }
        $currentPage = intval($this->input->get('p'));

        $this->db->select('id');
        if ($cid) {
            $this->db->where('category', $cid);
        }

        $this->db->from('posts');
        $this->db->where('role_id', 6);
        $this->db->where('status', 'Publish');
        $this->db->where_in('post_show', ['Newsroom', 'Home']);
        $this->db->order_by('modified', 'DESC');

        $total = $this->db->get()->num_rows();
        $start = startPointOfPagination($limit, $currentPage);
        $paginator = getPaginator($total, $currentPage, $target, $limit);

        if ($cid) {
            $this->db->where('category', $cid);
        }
        $this->db->from('posts');
        $this->db->where('role_id', 6);
        $this->db->where('status', 'Publish');
        $this->db->where_in('post_show', ['Newsroom', 'Home']);
        $this->db->order_by('modified', 'DESC');
        $this->db->limit($limit, $start);
        $posts = $this->db->get()->result();

        //echo $this->db->last_query();

        $data = array(
            'pagination' => $paginator,
            'start' => $start,
            'posts_data' => $posts,
        );
        return $data;
    }

    public function news_archives()
    {
        $this->load->library('pagination');

        $cid = intval($this->input->get('cid', TRUE));
        $q = urlencode($this->input->get('q', TRUE));
        $date = urlencode($this->input->get('date', TRUE));
        $currentPage = intval($this->input->get('p'));

        $limit = 50;
        $target = 'news-archives?p';
        if (isset($q)) {
            $target = 'news-archives?q=' . $q . '&date=' . $date . '&cid=' . $cid . '&p';
        }

        $this->db->select('id');
        $this->db->where('status', 'Publish');
        $this->db->where('post_show', 'Archive');
        if ($cid != 0) {
            $this->db->where('category', $cid);
        }
        if ($date) {
            $this->db->where("( `modified` LIKE '%" . $date . "%' ESCAPE '!')");
        }
        if ($q) {
            $this->db->where("(`title` LIKE '%" . $q . "%' ESCAPE '!' OR `description` LIKE '%" . $q . "%' ESCAPE '!')");
        }

        $this->db->from('posts');
        $total = $this->db->get()->num_rows();

        $start = startPointOfPagination($limit, $currentPage);
        $paginator = getPaginator($total, $currentPage, $target, $limit);

        $this->db->where('status', 'Publish');
        $this->db->where('post_show', 'Archive');
        if ($cid != 0) {
            $this->db->where('category', $cid);
        }
        if ($date) {
            $this->db->where("( `modified` LIKE '%" . $date . "%' ESCAPE '!')");
        }
        if ($q) {
            $this->db->where("(`title` LIKE '%" . $q . "%' ESCAPE '!' OR `description` LIKE '%" . $q . "%' ESCAPE '!')");
        }

        $this->db->from('posts');
        $this->db->limit($limit, $start);
        $posts = $this->db->get()->result();

        $data = array(
            'pagination' => $paginator,
            'start' => $start,
            'posts_data' => $posts,
        );

//        dd( $this->db->last_query() );

        return $data;
    }

    public function journalists()
    {

        $this->load->library('pagination');
        $q = urlencode($this->input->get('q', TRUE));
        $currentPage = intval($this->input->get('p'));

        $limit = 25;
        $target = 'journalists?p';
        if (isset($q)) {
            $target = 'journalists?q=' . $q . '&p';
        }

        $this->db->select('id');
        if ($q) {
            $this->db->where("(`title` LIKE '%" . $q . "%' ESCAPE '!' OR `first_name` LIKE '%" . $q . "%' ESCAPE '!' OR `last_name` LIKE '%" . $q . "%' ESCAPE '!')");
        }
        $this->db->where('role_id', 5);
        $this->db->from('users');
        $total = $this->db->get()->num_rows();

        $start = startPointOfPagination($limit, $currentPage);
        $paginator = getPaginator($total, $currentPage, $target, $limit);

        $this->db->select('id, title, first_name, last_name, biography, profile_photo');
        if ($q) {
            $this->db->where("(`title` LIKE '%" . $q . "%' ESCAPE '!' OR `first_name` LIKE '%" . $q . "%' ESCAPE '!' OR `last_name` LIKE '%" . $q . "%' ESCAPE '!')");
        }
        $this->db->where('role_id', 5);
        $this->db->from('users');
        $this->db->limit($limit, $start);
        $users = $this->db->get()->result();

        $data = array(
            'pagination' => $paginator,
            'start' => $start,
            'users_data' => $users,
        );

//        dd( $this->db->last_query() );
//        dd($data);
        return $data;
    }

    public function journalists_profile($id)
    {
        $this->load->library('pagination');
        $currentPage = intval($this->input->get('p'));

        $limit = 25;
        $target = 'journalist_profile/' . $id . '/?tab=post&p';

        $this->db->select('id');
        $this->db->where('user_id', $id);
        $this->db->where('role_id', 5);
        $this->db->where('status', 'Publish');
        $this->db->from('posts');
        $total = $this->db->get()->num_rows();

        $start = startPointOfPagination($limit, $currentPage);
        $paginator = getPaginator($total, $currentPage, $target, $limit);

        $this->db->select('*');
        $this->db->order_by('modified', 'DESC');
        $this->db->where('user_id', $id);
        $this->db->where('role_id', 5);
        $this->db->where('status', 'Publish');
        $this->db->from('posts');
        $this->db->limit($limit, $start);
        $users = $this->db->get()->result();

        $data = array(
            'pagination' => $paginator,
            'start' => $start,
            'users_data_profile' => $users,
        );

//        dd( $this->db->last_query() );
//        dd($data);
        return $data;
    }

    public function journalists_profile_modified_post($id)
    {
//        dd($id);
        $this->load->library('pagination');
        $currentPage = intval($this->input->get('p'));

        $limit = 25;
        $target = 'journalist_profile/' . $id . '/?tab=modified&p';

        $this->db->select('id');
//        $this->db->where('user_id', $id);        
        $this->db->where('journalist', $id);
        $this->db->where('status', 'Publish');
        $this->db->from('posts');
        $total = $this->db->get()->num_rows();

        $start = startPointOfPagination($limit, $currentPage);
        $paginator = getPaginator($total, $currentPage, $target, $limit);

        $this->db->select('*');
//        $this->db->where('user_id', $id);     
        $this->db->where('journalist', $id);
        $this->db->where('status', 'Publish');
        $this->db->from('posts');
        $this->db->limit($limit, $start);
        $users = $this->db->get()->result();

        $data = array(
            'pagination' => $paginator,
            'start' => $start,
            'users_data_profile_modified_post' => $users,
        );

//        dd( $this->db->last_query() );
//        dd($data);
        return $data;
    }

    public function category()
    {
        $target_path = 'category/';
        $limit = 15;
        $category_id = getCategoryIDBySlug($this->uri->segment(2));
        $sub_category_id = getCategoryIDBySlug($this->uri->segment(3));
        $child_category_id = getCategoryIDBySlug($this->uri->segment(4));
        $currentPage = ($this->input->get('p')) ? intval($this->input->get('p')) : 1;
        $main_category = '';
        $sub_category = '';
        $child_category = '';
        $sub_category_menu = null;
        $addvert = "category";
        $addString = "C";
        if ($category_id) {
            $target_path .= $this->uri->segment(2) . '/';

            $main_category = $this->db->select('*')
                ->get_where('post_category', array('id' => $category_id))
                ->row();
            $this->db->select('*');
            $this->db->where('parent_id', $category_id)->where('sub_category_id', 0);
            $this->db->from('post_category');
            $addvert = $addvert . "/" . $main_category->slug;
            $addString = $addString . strtoupper($main_category->slug[0]);
            $sub_category_menu = $this->db->get()->result();
        }

        if (empty($main_category)) {
            $this->viewFrontContent('frontend/404');
            return false;
        }
        if ($sub_category_id) {
            $target_path .= $this->uri->segment(3) . '/';
            $sub_category = $this->db->select('*')
                ->get_where('post_category', array('id' => $sub_category_id))
                ->row();
            if (isset($main_category) && !empty($main_category) && in_array($main_category->template_design, [2, 6, 7])) {
                $addvert = $addvert . "/" . $sub_category->slug;
                $addString = $addString . strtoupper($sub_category->slug[0]);
            }
        }
        if ($child_category_id) {
            $target_path .= $this->uri->segment(4) . '/';
            $child_category = $this->db->select('*')
                ->get_where('post_category', array('id' => $child_category_id))
                ->row();

            if (isset($main_category) && !empty($main_category) && in_array($main_category->template_design, [2, 6, 7])) {
                $addvert = $addvert . "/" . $child_category->slug;
                $addString = $addString . strtoupper($child_category->slug[0]);
            }
        }

        $cid = $child_category_id ? $child_category_id : ($sub_category_id ? $sub_category_id : $category_id);
        $this->db->where('id', $cid);
        $this->db->from('post_category');
        $cdata = $this->db->get()->row();

        if ($main_category->template_design == 15 && empty($sub_category_id) && empty($child_category_id)) {
            $data = array(
                'meta_title' => ($cdata->seo_title) ? $cdata->seo_title : $cdata->name,
                'meta_description' => $cdata->seo_keyword,
                'meta_keywords' => $cdata->seo_description,
                'main_category' => $main_category,
                'sub_category' => $sub_category,
                'child_category' => $child_category,
                'sub_category_menu' => $sub_category_menu,
                'revenue_url' => $addvert,
                'add_string' => $addString,
            );

            $data['entertainment'] = $this->entertainmentHomePage($category_id);

            $this->viewFrontContent('frontend/template/entertainment/entertainment_category', $data);
        }
        if ($main_category->template_design == 2) {
            $this->video_category($cdata, $main_category, $sub_category, $child_category, $sub_category_menu);
            return 1;
        }

        if ($main_category->template_design == 32) {
            $this->oil_category($cdata, $main_category, $sub_category, $child_category, $sub_category_menu);
            return 1;
        }

        if ($main_category->template_design == 43) {
            $this->tech_category($cdata, $main_category, $sub_category, $child_category, $sub_category_menu);
            return 1;
        }

        if ($main_category->template_design == 16) {
            $ci = &get_instance();
            $ci->db->query("SET @@group_concat_max_len = 99999999;");
            $feature = $this->db->query("
                        SELECT
                            gallery.id, 
                            gallery.created, 
                            gallery.slug, 
                            gallery.title, 
                            gallery.description, 
                            GROUP_CONCAT(gallery_photos.photo SEPARATOR '|') as photo, 
                            GROUP_CONCAT(gallery_photos.description SEPARATOR '|') as photo_description, 
                            GROUP_CONCAT(gallery_photos.photo_credit SEPARATOR '|') as photo_credit
                        FROM
                            gallery
                            INNER JOIN
                            gallery_photos
                            ON 
                                gallery.id = gallery_photos.gallery_id
                        WHERE
                            gallery.is_featured = 1
                            GROUP BY
                            gallery.id
                        ORDER BY
                            gallery.id DESC
            ")->row();
            $ci->db->query("SET @@group_concat_max_len = 99999999;");
            $latest = $this->db->query("
                    SELECT
                        gallery.id, 
                        gallery.created, 
                        gallery.slug, 
                        gallery.title, 
                        gallery_photos.photo, 
                        gallery_photos.description, 
                        gallery_photos.photo_credit
                    FROM
                        gallery
                        INNER JOIN
                        gallery_photos
                        ON 
                            gallery.id = gallery_photos.gallery_id
                    GROUP BY
			            gallery.id
                    ORDER BY
                        gallery.id DESC
                    LIMIT 3
            ")->result();

            $this->db->query("SET @rank := 0");
            $this->db->query("SET @category := 0");
            $category_data = $this->db->query("
                                    SELECT 
                                        category_name,
                                        category_id,
                                        category_slug,
                                                    GROUP_CONCAT(gallery_id ORDER BY gallery_id DESC SEPARATOR '|') as gallery_id,
                                        GROUP_CONCAT(gallery_title ORDER BY gallery_id DESC SEPARATOR '|') as gallery_title,
                                        GROUP_CONCAT(gallery_slug ORDER BY gallery_id DESC SEPARATOR '|') as gallery_slug,
                                        GROUP_CONCAT(gallery_created ORDER BY gallery_id DESC SEPARATOR '|') as gallery_created,
                                        GROUP_CONCAT(gallery_photo ORDER BY gallery_id DESC SEPARATOR '|') as gallery_photo
                                        FROM (    
                                        SELECT temp.*,
                                            @rank := IF(@category = temp.category_id, @rank + 1, 1) AS rank,
                                            @category := temp.category_id as cat FROM (
                                            SELECT
                                                gallery_category.`name` as category_name, 
                                                gallery_category.id as category_id, 
                                                gallery_category.slug as category_slug, 
                                                gallery.title as gallery_title, 
                                                gallery.slug as gallery_slug,
                                                gallery.created as gallery_created, 
                                                gallery_photos.photo as gallery_photo,
                                                gallery.id as gallery_id
                                            FROM
                                                gallery
                                                INNER JOIN
                                                gallery_photos
                                                ON 
                                                    gallery.id = gallery_photos.gallery_id
                                                INNER JOIN
                                                gallery_category
                                                ON 
                                                    gallery_category.id = gallery.category_id
                                                    GROUP BY
                                                gallery.id
                                                ORDER BY
                                                gallery_category.id ASC) temp 
                                            ORDER BY category_id ASC, gallery_id DESC) temp2 
                                        WHERE rank <= 4
                                        GROUP BY category_id
            ")->result();

            $data = array(
                'meta_title' => ($cdata->seo_title) ? $cdata->seo_title : $cdata->name,
                'meta_description' => $cdata->seo_keyword,
                'meta_keywords' => $cdata->seo_description,
                'feature' => $feature,
                'latest' => $latest,
                'category_data' => $category_data,
                'revenue_url' => $addvert,
                'add_string' => $addString,
            );
            $this->viewFrontContent('frontend/template/gallery/category', $data);
            return 1;
        }


        if ($main_category->template_design == 7) {
            if (!empty($child_category) && $child_category->template_design == 1) {
                $this->category_sql($category_id, $sub_category_id, $child_category_id);
            } else {
                $this->category_sql($category_id, $sub_category_id);
            }
            $this->db->join('post_category as child_category', 'child_category.id = posts.child_category_id', "LEFT");
            $this->db->where('child_category.game_type', null);
            $this->db->where('child_category.template_design !=', 11);
            $total = $this->db->count_all_results();
        } else {
            $this->category_sql($category_id, $sub_category_id, $child_category_id);
            $total = $this->db->count_all_results();
        }


        $target_path .= '?p';
        $start = startPointOfPagination($limit, $currentPage);
        $paginator = getPaginator($total, $currentPage, $target_path, $limit);
        if ($main_category->template_design == 7) {
            $limit = 17;
            if (!empty($child_category) && $child_category->template_design == 1) {
                $this->category_sql($category_id, $sub_category_id, $child_category_id);
            } else {
                $this->category_sql($category_id, $sub_category_id);
            }
            $this->db->join('post_category as child_category', 'child_category.id = posts.child_category_id', "LEFT");
            $this->db->where('child_category.game_type', null);
            $this->db->where('child_category.template_design !=', 11);
        } else {
            $this->category_sql($category_id, $sub_category_id, $child_category_id);
        }
        $this->db->order_by('modified', 'DESC');
        if (!empty($main_category) && ($main_category->template_design == 1 || $main_category->template_design == 6
                || $main_category->template_design == 7 || isset($sub_category) && !empty($sub_category) && $sub_category->template_design == 17)) {
            $this->db->limit($limit, $start);
        }
        $posts = $this->db->get()->result();

        if ($cdata) {
            $data = array(
                'pagination' => $paginator,
                'start' => $start,
                'posts_data' => $posts,
                'meta_title' => ($cdata->seo_title) ? $cdata->seo_title : $cdata->name,
                'meta_description' => $cdata->seo_keyword,
                'meta_keywords' => $cdata->seo_description,
                'main_category' => $main_category,
                'sub_category' => $sub_category,
                'child_category' => $child_category,
                'sub_category_menu' => $sub_category_menu,
                'total' => $total,
                'revenue_url' => $addvert,
                'add_string' => $addString,
            );
            if (!empty($child_category) && $child_category->template_design == 5) {
                $state = $this->db->get_where('states', ['post_category_id' => $child_category->id])->row();
                $data['state'] = $state;
                $this->viewFrontContent('frontend/template/category', $data);
            } elseif ($main_category->template_design == 1) {
                $this->viewFrontContent('frontend/template/category', $data);
            } elseif ($main_category->template_design == 2) {
                $this->viewFrontContent('frontend/template/video_category', $data);
            } elseif ($main_category->template_design == 6) {

                if (!empty($sub_category) && $sub_category->template_design == 13) {
                    $data['active_menu'] = 'stock';
                    $this->viewFrontContent('frontend/template/busniess/stock', $data);
                } elseif (!empty($sub_category) && $sub_category->template_design == 14) {
                    $this->viewFrontContent('frontend/template/busniess/company', $data);
                } elseif (!empty($sub_category) && $sub_category->template_design == 1) {
                    $this->viewFrontContent('frontend/template/category', $data);
                } else {
                    $this->viewFrontContent('frontend/template/business_category', $data);
                }
            } elseif ($main_category->template_design == 15) {
                if (!empty($sub_category) && $sub_category->template_design == 17) {
                    $data['active_menu'] = 'Featured';
                    $this->viewFrontContent('frontend/template/entertainment/featured_entertainment_list', $data);
                } elseif (!empty($sub_category) && $sub_category->template_design == 18) {
                    $data['active_menu'] = 'Celebrity Gist';
                    $data['child_category'] = getCelebrityGistChild($main_category->id);
                    foreach ($data['child_category'] as $child) {
                        $data['gist'][$child->name] = getCategoryData($main_category->id, $sub_category->id, $child->id, 10);
                        $data['totalChile'][$child->name] = getCategoryDataTotal($main_category->id, $sub_category->id, $child->id);
                    }

                    $this->viewFrontContent('frontend/template/entertainment/celebrity_gist_list', $data);
                } elseif (!empty($sub_category) && $sub_category->template_design == 19) {
                    $data['active_menu'] = 'NollyWood';
                    $data['top_movies'] = $this->db->order_by('modified', 'DESC')->from('movies')
                        ->where('status', 'Publish')->where('is_popular', 1)->limit(5)->get()->result();
                    $data['coming_soon'] = $this->db->order_by('modified', 'DESC')->from('movies')
                        ->where('status', 'Publish')->where('date >', date("Y-m-d"))->where('date <', date("Y-m-d", strtotime("2021-01-01")))->limit(10)->get()->result();
                    $data['trailers'] = $this->db->order_by('modified', 'DESC')->from('movies')
                        ->where('status', 'Publish')->where('video_id !=', null)->limit(10)->get()->result();
                    $data['nollywood_premiers'] = $this->db->select('movies.*, posts.post_url as premier, posts.modified as pre_date')->order_by('posts.modified', 'DESC')->from('movie_premiers')
                        ->join('posts', "posts.id = movie_premiers.post_id", 'left')
                        ->join('movies', "movies.id = movie_premiers.movie_id", 'left')
                        ->where('posts.status', 'Publish')->where_in('posts.post_show', ['Frontend'])->where('movies.status', 'Publish')
                        ->where('posts.modified >', date("Y-m-d", strtotime("last day of previous month")))
                        ->where('posts.modified <=', date("Y-m-t"))->limit(4)->get()->result();

                    $data['nollywood_premiers_last'] = $this->db->select('movies.*, posts.post_url as premier, posts.modified as pre_date')->order_by('posts.modified', 'DESC')->from('movie_premiers')
                        ->join('posts', "posts.id = movie_premiers.post_id", 'left')
                        ->join('movies', "movies.id = movie_premiers.movie_id", 'left')
                        ->where('posts.status', 'Publish')->where_in('posts.post_show', ['Frontend'])->where('movies.status', 'Publish')
                        ->where('posts.modified >=', date("Y-m-d", strtotime("first day of previous month")))
                        ->where('posts.modified <=', date("Y-m-d", strtotime("last day of previous month")))
                        ->limit(4)->get()->result();

                    $data['nollywood_premiers_90'] = $this->db->select('movies.*, posts.post_url as premier, posts.modified as pre_date')->order_by('posts.modified', 'DESC')->from('movie_premiers')
                        ->join('posts', "posts.id = movie_premiers.post_id", 'left')
                        ->join('movies', "movies.id = movie_premiers.movie_id", 'left')
                        ->where('posts.status', 'Publish')->where_in('posts.post_show', ['Frontend'])->where('movies.status', 'Publish')
                        ->where('posts.modified <', date("Y-m-d", strtotime("+1 days")))
                        ->where('posts.modified >=', date('Y-m-d', strtotime('-90 days')))
                        ->limit(4)->get()->result();

                    $data['give_rate'] = $this->db->order_by('modified', 'DESC')
                        ->where('status', 'Publish')->get('movies')->row();

                    $this->viewFrontContent('frontend/template/entertainment/nollywood', $data);
                } elseif (!empty($sub_category) && $sub_category->template_design == 20 && !empty($child_category)) {
                    if ($child_category->template_design == 27) {
                        $data['search'] = "";
                        if (isset($_GET['search'])) {
                            $this->db->like('title', $_GET['search']);
                            $data['search'] = $_GET['search'];
                        }
                        $data['videos'] = $this->db->select('posts.*')->from('posts')
                            ->where_in('post_show', ['Frontend'])
                            ->where('posts.status', 'Publish')
                            ->where('category_id', $main_category->id)
                            ->where('sub_category_id', $sub_category->id)
                            ->where('child_category_id', $child_category->id)
                            ->order_by('modified', 'DESC')->get()->result();

                        $this->viewFrontContent('frontend/template/entertainment/music_videos', $data);
                    } elseif ($child_category->template_design == 28) {
                        $data['search'] = '';
                        // $data['meta_title'] = '';
                        if (isset($_GET['search'])) {
                            $this->db->like('name', $_GET['search']);
                            $data['search'] = $_GET['search'];
                            $data['meta_title'] = 'You searched for '.$data['search'].'. '.$data['meta_title'];
                            $data['meta_description'] = 'You searched for '.$data['search'].'. '.$data['meta_description'];
                            $data['meta_keywords'] = 'You searched for '.$data['search'].', '.$data['meta_keywords'];
                        }

                        $data['videos'] = $this->db->order_by('modified', 'DESC')->from('movies')
                                ->where('status', 'Publish')->where('video_id !=', null)->limit(12)->get()->result();

                        $data['reviews'] = $this->db->select('
                        movies.id, 
                        movies.slug, 
                        movies.name, 
                        movies.date, 
                        movies.modified, 
                        movie_reviews.review, 
                        movie_reviews.rating, 
                        movie_reviews.created,
                        CONCAT(CONCAT(users.first_name," "),users.last_name) as full_name')->from('movie_reviews')
                            ->where(['movie_reviews.status' => 'Publish'])
                            ->order_by('movie_reviews.id', 'DESC')
                            ->join('movies', 'movies.id = movie_reviews.movie_id', 'LEFT')
                            ->join('users', 'users.id = movie_reviews.user_id', 'LEFT')
                            ->where(['movies.status' => 'Publish'])
                            ->group_by('movie_reviews.movie_id')
                            ->limit(4)
                            ->get()->result();

                          //  pp($data);


                        $this->viewFrontContent('frontend/template/entertainment/movie_trailers', $data);
                    } elseif ($child_category->template_design == 29) {
                        $data['search'] = "";
                        if (isset($_GET['search'])) {
                            $this->db->like('title', $_GET['search']);
                            $data['search'] = $_GET['search'];
                        }
                        $data['videos'] = $this->db->select('posts.*')->from('posts')
                            ->where_in('post_show', ['Frontend'])
                            ->where('posts.status', 'Publish')
                            ->where('category_id', $main_category->id)
                            ->where('sub_category_id', $sub_category->id)
                            ->where('child_category_id', $child_category->id)
                            ->order_by('modified', 'DESC')->get()->result();

                        $this->viewFrontContent('frontend/template/entertainment/comedy_videos', $data);
                    } else {
                        $data['active_menu'] = 'Video';
                        $data['movie_trailers_category'] = getChildCategoryByTemplate($main_category->id, $sub_category->id, 28);
                        $data['music_videos_category'] = getChildCategoryByTemplate($main_category->id, $sub_category->id, 27);
                        $data['comedy_videos_category'] = getChildCategoryByTemplate($main_category->id, $sub_category->id, 29);
                        $data['movie_trailers'] = getCategoryData($main_category->id, $sub_category->id, $data['movie_trailers_category'] ? $data['movie_trailers_category']->id : 0, 6);
                        $data['music_videos'] = getCategoryData($main_category->id, $sub_category->id, $data['music_videos_category'] ? $data['music_videos_category']->id : 0, 6);
                        $data['comedy_videos'] = getCategoryData($main_category->id, $sub_category->id, $data['comedy_videos_category'] ? $data['comedy_videos_category']->id : 0, 6);

                        $this->viewFrontContent('frontend/template/entertainment/entertainment_videos', $data);
                    }
                } elseif (!empty($sub_category) && $sub_category->template_design == 20) {
                    $data['active_menu'] = 'Video';
                    $data['movie_trailers'] = $this->db->order_by('modified', 'DESC')->from('movies')
                        ->where('status', 'Publish')->where('video_id !=', null)->limit(6)->get()->result();
                    $data['movie_trailers_category'] = getChildCategoryByTemplate($main_category->id, $sub_category->id, 28);
                    $data['music_videos_category'] = getChildCategoryByTemplate($main_category->id, $sub_category->id, 27);
                    $data['comedy_videos_category'] = getChildCategoryByTemplate($main_category->id, $sub_category->id, 29);
                    $data['music_videos'] = getCategoryData($main_category->id, $sub_category->id, $data['music_videos_category'] ? $data['music_videos_category']->id : 0, 6);
                    $data['comedy_videos'] = getCategoryData($main_category->id, $sub_category->id, $data['comedy_videos_category'] ? $data['comedy_videos_category']->id : 0, 6);
                    $data['reviews'] = $this->db->select('
                        movies.id, 
                        movies.slug, 
                        movies.name, 
                        movies.date, 
                        movies.modified, 
                        movie_reviews.review, 
                        movie_reviews.rating, 
                        movie_reviews.created,
                        CONCAT(CONCAT(users.first_name," "),users.last_name) as full_name')->from('movie_reviews')
                        ->where(['movie_reviews.status' => 'Publish'])
                        ->order_by('movie_reviews.id', 'DESC')
                        ->join('movies', 'movies.id = movie_reviews.movie_id', 'LEFT')
                        ->join('users', 'users.id = movie_reviews.user_id', 'LEFT')
                        ->where(['movies.status' => 'Publish'])
                        ->group_by('movie_reviews.movie_id')
                        ->limit(4)
                        ->get()->result();

                    $data['top_movies'] = $this->db->order_by('modified', "DESC")
                        ->from("movies")->where('status', 'Publish')->where('is_popular', 1)
                        ->limit(5)->get()->result();

                    $this->viewFrontContent('frontend/template/entertainment/entertainment_videos', $data);
                } elseif (!empty($sub_category) && $sub_category->template_design == 30) {
                    $data['search'] = "";
                    if (isset($_GET['search'])) {
                        $this->db->like('title', $_GET['search']);
                        $data['search'] = $_GET['search'];
                        $data['meta_title'] = 'You searched for '.$data['search'].' | '.$data['meta_title'];
                        $data['meta_keywords'] = 'You searched for '.$data['search'].', '.$data['meta_title'];
                        $data['meta_description'] = 'You searched for '.$data['search'].'. '.$data['meta_title'];
                    }

                    $data['videos'] = $this->db->select('posts.*')->from('posts')
                        ->where_in('post_show', ['Frontend'])
                        ->where('posts.status', 'Publish')
                        ->where('category_id', $main_category->id)
                        ->where('sub_category_id', $sub_category->id)
                        ->order_by('modified', 'DESC')->limit(6)->get()->result();

                    if (isset($_GET['search'])) {
                        $this->db->like('title', $_GET['search']);
                    }
                    $data['total'] = $this->db->select('posts.*')->from('posts')
                        ->where_in('post_show', ['Frontend'])
                        ->where('posts.status', 'Publish')
                        ->where('category_id', $main_category->id)
                        ->where('sub_category_id', $sub_category->id)
                        ->order_by('modified', 'DESC')->count_all_results();

                    $this->viewFrontContent('frontend/template/entertainment/entertainment_musics', $data);
                }
            } elseif ($main_category->template_design == 7) {
                $ci = &get_instance();
                $ci->db->order_by('id', 'ASC');
                $ci->db->from('leagues');
                $ci->db->where('post_category_id', $main_category->id);
                $ci->db->where('game_type', empty($sub_category) ? 1 : $sub_category->game_type);
                $ci->db->order_by('is_default', 'DESC');
                $data['leagues'] = $ci->db->get()->result();
                if (!empty($child_category) && $child_category->template_design == 2) {

                    $this->viewFrontContent('frontend/template/video_category', $data);
                    return true;
                } elseif (!empty($child_category) && $child_category->template_design == 10) {
                    $league = !empty($data['leagues'][0]) ? $data['leagues'][0] : new stdClass();
                    $data = array(
                        'posts_data' => $posts,
                        'meta_title' => ($cdata->seo_title) ? $cdata->seo_title : $cdata->name,
                        'meta_description' => $cdata->seo_keyword,
                        'meta_keywords' => $cdata->seo_description,
                        'main_category' => $main_category,
                        'sub_category' => $sub_category,
                        'sub_category_menu' => $sub_category_menu,
                        'child_category' => $child_category,
                        'total' => $total,
                        'revenue_url' => $addvert,
                        'add_string' => $addString,
                    );

                    $ci = &get_instance();
                    $ci->db->order_by('is_default', 'DESC');
                    $ci->db->from('leagues');
                    $ci->db->where('post_category_id', $main_category->id);
                    $ci->db->where('game_type', $sub_category->game_type);
                    $data['leagues'] = $ci->db->get()->result();
                    $data['selected_league'] = $league;
                    $data['sports_video'] = getSportsVideos($sub_category_id);

                    if ($sub_category->game_type == 1) {
                        $ci = &get_instance();
                        $ci->db->from('football_leagues');
                        $ci->db->order_by('id', 'DESC');
                        $data['leagues'] = $ci->db->get()->result();
                        $ci = &get_instance();
                        $ci->db->from('football_leagues');
                        $ci->db->where('is_default', '1');
                        $data['selected_league'] = $ci->db->get()->row();
                        $detailsData = $this->getFootballData($data['selected_league']);
                        $data['results'] = $detailsData['results'];
                        $data['features'] = $detailsData['features'];
                        $data['teams'] = $detailsData['teams'];
                        $this->viewFrontContent('frontend/template/results_features', $data);
                    } elseif ($sub_category->game_type == 2) {
                        $ci = &get_instance();
                        $ci->db->from('tennis_leagues');
                        $ci->db->order_by('id', 'DESC');
                        $data['leagues'] = $ci->db->get()->result();
                        $data['selected_league'] = !empty($data['leagues'][0]) ? $data['leagues'][0] : null;
                        $detailsData = $this->getTennisData($data['selected_league']);
                        $data['results'] = $detailsData['results'];
                        $data['features'] = $detailsData['features'];
                        $data['teams'] = $detailsData['teams'];
                        $this->viewFrontContent('frontend/template/tennis_results_features', $data);
                    } elseif ($sub_category->game_type == 3) {
                        $ci = &get_instance();
                        $ci->db->from('boxing_leagues');
                        $ci->db->order_by('id', 'DESC');
                        $data['leagues'] = $ci->db->get()->result();
                        $data['selected_league'] = !empty($data['leagues'][0]) ? $data['leagues'][0] : null;
                        $detailsData = $this->getBoxingData($data['selected_league']);
                        $data['results'] = $detailsData['results'];
                        $data['features'] = $detailsData['features'];
                        $data['teams'] = $detailsData['teams'];
                        $this->viewFrontContent('frontend/template/boxing_results_features', $data);
                    } elseif ($sub_category->game_type == 4) {
                        $ci = &get_instance();
                        $ci->db->from('formula1_league');
                        $ci->db->order_by('id', 'DESC');
                        $data['leagues'] = $ci->db->get()->result();
                        $data['selected_league'] = !empty($data['leagues'][0]) ? $data['leagues'][0] : null;
                        $data['selected_league'] = !empty($data['leagues'][0]) ? $data['leagues'][0] : null;
                        $detailsData = $this->getFormula1Data($data['selected_league']);
                        $data['features'] = $detailsData['features'];
                        $data['teams'] = $detailsData['teams'];
                        $this->viewFrontContent('frontend/template/formula1_results_features', $data);
                    } elseif ($sub_category->game_type == 5) {

                        $ci = &get_instance();
                        $ci->db->from('basketball_leagues');
                        $ci->db->order_by('id', 'DESC');
                        $data['leagues'] = $ci->db->get()->result();
                        $data['selected_league'] = !empty($data['leagues'][0]) ? $data['leagues'][0] : null;
                        $detailsData = $this->getBasketballData($data['selected_league']);
                        $data['results'] = $detailsData['results'];
                        $data['features'] = $detailsData['features'];
                        $data['teams'] = $detailsData['teams'];
                        $this->viewFrontContent('frontend/template/basketball_results_features', $data);
                    } else {
                        $detailsData = $this->getFootballData($league);
                        $data['results'] = $detailsData['results'];
                        $data['features'] = $detailsData['features'];
                        $data['teams'] = $detailsData['teams'];
                        $this->viewFrontContent('frontend/template/results_features', $data);
                    }
                    return true;
                } elseif (!empty($child_category) && $child_category->template_design == 9) {
                    $league = !empty($data['leagues'][0]) ? $data['leagues'][0] : new stdClass();
                    $data = array(
                        'posts_data' => $posts,
                        'meta_title' => ($cdata->seo_title) ? $cdata->seo_title : $cdata->name,
                        'meta_description' => $cdata->seo_description,
                        'meta_keywords' => $cdata->seo_keyword,
                        'main_category' => $main_category,
                        'sub_category' => $sub_category,
                        'sub_category_menu' => $sub_category_menu,
                        'child_category' => $child_category,
                        'total' => $total,
                        'revenue_url' => $addvert,
                        'add_string' => $addString,
                    );
                    $ci = &get_instance();
                    $ci->db->order_by('id', 'DESC');
                    $ci->db->from('leagues');
                    $ci->db->where('post_category_id', $main_category->id);
                    $ci->db->where('game_type', $sub_category->game_type);
                    $data['leagues'] = $ci->db->get()->result();
                    $data['sports_video'] = getSportsVideos($sub_category_id);

                    
                    if ($sub_category->game_type == 1) {

                        $ci = &get_instance();
                        $ci->db->select('football_leagues.name, football_leagues.id, football_leagues.is_group, football_leagues.slug, football_leagues_teams.session_id');
                        $ci->db->order_by('id', 'DESC');
                        $ci->db->join('football_leagues_teams', 'football_leagues_teams.league_id = football_leagues.id', 'LEFT');
                        $ci->db->join('football_leagues_teams as self', 'self.session_id > football_leagues_teams.session_id AND self.league_id = football_leagues_teams.league_id', 'LEFT');
                        $ci->db->where("self.id IS NULL");
                        $ci->db->group_by("football_leagues.id");

                        $data['football_leagues'] = $ci->db->get('football_leagues')->result();


                        $ci = &get_instance();
                        $ci->db->order_by('name', 'DESC');
                        $data['football_sessions'] = $ci->db->get('football_sessions')->result();

                        if(!empty($this->input->get('league_id'))) {
                            foreach($data['football_leagues'] as $football_leagues) {
                                if($football_leagues->slug == $this->input->get('league_id')) {
                                    $league = $football_leagues;
                                    $data['selected_league'] = $football_leagues;
                                    break;
                                }
                            }
                        }
                        $session = '';
                        if(!empty($this->input->get('session_id'))) {
                            foreach($data['football_sessions'] as $football_sessions) {
                                if($football_sessions->id == $this->input->get('session_id')) {
                                    $session = $football_sessions;
                                    break;
                                }
                            }
                        }
                        $data['meta_title'] = $data['meta_title'].((isset($league) && !empty($league)) ? ' | '.$league->name : '').((isset($session)) && !empty($session) ? ' | '.$session->name : '');
                        $data['meta_description'] = $data['meta_description'].((isset($league) && !empty($league)) ? ' '.$league->name : '').((isset($session) && !empty($session)) ? ' '.$session->name : '');
                        $data['meta_keywords'] = $data['meta_keywords'].((isset($league) && !empty($league)) ? ','.$league->name : '').((isset($session) && !empty($session)) ? ','.$session->name : '');

                        $this->viewFrontContent('frontend/template/football_tables', $data);
                    } elseif ($sub_category->game_type == 2) {

//                        $ci = &get_instance();
//                        $ci->db->order_by('points', 'DESC');
//                        $ci->db->from('tennis_standing_table');
//                        $ci->db->where('tennis_standing_table.league_id', $league->id);
//                        $ci->db->where('tennis_standing_table.gender', 1);
//                        $data['men_teams'] = $ci->db->get()->result();
//                        $ci = &get_instance();
//                        $ci->db->order_by('points', 'DESC');
//                        $ci->db->from('tennis_standing_table');
//                        $ci->db->where('tennis_standing_table.league_id', $league->id);
//                        $ci->db->where('tennis_standing_table.gender', 2);
//                        $data['women_teams'] = $ci->db->get()->result();
//                        $data['selected_league'] = $league;

                        $this->viewFrontContent('frontend/template/tennis_standings', $data);
                    } elseif ($sub_category->game_type == 3) {
                        $detailsData = $this->getBoxingData($league, 1);
                        $data['men_teams'] = $detailsData['teams'];
                        $detailsDataWomen = $this->getBoxingData($league, 2);
                        $data['women_teams'] = $detailsDataWomen['teams'];
                        $data['selected_league'] = $league;
                        $this->viewFrontContent('frontend/template/boxing_standings', $data);
                    } elseif ($sub_category->game_type == 4) {
                        $ci = &get_instance();
                        $ci->db->select('formula1_driver.name, SUM(formula1_feature.victories) as victories, SUM(formula1_feature.points) as points');
                        $ci->db->order_by('formula1_feature.points', 'DESC');
                        $ci->db->order_by('formula1_feature.victories', 'DESC');
                        $ci->db->from('formula1_feature');
                        $ci->db->join('formula1_driver', 'formula1_feature.driver_id = formula1_driver.id');
                        $ci->db->group_by('formula1_feature.driver_id');
                        $data['driver_teams'] = $ci->db->get()->result();
                        $ci = &get_instance();
                        $ci->db->select('name, SUM(victory) as victory, SUM(points) as points, SUM(podium) as podium,');
                        $ci->db->order_by('points', 'DESC');
                        $ci->db->order_by('victory', 'DESC');
                        $ci->db->from('formula1_standing_table');
                        $ci->db->group_by('formula1_standing_table.name');
                        $data['constructor_teams'] = $ci->db->get()->result();
                        $data['selected_league'] = $league;
                        $this->viewFrontContent('frontend/template/formula1_standings', $data);
                    } elseif ($sub_category->game_type == 5) {
                        $ci = &get_instance();
                        $ci->db->order_by('points', 'DESC');
                        $ci->db->from('basketball_league_teams');
                        //$ci->db->where('basketball_league_teams.league_id', $league->id);
                        $data['teams'] = $ci->db->get()->result();
                        $data['selected_league'] = $league;
                        $this->viewFrontContent('frontend/template/basketball_tables', $data);
                    } else {
                        $ci = &get_instance();
                        $ci->db->order_by('points', 'DESC');
                        $ci->db->from('football_league_teams');
                        $ci->db->where('football_league_teams.league_id', $league->id);
                        $data['teams'] = $ci->db->get()->result();
                        $data['selected_league'] = $league;
                        foreach ($data['teams'] as $team) {
                            $ci = &get_instance();
                            $ci->db->order_by('date', 'DESC');
                            $ci->db->limit('5');
                            $ci->db->from('football_league_features');
                            $ci->db->where("(football_league_features.team_1 = '" . $team->id . "' OR football_league_features.team_2=' " . $team->id . "')");
                            $ci->db->where('football_league_features.winner !=', null);
                            $team_results = $ci->db->get()->result();
                            $last_result = "<ul class=\"result-table\">";
                            foreach ($team_results as $result) {
                                if ($result->winner == 0) {
                                    $last_result .= "<li>D</li>";
                                } elseif ($result->winner == $team->id) {
                                    $last_result .= "<li class=\"text-success\">W</li>";
                                } else {
                                    $last_result .= "<li class=\"text-danger\">L</li>";
                                }
                            }

                            $data['teams_results'][$team->id] = $last_result;
                        }
                        
                        $this->viewFrontContent('frontend/template/football_tables', $data);
                    }
                    return true;
                } elseif (!empty($child_category) && $child_category->template_design == 1) {
                    $this->viewFrontContent('frontend/template/category', $data);
                    return true;
                } elseif (!empty($child_category) && $child_category->template_design == 11) {
                    $data = array(
                        'meta_title' => ($cdata->seo_title) ? $cdata->seo_title : $cdata->name,
                        'meta_description' => $cdata->seo_keyword,
                        'meta_keywords' => $cdata->seo_description,
                        'main_category' => $main_category,
                        'sub_category' => $sub_category,
                        'sub_category_menu' => $sub_category_menu,
                        'child_category' => $child_category,
                        'revenue_url' => $addvert,
                        'add_string' => $addString,
                    );
                    $data['sports_video'] = getSportsVideos($sub_category_id, 11);
                    $data['video_total'] = getSportsVideosCount($sub_category_id);
                    $this->viewFrontContent('frontend/template/football_sports_video', $data);
                    return true;
                } elseif (!empty($child_category) && $child_category->template_design == 12) {
                    $data = array(
                        'meta_title' => ($cdata->seo_title) ? $cdata->seo_title : $cdata->name,
                        'meta_description' => $cdata->seo_keyword,
                        'meta_keywords' => $cdata->seo_description,
                        'main_category' => $main_category,
                        'sub_category' => $sub_category,
                        'sub_category_menu' => $sub_category_menu,
                        'child_category' => $child_category,
                        'revenue_url' => $addvert,
                        'add_string' => $addString,
                    );
                    if ($sub_category->game_type == 3) {
                        $data['all_teams'] = getBoxingAllTeams();
                        $this->viewFrontContent('frontend/template/boxing_all_team', $data);

                        return true;
                    } elseif ($sub_category->game_type == 4) {
                        $data['posts_data'] = $posts;
                        $data['sports_video'] = getSportsVideos($sub_category_id);
                        $data['all_teams'] = getFormula1Team();
                        $this->viewFrontContent('frontend/template/formula1_all_team', $data);
                        return true;
                    } elseif ($sub_category->game_type == 5) {
                        $data['posts_data'] = $posts;
                        $data['total'] = $total;
                        $data['sports_video'] = getSportsVideos($sub_category_id);
                        $data['all_teams'] = getBasketballTeam();
                        $this->viewFrontContent('frontend/template/basketball_all_team', $data);
                        return true;
                    } elseif ($sub_category->game_type == 2) {
//                        $data['posts_data'] = $posts;
//                        $data['total'] = $total;
//                        $data['sports_video'] = getSportsVideos($sub_category_id);
                        $data['all_teams'] = getTennisTeam();
                        $this->viewFrontContent('frontend/template/tennis_all_team', $data);
                        return true;
                    } else {
                        $data['all_teams'] = getFootballTeam();
                    }
                    if ($sub_category->game_type == 1) {
                        $data['national_teams'] = getAllNationalTeams($sub_category->game_type);
                    }
                    $this->viewFrontContent('frontend/template/football_all_team', $data);
                    return true;
                }
                $data['sports_video'] = getSportsVideos($sub_category_id);
                if ((!empty($sub_category) && $sub_category->game_type == 1) || (!empty($child_category) && $child_category->game_type == 1)) {
                    $ci = &get_instance();
                    $ci->db->from('football_leagues');
                    // $ci->db->where('post_category_id', $main_category->id);
                    //$ci->db->where('game_type', 1);
                    $ci->db->order_by('id', 'DESC');
                    $data['leagues'] = $ci->db->get()->result();
                    //print_r($data['leagues']);die;
                    $ci = &get_instance();
                    $ci->db->from('football_leagues');
                    $ci->db->where('is_default', '1');
                    $data['selected_league'] = $ci->db->get()->row();
                    //print_r($data['selected_league']);die;
                    $detailsData = $this->getFootballData($data['selected_league']);
                    $data['results'] = $detailsData['results'];
                    $data['features'] = $detailsData['features'];
                    $data['teams'] = $detailsData['teams'];
                    $this->viewFrontContent('frontend/template/football_sports_category', $data);
                    return 1;
                } elseif ((!empty($sub_category) && $sub_category->game_type == 2) || (!empty($child_category) && $child_category->game_type == 2)) {
                    $ci = &get_instance();
                    $ci->db->from('tennis_leagues');
                    $ci->db->order_by('id', 'DESC');
                    $data['leagues'] = $ci->db->get()->result();
                    $detailsData = $this->getTennisData(@$data['leagues'][0]);
                    $data['results'] = $detailsData['results'];
                    $data['features'] = $detailsData['features'];
                    $data['teams'] = $detailsData['teams'];
                    $this->viewFrontContent('frontend/template/tennis_sports_category', $data);
                    return 1;
                } elseif ((!empty($sub_category) && $sub_category->game_type == 3) || (!empty($child_category) && $child_category->game_type == 3)) {
                    $ci = &get_instance();
                    $ci->db->from('boxing_leagues');
                    $ci->db->order_by('name', 'ASC');
//                    $ci->db->order_by('id', 'DESC');
                    $data['leagues'] = $ci->db->get()->result();
                    $detailsData = $this->getBoxingData(isset($data['leagues'][0]) ? $data['leagues'][0] : null);
                    $data['results'] = $detailsData['results'];
                    $data['features'] = $detailsData['features'];
                    $data['teams'] = $detailsData['teams'];
                    $this->viewFrontContent('frontend/template/boxing_sports_category', $data);
                    return 1;
                } elseif ((!empty($sub_category) && $sub_category->game_type == 4) || (!empty($child_category) && $child_category->game_type == 4)) {
                    $ci = &get_instance();
                    $data['leagues'] = $ci->db->get('formula1_league')->result();
                    $data['selected_league'] = !empty($data['leagues'][0]) ? $data['leagues'][0] : null;
                    $detailsData = $this->getFormula1Data($data['selected_league']);
                    $data['features'] = $detailsData['features'];
                    $data['teams'] = $detailsData['teams'];
                    $this->viewFrontContent('frontend/template/formula1_sports_category', $data);
                    return 1;
                } elseif ((!empty($sub_category) && $sub_category->game_type == 5) || (!empty($child_category) && $child_category->game_type == 5)) {
                    $ci = &get_instance();
                    $ci->db->from('basketball_leagues');
                    $ci->db->order_by('id', 'DESC');
                    $data['leagues'] = $ci->db->get()->result();
                    $data['selected_league'] = !empty($data['leagues'][0]) ? $data['leagues'][0] : null;
                    $detailsData = $this->getBasketballData($data['selected_league']);
                    $data['results'] = $detailsData['results'];
                    $data['features'] = $detailsData['features'];
                    $data['teams'] = $detailsData['teams'];
                    $this->viewFrontContent('frontend/template/basketball_sports_category', $data);
                    return 1;
                } else {
                    $ci = &get_instance();
                    $ci->db->from('football_leagues');
                    //$ci->db->where('post_category_id', $main_category->id);
                    //$ci->db->where('game_type', 1);
                    $ci->db->order_by('id', 'DESC');
                    $data['leagues'] = $ci->db->get()->result();
                    $ci = &get_instance();
                    $ci->db->from('football_leagues');
                    $ci->db->where('is_default', '1');
                    $data['selected_league'] = $ci->db->get()->row();
                    $detailsData = $this->getFootballData($data['selected_league']);
                    $data['results'] = $detailsData['results'];
                    $data['features'] = $detailsData['features'];
                    $data['teams'] = $detailsData['teams'];
                }


                if (empty($sub_category)) {
                    $ci = &get_instance();
                    $ci->db->order_by('id', 'DESC');
                    $ci->db->from('tennis_leagues');
                    $data['tennis_leagues'] = $ci->db->get()->result();
                    $select_tennis_league = !empty($data['tennis_leagues']) ? $data['tennis_leagues'][0] : 0;
                    $data['tennis_teams'] = $this->getTennisData($select_tennis_league)['teams'];

                    $ci = &get_instance();
                    $ci->db->from('boxing_leagues');
                    $ci->db->order_by('name', 'DESC');
                    $data['boxing_leagues'] = $ci->db->get()->result();

                    $detailsData = $this->getBoxingData("", 1);
                    $data['boxing_teams'] = $detailsData['teams'];
                }
                $this->viewFrontContent('frontend/template/sports_category', $data);
            } else {
                $this->viewFrontContent('frontend/template/category', $data);
            }
        } else {
            $this->viewFrontContent('frontend/404');
        }
    }

    private function category_sql($category_id, $sub_category_id, $child_category_id = null)
    {
        $this->db->from('posts');
        $this->db->select('posts.*');
        $this->db->where_in('post_show', ['Frontend']);
        $this->db->where('posts.status', 'Publish');
        if ($category_id) {
            $this->db->where('posts.category_id', $category_id);
        }
        if ($sub_category_id) {
            $this->db->where('posts.sub_category_id', $sub_category_id);
        }
        if ($child_category_id) {
            $this->db->where('posts.child_category_id', $child_category_id);
        }
    }

    public function tag_list()
    {
        $target_path = 'tag/';
        $limit = 15;
        $slug = $this->uri->segment(2);
        $currentPage = ($this->input->get('p')) ? intval($this->input->get('p')) : 1;

        $tag_name = getTagNameBySlug($slug);
        if (empty($tag_name)) {
            $this->viewFrontContent('frontend/404');
            return false;
        }
        $metaInfo = getMetaInfoBySlug($slug);

        if ($slug) {
            $target_path .= $slug . '/';
        }
        $this->tag_list_sql($slug);
        $total = $this->db->count_all_results();

        $target_path .= '?p';
        $start = startPointOfPagination($limit, $currentPage);
        $paginator = getPaginator($total, $currentPage, $target_path, $limit);

        $this->tag_list_sql($slug);
        $this->db->order_by('modified', 'DESC');
        $this->db->limit($limit, $start);
        $posts = $this->db->get()->result();

        $video_limit = TAG_VIDEO_LIMIT;
        // video list using tags
        $this->tag_video_list_sql($slug);
        $total_videos = $this->db->count_all_results();

        $this->tag_video_list_sql($slug);
        $this->db->order_by('modified', 'DESC');
        $this->db->limit($video_limit, 0);
        $videos = $this->db->get()->result();



        $data = array(
            'pagination' => $paginator,
            'start' => $start,
            'posts_data' => $posts,
            'tag_name' => $tag_name,
            'meta_title' => $metaInfo->heading ? $metaInfo->heading : $tag_name,
            'meta_description' => $metaInfo->meta_description ? $metaInfo->meta_description : $tag_name,
            'total' => $total,
            'slug' => $slug,
            'pin_posts' => getTagPinPostsBySlug($slug),
            'revenue_url' => 'tag/',
            'add_string' => 'Tp',
            'total_videos' => $total_videos,
            'video_limit' => $video_limit,
            'tag_videos' => $videos
        );


        $this->viewFrontContent('frontend/template/tag_list', $data);
    }

    private function tag_list_sql($slug = '')
    {
        $tag_id = getTagIdBySlug($slug);
        if (empty($tag_id)) {
            $tag_id = -1;
        }

        $this->db->select('p.*, sub_category.template_design as sub_cat_tem_desgin');
        $this->db->where_in('p.post_show', ['Frontend']);
        $this->db->where('p.status', 'Publish');
        $this->db->where('pt.tag_id', $tag_id);
        $this->db->from('post_tags as pt');
        $this->db->join('posts as p', 'p.id = pt.post_id');
        $this->db->join('post_category as sub_category', 'sub_category.id = p.sub_category_id', 'LEFT');
    }

    private function tag_video_list_sql($slug = '')
    {
        $tag_id = getTagIdBySlug($slug);
        if (empty($tag_id)) {
            $tag_id = -1;
        }

        $this->db->select('p.*, sub_category.template_design as sub_cat_tem_desgin');
        $this->db->where_in('p.post_show', ['Frontend']);
        $this->db->where('p.status', 'Publish');
        $this->db->where('pt.tag_id', $tag_id);
        $this->db->where('p.category_id', 87);
        $this->db->from('post_tags as pt');
        $this->db->join('posts as p', 'p.id = pt.post_id');
        $this->db->join('post_category as sub_category', 'sub_category.id = p.sub_category_id', 'LEFT');
    }

    public function results_features($type, $league_slug)
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $url = explode('/', explode(base_url(), $_SERVER['HTTP_REFERER'])[1]);
            $currentType = (object)[];
            if (!isset($url[2])) {
                $ci = &get_instance();
                $ci->db->like('post_category.slug', $type);
                $ci->db->from('post_category');
                $currentType = $ci->db->get()->row();
            }
        } else {
            $url = [];
            $ci = &get_instance();
            $ci->db->like('post_category.slug', $type);
            $ci->db->from('post_category');
            $currentType = $ci->db->get()->row();
        }
        $category_id = getCategoryIDBySlug(isset($url[1]) ? $url[1] : "sports");
        $sub_category_id = getCategoryIDBySlug(isset($url[2]) ? $url[2] : $currentType->slug);
        $child_category_id = getCategoryIDBySlug(isset($url[3]) ? $url[3] : "");
        $main_category = '';
        $sub_category = '';
        $child_category = '';
        $sub_category_menu = null;
        if ($category_id) {
            $main_category = $this->db->select('*')
                ->get_where('post_category', array('id' => $category_id))
                ->row();
            $this->db->select('*');
            $this->db->where('parent_id', $category_id)->where('sub_category_id', 0);
            $this->db->from('post_category');
            $sub_category_menu = $this->db->get()->result();
        }
        if ($sub_category_id) {
            $sub_category = $this->db->select('*')
                ->get_where('post_category', array('id' => $sub_category_id))
                ->row();
        }
        if ($child_category_id) {
            $child_category = $this->db->select('*')
                ->get_where('post_category', array('id' => $child_category_id))
                ->row();
        }

        $this->category_sql($category_id, $sub_category_id);
        $this->db->join('post_category as sub', 'sub.id = posts.sub_category_id');
        $this->db->where('sub.template_design !=', '11');
        $this->db->order_by('modified', 'DESC');
        $posts = $this->db->get()->result();
        $cid = $sub_category_id ? $sub_category_id : $category_id;
        $this->db->where('id', $cid);
        $this->db->from('post_category');
        $cdata = $this->db->get()->row();

        if ($cdata) {
            $data = array(
                'posts_data' => $posts,
                'meta_title' => ($cdata->seo_title) ? $cdata->seo_title : $cdata->name,
                'meta_description' => $cdata->seo_keyword,
                'meta_keywords' => $cdata->seo_description,
                'main_category' => $main_category,
                'sub_category' => $sub_category,
                'sub_category_menu' => $sub_category_menu,
                'child_category' => $child_category,
                'total' => count($posts)
            );
            $data['sports_video'] = getSportsVideos($sub_category_id);

            if ($type == 'football') {
                $ci = &get_instance();
                $data['leagues'] = $ci->db->get('football_leagues')->result();

                $ci = &get_instance();
                $ci->db->where('slug', $league_slug);
                $data['selected_league'] = $ci->db->get('football_leagues')->row();
                $detailsData = $this->getFootballData($data['selected_league']);
                $data['results'] = $detailsData['results'];
                $data['features'] = $detailsData['features'];
                $data['teams'] = $detailsData['teams'];
                $this->viewFrontContent('frontend/template/results_features', $data);
            } elseif ($type == 'tennis') {
                $ci = &get_instance();
                $data['leagues'] = $ci->db->get('tennis_leagues')->result();

                $ci = &get_instance();
                $ci->db->where('slug', $league_slug);
                $data['selected_league'] = $ci->db->get('tennis_leagues')->row();

                $detailsData = $this->getTennisData($data['selected_league']);
                $data['results'] = $detailsData['results'];
                $data['features'] = $detailsData['features'];
                $data['teams'] = $detailsData['teams'];
                $this->viewFrontContent('frontend/template/tennis_results_features', $data);
            } elseif ($type == 'boxing') {
                $ci = &get_instance();
                $data['leagues'] = $ci->db->get('boxing_leagues')->result();
                $ci = &get_instance();
                $ci->db->where('slug', $league_slug);
                $data['selected_league'] = $ci->db->get('boxing_leagues')->row();
                $detailsData = $this->getBoxingData($data['selected_league']);
                $data['results'] = $detailsData['results'];
                $data['features'] = $detailsData['features'];
                $data['teams'] = $detailsData['teams'];
                $this->viewFrontContent('frontend/template/boxing_results_features', $data);
            } elseif ($type == 'formula1') {
                $ci = &get_instance();
                $data['leagues'] = $ci->db->get('formula1_league')->result();

                $ci = &get_instance();
                $ci->db->where('slug', $league_slug);
                $data['selected_league'] = $ci->db->get('formula1_league')->row();

                $detailsData = $this->getFormula1Data($data['selected_league']);
                $data['features'] = $detailsData['features'];
                $data['teams'] = $detailsData['teams'];
                $this->viewFrontContent('frontend/template/formula1_results_features', $data);
            } elseif ($type == 'basketball') {
                $ci = &get_instance();
                $data['leagues'] = $ci->db->get('basketball_leagues')->result();

                $ci = &get_instance();
                $ci->db->where('slug', $league_slug);
                $data['selected_league'] = $ci->db->get('basketball_leagues')->row();

                $detailsData = $this->getBasketballData($data['selected_league']);
                $data['results'] = $detailsData['results'];
                $data['features'] = $detailsData['features'];
                $data['teams'] = $detailsData['teams'];
                $this->viewFrontContent('frontend/template/basketball_results_features', $data);
            } else {
                $ci = &get_instance();
                $data['leagues'] = $ci->db->get('football_leagues')->result();

                $ci = &get_instance();
                $ci->db->where('slug', $league_slug);
                $data['selected_league'] = $ci->db->get('football_leagues')->row();
                $detailsData = $this->getFootballData($data['selected_league']);
                $data['results'] = $detailsData['results'];
                $data['features'] = $detailsData['features'];
                $data['teams'] = $detailsData['teams'];
                $this->viewFrontContent('frontend/template/results_features', $data);
            }
        }
    }

    public function load_football_results_fixture_tables()
    {
        $league_slug = $this->input->post('league_id');
        $team_id = @$this->input->post('team_id');

        $ci = &get_instance();
        $ci->db->from('football_leagues');
        $ci->db->where('slug', $league_slug);
        $data['leagues'] = $ci->db->get()->row();
        $ci = &get_instance();
        $ci->db->order_by('football_leagues_matchs.datetime', 'DESC');
        $ci->db->select('GROUP_CONCAT(if(football_leagues_matchs.team_1_goal is NULL, 0, football_leagues_matchs.team_1_goal) ORDER BY football_leagues_matchs.datetime DESC SEPARATOR "," ) as team_1_goal, 
                                 GROUP_CONCAT(if(football_leagues_matchs.team_2_goal is NULL, 0, football_leagues_matchs.team_2_goal) ORDER BY football_leagues_matchs.datetime DESC SEPARATOR "," ) as team_2_goal, 
                                 GROUP_CONCAT(team1.name ORDER BY football_leagues_matchs.datetime DESC SEPARATOR "," ) as team_1_name,
                                 GROUP_CONCAT(team2.name ORDER BY football_leagues_matchs.datetime DESC SEPARATOR "," ) as team_2_name,
                                 GROUP_CONCAT(team2.logo ORDER BY football_leagues_matchs.datetime DESC SEPARATOR "," ) as team_2_logo,
                                 GROUP_CONCAT(team1.logo ORDER BY football_leagues_matchs.datetime DESC SEPARATOR "," ) as team_1_logo,
                                 GROUP_CONCAT(team1.slug ORDER BY football_leagues_matchs.datetime DESC SEPARATOR "," ) as team_1_slug,
                                 GROUP_CONCAT(team2.slug ORDER BY football_leagues_matchs.datetime DESC SEPARATOR "," ) as team_2_slug,
                                 GROUP_CONCAT(football_leagues_matchs.slug ORDER BY football_leagues_matchs.datetime DESC SEPARATOR "," ) as match_slug,
                                 GROUP_CONCAT(if(football_leagues_matchs.winner is NULL, "live", "finish") ORDER BY football_leagues_matchs.datetime DESC SEPARATOR "," ) as match_status,
                                 football_leagues_matchs.datetime');
        $ci->db->from('football_leagues_matchs');
        $this->db->join('football_teams as team1', 'team1.id = football_leagues_matchs.team_1');
        $this->db->join('football_teams as team2', 'team2.id = football_leagues_matchs.team_2');
        $this->db->join('football_leagues_matchs as dup', 'dup.session_id > football_leagues_matchs.session_id', 'LEFT');

        $ci->db->where('football_leagues_matchs.league_id', isset($data['leagues']) ? $data['leagues']->id : 0);
        $ci->db->where('football_leagues_matchs.datetime <=', Carbon\Carbon::now()->format('Y-m-d H:i'));
//        $ci->db->where('football_leagues_matchs.winner !=', null);
        $ci->db->where('dup.id IS NULL');
        if (!empty($team_id)) {
            $this->db->group_start();
            $this->db->where('football_leagues_matchs.team_1', $team_id);
            $this->db->or_where('football_leagues_matchs.team_2', $team_id);
            $this->db->group_end();
        }
        $ci->db->group_by("DATE(football_leagues_matchs.datetime)");
        $data['results'] = $ci->db->get()->result();

        $ci = &get_instance();
        $ci->db->select('GROUP_CONCAT(team1.name ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team2.name ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(team2.logo ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_2_logo,
                                 GROUP_CONCAT(team1.logo ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_1_logo,
                                 GROUP_CONCAT(team1.slug ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_1_slug,
                                 GROUP_CONCAT(team2.slug ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_2_slug,
                                 GROUP_CONCAT(football_leagues_matchs.slug ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as match_slug,
                                 GROUP_CONCAT(TIME(football_leagues_matchs.datetime) ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as time,
                                 football_leagues_matchs.datetime');
        $ci->db->order_by('football_leagues_matchs.datetime', 'ASC');
        $ci->db->from('football_leagues_matchs');
        $ci->db->group_by("DATE(football_leagues_matchs.datetime)");
        $this->db->join('football_teams as team1', 'team1.id = football_leagues_matchs.team_1');
        $this->db->join('football_teams as team2', 'team2.id = football_leagues_matchs.team_2');
        $this->db->join('football_leagues_matchs as dup', 'dup.session_id > football_leagues_matchs.session_id', 'LEFT');
        $ci->db->where('football_leagues_matchs.league_id', isset($data['leagues']) ? $data['leagues']->id : 0);
        $ci->db->where('football_leagues_matchs.datetime >', Carbon\Carbon::now()->format('Y-m-d H:i'));
//        $ci->db->where('football_leagues_matchs.winner', null);
        $ci->db->where('dup.id IS NULL');
        if (!empty($team_id)) {
            $this->db->group_start();
            $this->db->where('football_leagues_matchs.team_1', $team_id);
            $this->db->or_where('football_leagues_matchs.team_2', $team_id);
            $this->db->group_end();
        }
        $data['features'] = $ci->db->get()->result();

        $ci = &get_instance();
        $ci->db->order_by('football_leagues_teams.points', 'DESC');
        $ci->db->order_by('football_leagues_teams.goal_difference', 'DESC');
        $ci->db->order_by('football_leagues_teams.played', 'ASC');
        $ci->db->select('football_leagues_teams.*, team.name, team.slug');
        $ci->db->from('football_leagues_teams');
        $this->db->join('football_teams as team', 'team.id = football_leagues_teams.team_id', 'INNER');
        $this->db->join('football_leagues_teams as dup', 'dup.league_id = football_leagues_teams.league_id AND dup.session_id > football_leagues_teams.session_id', 'LEFT');
        $ci->db->where('football_leagues_teams.league_id', isset($data['leagues']) ? $data['leagues']->id : 0);
        if (!empty($data['leagues']) && !empty($data['leagues']->is_group)) {
            $this->db->join('football_leagues_teams as self', 'self.league_id = football_leagues_teams.league_id AND self.group_id > football_leagues_teams.group_id', 'LEFT');
            $ci->db->where('self.id IS NULL');

        }
        $ci->db->where('dup.id IS NULL');
        $data['teams'] = $ci->db->get()->result();

        echo json_encode(['data' => $data]);
    }

    public function load_tennis_results_fixture_tables()
    {
        $league_slug = $this->input->post('league_id');
        $type = $this->input->post('type');
        $player_id = @$this->input->post('player_id');
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : 100;

        $ci = &get_instance();
        $ci->db->from('tennis_leagues');
        $ci->db->where('slug', $league_slug);
        $ci->db->order_by('id', 'DESC');
        $data['leagues'] = $ci->db->get()->result();
        $data['selected_league'] = !empty($data['leagues'][0]) ? $data['leagues'][0] : null;
        $data = $this->getTennisData($data['selected_league'], $player_id);
        $html = '';
        if ($type == 'result') {
            $count = 1;
            foreach ($data['results'] as $k => $result) {
                $player_1 = explode('|', $result->player_1);
                $slug_1 = explode('|', $result->slug_1);
                $picture_1 = explode('|', $result->picture_1);

                $player_2 = explode('|', $result->player_2);
                $slug_2 = explode('|', $result->slug_2);
                $picture_2 = explode('|', $result->picture_2);
                $winner = explode('|', $result->winner);
                $slug = explode('|', $result->slug);
                $team_1_id = explode('|', $result->team_1_id);
                $team_2_id = explode('|', $result->team_2_id);
                $player_1_id = explode('|', $result->player_1_id);
                $player_2_id = explode('|', $result->player_2_id);
                $team_type = explode('|', $result->team_type);

                $html .= "<h4>" . \Carbon\Carbon::parse($result->datetime)->isoFormat('dddd') . \Carbon\Carbon::parse($result->datetime)->format(', F d, Y') . "</h4>";
                $html .= "<ul class=\"mb-3\">";
                for ($i = 0; $i < count($player_1); $i++) {
                    if ($count <= $limit) {
                        $count++;
                        if (empty($winner[$i])) {
                            $win_pic = 'result.svg';
                        } else {
                            if ($team_type[$i] == 'single') {
                                if ($player_1_id[$i] == $winner[$i]) {
                                    $win_pic = 'result2.svg';
                                } else {
                                    $win_pic = 'result1.svg';
                                }
                            } else {
                                if ($team_1_id[$i] == $winner[$i]) {
                                    $win_pic = 'result2.svg';
                                } else {
                                    $win_pic = 'result1.svg';
                                }
                            }
                        }
                        $player_1s = explode(',', $player_1[$i]);
                        $slug_1s = explode(',', $slug_1[$i]);
                        $picture_1s = explode(',', $picture_1[$i]);

                        $player_2s = explode(',', $player_2[$i]);
                        $slug_2s = explode(',', $slug_2[$i]);
                        $picture_2s = explode(',', $picture_2[$i]);

                        $html .= "<li><span class=\"left\">";
                        for ($p1 = 0; $p1 < count($player_1s); $p1++) {
                            $left_class = count($player_1s) > 1 ? $p1 == 0 ? 'mr-2' : '' : '';
                            $player_1_name = count($player_1s) > 1 ? getShortWordWithDot($player_1s[$p1]) : $player_1s[$p1];
                            $html .= "<a href='" . base_url() . 'sport/tennis/player/' . $slug_1s[$p1] . "'><span class=\"name " . $left_class . "\">" . $player_1_name . "</span></a>";
                        }
                        $html .= "<span class=\"image\">";
                        for ($p1 = 0; $p1 < count($picture_1s); $p1++) {
                            $html .= "<img src=\"" . getPhoto3($picture_1s[$p1]) . "\" alt=\"\">";
                        }
                        $html .= "</span> </span>
                                    <span class=\"middle\"><a href=\"" . base_url() . 'sport/tennis/match/' . $slug[$i] . "\"><img src=\"assets/images/sports/$win_pic\"
                                                              alt=\"\"> </a></span>
                                    <span class=\"right\">
                                                <span class=\"image\">";
                        for ($p2 = 0; $p2 < count($picture_2s); $p2++) {
                            $html .= "<img src=\"" . getPhoto3($picture_2s[$p2]) . "\" alt=\"\">";
                        }
                        $html .= "</span>";
                        for ($p2 = 0; $p2 < count($player_1s); $p2++) {
                            $right_class = count($player_2s) > 1 ? $p2 == 1 ? 'ml-2' : '' : '';
                            $player_2_name = count($player_2s) > 1 ? getShortWordWithDot($player_2s[$p2]) : $player_2s[$p2];
                            $html .= "<a href=\"" . base_url() . 'sport/tennis/player/' . $slug_2s[$p2] . "\"><span class=\"name " . $right_class . "\">" . $player_2_name . "</span></a>";
                        }
                        $html .= "</span> </li>";

                    }
                }
                $html .= "</ul>";
            }
        } elseif ($type == 'feature') {
            $count = 1;
            foreach ($data['features'] as $k => $feature) {
                $player_1 = explode('|', $feature->player_1);
                $slug_1 = explode('|', $feature->slug_1);
                $picture_1 = explode('|', $feature->picture_1);

                $player_2 = explode('|', $feature->player_2);
                $slug_2 = explode('|', $feature->slug_2);
                $picture_2 = explode('|', $feature->picture_2);
                $slug = explode('|', $feature->slug);
                $time = explode('|', $feature->time);

                $html .= "<h4>" . \Carbon\Carbon::parse($feature->datetime)->isoFormat('dddd') . \Carbon\Carbon::parse($feature->datetime)->format(', F d, Y') . "</h4>";
                $html .= "<ul class=\"mb-4\">";
                for ($i = 0; $i < count($player_1); $i++) {
                    if ($count <= $limit) {
                        $count++;
                        $player_1s = explode(',', $player_1[$i]);
                        $slug_1s = explode(',', $slug_1[$i]);
                        $picture_1s = explode(',', $picture_1[$i]);

                        $player_2s = explode(',', $player_2[$i]);
                        $slug_2s = explode(',', $slug_2[$i]);
                        $picture_2s = explode(',', $picture_2[$i]);

                        $html .= "<li><div class=\"fixtures-result \"><span class=\"left\">";
                        for ($p1 = 0; $p1 < count($player_1s); $p1++) {
                            $left_class = count($player_1s) > 1 ? $p1 == 0 ? 'mr-2' : '' : '';
                            $player_1_name = count($player_1s) > 1 ? getShortWordWithDot($player_1s[$p1]) : $player_1s[$p1];
                            $html .= "<a href='" . base_url() . 'sport/tennis/player/' . $slug_1s[$p1] . "'><span class=\"name " . $left_class . "\">" . $player_1_name . "</span></a>";
                        }
                        $html .= "<span class=\"image\">";
                        for ($p1 = 0; $p1 < count($picture_1s); $p1++) {
                            $html .= "<img src=\"" . getPhoto3($picture_1s[$p1]) . "\" alt=\"\">";
                        }
                        $html .= "</span> </span>
                                    <strong class=\"middle\"><a href=\"" . base_url() . 'sport/tennis/match/' . $slug[$i] . "\">" . $time[$i] . "</a> </strong>
                                    <span class=\"right\">
                                                <span class=\"image\">";
                        for ($p2 = 0; $p2 < count($picture_2s); $p2++) {
                            $html .= "<img src=\"" . getPhoto3($picture_2s[$p2]) . "\" alt=\"\">";
                        }
                        $html .= "</span>";
                        for ($p2 = 0; $p2 < count($player_1s); $p2++) {
                            $right_class = count($player_2s) > 1 ? $p2 == 1 ? 'ml-2' : '' : '';
                            $player_2_name = count($player_2s) > 1 ? getShortWordWithDot($player_2s[$p2]) : $player_2s[$p2];
                            $html .= "<a href=\"" . base_url() . 'sport/tennis/player/' . $slug_2s[$p2] . "\"><span class=\"name " . $right_class . "\">" . $player_2_name . "</span></a>";
                        }
                        $html .= "</span></div> </li>";

                    }
                }
                $html .= "</ul>";
            }
        } else {
            $count = 1;
            foreach ($data['teams'] as $team) {
                if ($count <= 10) {
                    $html .= "<tr>
                        <td class=\"text-center\">$count</td>
                        <td><a href=\"sport/tennis/player/$team->slug\">$team->name</a> </td>
                        <td class=\"text-center\">$team->nationality</td>
                        <td class=\"text-center\">$team->point</td>
                    </tr>";
                }
                $count++;
            }
        }


        echo json_encode(['html' => $html, 'teams' => $data['teams']]);
    }

    public function load_basketball_results_fixture_tables()
    {
        $league_slug = $this->input->post('league_id');
        $team_id = @$this->input->post('team_id');

        $ci = &get_instance();
        $ci->db->from('basketball_leagues');
        $ci->db->where('slug', $league_slug);
        $ci->db->order_by('id', 'DESC');
        $data['leagues'] = $ci->db->get()->result();
        $data['selected_league'] = !empty($data['leagues'][0]) ? $data['leagues'][0] : null;
        $detailsData = $this->getBasketballData($data['selected_league'], $team_id);
        $data['results'] = $detailsData['results'];
        $data['features'] = $detailsData['features'];
        $data['teams'] = $detailsData['teams'];

        echo json_encode(['data' => $data]);
    }

    public function load_formula1_results_fixture_tables()
    {
        $league_slug = $this->input->post('league_id');
        $team_id = @$this->input->post('team_id');

        $ci = &get_instance();
        $ci->db->from('formula1_league');
        $ci->db->where('slug', $league_slug);
        $data['leagues'] = $ci->db->get()->row();
        $detailsData = $this->getFormula1Data($data['leagues'], $team_id);
        //print_r($this->db->last_query());die();
        $data['features'] = $detailsData['features'];
        $data['teams'] = $detailsData['teams'];

        echo json_encode(['data' => $data]);
    }

    public function load_boxing_results_fixture_tables()
    {
        $league_id = $this->input->post('league_id');

        $ci = &get_instance();
        $ci->db->from('boxing_leagues');
        $ci->db->where('slug', $league_id);
        $data['leagues'] = $ci->db->get()->row();
        $league = $data['leagues'];

        if (!empty($league)) {
            $ci = &get_instance();
            $ci->db->order_by('boxing_league_features.date', 'DESC');
            $ci->db->select('GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team1.slug SEPARATOR ",") as team_1_slug,
                                 GROUP_CONCAT(team2.slug SEPARATOR ",") as team_2_slug,
                                 GROUP_CONCAT(team1.id SEPARATOR ",") as team_1_id,
                                 GROUP_CONCAT(team2.id SEPARATOR ",") as team_2_id,
                                 GROUP_CONCAT(boxing_league_features.slug SEPARATOR ",") as match_slug,
                                 GROUP_CONCAT(boxing_league_features.time SEPARATOR ",") as time,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(team2.image SEPARATOR ",") as team_2_logo,
                                 GROUP_CONCAT(team1.image SEPARATOR ",") as team_1_logo,
                                 boxing_league_features.date,
                                 ');
            $ci->db->from('boxing_league_features');
            $this->db->join('boxing_players as team1', 'team1.id = boxing_league_features.team_1');
            $this->db->join('boxing_players as team2', 'team2.id = boxing_league_features.team_2');
            $ci->db->where('boxing_league_features.league_id', isset($league) ? $league->id : 0);
            $ci->db->where('boxing_league_features.date > ', Carbon\Carbon::now()->format('Y-m-d'));
            $ci->db->where('boxing_league_features.winner is NULL', NULL, FALSE);
            $ci->db->group_by("boxing_league_features.date");
            $data['features'] = $ci->db->get()->result();
        } else {
            $data['features'] = [];
        }

        if (!empty($league)) {
            $ci = &get_instance();
            $ci->db->order_by('boxing_league_features.date', 'DESC');
            $ci->db->select('GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team1.slug SEPARATOR ",") as team_1_slug,
                                 GROUP_CONCAT(team2.slug SEPARATOR ",") as team_2_slug,
                                 GROUP_CONCAT(team1.id SEPARATOR ",") as team_1_id,
                                 GROUP_CONCAT(team2.id SEPARATOR ",") as team_2_id,
                                 GROUP_CONCAT(boxing_league_features.slug SEPARATOR ",") as match_slug,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(team2.image SEPARATOR ",") as team_2_logo,
                                 GROUP_CONCAT(team1.image SEPARATOR ",") as team_1_logo,
                                 GROUP_CONCAT(boxing_league_features.winner SEPARATOR ",") as winner,
                                 boxing_league_features.date,
                                 ');
            $ci->db->from('boxing_league_features');
            $this->db->join('boxing_players as team1', 'team1.id = boxing_league_features.team_1');
            $this->db->join('boxing_players as team2', 'team2.id = boxing_league_features.team_2');
            $ci->db->where('boxing_league_features.league_id', isset($league) ? $league->id : 0);
            $ci->db->where('boxing_league_features.date <=', Carbon\Carbon::now()->format('Y-m-d'));
            $ci->db->where('boxing_league_features.winner is NOT NULL', NULL, FALSE);
            $ci->db->group_by("boxing_league_features.date");
            $data['results'] = $ci->db->get()->result();
        } else {
            $data['results'] = [];
        }

        echo json_encode(['data' => $data]);
    }

    public function tables($type, $league_slug)
    {
        if ($this->input->is_ajax_request()) {
            if ($type == 'football') {
                $ci = &get_instance();
                $ci->db->order_by('points', 'DESC');
                $ci->db->order_by('goal_difference', 'DESC');
                $ci->db->order_by('goal_for', 'DESC');
                $ci->db->from('football_league_teams');
                $ci->db->where('football_league_teams.league_id', $league->id);
                $data['teams'] = $ci->db->get()->result();
                $data['selected_league'] = $league;
                foreach ($data['teams'] as $team) {
                    $ci = &get_instance();
                    $ci->db->order_by('date', 'DESC');
                    $ci->db->limit('5');
                    $ci->db->from('football_league_features');
                    $ci->db->where("(football_league_features.team_1 = '" . $team->id . "' OR football_league_features.team_2=' " . $team->id . "')");
                    $ci->db->where('football_league_features.winner !=', null);
                    $team_results = $ci->db->get()->result();
                    $last_result = "<ul class=\"result-table\">";
                    foreach ($team_results as $result) {
                        if ($result->winner == 0) {
                            $last_result .= "<li>D</li>";
                        } elseif ($result->winner == $team->id) {
                            $last_result .= "<li class=\"text-success\">W</li>";
                        } else {
                            $last_result .= "<li class=\"text-danger\">L</li>";
                        }
                    }

                    $data['teams_results'][$team->id] = $last_result;
                }
            } elseif ($type == 'basketball') {
                $ci = &get_instance();
                $ci->db->order_by('points', 'DESC');
                $ci->db->from('basketball_league_teams');
                $ci->db->where('basketball_league_teams.league_id', $league->id);
                $data['teams'] = $ci->db->get()->result();
                $data['selected_league'] = $league;
            } else {
                $ci = &get_instance();
                $ci->db->order_by('points', 'DESC');
                $ci->db->order_by('goal_difference', 'DESC');
                $ci->db->order_by('goal_for', 'DESC');
                $ci->db->from('football_league_teams');
                $ci->db->where('football_league_teams.league_id', $league->id);
                $data['teams'] = $ci->db->get()->result();
                $data['selected_league'] = $league;
                foreach ($data['teams'] as $team) {
                    $ci = &get_instance();
                    $ci->db->order_by('date', 'DESC');
                    $ci->db->limit('5');
                    $ci->db->from('football_league_features');
                    $ci->db->where("(football_league_features.team_1 = '" . $team->id . "' OR football_league_features.team_2=' " . $team->id . "')");
                    $ci->db->where('football_league_features.winner !=', null);
                    $team_results = $ci->db->get()->result();
                    $last_result = "<ul class=\"result-table\">";
                    foreach ($team_results as $result) {
                        if ($result->winner == 0) {
                            $last_result .= "<li>D</li>";
                        } elseif ($result->winner == $team->id) {
                            $last_result .= "<li class=\"text-success\">W</li>";
                        } else {
                            $last_result .= "<li class=\"text-danger\">L</li>";
                        }
                    }

                    $data['teams_results'][$team->id] = $last_result;
                }
            }
            echo json_encode(['data' => $data]);
            return 1;
        }

        if (isset($_SERVER['HTTP_REFERER'])) {
            $url = explode('/', explode(base_url(), $_SERVER['HTTP_REFERER'])[1]);
        } else {
            $url = [];
        }
        $category_id = getCategoryIDBySlug(isset($url[1]) ? $url[1] : "sports");
        $sub_category_id = getCategoryIDBySlug(isset($url[2]) ? $url[2] : "");
        $child_category_id = getCategoryIDBySlug(isset($url[3]) ? $url[3] : "");
        $sub_category = '';
        $child_category = '';
        $sub_category_menu = null;
        if ($category_id) {
            $main_category = $this->db->select('*')
                ->get_where('post_category', array('id' => $category_id))
                ->row();
            $this->db->select('*');
            $this->db->where('parent_id', $category_id)->where('sub_category_id', 0);
            $this->db->from('post_category');
            $sub_category_menu = $this->db->get()->result();
        } else {
            $main_category = $this->db->select('*')
                ->get_where('post_category', array('id' => $league->post_category_id))
                ->row();
            $this->db->select('*');
            $this->db->where('parent_id', $league->post_category_id)->where('sub_category_id', 0);
            $this->db->from('post_category');
            $sub_category_menu = $this->db->get()->result();
            $category_id = $league->post_category_id;
        }
        if ($sub_category_id) {
            $sub_category = $this->db->select('*')
                ->get_where('post_category', array('id' => $sub_category_id))
                ->row();
        }
        if ($child_category_id) {
            $child_category = $this->db->select('*')
                ->get_where('post_category', array('id' => $child_category_id))
                ->row();
        }
        $this->category_sql($category_id, $sub_category_id);
        $this->db->join('post_category as sub', 'sub.id = posts.sub_category_id');
        $this->db->where('sub.template_design !=', '11');
        $total = $this->db->count_all_results();
        $start = startPointOfPagination(16, 1);
        $this->category_sql($category_id, $sub_category_id);
        $this->db->join('post_category as sub', 'sub.id = posts.sub_category_id');
        $this->db->where('sub.template_design !=', '11');
        $this->db->limit(16, $start);
        $this->db->order_by('modified', 'DESC');
        $posts = $this->db->get()->result();
        $cid = $sub_category_id ? $sub_category_id : $category_id;
        $this->db->where('id', $cid);
        $this->db->from('post_category');
        $cdata = $this->db->get()->row();

        if ($cdata) {
            $data = array(
                'posts_data' => $posts,
                'meta_title' => ($cdata->seo_title) ? $cdata->seo_title : $cdata->name,
                'meta_description' => $cdata->seo_keyword,
                'meta_keywords' => $cdata->seo_description,
                'main_category' => $main_category,
                'sub_category' => $sub_category,
                'sub_category_menu' => $sub_category_menu,
                'child_category' => $child_category,
                'total' => $total
            );
            $ci = &get_instance();
            $ci->db->order_by('points', 'DESC');
            $ci->db->order_by('goal_difference', 'DESC');
            $ci->db->order_by('goal_for', 'DESC');
            $ci->db->from('football_league_teams');
            $ci->db->where('football_league_teams.league_id', $league->id);
            $data['teams'] = $ci->db->get()->result();
            $ci = &get_instance();
            $ci->db->order_by('id', 'DESC');
            $ci->db->from('leagues');
            $ci->db->where('post_category_id', $main_category->id);
            $ci->db->where('game_type', $league->game_type);
            $data['leagues'] = $ci->db->get()->result();
            $data['sports_video'] = getSportsVideos($sub_category_id);
            if ($type == 'football') {
                $ci = &get_instance();
                $ci->db->order_by('points', 'DESC');
                $ci->db->order_by('goal_difference', 'DESC');
                $ci->db->order_by('goal_for', 'DESC');
                $ci->db->from('football_league_teams');
                $ci->db->where('football_league_teams.league_id', $league->id);
                $data['teams'] = $ci->db->get()->result();
                $data['selected_league'] = $league;
                foreach ($data['teams'] as $team) {
                    $ci = &get_instance();
                    $ci->db->order_by('date', 'DESC');
                    $ci->db->limit('5');
                    $ci->db->from('football_league_features');
                    $ci->db->where("(football_league_features.team_1 = '" . $team->id . "' OR football_league_features.team_2=' " . $team->id . "')");
                    $ci->db->where('football_league_features.winner !=', null);
                    $team_results = $ci->db->get()->result();
                    $last_result = "<ul class=\"result-table\">";
                    foreach ($team_results as $result) {
                        if ($result->winner == 0) {
                            $last_result .= "<li>D</li>";
                        } elseif ($result->winner == $team->id) {
                            $last_result .= "<li class=\"text-success\">W</li>";
                        } else {
                            $last_result .= "<li class=\"text-danger\">L</li>";
                        }
                    }

                    $data['teams_results'][$team->id] = $last_result;
                }
                $this->viewFrontContent('frontend/template/football_tables', $data);
            } elseif ($type == 'tennis') {

                $ci = &get_instance();
                $ci->db->order_by('points', 'DESC');
                $ci->db->from('tennis_standing_table');
                $ci->db->where('tennis_standing_table.league_id', $league->id);
                $ci->db->where('tennis_standing_table.gender', 1);
                $data['men_teams'] = $ci->db->get()->result();
                $ci = &get_instance();
                $ci->db->order_by('points', 'DESC');
                $ci->db->from('tennis_standing_table');
                $ci->db->where('tennis_standing_table.league_id', $league->id);
                $ci->db->where('tennis_standing_table.gender', 2);
                $data['women_teams'] = $ci->db->get()->result();
                $data['selected_league'] = $league;
                $this->viewFrontContent('frontend/template/tennis_standings', $data);
            } elseif ($type == 'boxing') {
                $detailsData = $this->getBoxingData($league, 1);
                $data['men_teams'] = $detailsData['teams'];
                $detailsDataWomen = $this->getBoxingData($league, 2);
                $data['women_teams'] = $detailsDataWomen['teams'];
                $data['selected_league'] = $league;
                $this->viewFrontContent('frontend/template/boxing_standings', $data);
            } elseif ($type == 'basketball') {
                $ci = &get_instance();
                $ci->db->order_by('points', 'DESC');
                $ci->db->from('basketball_league_teams');
                $ci->db->where('basketball_league_teams.league_id', $league->id);
                $data['teams'] = $ci->db->get()->result();
                $data['selected_league'] = $league;
                $this->viewFrontContent('frontend/template/basketball_tables', $data);
            } else {
                $ci = &get_instance();
                $ci->db->order_by('points', 'DESC');
                $ci->db->order_by('goal_difference', 'DESC');
                $ci->db->order_by('goal_for', 'DESC');
                $ci->db->from('football_league_teams');
                $ci->db->where('football_league_teams.league_id', $league->id);
                $data['teams'] = $ci->db->get()->result();
                $data['selected_league'] = $league;
                foreach ($data['teams'] as $team) {
                    $ci = &get_instance();
                    $ci->db->order_by('date', 'DESC');
                    $ci->db->limit('5');
                    $ci->db->from('football_league_features');
                    $ci->db->where("(football_league_features.team_1 = '" . $team->id . "' OR football_league_features.team_2=' " . $team->id . "')");
                    $ci->db->where('football_league_features.winner !=', null);
                    $team_results = $ci->db->get()->result();
                    $last_result = "<ul class=\"result-table\">";
                    foreach ($team_results as $result) {
                        if ($result->winner == 0) {
                            $last_result .= "<li>D</li>";
                        } elseif ($result->winner == $team->id) {
                            $last_result .= "<li class=\"text-success\">W</li>";
                        } else {
                            $last_result .= "<li class=\"text-danger\">L</li>";
                        }
                    }

                    $data['teams_results'][$team->id] = $last_result;
                }
                $this->viewFrontContent('frontend/template/football_tables', $data);
            }
        }
    }

    private function getTennisData($league, $player_id = null)
    {
        $ci = &get_instance();
        $ci->db->order_by('DATE(tennis_leagues_matchs.datetime)', 'DESC');
        $ci->db->select("
        	COALESCE(player1.`name`, GROUP_CONCAT(DISTINCT(team1_player.`name`))) as player_1, 
            COALESCE(player1.`slug`, GROUP_CONCAT(DISTINCT(team1_player.`slug`))) as slug_1,
            COALESCE(player1.picture, GROUP_CONCAT(DISTINCT(team1_player.picture))) as picture_1,
            
            COALESCE(player2.`name`, GROUP_CONCAT(DISTINCT(team2_player.`name`))) as player_2, 
            COALESCE(player2.`slug`, GROUP_CONCAT(DISTINCT(team2_player.`slug`))) as slug_2,
            COALESCE(player2.picture, GROUP_CONCAT(DISTINCT(team2_player.picture))) as picture_2,
        
            tennis_leagues_matchs.winner, 
            tennis_leagues_matchs.datetime, 
            tennis_leagues_matchs.slug,
            tennis_leagues_matchs.team_1 as team_1_id,
            tennis_leagues_matchs.team_2 as team_2_id,
            tennis_leagues_matchs.player_1 as player_1_id,
            tennis_leagues_matchs.player_2 as player_2_id,
            tennis_leagues_matchs.team_type as team_type
        ");
        $ci->db->from('tennis_leagues_matchs');
        $ci->db->join('tennis_players AS player1', "tennis_leagues_matchs.player_1 = player1.id AND tennis_leagues_matchs.team_type = 'single'", 'LEFT OUTER');
        $ci->db->join('tennis_players AS player2', "tennis_leagues_matchs.player_2 = player2.id AND tennis_leagues_matchs.team_type = 'single'", 'LEFT OUTER');
        $ci->db->join('tennis_teams_players as team2', "tennis_leagues_matchs.team_2 = team2.team_id AND tennis_leagues_matchs.team_type = 'double'", 'LEFT OUTER');
        $ci->db->join('tennis_players as team2_player', "team2.player_id = team2_player.id", 'LEFT OUTER');
        $ci->db->join('tennis_teams_players as team1', "tennis_leagues_matchs.team_1 = team1.team_id AND tennis_leagues_matchs.team_type = 'double'", 'LEFT OUTER');
        $ci->db->join('tennis_players as team1_player', "team1.player_id = team1_player.id", 'LEFT OUTER');
        $ci->db->join('tennis_leagues_matchs as self', "self.session_id > tennis_leagues_matchs.session_id", 'LEFT');
        $ci->db->where('self.id IS NULL');
        $ci->db->where('tennis_leagues_matchs.league_id', !empty($league) ? $league->id : 0);
        $ci->db->where('DATE(tennis_leagues_matchs.datetime) <=', Carbon\Carbon::now()->format('Y-m-d'));
        $ci->db->where('tennis_leagues_matchs.winner !=', null);

        if (!empty($player_id)) {
            $this->db->group_start();
            $this->db->where('tennis_leagues_matchs.player_1', $player_id);
            $this->db->or_where('tennis_leagues_matchs.player_2', $player_id);
            $this->db->or_where('team2.player_id', $player_id);
            $this->db->or_where('team1.player_id', $player_id);
            $this->db->group_end();
        }
        $ci->db->group_by("tennis_leagues_matchs.id");

        $result_sub_sql = $ci->db->get_compiled_select();

        $ci->db->select('
        	GROUP_CONCAT( temp.player_1 SEPARATOR "|" ) as player_1,
            GROUP_CONCAT( temp.slug_1 SEPARATOR "|" ) as slug_1,
            GROUP_CONCAT( temp.picture_1 SEPARATOR "|" ) as picture_1,
            GROUP_CONCAT( temp.player_2 SEPARATOR "|" ) as player_2,
            GROUP_CONCAT( temp.slug_2 SEPARATOR "|" ) as slug_2,
            GROUP_CONCAT( temp.picture_2 SEPARATOR "|" ) as picture_2,
            GROUP_CONCAT( temp.winner SEPARATOR "|" ) as winner,
            GROUP_CONCAT( temp.slug SEPARATOR "|" ) as slug,
            GROUP_CONCAT( temp.team_1_id SEPARATOR "|" ) as team_1_id,
            GROUP_CONCAT( temp.team_2_id SEPARATOR "|" ) as team_2_id,
            GROUP_CONCAT( temp.player_1_id SEPARATOR "|" ) as player_1_id,
            GROUP_CONCAT( temp.player_2_id SEPARATOR "|" ) as player_2_id,
            GROUP_CONCAT( temp.team_type SEPARATOR "|" ) as team_type,
            datetime
        ');
        $ci->db->from('(' . $result_sub_sql . ') temp');
        $ci->db->group_by("DATE(temp.datetime)");
        $data['results'] = $ci->db->get()->result();

        //print_r($league);die;
        // print_r($ci->db->last_query());die;
        $ci = &get_instance();
        $ci->db->order_by('DATE(tennis_leagues_matchs.datetime)', 'DESC');
        $ci->db->select("
        	COALESCE(player1.`name`, GROUP_CONCAT(DISTINCT(team1_player.`name`))) as player_1, 
            COALESCE(player1.`slug`, GROUP_CONCAT(DISTINCT(team1_player.`slug`))) as slug_1,
            COALESCE(player1.picture, GROUP_CONCAT(DISTINCT(team1_player.picture))) as picture_1,
            
            COALESCE(player2.`name`, GROUP_CONCAT(DISTINCT(team2_player.`name`))) as player_2, 
            COALESCE(player2.`slug`, GROUP_CONCAT(DISTINCT(team2_player.`slug`))) as slug_2,
            COALESCE(player2.picture, GROUP_CONCAT(DISTINCT(team2_player.picture))) as picture_2,
        
            tennis_leagues_matchs.winner, 
            tennis_leagues_matchs.datetime, 
            tennis_leagues_matchs.slug
        ");
        $ci->db->from('tennis_leagues_matchs');
        $ci->db->join('tennis_players AS player1', "tennis_leagues_matchs.player_1 = player1.id AND tennis_leagues_matchs.team_type = 'single'", 'LEFT OUTER');
        $ci->db->join('tennis_players AS player2', "tennis_leagues_matchs.player_2 = player2.id AND tennis_leagues_matchs.team_type = 'single'", 'LEFT OUTER');
        $ci->db->join('tennis_teams_players as team2', "tennis_leagues_matchs.team_2 = team2.team_id AND tennis_leagues_matchs.team_type = 'double'", 'LEFT OUTER');
        $ci->db->join('tennis_players as team2_player', "team2.player_id = team2_player.id", 'LEFT OUTER');
        $ci->db->join('tennis_teams_players as team1', "tennis_leagues_matchs.team_1 = team1.team_id AND tennis_leagues_matchs.team_type = 'double'", 'LEFT OUTER');
        $ci->db->join('tennis_players as team1_player', "team1.player_id = team1_player.id", 'LEFT OUTER');
        $ci->db->join('tennis_leagues_matchs as self', "self.session_id > tennis_leagues_matchs.session_id", 'LEFT');
        $ci->db->where('self.id IS NULL');
        $ci->db->where('tennis_leagues_matchs.league_id', !empty($league) ? $league->id : 0);
        $ci->db->where('DATE(tennis_leagues_matchs.datetime) >=', Carbon\Carbon::now()->format('Y-m-d'));
        $ci->db->where('tennis_leagues_matchs.winner', null);

        if (!empty($player_id)) {
            $this->db->group_start();
            $this->db->where('tennis_leagues_matchs.player_1', $player_id);
            $this->db->or_where('tennis_leagues_matchs.player_2', $player_id);
            $this->db->or_where('team2.player_id', $player_id);
            $this->db->or_where('team1.player_id', $player_id);
            $this->db->group_end();
        }
        $ci->db->group_by("tennis_leagues_matchs.id");

        $result_sub_sql = $ci->db->get_compiled_select();

        $ci->db->select('
        	GROUP_CONCAT( temp.player_1 SEPARATOR "|" ) as player_1,
            GROUP_CONCAT( temp.slug_1 SEPARATOR "|" ) as slug_1,
            GROUP_CONCAT( temp.picture_1 SEPARATOR "|" ) as picture_1,
            GROUP_CONCAT( temp.player_2 SEPARATOR "|" ) as player_2,
            GROUP_CONCAT( temp.slug_2 SEPARATOR "|" ) as slug_2,
            GROUP_CONCAT( temp.picture_2 SEPARATOR "|" ) as picture_2,
            GROUP_CONCAT( temp.slug SEPARATOR "|" ) as slug,
            GROUP_CONCAT( TIME(temp.datetime) SEPARATOR "|" ) as time,
            datetime
        ');
        $ci->db->from('(' . $result_sub_sql . ') temp');
        $ci->db->group_by("DATE(temp.datetime)");
        $data['features'] = $ci->db->get()->result();

        $league_id = !empty($league) ? $league->id : 0;
        $data['teams'] = $ci->db->query("
        SELECT
            tennis_players.`name`, 
            tennis_players.slug, 
                tennis_players.nationality, 
                tennis_players.point_single as point
            FROM
                tennis_players
            WHERE
                tennis_players.gender = 'men'
            ORDER BY
            tennis_players.point_single DESC
            
        
        ")->result();

        return $data;
    }

    private function getFootballData($league)
    {
        $ci = &get_instance();
        $ci->db->order_by('football_leagues_matchs.datetime', 'DESC');
        $ci->db->select('GROUP_CONCAT(if(football_leagues_matchs.team_1_goal is NULL, 0, football_leagues_matchs.team_1_goal) ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_1_goal, 
                                 GROUP_CONCAT(if(football_leagues_matchs.team_2_goal is NULL, 0, football_leagues_matchs.team_2_goal) ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_2_goal, 
                                 GROUP_CONCAT(team1.name ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team1.slug ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_1_slug,
                                 GROUP_CONCAT(team2.slug ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_2_slug,
                                 GROUP_CONCAT(football_leagues_matchs.slug ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as match_slug,
                                 GROUP_CONCAT(team2.name ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(team2.logo ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_2_logo,
                                 GROUP_CONCAT(team1.logo ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_1_logo,
                                 GROUP_CONCAT(if(football_leagues_matchs.winner is NULL, "live", "finish") ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as match_status,
                                 football_leagues_matchs.datetime');
        $ci->db->from('football_leagues_matchs');
        $this->db->join('football_teams as team1', 'team1.id = football_leagues_matchs.team_1');
        $this->db->join('football_teams as team2', 'team2.id = football_leagues_matchs.team_2');
        $this->db->join('football_leagues_matchs as dup', 'dup.session_id > football_leagues_matchs.session_id', 'LEFT');

        $ci->db->where('football_leagues_matchs.league_id', isset($league) ? $league->id : 0);
        $ci->db->where('football_leagues_matchs.datetime <=', Carbon\Carbon::now()->format('Y-m-d H:i'));
//        $ci->db->where('football_leagues_matchs.winner !=', null);
        $ci->db->where('dup.id IS NULL');
        $ci->db->group_by("DATE(football_leagues_matchs.datetime)");
        $data['results'] = $ci->db->get()->result();


        $ci = &get_instance();
        $ci->db->select('GROUP_CONCAT(team1.name ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team2.name ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(team2.logo ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_2_logo,
                                 GROUP_CONCAT(team1.logo ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_1_logo,
                                 GROUP_CONCAT(team1.slug ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_1_slug,
                                 GROUP_CONCAT(team2.slug ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as team_2_slug,
                                 GROUP_CONCAT(football_leagues_matchs.slug ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as match_slug,
                                 GROUP_CONCAT(TIME(football_leagues_matchs.datetime) ORDER BY football_leagues_matchs.datetime DESC SEPARATOR ",") as time,
                                 football_leagues_matchs.datetime
                                 ');
        $ci->db->order_by('football_leagues_matchs.datetime', 'ASC');
        $ci->db->from('football_leagues_matchs');
        $ci->db->group_by("DATE(football_leagues_matchs.datetime)");
        $this->db->join('football_teams as team1', 'team1.id = football_leagues_matchs.team_1');
        $this->db->join('football_teams as team2', 'team2.id = football_leagues_matchs.team_2');
        $this->db->join('football_leagues_matchs as dup', 'dup.session_id > football_leagues_matchs.session_id', 'LEFT');
        $ci->db->where('football_leagues_matchs.league_id', isset($league) ? $league->id : 0);
        $ci->db->where('football_leagues_matchs.datetime >', Carbon\Carbon::now()->format('Y-m-d H:i'));
//        $ci->db->where('football_leagues_matchs.winner', null);
        $ci->db->where('dup.id IS NULL');
        $data['features'] = $ci->db->get()->result();

        $ci = &get_instance();
        $ci->db->order_by('football_leagues_teams.points', 'DESC');
        $ci->db->order_by('football_leagues_teams.goal_difference', 'DESC');
        $ci->db->order_by('football_leagues_teams.played', 'ASC');

        $ci->db->select('football_leagues_teams.*, team.name, team.slug');
        $ci->db->from('football_leagues_teams');
        $this->db->join('football_teams as team', 'team.id = football_leagues_teams.team_id', 'INNER');
        $this->db->join('football_leagues_teams as dup', 'dup.league_id = football_leagues_teams.league_id AND dup.session_id > football_leagues_teams.session_id', 'LEFT');
        if (!empty($league) && !empty($league->is_group)) {
            $this->db->join('football_leagues_teams as self', 'self.league_id = football_leagues_teams.league_id AND self.group_id > football_leagues_teams.group_id', 'LEFT');
            $ci->db->where('self.id IS NULL');

        }
        $ci->db->where('football_leagues_teams.league_id', isset($league) ? $league->id : 0);
        $ci->db->where('dup.id IS NULL');
        $data['teams'] = $ci->db->get()->result();

        return $data;
    }

    private function getBoxingData($league = null, $gender = 1)
    {
        $ci = &get_instance();
        $query = $ci->db->query("SELECT boxing_leagues.weight_category, boxing_leagues.championship, (CASE 
                    WHEN boxing_leagues.weight_category = 1 THEN 'Heavy' 
                    WHEN boxing_leagues.weight_category = 2 THEN 'Cruiser' 
                    WHEN boxing_leagues.weight_category = 3 THEN 'Light-heavy' 
                    WHEN boxing_leagues.weight_category = 4 THEN 'Super-middle' 
                    WHEN boxing_leagues.weight_category = 5 THEN 'Middle' 
                    WHEN boxing_leagues.weight_category = 6 THEN 'Light-middle' 
                    WHEN boxing_leagues.weight_category = 7 THEN 'Welter' 
                    WHEN boxing_leagues.weight_category = 8 THEN 'Light-welter' 
                    WHEN boxing_leagues.weight_category = 9 THEN 'Light' 
                    WHEN boxing_leagues.weight_category = 10 THEN 'Super-feather' 
                    WHEN boxing_leagues.weight_category = 11 THEN 'Feather' 
                    WHEN boxing_leagues.weight_category = 12 THEN 'Super-bantam' 
                    WHEN boxing_leagues.weight_category = 13 THEN 'Bantam' 
                    WHEN boxing_leagues.weight_category = 14 THEN 'Super-fly' 
                    WHEN boxing_leagues.weight_category = 15 THEN 'Fly' 
                    WHEN boxing_leagues.weight_category = 16 THEN 'Light-fly' 
                    WHEN boxing_leagues.weight_category = 17 THEN 'Strawweight' 
                    ELSE null END) 'weight',
                    (CASE WHEN boxing_leagues.championship = '1' THEN boxing_players.name ELSE null END) 'wbc',
                    (CASE WHEN boxing_leagues.championship = '2' THEN boxing_players.name ELSE null END) 'wba',
                    (CASE WHEN boxing_leagues.championship = '3' THEN boxing_players.name ELSE null END) 'ibf',
                    (CASE WHEN boxing_leagues.championship = '4' THEN boxing_players.name ELSE null END) 'wbo',
                    (CASE WHEN boxing_leagues.championship = '5' THEN boxing_players.name ELSE null END) 'british',
                    
                    (CASE WHEN boxing_leagues.championship = '1' THEN boxing_players.slug ELSE null END) 'wbc_slug',
                    (CASE WHEN boxing_leagues.championship = '2' THEN boxing_players.slug ELSE null END) 'wba_slug',
                    (CASE WHEN boxing_leagues.championship = '3' THEN boxing_players.slug ELSE null END) 'ibf_slug',
                    (CASE WHEN boxing_leagues.championship = '4' THEN boxing_players.slug ELSE null END) 'wbo_slug',
                    (CASE WHEN boxing_leagues.championship = '5' THEN boxing_players.slug ELSE null END) 'british_slug',
                    
                    (CASE WHEN boxing_leagues.championship = '1' THEN boxing_players.image ELSE null END) 'wbc_image',
                    (CASE WHEN boxing_leagues.championship = '2' THEN boxing_players.image ELSE null END) 'wba_image',
                    (CASE WHEN boxing_leagues.championship = '3' THEN boxing_players.image ELSE null END) 'ibf_image',
                    (CASE WHEN boxing_leagues.championship = '4' THEN boxing_players.image ELSE null END) 'wbo_image',
                    (CASE WHEN boxing_leagues.championship = '5' THEN boxing_players.image ELSE null END) 'british_image'
                FROM boxing_league_features 
                    JOIN boxing_leagues ON boxing_league_features.league_id = boxing_leagues.id 
                    LEFT JOIN boxing_leagues l2 ON boxing_leagues.id < l2.id AND boxing_leagues.weight_category = l2.weight_category 
                    AND boxing_leagues.championship = l2.championship AND boxing_leagues.gender = l2.gender
                    LEFT JOIN boxing_players ON boxing_league_features.winner = boxing_players.id 
                WHERE boxing_league_features.winner IS NOT null 
                    AND l2.id is NULL
                    and boxing_leagues.gender = $gender
                ORDER BY boxing_leagues.weight_category;");
        $results = $query->result();
        $teams = [];
        foreach ($results as $result) {
            if (!array_key_exists($result->weight_category, $teams)) {
                $teams[$result->weight_category]['weight'] = $result->weight;
            }

            if ($result->championship == 1) {
                $teams[$result->weight_category]['wbc'] = $result->wbc;
                $teams[$result->weight_category]['wbc_slug'] = $result->wbc_slug;
                $teams[$result->weight_category]['wbc_image'] = $result->wbc_image;
            } elseif ($result->championship == 2) {
                $teams[$result->weight_category]['wba'] = $result->wba;
                $teams[$result->weight_category]['wba_slug'] = $result->wba_slug;
                $teams[$result->weight_category]['wba_image'] = $result->wba_image;
            } elseif ($result->championship == 3) {
                $teams[$result->weight_category]['ibf'] = $result->ibf;
                $teams[$result->weight_category]['ibf_slug'] = $result->ibf_slug;
                $teams[$result->weight_category]['ibf_image'] = $result->ibf_image;
            } elseif ($result->championship == 4) {
                $teams[$result->weight_category]['wbo'] = $result->wbo;
                $teams[$result->weight_category]['wbo_slug'] = $result->wbo_slug;
                $teams[$result->weight_category]['wbo_image'] = $result->wbo_image;
            } elseif ($result->championship == 5) {
                $teams[$result->weight_category]['british'] = $result->british;
                $teams[$result->weight_category]['british_slug'] = $result->british_slug;
                $teams[$result->weight_category]['british_image'] = $result->british_image;
            }
        }

        foreach ($teams as $key => $team) {
            if (!array_key_exists('wbc', $team)) {
                $teams[$key]['wbc'] = null;
                $teams[$key]['wbc_slug'] = null;
                $teams[$key]['wbc_image'] = null;
            }
            if (!array_key_exists('wba', $team)) {
                $teams[$key]['wba'] = null;
                $teams[$key]['wba_slug'] = null;
                $teams[$key]['wba_image'] = null;
            }
            if (!array_key_exists('ibf', $team)) {
                $teams[$key]['ibf'] = null;
                $teams[$key]['ibf_slug'] = null;
                $teams[$key]['ibf_image'] = null;
            }
            if (!array_key_exists('wbo', $team)) {
                $teams[$key]['wbo'] = null;
                $teams[$key]['wbo_slug'] = null;
                $teams[$key]['wbo_image'] = null;
            }
            if (!array_key_exists('british', $team)) {
                $teams[$key]['british'] = null;
                $teams[$key]['british_slug'] = null;
                $teams[$key]['british_image'] = null;
            }
        }

        if (!empty($league)) {
            $ci = &get_instance();
            $ci->db->order_by('boxing_league_features.date', 'DESC');
            $ci->db->select('GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team1.slug SEPARATOR ",") as team_1_slug,
                                 GROUP_CONCAT(team2.slug SEPARATOR ",") as team_2_slug,
                                 GROUP_CONCAT(team1.id SEPARATOR ",") as team_1_id,
                                 GROUP_CONCAT(team2.id SEPARATOR ",") as team_2_id,
                                 GROUP_CONCAT(boxing_league_features.slug SEPARATOR ",") as match_slug,
                                 GROUP_CONCAT(boxing_league_features.time SEPARATOR ",") as time,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(team2.image SEPARATOR ",") as team_2_logo,
                                 GROUP_CONCAT(team1.image SEPARATOR ",") as team_1_logo,
                                 boxing_league_features.date,
                                 ');
            $ci->db->from('boxing_league_features');
            $this->db->join('boxing_players as team1', 'team1.id = boxing_league_features.team_1');
            $this->db->join('boxing_players as team2', 'team2.id = boxing_league_features.team_2');
            $ci->db->where('boxing_league_features.league_id', isset($league) ? $league->id : 0);
            $ci->db->where('boxing_league_features.date > ', Carbon\Carbon::now()->format('Y-m-d'));
            $ci->db->where('boxing_league_features.winner is NULL', NULL, FALSE);
            $ci->db->group_by("boxing_league_features.date");
            $data['features'] = $ci->db->get()->result();
        } else {
            $data['features'] = [];
        }

        if (!empty($league)) {
            $ci = &get_instance();
            $ci->db->order_by('boxing_league_features.date', 'DESC');
            $ci->db->select('GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team1.slug SEPARATOR ",") as team_1_slug,
                                 GROUP_CONCAT(team2.slug SEPARATOR ",") as team_2_slug,
                                 GROUP_CONCAT(team1.id SEPARATOR ",") as team_1_id,
                                 GROUP_CONCAT(team2.id SEPARATOR ",") as team_2_id,
                                 GROUP_CONCAT(boxing_league_features.slug SEPARATOR ",") as match_slug,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(team2.image SEPARATOR ",") as team_2_logo,
                                 GROUP_CONCAT(team1.image SEPARATOR ",") as team_1_logo,
                                 GROUP_CONCAT(boxing_league_features.winner SEPARATOR ",") as winner,
                                 boxing_league_features.date,
                                 ');
            $ci->db->from('boxing_league_features');
            $this->db->join('boxing_players as team1', 'team1.id = boxing_league_features.team_1');
            $this->db->join('boxing_players as team2', 'team2.id = boxing_league_features.team_2');
            $ci->db->where('boxing_league_features.league_id', isset($league) ? $league->id : 0);
            $ci->db->where('boxing_league_features.date <=', Carbon\Carbon::now()->format('Y-m-d'));
            $ci->db->where('boxing_league_features.winner is NOT NULL', NULL, FALSE);
            $ci->db->group_by("boxing_league_features.date");
            $data['results'] = $ci->db->get()->result();
        } else {
            $data['results'] = [];
        }


        $data['teams'] = json_decode(json_encode($teams));

        return $data;
    }

    private function getFormula1Data($league, $team_id = null)
    {
//        $ci = & get_instance();
//        $ci->db->order_by('date', 'DESC');
//        $ci->db->select('GROUP_CONCAT(formula1_league_features.name SEPARATOR ",") as name,
//                                 GROUP_CONCAT(winner.name SEPARATOR ",") as winner_name,
//                                 GROUP_CONCAT(r1.name SEPARATOR ",") as first_runner_up_name,
//                                 GROUP_CONCAT(r2.name SEPARATOR ",") as second_runner_up_name,
//                                 formula1_league_features.date');
//        $ci->db->from('formula1_league_features');
//        $this->db->join('formula1_standing_table as winner', 'winner.id = formula1_league_features.winner');
//        $this->db->join('formula1_standing_table as r1', 'r1.id = formula1_league_features.first_runner_up');
//        $this->db->join('formula1_standing_table as r2', 'r2.id = formula1_league_features.second_runner_up');
//        $ci->db->where('formula1_league_features.league_id', isset($league) ? $league->id : 0);
//        $ci->db->where('date <=', Carbon\Carbon::now()->format('Y-m-d'));
//        $ci->db->where('winner !=', null);
//        $ci->db->group_by("date");
//        $data['results'] = $ci->db->get()->result();
        $ci = &get_instance();
        $ci->db->select('GROUP_CONCAT(formula1_driver.name order by formula1_feature.points DESC SEPARATOR ",") as driver,
                            GROUP_CONCAT(CASE when formula1_feature.grids is null then "--" else formula1_feature.grids end order by formula1_feature.points DESC SEPARATOR ",") as grids,
                            GROUP_CONCAT(CASE when formula1_feature.laps is null then "--" else formula1_feature.laps end order by formula1_feature.points DESC SEPARATOR ",") as laps,
                            GROUP_CONCAT(CASE when formula1_feature.time is null then "--" else formula1_feature.time end order by formula1_feature.points DESC SEPARATOR ",") as race_time,
                            GROUP_CONCAT(CASE when formula1_feature.fastest_lap is null then "--" else formula1_feature.fastest_lap end order by formula1_feature.points DESC SEPARATOR ",") as fastest_lap,
                            GROUP_CONCAT(CASE when formula1_feature.points is null then "--" else formula1_feature.points end order by formula1_feature.points DESC SEPARATOR ",") as point,
                            GROUP_CONCAT(formula1_team.name order by formula1_feature.points DESC SEPARATOR ",") as teams,
                                 TIME(formula1_feature.datetime) as time,
                                 DATE(formula1_feature.datetime) as date');
        $ci->db->order_by('formula1_feature.datetime', 'DESC');
        $ci->db->from('formula1_feature');
        $ci->db->group_by("DATE(formula1_feature.datetime)");
        $ci->db->group_by("TIME(formula1_feature.datetime)");
        $ci->db->join('formula1_driver', 'formula1_feature.driver_id = formula1_driver.id');
        $ci->db->join('formula1_team', 'formula1_feature.team_id = formula1_team.id');
        $ci->db->join('formula1_feature as self', 'self.league_id = formula1_feature.league_id AND self.session_id > formula1_feature.session_id', 'LEFT');
        $ci->db->where('formula1_feature.league_id', isset($league) ? $league->id : 0);
        $ci->db->where('self.id IS NULL');
        if (!empty($team_id)) {
            $ci->db->where('formula1_feature.team_id', $team_id);
        }
        $data['features'] = $ci->db->get()->result();

        $ci = &get_instance();
        $ci->db->select('formula1_driver.name, formula1_driver.slug, SUM(formula1_feature.victories) as victories, SUM(formula1_feature.points) as points');
        $ci->db->order_by('formula1_feature.points', 'DESC');
        $ci->db->order_by('formula1_feature.victories', 'DESC');
        $ci->db->from('formula1_feature');
        $ci->db->join('formula1_driver', 'formula1_feature.driver_id = formula1_driver.id');
        $ci->db->group_by('formula1_feature.driver_id');
        $data['teams'] = $ci->db->get()->result();

        return $data;
    }

    private function getBasketballData($league, $team_id = null)
    {
        $ci = &get_instance();
        $ci->db->order_by('basketball_leagues_matchs.datetime', 'DESC');
        $ci->db->select('GROUP_CONCAT(basketball_leagues_matchs.team_1_goal SEPARATOR ",") as team_1_goal, 
                                 GROUP_CONCAT(basketball_leagues_matchs.team_2_goal SEPARATOR ",") as team_2_goal, 
                                 GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(team2.logo SEPARATOR ",") as team_2_logo,
                                 GROUP_CONCAT(team1.logo SEPARATOR ",") as team_1_logo,
                                 GROUP_CONCAT(team1.slug SEPARATOR ",") as team_1_slug,
                                 GROUP_CONCAT(team2.slug SEPARATOR ",") as team_2_slug,
                                 GROUP_CONCAT(basketball_leagues_matchs.slug SEPARATOR ",") as match_slug,
                                 basketball_leagues_matchs.datetime');
        $ci->db->from('basketball_leagues_matchs');
        $this->db->join('basketball_teams as team1', 'team1.id = basketball_leagues_matchs.team_1');
        $this->db->join('basketball_teams as team2', 'team2.id = basketball_leagues_matchs.team_2');
        $this->db->join('basketball_leagues_matchs as dup', 'dup.session_id > basketball_leagues_matchs.session_id', 'LEFT');
        $ci->db->where('basketball_leagues_matchs.league_id', isset($league) ? $league->id : 0);
        $ci->db->where('DATE(basketball_leagues_matchs.datetime) <=', Carbon\Carbon::now()->format('Y-m-d'));
        $ci->db->where('basketball_leagues_matchs.winner !=', null);
        $ci->db->where('dup.id IS NULL');
        if (!empty($team_id)) {
            $this->db->group_start();
            $this->db->where('basketball_leagues_matchs.team_1', $team_id);
            $this->db->or_where('basketball_leagues_matchs.team_2', $team_id);
            $this->db->group_end();
        }
        $ci->db->group_by("DATE(basketball_leagues_matchs.datetime)");
        $data['results'] = $ci->db->get()->result();

//print_r($data['results']);die;
        $ci = &get_instance();
        $ci->db->select('GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(team2.logo SEPARATOR ",") as team_2_logo,
                                 GROUP_CONCAT(team1.logo SEPARATOR ",") as team_1_logo,
                                 GROUP_CONCAT(TIME(basketball_leagues_matchs.datetime) SEPARATOR ",") as time,
                                 GROUP_CONCAT(team1.slug SEPARATOR ",") as team_1_slug,
                                 GROUP_CONCAT(team2.slug SEPARATOR ",") as team_2_slug,
                                 GROUP_CONCAT(basketball_leagues_matchs.slug SEPARATOR ",") as match_slug,
                                 basketball_leagues_matchs.datetime');
        $ci->db->order_by('basketball_leagues_matchs.datetime', 'ASC');
        $ci->db->from('basketball_leagues_matchs');
        $ci->db->group_by("DATE(basketball_leagues_matchs.datetime)");
        $this->db->join('basketball_teams as team1', 'team1.id = basketball_leagues_matchs.team_1');
        $this->db->join('basketball_teams as team2', 'team2.id = basketball_leagues_matchs.team_2');
        $this->db->join('basketball_leagues_matchs as dup', 'dup.session_id > basketball_leagues_matchs.session_id', 'LEFT');
        $ci->db->where('basketball_leagues_matchs.league_id', isset($league) ? $league->id : 0);
        $ci->db->where('DATE(basketball_leagues_matchs.datetime) >=', Carbon\Carbon::now()->format('Y-m-d'));
        $ci->db->where('basketball_leagues_matchs.winner', null);
        $ci->db->where('dup.id IS NULL');
        if (!empty($team_id)) {
            $this->db->group_start();
            $this->db->where('basketball_leagues_matchs.team_1', $team_id);
            $this->db->or_where('basketball_leagues_matchs.team_2', $team_id);
            $this->db->group_end();
        }
        $data['features'] = $ci->db->get()->result();

        $ci = &get_instance();
        $ci->db->order_by('basketball_leagues_teams.points', 'DESC');
        $ci->db->select('basketball_leagues_teams.*, team.name, team.slug');
        $ci->db->from('basketball_leagues_teams');
        $this->db->join('basketball_teams as team', 'team.id = basketball_leagues_teams.team_id', 'INNER');
        $this->db->join('basketball_leagues_teams as dup', 'dup.league_id = basketball_leagues_teams.league_id AND dup.session_id > basketball_leagues_teams.session_id', 'LEFT');
        $ci->db->where('basketball_leagues_teams.league_id', isset($league) ? $league->id : 0);
        $ci->db->where('dup.id IS NULL');
        //$ci->db->limit(10);
        $data['teams'] = $ci->db->get()->result();

        return $data;
    }

    private function _get_national_team_video($slug = '')
    {
        $tag_id = getTagIdBySlug($slug);

        $this->db->select('p.id, p.title, video01, video02, modified, post_url, p.youtube_json');
        $this->db->select("CONCAT(u.first_name, ' ', u.last_name) AS name, main_cat.name as main_name, sub_cat.name as sub_name, child_cat.name as child_name");
        $this->db->where_in('p.post_show', ['Frontend']);
        $this->db->where('p.status', 'Publish');
        $this->db->where('pt.tag_id', $tag_id);
        $this->db->from('post_tags as pt');
        $this->db->join('posts as p', 'p.id = pt.post_id');
        $this->db->join('users as u', 'u.id = p.user_id', 'LEFT');
        $this->db->join('post_category as main_cat', 'main_cat.id = p.category_id', 'LEFT');
        $this->db->join('post_category as sub_cat', 'sub_cat.id = p.sub_category_id', 'LEFT');
        $this->db->join('post_category as child_cat', 'child_cat.id = p.child_category_id', 'LEFT');
        $this->db->where("(main_cat.template_design='11' OR sub_cat.template_design='11' OR child_cat.template_design='11')");
    }

    private function _getFootballTeamData($teamName = "")
    {
        $ci = &get_instance();
        $ci->db->order_by('date', 'DESC');
        $ci->db->select('GROUP_CONCAT(football_league_features.team_1_goal SEPARATOR ",") as team_1_goal, 
                                 GROUP_CONCAT(football_league_features.team_2_goal SEPARATOR ",") as team_2_goal, 
                                 GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(leagues.name SEPARATOR ",") as league_name,
                                 GROUP_CONCAT(leagues.id SEPARATOR ",") as league_id,
                                 football_league_features.date');
        $ci->db->from('football_league_features');
        $ci->db->join('football_league_teams as team1', 'team1.id = football_league_features.team_1');
        $ci->db->join('football_league_teams as team2', 'team2.id = football_league_features.team_2');
        $ci->db->join('leagues', 'leagues.id = football_league_features.league_id');
        $ci->db->where('date <=', Carbon\Carbon::now()->format('Y-m-d'));
//        $ci->db->where('winner !=', null);
        $ci->db->where("(team1.name like '%" . $teamName . "%' OR team2.name like '%" . $teamName . "%')");
        $ci->db->group_by("date");
        $ci->db->group_by("leagues.id");
        $data['results'] = $ci->db->get()->result();
        $ci = &get_instance();
        $ci->db->select('GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(football_league_features.time SEPARATOR ",") as time,
                                 GROUP_CONCAT(leagues.name SEPARATOR ",") as league_name,
                                 GROUP_CONCAT(leagues.id SEPARATOR ",") as league_id,
                                 football_league_features.date');
        $ci->db->order_by('date', 'ASC');
        $ci->db->from('football_league_features');
        $ci->db->group_by("date");
        $ci->db->group_by("leagues.id");
        $ci->db->join('football_league_teams as team1', 'team1.id = football_league_features.team_1', "LEFT");
        $ci->db->join('football_league_teams as team2', 'team2.id = football_league_features.team_2', "LEFT");
        $ci->db->join('leagues', 'leagues.id = football_league_features.league_id', "LEFT");
        $ci->db->where('date >', Carbon\Carbon::now()->format('Y-m-d'));
//        $ci->db->where('winner', null);
        $ci->db->where("(team1.name like '%" . $teamName . "%' OR team2.name like '%" . $teamName . "%')");
        $data['features'] = $ci->db->get()->result();
        $ci = &get_instance();
        $ci->db->select("football_league_teams.league_id, leagues.name, leagues.id");
        $ci->db->from('football_league_teams');
        $ci->db->like('football_league_teams.name', $teamName);
        $ci->db->join('leagues', 'leagues.id = football_league_teams.league_id', "LEFT");
        $data['team_leagues'] = $ci->db->get()->result();
        $leagues = [];
        foreach ($data['team_leagues'] as $league) {
            $leagues[] = $league->league_id;
        }
        $ci = &get_instance();
        $ci->db->order_by('points', 'DESC');
        $ci->db->order_by('goal_difference', 'DESC');
        $ci->db->order_by('goal_for', 'DESC');
        $ci->db->from('football_league_teams');
        $ci->db->where('football_league_teams.league_id', isset($leagues[0]) ? $leagues[0] : 0);
        $data['teams'] = $ci->db->get()->result();

        return $data;
    }

    private function _getTennisTeamData($teamName = "", $gender = 1)
    {
        $ci = &get_instance();
        $ci->db->order_by('date', 'DESC');
        $ci->db->select('GROUP_CONCAT(tennis_league_features.team_1_point SEPARATOR ",") as team_1_point, 
                                 GROUP_CONCAT(tennis_league_features.team_2_point SEPARATOR ",") as team_2_point, 
                                 GROUP_CONCAT(tennis_league_features.date SEPARATOR ",") as date, 
                                 GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(leagues.name SEPARATOR ",") as league_name,
                                 GROUP_CONCAT(leagues.id SEPARATOR ",") as league_id,
                                 tennis_league_features.name');
        $ci->db->from('tennis_league_features');
        $ci->db->join('tennis_standing_table as team1', 'team1.id = tennis_league_features.team_1', "LEFT");
        $ci->db->join('tennis_standing_table as team2', 'team2.id = tennis_league_features.team_2', "LEFT");
        $ci->db->join('leagues', 'leagues.id = tennis_league_features.league_id', "LEFT");
        $ci->db->where('date <=', Carbon\Carbon::now()->format('Y-m-d'));
        $ci->db->where('winner !=', null);
        $ci->db->where("(team1.name like '%" . $teamName . "%' OR team2.name like '%" . $teamName . "%')");
        $ci->db->group_by("tennis_league_features.name");
        $data['results'] = $ci->db->get()->result();
        $ci = &get_instance();
        $ci->db->select('GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(tennis_league_features.time SEPARATOR ",") as time,
                                 GROUP_CONCAT(tennis_league_features.date SEPARATOR ",") as date,
                                 GROUP_CONCAT(leagues.name SEPARATOR ",") as league_name,
                                 GROUP_CONCAT(leagues.id SEPARATOR ",") as league_id,
                                 tennis_league_features.name');
        $ci->db->order_by('date', 'ASC');
        $ci->db->from('tennis_league_features');
        $ci->db->group_by("tennis_league_features.name");
        $ci->db->join('tennis_standing_table as team1', 'team1.id = tennis_league_features.team_1');
        $ci->db->join('tennis_standing_table as team2', 'team2.id = tennis_league_features.team_2');
        $ci->db->join('leagues', 'leagues.id = tennis_league_features.league_id');
        $ci->db->where('date >=', Carbon\Carbon::now()->format('Y-m-d'));
        $ci->db->where('winner', null);
        $ci->db->where("(team1.name like '%" . $teamName . "%' OR team2.name like '%" . $teamName . "%')");
        $data['features'] = $ci->db->get()->result();
        $ci = &get_instance();
        $ci->db->select("tennis_standing_table.league_id, leagues.name, leagues.id");
        $ci->db->from('tennis_standing_table');
        $ci->db->like('tennis_standing_table.name', $teamName);
        $ci->db->join('leagues', 'leagues.id = tennis_standing_table.league_id', "LEFT");
        $data['team_leagues'] = $ci->db->get()->result();
        $leagues = [];
        foreach ($data['team_leagues'] as $league) {
            $leagues[] = $league->league_id;
        }
        $ci = &get_instance();
        $ci->db->order_by('points', 'DESC');
        $ci->db->from('tennis_standing_table');
        $ci->db->where('tennis_standing_table.league_id', isset($leagues[0]) ? $leagues[0] : 0);
        $ci->db->where('tennis_standing_table.gender', $gender);
        $data['teams'] = $ci->db->get()->result();

        return $data;
    }

    private function _getFormula1TeamData($teamName = "")
    {
        $ci = &get_instance();
        $ci->db->select("formula1_drivers.league_id, leagues.name, leagues.id");
        $ci->db->from('formula1_drivers');
        $ci->db->like('formula1_drivers.name', $teamName);
        $ci->db->join('leagues', 'leagues.id = formula1_drivers.league_id');
        $data['team_leagues'] = $ci->db->get()->result();
        $leagues = [];
        foreach ($data['team_leagues'] as $league) {
            $leagues[] = $league->league_id;
        }
        $ci = &get_instance();
        $ci->db->select('GROUP_CONCAT(formula1_drivers.name order by formula1_drivers.points DESC SEPARATOR ",") as driver,
                            GROUP_CONCAT(CASE when formula1_drivers.grids is null then "--" else formula1_drivers.grids end order by formula1_drivers.points DESC SEPARATOR ",") as grids,
                            GROUP_CONCAT(CASE when formula1_drivers.laps is null then "--" else formula1_drivers.laps end order by formula1_drivers.points DESC SEPARATOR ",") as laps,
                            GROUP_CONCAT(CASE when formula1_drivers.time is null then "--" else formula1_drivers.time end order by formula1_drivers.points DESC SEPARATOR ",") as race_time,
                            GROUP_CONCAT(CASE when formula1_drivers.fastest_lap is null then "--" else formula1_drivers.fastest_lap end order by formula1_drivers.points DESC SEPARATOR ",") as fastest_lap,
                            GROUP_CONCAT(CASE when formula1_drivers.points is null then "--" else formula1_drivers.points end order by formula1_drivers.points DESC SEPARATOR ",") as point,
                            GROUP_CONCAT(formula1_standing_table.name order by formula1_drivers.points DESC SEPARATOR ",") as teams,
                                 formula1_league_features.time,
                                 formula1_league_features.date');
        $ci->db->order_by('date', 'DESC');
        $ci->db->from('formula1_league_features');
        $ci->db->group_by("date");
        $ci->db->group_by("formula1_league_features.time");
        $ci->db->join('formula1_standing_table', 'formula1_standing_table.league_id = formula1_league_features.league_id');
        $ci->db->join('formula1_drivers', 'formula1_standing_table.id = formula1_drivers.team_id');
        $ci->db->where('formula1_league_features.league_id', isset($leagues[0]) ? $leagues[0] : 0);
        $data['features'] = $ci->db->get()->result();
        $ci = &get_instance();
        $ci->db->select('name, SUM(victories) as victories, SUM(points) as points, SUM(podium) as podium,');
        $ci->db->order_by('points', 'DESC');
        $ci->db->order_by('victories', 'DESC');
        $ci->db->from('formula1_drivers');
        if (!empty($leagues)) {
            $ci->db->where_in('league_id', $leagues);
        } else {
            $ci->db->where('league_id', 0);
        }
        $ci->db->group_by('formula1_drivers.name');
        $data['teams'] = $ci->db->get()->result();

        return $data;
    }

    private function _getBoxingTeamData($teamName = "", $gender = 1)
    {
        $ci = &get_instance();
        $ci->db->select('GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(boxing_league_features.time SEPARATOR ",") as time,
                                 GROUP_CONCAT(boxing_league_features.location SEPARATOR ",") as location,
                                 GROUP_CONCAT(leagues.name SEPARATOR ",") as league_name,
                                 GROUP_CONCAT(leagues.id SEPARATOR ",") as league_id,
                                 boxing_league_features.date');
        $ci->db->order_by('date', 'ASC');
        $ci->db->from('boxing_league_features');
        $ci->db->group_by("date");
        $ci->db->join('boxing_standing_table as team1', 'team1.id = boxing_league_features.team_1', "LEFT");
        $ci->db->join('boxing_standing_table as team2', 'team2.id = boxing_league_features.team_2', "LEFT");
        $ci->db->join('leagues', 'leagues.id = boxing_league_features.league_id', "LEFT");
        $ci->db->where('date >=', Carbon\Carbon::now()->format('Y-m-d'));
        $ci->db->where("(team1.name like '%" . $teamName . "%' OR team2.name like '%" . $teamName . "%')");
        $ci->db->where('winner', null);
        $data['features'] = $ci->db->get()->result();
        $ci = &get_instance();
        $ci->db->select("boxing_standing_table.league_id, leagues.name, leagues.id");
        $ci->db->from('boxing_standing_table');
        $ci->db->like('boxing_standing_table.name', $teamName);
        $ci->db->join('leagues', 'leagues.id = boxing_standing_table.league_id', "LEFT");
        $data['team_leagues'] = $ci->db->get()->result();
        $leagues = [];
        $leaguesArray = "";
        foreach ($data['team_leagues'] as $league) {
            $leagues[] = $league->league_id;
            $leaguesArray = $leaguesArray . $league->league_id . ",";
        }

        $leaguesArray = substr($leaguesArray, 0, -1);


        $ci = &get_instance();
        $query = $ci->db->query("SELECT boxing_leagues.weight_category, boxing_leagues.championship, (CASE 
                    WHEN boxing_leagues.weight_category = 1 THEN 'Heavy' 
                    WHEN boxing_leagues.weight_category = 2 THEN 'Cruiser' 
                    WHEN boxing_leagues.weight_category = 3 THEN 'Light-heavy' 
                    WHEN boxing_leagues.weight_category = 4 THEN 'Super-middle' 
                    WHEN boxing_leagues.weight_category = 5 THEN 'Middle' 
                    WHEN boxing_leagues.weight_category = 6 THEN 'Light-middle' 
                    WHEN boxing_leagues.weight_category = 7 THEN 'Welter' 
                    WHEN boxing_leagues.weight_category = 8 THEN 'Light-welter' 
                    WHEN boxing_leagues.weight_category = 9 THEN 'Light' 
                    WHEN boxing_leagues.weight_category = 10 THEN 'Super-feather' 
                    WHEN boxing_leagues.weight_category = 11 THEN 'Feather' 
                    WHEN boxing_leagues.weight_category = 12 THEN 'Super-bantam' 
                    WHEN boxing_leagues.weight_category = 13 THEN 'Bantam' 
                    WHEN boxing_leagues.weight_category = 14 THEN 'Super-fly' 
                    WHEN boxing_leagues.weight_category = 15 THEN 'Fly' 
                    WHEN boxing_leagues.weight_category = 16 THEN 'Light-fly' 
                    WHEN boxing_leagues.weight_category = 17 THEN 'Strawweight' 
                    ELSE null END) 'weight',
                    (CASE WHEN boxing_leagues.championship = '1' THEN boxing_standing_table.name ELSE null END) 'wbc',
                    (CASE WHEN boxing_leagues.championship = '2' THEN boxing_standing_table.name ELSE null END) 'wba',
                    (CASE WHEN boxing_leagues.championship = '3' THEN boxing_standing_table.name ELSE null END) 'ibf',
                    (CASE WHEN boxing_leagues.championship = '4' THEN boxing_standing_table.name ELSE null END) 'wbo',
                    (CASE WHEN boxing_leagues.championship = '5' THEN boxing_standing_table.name ELSE null END) 'british'
                FROM boxing_league_features 
                    JOIN boxing_leagues ON boxing_league_features.league_id = boxing_leagues.league_id 
                    LEFT JOIN boxing_leagues l2 ON boxing_leagues.id < l2.id AND boxing_leagues.weight_category = l2.weight_category AND boxing_leagues.championship = l2.championship
                    LEFT JOIN boxing_standing_table ON boxing_league_features.winner = boxing_standing_table.id 
                    LEFT JOIN boxing_league_features t2 ON boxing_league_features.date < t2.date and boxing_league_features.league_id = t2.league_id
                WHERE boxing_league_features.winner IS NOT null 
                    AND l2.id is NULL
                    AND t2.id is NULL
                    AND boxing_league_features.league_id IN ($leaguesArray)
                    and boxing_standing_table.gender = $gender
                ORDER BY boxing_leagues.weight_category;");
        $results = $query->result();
        $teams = [];
        foreach ($results as $result) {
            if (!array_key_exists($result->weight_category, $teams)) {
                $teams[$result->weight_category]['weight'] = $result->weight;
            }

            if ($result->championship == 1) {
                $teams[$result->weight_category]['wbc'] = $result->wbc;
            } elseif ($result->championship == 2) {
                $teams[$result->weight_category]['wba'] = $result->wba;
            } elseif ($result->championship == 3) {
                $teams[$result->weight_category]['ibf'] = $result->ibf;
            } elseif ($result->championship == 4) {
                $teams[$result->weight_category]['wbo'] = $result->wbo;
            } elseif ($result->championship == 5) {
                $teams[$result->weight_category]['british'] = $result->british;
            }
        }

        foreach ($teams as $key => $team) {
            if (!array_key_exists('wbc', $team)) {
                $teams[$key]['wbc'] = null;
            }
            if (!array_key_exists('wba', $team)) {
                $teams[$key]['wba'] = null;
            }
            if (!array_key_exists('ibf', $team)) {
                $teams[$key]['ibf'] = null;
            }
            if (!array_key_exists('wbo', $team)) {
                $teams[$key]['wbo'] = null;
            }
            if (!array_key_exists('british', $team)) {
                $teams[$key]['british'] = null;
            }
        }

        $data['teams'] = json_decode(json_encode($teams));

        return $data;
    }

    private function _getBasketballTeamData($teamName = "")
    {
        $ci = &get_instance();
        $ci->db->order_by('date', 'DESC');
        $ci->db->select('GROUP_CONCAT(basketball_league_features.team_1_goal SEPARATOR ",") as team_1_goal, 
                                 GROUP_CONCAT(basketball_league_features.team_2_goal SEPARATOR ",") as team_2_goal, 
                                 GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(leagues.name SEPARATOR ",") as league_name,
                                 GROUP_CONCAT(leagues.id SEPARATOR ",") as league_id,
                                 basketball_league_features.date');
        $ci->db->from('basketball_league_features');
        $ci->db->join('basketball_league_teams as team1', 'team1.id = basketball_league_features.team_1');
        $ci->db->join('basketball_league_teams as team2', 'team2.id = basketball_league_features.team_2');
        $ci->db->join('leagues', 'leagues.id = basketball_league_features.league_id');
        $ci->db->where('date <=', Carbon\Carbon::now()->format('Y-m-d'));
        $ci->db->where('winner !=', null);
        $ci->db->where("(team1.name like '%" . $teamName . "%' OR team2.name like '%" . $teamName . "%')");
        $ci->db->group_by("date");
        $ci->db->group_by("leagues.id");
        $data['results'] = $ci->db->get()->result();
        $ci = &get_instance();
        $ci->db->select('GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(basketball_league_features.time SEPARATOR ",") as time,
                                 GROUP_CONCAT(leagues.name SEPARATOR ",") as league_name,
                                 GROUP_CONCAT(leagues.id SEPARATOR ",") as league_id,
                                 basketball_league_features.date');
        $ci->db->order_by('date', 'ASC');
        $ci->db->from('basketball_league_features');
        $ci->db->group_by("date");
        $ci->db->group_by("leagues.id");
        $ci->db->join('basketball_league_teams as team1', 'team1.id = basketball_league_features.team_1', "LEFT");
        $ci->db->join('basketball_league_teams as team2', 'team2.id = basketball_league_features.team_2', "LEFT");
        $ci->db->join('leagues', 'leagues.id = basketball_league_features.league_id', "LEFT");
        $ci->db->where('date >=', Carbon\Carbon::now()->format('Y-m-d'));
        $ci->db->where('winner', null);
        $ci->db->where("(team1.name like '%" . $teamName . "%' OR team2.name like '%" . $teamName . "%')");
        $data['features'] = $ci->db->get()->result();
        $ci = &get_instance();
        $ci->db->select("basketball_league_teams.league_id, leagues.name, leagues.id");
        $ci->db->from('basketball_league_teams');
        $ci->db->like('basketball_league_teams.name', $teamName);
        $ci->db->join('leagues', 'leagues.id = basketball_league_teams.league_id', "LEFT");
        $data['team_leagues'] = $ci->db->get()->result();
        $leagues = [];
        foreach ($data['team_leagues'] as $league) {
            $leagues[] = $league->league_id;
        }
        $ci = &get_instance();
        $ci->db->order_by('points', 'DESC');
        $ci->db->from('basketball_league_teams');
        $ci->db->where('basketball_league_teams.league_id', isset($leagues[0]) ? $leagues[0] : 0);
        $data['teams'] = $ci->db->get()->result();

        return $data;
    }

    public function league_team($gameType, $teamId)
    {
        $teamId = urldecode($teamId);
        $teamId = str_replace('_', ' ', $teamId);
        if ($gameType == 1) {
            $ci = &get_instance();
            $ci->db->from('football_league_teams');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId
            );
            $detailsData = $this->_getFootballTeamData($slug);
            $data['results'] = $detailsData['results'];
            $data['features'] = $detailsData['features'];
            $data['teams'] = $detailsData['teams'];
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/football_team_details', $data);
            return 1;
        } elseif ($gameType == 2) {
            $ci = &get_instance();
            $ci->db->from('tennis_standing_table');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId
            );
            $detailsData = $this->_getTennisTeamData($slug, $team->gender);
            $data['results'] = $detailsData['results'];
            $data['features'] = $detailsData['features'];
            $data['teams'] = $detailsData['teams'];
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/tennis_team_details', $data);
            return 1;
        } elseif ($gameType == 3) {
            $ci = &get_instance();
            $ci->db->from('boxing_standing_table');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId
            );
            $detailsData = $this->_getBoxingTeamData($slug, $team->gender);
            $data['features'] = $detailsData['features'];
            $data['teams'] = $detailsData['teams'];
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/boxing_team_details', $data);
            return 1;
        } elseif ($gameType == 4) {
            $ci = &get_instance();
            $ci->db->from('formula1_drivers');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId
            );
            $detailsData = $this->_getFormula1TeamData($slug);
            $data['features'] = $detailsData['features'];
            $data['teams'] = $detailsData['teams'];
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/formula1_team_details', $data);
            return 1;
        } elseif ($gameType == 5) {
            $ci = &get_instance();
            $ci->db->from('basketball_league_teams');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId
            );
            $detailsData = $this->_getBasketballTeamData($slug);
            $data['results'] = $detailsData['results'];
            $data['features'] = $detailsData['features'];
            $data['teams'] = $detailsData['teams'];
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/basketball_team_details', $data);
            return 1;
        } else {
            $ci = &get_instance();
            $ci->db->from('football_league_teams');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId
            );
            $detailsData = $this->_getFootballTeamData($slug);
            $data['results'] = $detailsData['results'];
            $data['features'] = $detailsData['features'];
            $data['teams'] = $detailsData['teams'];
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/football_team_details', $data);
            return 1;
        }
    }

    public function team_results_features($gameType, $teamId)
    {
        $teamId = urldecode($teamId);
        $teamId = str_replace('_', ' ', $teamId);
        if ($gameType == 1) {
            $ci = &get_instance();
            $ci->db->from('football_league_teams');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId,
            );
            $detailsData = $this->_getFootballTeamData($slug);
            $data['results'] = $detailsData['results'];
            $data['features'] = $detailsData['features'];
            $data['teams'] = $detailsData['teams'];
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/football_team_results_features', $data);
        } elseif ($gameType == 2) {
            $ci = &get_instance();
            $ci->db->from('tennis_standing_table');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId,
            );
            $detailsData = $this->_getTennisTeamData($slug, $team->gender);
            $data['results'] = $detailsData['results'];
            $data['features'] = $detailsData['features'];
            $data['teams'] = $detailsData['teams'];
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/tennis_team_results_features', $data);
        } elseif ($gameType == 3) {
            $ci = &get_instance();
            $ci->db->from('boxing_standing_table');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId
            );
            $detailsData = $this->_getBoxingTeamData($slug, $team->gender);
            $data['features'] = $detailsData['features'];
            $data['teams'] = $detailsData['teams'];
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/boxing_team_results_features', $data);
        } elseif ($gameType == 4) {
            $ci = &get_instance();
            $ci->db->from('formula1_drivers');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId,
            );
            $detailsData = $this->_getFormula1TeamData($slug);
            $data['features'] = $detailsData['features'];
            $data['teams'] = $detailsData['teams'];
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/formula1_team_results_features', $data);
        } elseif ($gameType == 5) {
            $ci = &get_instance();
            $ci->db->from('basketball_league_teams');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId,
            );
            $detailsData = $this->_getBasketballTeamData($slug);
            $data['results'] = $detailsData['results'];
            $data['features'] = $detailsData['features'];
            $data['teams'] = $detailsData['teams'];
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/basketball_team_results_features', $data);
        } else {
            $ci = &get_instance();
            $ci->db->from('football_league_teams');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId,
            );
            $detailsData = $this->_getFootballTeamData($slug);
            $data['results'] = $detailsData['results'];
            $data['features'] = $detailsData['features'];
            $data['teams'] = $detailsData['teams'];
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/football_team_results_features', $data);
        }
    }

    public function team_tables($gameType, $teamId)
    {
        $teamId = urldecode($teamId);
        $teamId = str_replace('_', ' ', $teamId);
        if ($gameType == 1) {
            $ci = &get_instance();
            $ci->db->from('football_league_teams');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId,
            );
            $detailsData = $this->_getFootballTeamData($slug);
            $data['leagues'] = $detailsData['team_leagues'];
            $ci = &get_instance();
            $ci->db->order_by('points', 'DESC');
            $ci->db->order_by('goal_difference', 'DESC');
            $ci->db->order_by('goal_for', 'DESC');
            $ci->db->from('football_league_teams');
            $ci->db->where('football_league_teams.league_id', $data['leagues'][0]->id);
            $data['teams'] = $ci->db->get()->result();
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            foreach ($data['teams'] as $team) {
                $ci = &get_instance();
                $ci->db->order_by('date', 'DESC');
                $ci->db->limit('5');
                $ci->db->from('football_league_features');
                $ci->db->where("(football_league_features.team_1 = '" . $team->id . "' OR football_league_features.team_2=' " . $team->id . "')");
                $ci->db->where('football_league_features.winner !=', null);
                $team_results = $ci->db->get()->result();
                $last_result = "<ul class=\"result-table\">";
                foreach ($team_results as $result) {
                    if ($result->winner == 0) {
                        $last_result .= "<li>D</li>";
                    } elseif ($result->winner == $team->id) {
                        $last_result .= "<li class=\"text-success\">W</li>";
                    } else {
                        $last_result .= "<li class=\"text-danger\">L</li>";
                    }
                }

                $data['teams_results'][$team->id] = $last_result;
            }
            $this->viewFrontContent('frontend/template/teams/football_team_tables', $data);
        } elseif ($gameType == 2) {
            $ci = &get_instance();
            $ci->db->from('tennis_standing_table');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId
            );
            $detailsData = $this->_getTennisTeamData($slug, $team->gender);
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $ci = &get_instance();
            $ci->db->order_by('points', 'DESC');
            $ci->db->from('tennis_standing_table');
            $ci->db->where('tennis_standing_table.league_id', $data['leagues'][0]->id);
            $ci->db->where('tennis_standing_table.gender', 1);
            $data['men_teams'] = $ci->db->get()->result();
            $ci = &get_instance();
            $ci->db->order_by('points', 'DESC');
            $ci->db->from('tennis_standing_table');
            $ci->db->where('tennis_standing_table.league_id', $data['leagues'][0]->id);
            $ci->db->where('tennis_standing_table.gender', 2);
            $data['women_teams'] = $ci->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/tennis_team_standings', $data);
        } elseif ($gameType == 3) {
            $ci = &get_instance();
            $ci->db->from('boxing_standing_table');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId
            );
            $detailsData = $this->_getBoxingTeamData($slug, $team->gender);
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $detailsData = $this->_getBoxingTeamData($slug, 1);
            $data['men_teams'] = $detailsData['teams'];
            $detailsDataWomen = $this->_getBoxingTeamData($slug, 2);
            $data['women_teams'] = $detailsDataWomen['teams'];
            $this->viewFrontContent('frontend/template/teams/boxing_team_standings', $data);
        } elseif ($gameType == 4) {
            $ci = &get_instance();
            $ci->db->from('formula1_drivers');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId
            );
            $detailsData = $this->_getFormula1TeamData($slug);
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $ci = &get_instance();
            $ci->db->select('name, SUM(victories) as victories, SUM(points) as points, SUM(podium) as podium,');
            $ci->db->order_by('points', 'DESC');
            $ci->db->order_by('victories', 'DESC');
            $ci->db->from('formula1_drivers');
            $ci->db->group_by('formula1_drivers.name');
            $data['driver_teams'] = $ci->db->get()->result();
            $ci = &get_instance();
            $ci->db->select('name, SUM(victory) as victory, SUM(points) as points, SUM(podium) as podium,');
            $ci->db->order_by('points', 'DESC');
            $ci->db->order_by('victory', 'DESC');
            $ci->db->from('formula1_standing_table');
            $ci->db->group_by('formula1_standing_table.name');
            $data['constructor_teams'] = $ci->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/formula1_team_standings', $data);
        } elseif ($gameType == 5) {
            $ci = &get_instance();
            $ci->db->from('basketball_league_teams');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId,
            );
            $detailsData = $this->_getBasketballTeamData($slug);
            $data['leagues'] = $detailsData['team_leagues'];
            $ci = &get_instance();
            $ci->db->order_by('points', 'DESC');
            $ci->db->from('basketball_league_teams');
            $ci->db->where('basketball_league_teams.league_id', $data['leagues'][0]->id);
            $data['teams'] = $ci->db->get()->result();
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/basketball_team_tables', $data);
        } else {
            $ci = &get_instance();
            $ci->db->from('football_league_teams');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId,
            );
            $detailsData = $this->_getFootballTeamData($slug);
            $data['leagues'] = $detailsData['team_leagues'];
            $ci = &get_instance();
            $ci->db->order_by('points', 'DESC');
            $ci->db->order_by('goal_difference', 'DESC');
            $ci->db->order_by('goal_for', 'DESC');
            $ci->db->from('football_league_teams');
            $ci->db->where('football_league_teams.league_id', $data['leagues'][0]->id);
            $data['teams'] = $ci->db->get()->result();
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            foreach ($data['teams'] as $team) {
                $ci = &get_instance();
                $ci->db->order_by('date', 'DESC');
                $ci->db->limit('5');
                $ci->db->from('football_league_features');
                $ci->db->where("(football_league_features.team_1 = '" . $team->id . "' OR football_league_features.team_2=' " . $team->id . "')");
                $ci->db->where('football_league_features.winner !=', null);
                $team_results = $ci->db->get()->result();
                $last_result = "<ul class=\"result-table\">";
                foreach ($team_results as $result) {
                    if ($result->winner == 0) {
                        $last_result .= "<li>D</li>";
                    } elseif ($result->winner == $team->id) {
                        $last_result .= "<li class=\"text-success\">W</li>";
                    } else {
                        $last_result .= "<li class=\"text-danger\">L</li>";
                    }
                }

                $data['teams_results'][$team->id] = $last_result;
            }
            $this->viewFrontContent('frontend/template/teams/football_team_tables', $data);
        }
    }

    public function national_team($gameType, $teamId)
    {
        $teamId = urldecode($teamId);
        $teamId = str_replace('_', ' ', $teamId);
        if ($gameType == 1) {
            $ci = &get_instance();
            $ci->db->from('country_teams');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId
            );
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/football_team_details', $data);
            return 1;
        } elseif ($gameType == 2) {
            $ci = &get_instance();
            $ci->db->from('tennis_standing_table');
            $ci->db->where('name', $teamId);
            $team = $ci->db->get()->row();
            $slug = $team->name;
            $this->tag_list_sql(slugify($slug));
            $this->db->order_by('modified', 'DESC');
            $posts = $this->db->get()->result();
            $data = array(
                'posts_data' => $posts,
                'tag_name' => $team->name,
                'meta_title' => $team->name,
                'gameType' => $gameType,
                'teamId' => $teamId
            );
            $detailsData = $this->_getTennisTeamData($slug, $team->gender);
            $data['results'] = $detailsData['results'];
            $data['features'] = $detailsData['features'];
            $data['teams'] = $detailsData['teams'];
            $data['leagues'] = $detailsData['team_leagues'];
            $data['selected_team'] = $team;
            $this->_get_national_team_video($slug);
            $this->db->order_by('modified', 'DESC');
            $data['sports_video'] = $this->db->get()->result();
            $this->viewFrontContent('frontend/template/teams/tennis_team_details', $data);
            return 1;
        }
    }

    public function load_tennis_team_results_fixture_tables()
    {
        $league_id = $this->input->post('league_id');
        $gender = $this->input->post('gender');
        $teamName = $this->input->post('team_name');

        $ci = &get_instance();
        $ci->db->from('leagues');
        $ci->db->where('id', $league_id);
        $data['leagues'] = $ci->db->get()->row();
        $ci = &get_instance();
        $ci->db->order_by('date', 'DESC');
        $ci->db->select('GROUP_CONCAT(tennis_league_features.team_1_point SEPARATOR ",") as team_1_point, 
                                 GROUP_CONCAT(tennis_league_features.team_2_point SEPARATOR ",") as team_2_point, 
                                 GROUP_CONCAT(tennis_league_features.date SEPARATOR ",") as date, 
                                 GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 tennis_league_features.name');
        $ci->db->from('tennis_league_features');
        $ci->db->join('tennis_standing_table as team1', 'team1.id = tennis_league_features.team_1');
        $ci->db->join('tennis_standing_table as team2', 'team2.id = tennis_league_features.team_2');
        $ci->db->like('tennis_league_features.name', $league_id);
        $ci->db->where('date <=', Carbon\Carbon::now()->format('Y-m-d'));
        $ci->db->where('winner !=', null);
        $ci->db->where("(team1.name like '%" . $teamName . "%' OR team2.name like '%" . $teamName . "%')");
        $ci->db->group_by("tennis_league_features.name");
        $data['results'] = $ci->db->get()->result();
        $ci = &get_instance();
        $ci->db->select('GROUP_CONCAT(team1.name SEPARATOR ",") as team_1_name,
                                 GROUP_CONCAT(team2.name SEPARATOR ",") as team_2_name,
                                 GROUP_CONCAT(tennis_league_features.time SEPARATOR ",") as time,
                                 GROUP_CONCAT(tennis_league_features.date SEPARATOR ",") as date,
                                 tennis_league_features.name');
        $ci->db->order_by('date', 'ASC');
        $ci->db->from('tennis_league_features');
        $ci->db->group_by("tennis_league_features.name");
        $ci->db->join('tennis_standing_table as team1', 'team1.id = tennis_league_features.team_1');
        $ci->db->join('tennis_standing_table as team2', 'team2.id = tennis_league_features.team_2');
        $ci->db->like('tennis_league_features.name', $league_id);
        $ci->db->where('date >=', Carbon\Carbon::now()->format('Y-m-d'));
        $ci->db->where('winner', null);
        $ci->db->where("(team1.name like '%" . $teamName . "%' OR team2.name like '%" . $teamName . "%')");
        $data['features'] = $ci->db->get()->result();
        $ci = &get_instance();
        $ci->db->order_by('points', 'DESC');
        $ci->db->from('tennis_standing_table');
        $ci->db->where('tennis_standing_table.league_id', isset($data['leagues']) ? $data['leagues']->id : 0);
        if (empty($gender)) {
            $ci->db->where('tennis_standing_table.gender', 1);
        } else {
            $ci->db->where('tennis_standing_table.gender', $gender);
        }
        $data['teams'] = $ci->db->get()->result();

        echo json_encode(['data' => $data]);
    }

    public function video_category($cdata, $main_category, $sub_category, $child_category, $sub_category_menu)
    {
        if (!empty($cdata)) {
            $data = array(
                'meta_title' => ($cdata->seo_title) ? $cdata->seo_title : $cdata->name,
                'meta_description' => $cdata->seo_keyword,
                'meta_keywords' => $cdata->seo_description,
                'main_category' => $main_category,
                'sub_category' => $sub_category,
                'child_category' => $child_category,
                'sub_category_menu' => $sub_category_menu
            );
            if (!empty($sub_category) && $sub_category->template_design == 21) {
                // NewsClip
                if (!empty($child_category)) {
                    // child newsclip here
                    $data['featured'] = $this->_featured_or_video_list('featured', $main_category->id, $sub_category->id, $child_category->id);
                    $data['data'] = $this->_featured_or_video_list('video', $main_category->id, $sub_category->id, $child_category->id, 21);
                    $data['total'] = $this->_video_total($main_category->id, $sub_category->id, $child_category->id);
                    $data['offset'] = 21;
                    //print_r($data['total']);die;
                    $this->viewFrontContent('frontend/template/video/newsclip_subcategory', $data);
                    return 1;
                }
                //NewsClip Category Page
                $data['newsclip'] = $this->newsclipCommonData();
                $data['data'] = $this->_videoIndex('subcategory', 8, $sub_category->template_design);
                $this->viewFrontContent('frontend/template/video/newsclip_category', $data);
            } elseif (!empty($sub_category) && $sub_category->template_design == 22) {
                //OvalFaces
                if (!empty($child_category)) {
                    // child OvalFaces here
                    $data['data'] = $this->_featured_or_video_list('video', $main_category->id, $sub_category->id, $child_category->id, 21);
                    $data['total'] = $this->_video_total($main_category->id, $sub_category->id, $child_category->id);
                    $data['offset'] = 21;
                    $data['type'] = '';
                    $this->viewFrontContent('frontend/template/video/ovalface_subcategory', $data);
                    return 1;
                }
                //OvalFaces Category Page
                $data['featured'] = $this->_featured_or_video_list('featured', $main_category->id, $sub_category->id);
                $data['data'] = $this->_videoIndex('subcategory', 4, $sub_category->template_design);

                $this->viewFrontContent('frontend/template/video/ovalface_category', $data);
            } elseif (!empty($sub_category) && $sub_category->template_design == 23) {
                //Docummentary
                $data['featured'] = $this->_featured_or_video_list('featured', $main_category->id, $sub_category->id);
                $data['data'] = $this->_featured_or_video_list('video', $main_category->id, $sub_category->id, null, 8);
                $data['total'] = $this->_video_total($main_category->id, $sub_category->id);
                $data['offset'] = 8;
                $this->viewFrontContent('frontend/template/video/documentary_category', $data);
            } elseif (!empty($sub_category) && $sub_category->template_design == 24) {
                //Interviews
                $data['featured'] = $this->_featured_or_video_list('featured', $main_category->id, $sub_category->id);
                $data['data'] = $this->_featured_or_video_list('video', $main_category->id, $sub_category->id, null, 21);
                $data['total'] = $this->_video_total($main_category->id, $sub_category->id);
                $data['offset'] = 21;
                $this->viewFrontContent('frontend/template/video/interview_category', $data);
            } elseif (!empty($sub_category) && $sub_category->template_design == 25) {
                //Product review
                $data['featured'] = $this->_featured_or_video_list('featured', $main_category->id, $sub_category->id);
                $data['data'] = $this->_featured_or_video_list('video', $main_category->id, $sub_category->id, null, 24);
                $data['total'] = $this->_video_total($main_category->id, $sub_category->id);
                $data['offset'] = 24;
                $this->viewFrontContent('frontend/template/video/product_review_category', $data);
            } elseif (!empty($sub_category) && $sub_category->template_design == 26) {
                //featured
                $data['featured'] = $this->_featured_or_video_list('featured', $main_category->id, $sub_category->id);
                $data['data'] = $this->_featured_or_video_list('video', $main_category->id, $sub_category->id, null, 24);
                $data['total'] = $this->_video_total($main_category->id, $sub_category->id);
                $data['offset'] = 24;
                $this->viewFrontContent('frontend/template/video/featured_category', $data);
            } else {
                $data['see_more_slug'] = $this->db->get_where('post_category', ['template_design' => 21, 'sub_category_id' => 0])->row();
                $not_id = !empty($data['see_more_slug']) ? $data['see_more_slug']->id : 0;
                $data['newsclip'] = $this->newsclipCommonData();
                $data['data'] = $this->_videoIndex('category', 5, $main_category->template_design, $not_id);
                $this->viewFrontContent('frontend/template/video/category', $data);
            }
        } else {
            $this->viewFrontContent('frontend/404');
        }

    }

    private function newsclipCommonData()
    {
        return $this->db->query("
                                SELECT
                                    posts.title, 
                                    posts.post_url, 
                                    posts.post_image, 
                                    posts.youtube_json, 
                                    posts.video01, 
                                    posts.video02,
	                                child_category.slug,
	                                posts.vimeo_id
                                FROM
                                    posts
                                    INNER JOIN
                                    post_category AS main_category
                                    ON 
                                        posts.category_id = main_category.id
                                    INNER JOIN
                                    post_category AS sub_category
                                    ON 
                                        posts.sub_category_id = sub_category.id
                                    INNER JOIN
                                    post_category AS child_category
                                    ON 
                                        posts.child_category_id = child_category.id
                                    LEFT JOIN
                                    posts as self
                                    ON
                                        self.child_category_id = posts.child_category_id AND self.id > posts.id
                                WHERE
                                    posts.`status` = 'Publish' AND
                                    posts.post_show = 'Frontend' AND
                                    sub_category.template_design = 21 AND
                                    self.id IS NULL
                                LIMIT 3
        ")->result();
    }

    private function _videoIndex($type, $limit, $template_design = 2, $not = null)
    {
        if ($type == 'category') {
            $join = 'LEFT';
            $table = 'sub_category';
            $field = 'sub_category_id';
            $where = 'main_category';
            $concate_field = ", GROUP_CONCAT( description ORDER BY id DESC SEPARATOR ',' ) AS description";
            $select_field = ", posts.description";
        } else {
            $join = 'INNER';
            $table = 'child_category';
            $field = 'child_category_id';
            $where = 'sub_category';
            $concate_field = '';
            $select_field = '';
        }
        $not_query = '';
        if (!empty($not)) {
            $not_query = "AND posts.sub_category_id <> $not";
        }
        $this->db->query("SET @@group_concat_max_len = 999999999999;");
        $this->db->query("SET @rank := 0");
        $this->db->query("SET @category := 0");
        return $this->db->query("
                            SELECT
                                name,
                                slug,
                                GROUP_CONCAT( title ORDER BY id DESC SEPARATOR '|' ) AS title,
                                GROUP_CONCAT( post_url ORDER BY id DESC SEPARATOR '|' ) AS post_url,
                                GROUP_CONCAT( youtube_json ORDER BY id DESC SEPARATOR '|' ) AS youtube_json,
                                GROUP_CONCAT( video01 ORDER BY id DESC SEPARATOR '|' ) AS video01,
                                GROUP_CONCAT( video02 ORDER BY id DESC SEPARATOR '|' ) AS video02,
                                GROUP_CONCAT( vimeo_id ORDER BY id DESC SEPARATOR '|' ) AS vimeo_id,
                                GROUP_CONCAT( post_image ORDER BY id DESC SEPARATOR '|' ) AS post_image
                                $concate_field
                            FROM
                                (
                                SELECT
                                    temp.*,
                                    @rank :=
                                IF
                                    ( @category = temp.$field, @rank + 1, 1 ) AS rank,
                                    @category := temp.$field AS cat 
                                FROM
                                    (
                                    SELECT
                                        posts.title,
                                        posts.id,
                                        posts.post_url,
                                        posts.post_image,
                                        posts.youtube_json,
                                        posts.video01,
                                        posts.video02,
                                        $table.slug,
                                        $table.`name`,
                                        posts.vimeo_id,
                                        posts.$field
                                        $select_field
                                    FROM
                                        posts
                                        INNER JOIN post_category AS main_category ON posts.category_id = main_category.id
                                        INNER JOIN post_category AS sub_category ON posts.sub_category_id = sub_category.id
                                        $join JOIN post_category AS child_category ON posts.child_category_id = child_category.id 
                                    WHERE
                                        posts.`status` = 'Publish' AND
                                        posts.post_show = 'Frontend' AND
                                        $where.template_design = $template_design
                                        $not_query
                                    ORDER BY
                                        $table.id ASC 
                                    ) temp 
                                ORDER BY
                                    $field ASC,
                                    id DESC 
                                ) temp2 
                            WHERE
                                rank <= $limit
                            GROUP BY
                                $field
        ")->result();

    }

    private function entertainmentHomePage($category_id)
    {
        $this->db->select('posts.*');
        $this->db->from('posts');
        $this->db->where_in('post_show', ['Frontend']);
        $this->db->where('posts.status', 'Publish');
        $this->db->join('post_category as sub_category', 'sub_category.id = posts.sub_category_id', "LEFT");
        $this->db->where('sub_category.parent_id', $category_id);
        $this->db->where('sub_category.sub_category_id !=', null);
        $this->db->where('sub_category.template_design', 17);
        $this->db->order_by('modified', 'DESC');
        $this->db->limit(5);
        $data['posts_data'] = $this->db->get()->result();
        $data['popular_movie'] = $this->db->select('movies.*')->from('movies')
            ->where(['status' => 'Publish', 'is_popular' => 1])
            ->order_by('modified', 'DESC')
            ->limit(5)->get()->result();
        $data['movie_review'] = $this->db->select('
                        movies.id, 
                        movies.slug, 
                        movies.name, 
                        movies.date, 
                        movies.modified, 
                        movie_reviews.review, 
                        movie_reviews.rating, 
                        movie_reviews.created,
                        CONCAT(CONCAT(users.first_name," "),users.last_name) as full_name')->from('movie_reviews')
            ->where(['movie_reviews.status' => 'Publish'])
            ->order_by('movie_reviews.id', 'DESC')
            ->join('movies', 'movies.id = movie_reviews.movie_id', 'LEFT')
            ->join('users', 'users.id = movie_reviews.user_id', 'LEFT')
            ->where(['movies.status' => 'Publish'])
            ->group_by('movie_reviews.movie_id')
            ->limit(4)
            ->get()->result();
        $sub_category_id = $this->db->where('parent_id', $category_id)->where('sub_category_id', 0)
            ->where('template_design', 20)->from('post_category')->get()->row();

        $child_category_id = $this->db->where('parent_id', $category_id)->where('sub_category_id', $sub_category_id->id)
            ->where('template_design', 27)->from('post_category')->get()->row();

        $data['music_videos'] = $this->db->select('posts.*')->from('posts')
            ->where_in('post_show', ['Frontend'])
            ->where('posts.status', 'Publish')
            ->where('category_id', $category_id)
            ->where('sub_category_id', $sub_category_id->id)
            ->where('child_category_id', $child_category_id->id)
            ->order_by('modified', 'DESC')->limit(20)->get()->result();

        $data['movie_trailers'] = $this->db->order_by('modified', 'DESC')->from('movies')
            ->where('status', 'Publish')->where('video_id !=', null)->limit(10)->get()->result();

        $child_category_id = $this->db->where('parent_id', $category_id)->where('sub_category_id', $sub_category_id->id)
            ->where('template_design', 29)->from('post_category')->get()->row();

        $data['comedy_videos'] = $this->db->select('posts.*')->from('posts')
            ->where_in('post_show', ['Frontend'])
            ->where('posts.status', 'Publish')
            ->where('category_id', $category_id)
            ->where('sub_category_id', $sub_category_id->id)
            ->where('child_category_id', $child_category_id->id)
            ->order_by('modified', 'DESC')->limit(20)->get()->result();

        $sub_category_music = $this->db->where('parent_id', $category_id)->where('sub_category_id', 0)
            ->where('template_design', 30)->from('post_category')->get()->row();

        $data['music'] = $this->db->select('posts.*')
            ->from('entertainment_featured_videos')
            ->join('posts', 'posts.id = entertainment_featured_videos.post_id')
            ->where_in('post_show', ['Frontend'])
            ->where('posts.status', 'Publish')
            ->where('entertainment_featured_videos.category_id', $sub_category_music->id)
            ->order_by('entertainment_featured_videos.id', 'DESC')
            ->limit(5)
            ->get()->result();

        return $data;
    }

    private function _featured_or_video_list($type = 'featured', $main_category, $subcategory, $child_category = null, $limit = 7)
    {
        $this->db->select("posts.title, posts.id,posts.post_url, posts.youtube_json, posts.video01, posts.video02, posts.vimeo_id,posts.post_image");
        $this->db->from('posts');
        $this->db->join('post_category AS main_category', "posts.category_id = main_category.id", 'INNER');
        $this->db->join('post_category AS sub_category', "posts.sub_category_id = sub_category.id", 'INNER');
        if ($type == 'featured') {
            $this->db->join('video_featured', "posts.id = video_featured.post_id", 'INNER');
        }
        if (!empty($child_category)) {
            $this->db->join('post_category AS child_category', "posts.child_category_id = child_category.id", 'INNER');
            $this->db->where('posts.child_category_id', $child_category);
        }
        if (!empty($subcategory)) {
            $this->db->where('posts.sub_category_id', $subcategory);
        }
        if (!empty($main_category)) {
            $this->db->where('posts.category_id', $main_category);
        }
        $this->db->where_in('posts.post_show', ['Frontend']);
        $this->db->where('posts.status', 'Publish');
        $this->db->order_by('posts.id', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result();
    }

    private function _video_total($main_category, $subcategory, $child_category = null)
    {
        $this->db->select("posts.title, posts.id,posts.post_url, posts.youtube_json, posts.video01, posts.video02, posts.vimeo_id");
        $this->db->from('posts');
        $this->db->join('post_category AS main_category', "posts.category_id = main_category.id", 'INNER');
        $this->db->join('post_category AS sub_category', "posts.sub_category_id = sub_category.id", 'INNER');
        if (!empty($child_category)) {
            $this->db->join('post_category AS child_category', "posts.child_category_id = child_category.id", 'INNER');
            $this->db->where('posts.child_category_id', $child_category);
        }
        if (!empty($subcategory)) {
            $this->db->where('posts.sub_category_id', $subcategory);
        }
        if (!empty($main_category)) {
            $this->db->where('posts.category_id', $main_category);
        }
        $this->db->where_in('posts.post_show', ['Frontend']);
        $this->db->where('posts.status', 'Publish');
        return $this->db->get()->num_rows();
    }

    public function video_tag($slug)
    {
        $data = [
            'type' => 'tag'
        ];

        $CI = &get_instance();
        $tag = $CI->db->select('*')->from('tags')->where('slug', $slug)->get()->row();
        if (empty($tag)) {
            $this->viewFrontContent('frontend/404');
            return false;
        }

        $data['tag'] = $tag;
        $data['meta_title'] = $tag->name;
        $data['meta_description'] = $tag->meta_description;
        $data['meta_keywords'] = $tag->name;

        $main_category = $this->db->select('*')
            ->get_where('post_category', array('template_design' => 2))
            ->row();
        $this->db->select('*');
        $this->db->where('parent_id', $main_category->id)->where('sub_category_id', 0);
        $this->db->from('post_category');
        $sub_category_menu = $this->db->get()->result();

        $data['main_category'] = $main_category;
        $x = new stdClass();
        $x->template_design = 0;
        $data['sub_category'] = $x;
        $data['sub_category_menu'] = $sub_category_menu;

        $this->db->select('posts.title, posts.id,posts.post_url, posts.youtube_json, posts.video01, posts.video02, posts.vimeo_id, posts.post_image');
        $this->db->from('posts');
        $this->db->join('post_tags', 'posts.id = post_tags.post_id');
        $this->db->join('post_category', 'posts.category_id = post_category.id');
        $this->db->where_in('posts.post_show', ['Frontend']);
        $this->db->where('posts.status', 'Publish');
        $this->db->where('post_tags.tag_id', $tag->id);
        $this->db->where('post_category.template_design', 2);

        $this->db->limit(21);

        $data['data'] = $this->db->get()->result();
        $data['offset'] = 21;

        $this->db->select('posts.id');
        $this->db->from('posts');
        $this->db->join('post_tags', 'posts.id = post_tags.post_id');
        $this->db->join('post_category', 'posts.category_id = post_category.id');
        $this->db->where_in('posts.post_show', ['Frontend']);
        $this->db->where('posts.status', 'Publish');
        $this->db->where('post_tags.tag_id', $tag->id);
        $this->db->where('post_category.template_design', 2);

        $data['total'] = $this->db->count_all_results();
        $this->viewFrontContent('frontend/template/video/ovalface_subcategory', $data);
    }


    function oil_category($cdata, $main_category, $sub_category, $child_category, $sub_category_menu)
    {
        if (!empty($cdata)) {
            $data = array(
                'meta_title' => ($cdata->seo_title) ? $cdata->seo_title : $cdata->name,
                'meta_description' => $cdata->seo_keyword,
                'meta_keywords' => $cdata->seo_description,
                'main_category' => $main_category,
                'sub_category' => $sub_category,
                'child_category' => $child_category,
                'sub_category_menu' => $sub_category_menu
            );
            if (!empty($sub_category) && $sub_category->template_design == 33) {
                // News
                $data['posts'] = $this->_post_list($main_category->id, $sub_category->id, null, 9);
                $data['total'] = $this->_post_total($main_category->id, $sub_category->id);

                $this->viewFrontContent('frontend/template/oil/news', $data);
            } elseif (!empty($sub_category) && ($sub_category->template_design == 34 || $sub_category->template_design == 38)) {
                // Article  ||  Energy
                $data['posts'] = $this->_post_list($main_category->id, $sub_category->id, null, 9);
                $data['total'] = $this->_post_total($main_category->id, $sub_category->id);
                $this->viewFrontContent('frontend/template/oil/article', $data);
            } elseif (!empty($sub_category) && $sub_category->template_design == 35) {
                // Industry Report
                $oil_category = !empty($this->input->get('category')) ? $this->input->get('category') : 1;
                $oil_subcategory = !empty($this->input->get('subcategory')) ? $this->input->get('subcategory') : 0;
                $year = !empty($this->input->get('year')) ? $this->input->get('year') : 0;
                $this->db->select("DISTINCT	YEAR(created) as date");
                $this->db->from('posts');
                $this->db->join('oil_category_post', 'oil_category_post.post_id = posts.id', 'INNER');
                $this->db->where('oil_category_post.category_id', $oil_category);

                if (!empty($oil_subcategory)) {
                    $this->db->where('oil_category_post.subcategory_id', $oil_subcategory);
                }
                $years = $this->db->get()->result();
                if (empty($year) && !empty($years[0])) {
                    $year = $years[0]->date;
                }
                $data['oil_category'] = $oil_category;
                $data['oil_subcategory'] = $oil_subcategory;
                $data['years'] = $years;
                $data['year'] = $year;
                $data['posts'] = $this->_post_list($main_category->id, $sub_category->id, null, 12, $oil_category, $oil_subcategory, $year);
                $data['total'] = $this->_post_total($main_category->id, $sub_category->id, $oil_category, $oil_subcategory, $year);

                $data['meta_title'] = $data['main_category']->name.' | '.$data['meta_title'].' | '.oil_category($oil_category).((!empty($oil_subcategory)) ? ' | '.oil_subcategory($oil_category, $oil_subcategory) : '');
                $data['meta_description'] = $data['main_category']->name.' '.$data['meta_description'].' '.oil_category($oil_category).((!empty($oil_subcategory)) ? oil_subcategory($oil_category, $oil_subcategory) : '');
                $data['meta_keywords'] = $data['main_category']->name.','.$data['meta_keywords'].','.oil_category($oil_category).((!empty($oil_subcategory)) ? ','.oil_subcategory($oil_category, $oil_subcategory) : '');

                $this->viewFrontContent('frontend/template/oil/report_index', $data);
            } else {
                $this->viewFrontContent('frontend/template/oil/market_data', $data);
            }
        } else {
            $this->viewFrontContent('frontend/404');
        }
    }


    /**
     * @param $main_category
     * @param $subcategory
     * @param null $child_category
     * @param int $limit
     * @param int $oil_category
     * @param int $oil_subcategory
     * @param null $year
     * @return ArrayObject
     */
    private function _post_list($main_category, $subcategory, $child_category = null, $limit = 9, $oil_category = 0, $oil_subcategory = 0, $year = null)
    {
        $this->db->select("posts.title, posts.id,posts.post_url, posts.created, posts.description, posts.post_image, posts.user_id");
        $this->db->from('posts');
        $this->db->join('post_category AS main_category', "posts.category_id = main_category.id", 'INNER');
        $this->db->join('post_category AS sub_category', "posts.sub_category_id = sub_category.id", 'INNER');

        if (!empty($oil_category)) {
            $this->db->join('oil_category_post', "posts.id = oil_category_post.post_id", 'INNER');
            $this->db->where('oil_category_post.category_id', $oil_category);
            if (!empty($oil_subcategory)) {
                $this->db->where('oil_category_post.subcategory_id', $oil_subcategory);
            }
            if (!empty($year)) {
                $this->db->where('YEAR(posts.created)', $year);
            }
        }


        if (!empty($child_category)) {
            $this->db->join('post_category AS child_category', "posts.child_category_id = child_category.id", 'INNER');
            $this->db->where('posts.child_category_id', $child_category);
        }
        if (!empty($subcategory)) {
            $this->db->where('posts.sub_category_id', $subcategory);
        }
        if (!empty($main_category)) {
            $this->db->where('posts.category_id', $main_category);
        }

        $this->db->where_in('posts.post_show', ['Frontend']);
        $this->db->where('posts.status', 'Publish');

        $this->db->order_by('posts.modified', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result();
    }

    /**
     * @param $main_category
     * @param $subcategory
     * @param null $child_category
     * @param int $oil_category
     * @param int $oil_subcategory
     * @return int
     */
    private function _post_total($main_category, $subcategory, $child_category = null, $oil_category = 0, $oil_subcategory = 0, $year = null)
    {
        $this->db->select("posts.id");
        $this->db->from('posts');
        $this->db->join('post_category AS main_category', "posts.category_id = main_category.id", 'INNER');
        $this->db->join('post_category AS sub_category', "posts.sub_category_id = sub_category.id", 'INNER');
        if (!empty($oil_category)) {
            $this->db->join('oil_category_post', "posts.id = oil_category_post.post_id", 'INNER');
            $this->db->where('oil_category_post.category_id', $oil_category);
            if (!empty($oil_subcategory)) {
                $this->db->where('oil_category_post.subcategory_id', $oil_subcategory);
            }
            if (!empty($year)) {
                $this->db->where('YEAR(posts.created)', $year);
            }
        }
        if (!empty($child_category)) {
            $this->db->join('post_category AS child_category', "posts.child_category_id = child_category.id", 'INNER');
            $this->db->where('posts.child_category_id', $child_category);
        }
        if (!empty($subcategory)) {
            $this->db->where('posts.sub_category_id', $subcategory);
        }
        if (!empty($main_category)) {
            $this->db->where('posts.category_id', $main_category);
        }

        $this->db->where_in('posts.post_show', ['Frontend']);
        $this->db->where('posts.status', 'Publish');

        return $this->db->get()->num_rows();
    }

    function tech_category($cdata, $main_category, $sub_category, $child_category, $sub_category_menu)
    {
        if (!empty($cdata)) {
            $data = array(
                'meta_title' => ($cdata->seo_title) ? $cdata->seo_title : $cdata->name,
                'meta_description' => $cdata->seo_keyword,
                'meta_keywords' => $cdata->seo_description,
                'main_category' => $main_category,
                'sub_category' => $sub_category,
                'child_category' => $child_category,
                'sub_category_menu' => $sub_category_menu
            );

            if (!empty($sub_category) && $sub_category->template_design == 44) {
                $brand_data = '';
                $device_data = '';
                $mode_text = @$this->input->get('mode') == 'all' ? '' : (@$this->input->get('mode') == 'compare' ? ' Compare' : (@$this->input->get('mode') == 'review' ? ' Review' : ''));

                if (!empty($this->input->get('device'))){
                    $device_data = $this->db->where('slug', $this->input->get('device'))->get('tech_device')->row();
                }
                if (!empty($this->input->get('brand'))){
                    $brand_data = $this->db->where('slug', $this->input->get('brand'))->get('tech_brand')->row();
                }
                if (!empty($device_data) && !empty($brand_data)){

                    $data['meta_title'] = $brand_data->seo_title. ' '. $device_data->seo_title.$mode_text;
                    $data['meta_description'] = $brand_data->seo_keyword. ' '. $device_data->seo_keyword.$mode_text;
                    $data['meta_keywords'] = $brand_data->seo_description. ' '. $device_data->seo_description.$mode_text;
                } elseif (!empty($device_data)){

                    $data['meta_title'] = $device_data->seo_title.$mode_text;
                    $data['meta_description'] = $device_data->seo_keyword.$mode_text;
                    $data['meta_keywords'] = $device_data->seo_description.$mode_text;
                } elseif (!empty($brand_data)){

                    $data['meta_title'] = $brand_data->seo_title.$mode_text;
                    $data['meta_description'] = $brand_data->seo_keyword.$mode_text;
                    $data['meta_keywords'] = $brand_data->seo_description.$mode_text;
                }

                $brand_id = isset($brand_data->id) && !empty($brand_data->id) ? $brand_data->id : 0;
                $device_id = isset($device_data->id) && !empty($device_data->id) ? $device_data->id : 0;
                $mode = @$this->input->get('mode') == 'all' ? 0 : (@$this->input->get('mode') == 'compare' ? 1 : (@$this->input->get('mode') == 'review' ? 2 : 0));
                // Tech Review And Compare
                $data['devices'] = get_device_list();
                //$data['brands'] = $this->db->get('tech_brand')->result();
                $data['compares'] = get_tech_review_post(1);
                $data['reviews'] = get_tech_review_post(0);
                $data['datas'] = $this->get_review_compare_list($device_id, $brand_id, $mode, 18);
                $data['offset'] = 18;
                $data['total'] = $this->get_review_compare_total($device_id, $brand_id, $mode);
                //pp($data['total']);
                $this->viewFrontContent('frontend/template/tech/review_compare', $data);
            } elseif (!empty($sub_category) && $sub_category->template_design == 45) {
                // Tech News
                if (!empty($child_category)) {
                    $data['postData'] = $this->_post_list($main_category->id, $sub_category->id, $child_category->id, 16);
                    $data['total'] = $this->_post_total($main_category->id, $sub_category->id, $child_category->id);
                    $data['offset'] = 16;
                    $this->viewFrontContent('frontend/template/tech/news_subcategory', $data);
                    return 1;
                }
                $data['postData'] = $this->_techNewsIndex(10);
                $this->viewFrontContent('frontend/template/tech/news_category', $data);
            } elseif (!empty($sub_category) && $sub_category->template_design == 46) {
                // Tech Tips And Tricks
                $data['compares'] = get_tech_review_post(1);
                $data['reviews'] = get_tech_review_post(0);
                $search = @$this->input->get('search');

                if (!empty($search)){
                    $data['postData']  =  searchOnPostTable($search, 'result', $main_category->id, $sub_category->id, 0, 10);
                    $data['total']  =  searchOnPostTable($search, 'count', $main_category->id, $sub_category->id);
                } else {
                    $data['postData'] = $this->_post_list($main_category->id, $sub_category->id, null, 10);
                    $data['total'] = $this->_post_total($main_category->id, $sub_category->id, null);
                }


                $data['offset'] = 10;
                $this->viewFrontContent('frontend/template/tech/tips_and_trick', $data);
            } else {
                // TECH category
                $data['mobile_tech_news'] = $this->get_post_by_template(52, 'child_category_id', 3);

                $selected_field = "posts.post_image,posts.post_url,posts.created,posts.title,posts.description";


                $data['technology_news_cat'] = get_cat_template(51);
                $data['technology_news'] = $this->get_post_by_template(51, 'child_category_id', 6, $selected_field);

                $data['tips_news_cat'] = get_cat_template(46);
                $data['tips_news'] = $this->get_post_by_template(46, 'sub_category_id', 6, $selected_field);

                $data['review_cat'] = get_cat_template(44);
                $data['featured_compare_reviews'] = $this->get_index_review_compare();


                $this->viewFrontContent('frontend/template/tech/tech', $data);
            }
        }
    }


    private function _techNewsIndex($limit, $template_design = 45)
    {
        $this->db->query("SET @@group_concat_max_len = 999999999999;");
        $this->db->query("SET @rank := 0");
        $this->db->query("SET @category := 0");
        return $this->db->query("
                            SELECT
                                name,
                                slug,
                                GROUP_CONCAT( title ORDER BY modified DESC SEPARATOR '|' ) AS title,
                                GROUP_CONCAT( post_url ORDER BY modified DESC SEPARATOR '|' ) AS post_url,
                                GROUP_CONCAT( post_image ORDER BY modified DESC SEPARATOR '|' ) AS post_image,
                                GROUP_CONCAT( description ORDER BY modified DESC SEPARATOR '|' ) AS description,
                                GROUP_CONCAT( created ORDER BY modified DESC SEPARATOR '|' ) AS created
                            FROM
                                (
                                SELECT
                                    temp.*,
                                    @rank :=
                                IF
                                    ( @category = temp.child_category_id, @rank + 1, 1 ) AS rank,
                                    @category := temp.child_category_id AS cat 
                                FROM
                                    (
                                    SELECT
                                        posts.modified,
                                        posts.title,
                                        posts.post_url,
                                        posts.post_image,
                                        posts.child_category_id,
                                        child_category.slug,
                                        child_category.`name`,
                                        posts.created,
                                        posts.description
                                    FROM
                                        posts
                                        INNER JOIN post_category AS main_category ON posts.category_id = main_category.id
                                        INNER JOIN post_category AS sub_category ON posts.sub_category_id = sub_category.id
                                        INNER JOIN post_category AS child_category ON posts.child_category_id = child_category.id 
                                    WHERE
                                        posts.`status` = 'Publish' AND
                                        posts.post_show = 'Frontend' AND
                                        sub_category.template_design = $template_design
                                    ORDER BY
                                        child_category.id ASC 
                                    ) temp 
                                ORDER BY
                                    child_category_id ASC,
                                    modified DESC 
                                ) temp2 
                            WHERE
                                rank <= $limit
                            GROUP BY
                                child_category_id
        ")->result();

    }

    private function get_post_by_template($template, $field, $limit = 3, $select = 'posts.post_image,posts.post_url,posts.created,posts.title')
    {
        $this->db->select($select);
        $this->db->from('posts');
        $this->db->join('post_category', "post_category.id = posts.$field", 'INNER');
        $this->db->where('post_category.template_design', $template);
        $this->db->where('posts.post_show', 'Frontend');
        $this->db->where('posts.status', 'Publish');
        $this->db->order_by('posts.modified', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();

    }

    private function get_index_review_compare()
    {
        $this->db->select('posts.post_image, posts.post_url, posts.title, posts.created, tech_post_identify.is_compare_review,tech_review_post.ratting');
        $this->db->select('CONCAT(users.first_name, " ", users.last_name) as name');
        $this->db->from('posts');
        $this->db->join('tech_post_identify', 'tech_post_identify.post_id = posts.id', 'INNER');
        $this->db->join('tech_review_post', 'tech_review_post.post_id = posts.id', 'LEFT');
        $this->db->join('users', 'posts.user_id = users.id', 'LEFT');
        $this->db->where('tech_post_identify.is_featured', 1);
        $this->db->where('posts.post_show', 'Frontend');
        $this->db->where('posts.status', 'Publish');
        $this->db->order_by('posts.modified', 'DESC');
        $this->db->limit(8);
        return $this->db->get()->result();
    }

    private function get_review_compare_list($device = 0, $brand = 0, $mode = 0, $limit = 18)
    {
        $this->db->select('posts.post_image, posts.post_url, posts.title, posts.created, tech_post_identify.is_compare_review,review.ratting');
        $this->db->select('CONCAT(users.first_name, " ", users.last_name) as name');
        $this->db->from('posts');

        $this->db->join('tech_post_identify', 'tech_post_identify.post_id = posts.id', 'INNER');
        $this->db->join('tech_review_post as review', 'review.post_id = posts.id', 'LEFT');
        $this->db->join('users', 'posts.user_id = users.id', 'LEFT');


        $this->db->join('tech_compare_option', 'tech_compare_option.post_id = posts.id', 'LEFT');

        $this->db->join('tech_review_post as review1', 'review1.post_id = tech_compare_option.review_1', 'LEFT');
        $this->db->join('tech_review_post as review2', 'review2.post_id = tech_compare_option.review_2', 'LEFT');
        if (!empty($device)) {
            $this->db->group_start();
            $this->db->where('review.device_id', $device);
            $this->db->or_where('review1.device_id', $device);
            $this->db->or_where('review2.device_id', $device);
            $this->db->group_end();
        }

        if (!empty($brand)) {

            $this->db->group_start();
            $this->db->where('review.brand_id', $brand);
            $this->db->or_where('review1.brand_id', $brand);
            $this->db->or_where('review2.brand_id', $brand);
            $this->db->group_end();
        }

        if ($mode == 1) {
            $this->db->where('tech_post_identify.is_compare_review', 1);
        } elseif ($mode == 2) {
            $this->db->where('tech_post_identify.is_compare_review', 0);
        }

        $this->db->where('posts.post_show', 'Frontend');
        $this->db->where('posts.status', 'Publish');

        $this->db->order_by('posts.id', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    private function get_review_compare_total($device = 0, $brand = 0, $mode = 0)
    {
        $this->db->select('posts.post_image, posts.post_url, posts.title, posts.created, tech_post_identify.is_compare_review,review.ratting');
        $this->db->select('CONCAT(users.first_name, " ", users.last_name) as name');
        $this->db->from('posts');

        $this->db->join('tech_post_identify', 'tech_post_identify.post_id = posts.id', 'INNER');
        $this->db->join('tech_review_post as review', 'review.post_id = posts.id', 'LEFT');
        $this->db->join('users', 'posts.user_id = users.id', 'LEFT');


        $this->db->join('tech_compare_option', 'tech_compare_option.post_id = posts.id', 'LEFT');

        $this->db->join('tech_review_post as review1', 'review1.post_id = tech_compare_option.review_1', 'LEFT');
        $this->db->join('tech_review_post as review2', 'review2.post_id = tech_compare_option.review_2', 'LEFT');

        $this->db->where('posts.post_show', 'Frontend');
        $this->db->where('posts.status', 'Publish');
        if (!empty($device)) {
            $this->db->group_start();
            $this->db->where('review.device_id', $device);
            $this->db->or_where('review1.device_id', $device);
            $this->db->or_where('review2.device_id', $device);
            $this->db->group_end();
        }

        if (!empty($brand)) {

            $this->db->group_start();
            $this->db->where('review.brand_id', $brand);
            $this->db->or_where('review1.brand_id', $brand);
            $this->db->or_where('review2.brand_id', $brand);
            $this->db->group_end();
        }

        if ($mode == 1) {
            $this->db->where('tech_post_identify.is_compare_review', 1);
        } elseif ($mode == 2) {
            $this->db->where('tech_post_identify.is_compare_review', 0);
        }

        return $this->db->get()->num_rows();
    }

}