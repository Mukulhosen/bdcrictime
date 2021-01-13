<?php

use Carbon\Carbon;

class Ajax extends Frontend_controller
{

    public function index()
    {
        ajaxAuthorized();
        exit();
    }

    public function like_unlike_change()
    {
        $like_unlike = $this->input->post('like_unlike');
        $post_id = $this->input->post('post_id');
        $user_id = $this->user_id;

        $insert_data = array(
            'user_id' => $user_id,
            'post_id' => $post_id,
            'like_unlike' => $like_unlike,
            'status' => 'Approved',
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
        );

        $this->db->delete('post_like_unlike', array('user_id' => $user_id, 'post_id' => $post_id));
        $this->db->insert('post_like_unlike', $insert_data);

        $like = countPostLikeFromTable($post_id);
        $unlike = countPostUnlikeFromTable($post_id);

        $update_data = array(
            'like_count' => $like,
            'unlike_count' => $unlike
        );
        $this->db->update('posts', $update_data, ['id' => $post_id]);

        echo json_encode(array('like' => $like, 'unlike' => $unlike));
    }

    public function comment_like_unlike_change()
    {
        $value = $this->input->post('value');
        $post_id = $this->input->post('post_id');
        $user_id = $this->input->post('user_id');
        $comment_id = $this->input->post('comment_id');

        $insert_data = array(
            'user_id' => $user_id,
            'post_id' => $post_id,
            'comment_id' => $comment_id,
            'like_unlike' => $value,
            'status' => 'Approved',
            'created' => date('Y-m-d H:i:s')
        );

        $this->db->delete('post_comments_like_unlike', ['user_id' => $user_id, 'post_id' => $post_id, 'comment_id' => $comment_id]);
        $this->db->insert('post_comments_like_unlike', $insert_data);

        $like = commentLikeCount($comment_id);
        $unlike = commentUnlikeCount($comment_id);

        $update_data = array(
            'like_count' => $like,
            'unlike_count' => $unlike,
        );
        $this->db->update('post_comments', $update_data, ['id' => $comment_id]);
        echo json_encode(array('like' => $like, 'unlike' => $unlike, 'comment_id' => $comment_id));
    }

    public function delete_comment()
    {
        $comment_id = (int)$this->input->post('comment_id');
        if (in_array($this->role_id, [1, 2])) {
            $this->db->delete('post_comments_like_unlike', ['comment_id' => $comment_id]);
            $this->db->delete('post_comments', ['id' => $comment_id]);

            echo ajaxRespond('OK', 'Done');
        } else {
            echo ajaxRespond('Fail', 'Fail');
        }
    }

    public function add_comment()
    {
        $user_id = getLoginUserData('user_id');
        $post_id = intval($this->input->post('post_id', TRUE));
        $insert_data = array(
            'user_id' => $user_id,
            'post_id' => $post_id,
            'parent_id' => $this->input->post('parent_id', TRUE),
            'reply_to' => $this->input->post('reply_to', TRUE),
            'description' => $this->input->post('description', TRUE),
            'status' => 'Approved',
            'created' => date('Y-m-d H:i:s')
        );

        $this->db->insert('post_comments', $insert_data);

        $this->db->update('posts', ['comment_count' => postCommentsCount($post_id)], ['id' => $post_id]);

        echo json_encode(array('comment' => $this->input->post('description', TRUE), 'msg' => '<p class="ajax_success">Comment Added</p>', 'query' => $this->db->last_query()));
    }

    public function edit_comment(){
        ajaxAuthorized();
        $id = intval($this->input->post('id', TRUE));
        $msg = $this->input->post('msg', TRUE);

        $this->db->update('post_comments',['description' => $msg], ['id' => $id]);
        echo json_encode(array('comment' => $msg, 'msg' => '<p class="ajax_success">Comment Edited</p>'));
    }

    public function load_file_to_server()
    {
        $photo = uploadPostPhoto($_FILES['upload']);
        $CKEditorFuncNum = $_GET['CKEditorFuncNum'];
        $url = base_url() . $photo;
        $msg = 'uploaded successfully';
        $re = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
        echo $re;
    }

    function get_tag()
    {
        if (!isset($_GET['term'])) {
            $this->viewFrontContent('frontend/404');
            return false;
        }
        $search = $_GET['term'];
        $ci = &get_instance();
        $ci->db->like('name', $search);
        $ci->db->limit(10);
        $query = $ci->db->get('tags')->result();
        echo json_encode($query);
    }


    function force_logout()
    {
        if (strtoupper($this->input->server('REQUEST_METHOD')) != 'POST') {
            $this->viewFrontContent('frontend/404');
            return false;
        }
        $user_id = $this->input->post('user_id');
        if (empty($user_id)) {
            $this->viewFrontContent('frontend/404');
            return false;
        }
        $this->db->select('id');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('id', 'DESC');
        $logout_data = $this->db->get('force_logout')->row();
        $logout = [];
        if (!empty($logout_data)) {
            $logout = $logout_data;
        }
        echo json_encode($logout);
    }

    public function loadAdminChartMonthlyData()
    {
        $data['currentMonth'] = Carbon::now()->format('F, Y');
        $this->db->select('COUNT(posts.id) as total_post, SUM(posts.hit_count) as total_view')
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->where('posts.created >=', Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'));
        if ($this->input->get('user_id')) {
            $this->db->where('user_id', $this->input->get('user_id'));
        }
        $data['totalCount'] = $this->db->from('posts')->get()->row();

        $this->db->select('COUNT(posts.id) as total_post, SUM(posts.hit_count) as total_view, DATE(posts.created) as post_date')
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->where('posts.created >=', Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'));
        if ($this->input->get('user_id')) {
            $this->db->where('user_id', $this->input->get('user_id'));
        }
        $chartDataQuery = $this->db->from('posts')->group_by('DATE(posts.created)')->get()->result();
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $dates = [];
        $chartDataTotalPost = [];
        $chartDataTotalView = [];
        $key = 0;
        while ($start->lte($end)) {
            foreach ($chartDataQuery as $chartItem) {
                if ($chartItem->post_date == $start->format('Y-m-d')) {
                    $chartDataTotalPost[$key] = (int)$chartItem->total_post;
                    $chartDataTotalView[$key] = (int)$chartItem->total_view;

                    continue;
                }
            }
            if (!isset($chartDataTotalPost[$key])) {
                $chartDataTotalPost[$key] = 0;
                $chartDataTotalView[$key] = 0;
            }
            $dates[] = $start->format('d');
            $start->addDay();
            $key++;
        }

        $data['dates'] = $dates;
        $data['chartDataTotalPost'] = $chartDataTotalPost;
        $data['chartDataTotalView'] = $chartDataTotalView;

        echo json_encode($data);
    }

    public function loadAdminChartYearlyData()
    {
        $selectedYear = $this->input->get('year') . '-01-01';
        $this->db->select('COUNT(posts.id) as total_post, SUM(posts.hit_count) as total_view')
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->where('posts.created >=', Carbon::parse($selectedYear)->startOfYear()->format('Y-m-d H:i:s'))
            ->where('posts.created <=', Carbon::parse($selectedYear)->endOfYear()->format('Y-m-d H:i:s'));
        if ($this->input->get('user_id')) {
            $this->db->where('user_id', $this->input->get('user_id'));
        }
        $data['totalCount'] = $this->db->from('posts')->get()->row();

        $this->db->select('COUNT(posts.id) as total_post, SUM(posts.hit_count) as total_view, MONTH(posts.created) as post_date')
            ->where('post_show', 'Frontend')
            ->where('posts.status', 'Publish')
            ->where('posts.created >=', Carbon::parse($selectedYear)->startOfYear()->format('Y-m-d H:i:s'))
            ->where('posts.created <=', Carbon::parse($selectedYear)->endOfYear()->format('Y-m-d H:i:s'));
        if ($this->input->get('user_id')) {
            $this->db->where('user_id', $this->input->get('user_id'));
        }
        $chartDataQuery = $this->db->from('posts')->group_by('MONTH(posts.created)')->get()->result();
        $start = Carbon::parse($selectedYear)->startOfYear();
        $end = Carbon::parse($selectedYear)->endOfYear();

        $dates = [];
        $chartDataTotalPost = [];
        $chartDataTotalView = [];
        $key = 0;

        while ($start->lte($end)) {
            foreach ($chartDataQuery as $chartItem) {
                if ($chartItem->post_date == $start->format('m')) {
                    $chartDataTotalPost[$key] = (int)$chartItem->total_post;
                    $chartDataTotalView[$key] = (int)$chartItem->total_view;

                    continue;
                }
            }
            if (!isset($chartDataTotalPost[$key])) {
                $chartDataTotalPost[$key] = 0;
                $chartDataTotalView[$key] = 0;
            }
            $dates[] = $start->format('M');
            $start->addMonth();
            $key++;
        }

        $data['dates'] = $dates;
        $data['chartDataTotalPost'] = $chartDataTotalPost;
        $data['chartDataTotalView'] = $chartDataTotalView;

        echo json_encode($data);
    }

    public function loadJournalistPostCountData()
    {
        $time = $this->input->get('time');
        $type = $this->input->get('type');
        $html = "";
        $max = maxJournalistPosts($time);
        foreach (journalistPosts($time) as $post) {
            $color = '#3289E8';
            if ($type == 'views') {
                $percent = ($post->total_views / $max->total_views) * 100;
                $totalNumber = $post->total_views;
                if ($time == 'last_7_days') {
                    if ($post->total_views < 120000) {
                        $color = '#FF3754';
                    }
                } elseif ($time == 'last_30_days') {
                    if ($post->total_views < 480000) {
                        $color = '#FF3754';
                    }
                } else {
                    if ($post->total_views < 20000) {
                        $color = '#FF3754';
                    }
                }
            } else {
                $percent = ($post->total_posts / $max->total_posts) * 100;
                $totalNumber = $post->total_posts;
                if ($time == 'last_7_days') {
                    if ($post->total_posts < 30) {
                        $color = '#FF3754';
                    }
                } elseif ($time == 'last_30_days') {
                    if ($post->total_posts < 120) {
                        $color = '#FF3754';
                    }
                } else {
                    if ($post->total_posts < 5) {
                        $color = '#FF3754';
                    }
                }
            }


            $html .= "<li class=\"journalist-posts-item\">
                        <div class=\"journalist-posts-content\">
                            <a href=\"author/$post->profile_slug\">$post->name </a>
                            <span style=\"background: $color;\">$totalNumber </span>
                        </div>
                        <div class=\"journalist-posts-progress\">
                            <div style=\"width:$percent%;background: $color;\" class=\"journalist-posts-progress-bar\"></div>
                        </div>
                    </li>";
        }

        echo json_encode(['html' => $html]);
    }

}
