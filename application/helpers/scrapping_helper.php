<?php

function getPostPhoto($photo = null, $alt = 'Image', $width = '100', $class = 'fixedheight lazyload')
{
    $filename = dirname(APPPATH) . '/' . $photo;
    if ($photo && file_exists($filename)) {
        return '<img width="' . $width . '"  class="' . $class . '" data-src="' . $photo . '" alt="' . $alt . '">';
    } else {
        return '<img width="' . $width . '" class="' . $class . '" src="assets/images/no-photo.jpg" alt="' . $alt . '">';
    }
}

function getCategoryIDBySlug($slug = '')
{
    if ($slug) {
        $slug = str_replace(array('%20',), array(' ',), $slug);
        $CI = &get_instance();
        $row = $CI->db->select('id')->get_where('post_category', ['slug' => $slug])->row();
        if ($row) {
            return $row->id;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

function getCategorySlugByID($id = 0)
{
    $CI = &get_instance();
    $row = $CI->db->get_where('post_category', ['id' => $id])->row();
    if ($row) {
        return $row->slug;
    } else {
        return FALSE;
    }
}

function getUserPhoto($thumb, $size = 120, $class = '', $alt = 'Image')
{
    $filepath = dirname(BASEPATH) . '/uploads/users_profile/' . $thumb;
    if ($thumb && file_exists($filepath)) {
        return '<img src="uploads/users_profile/' . $thumb . '" width="' . $size . '" alt="' . $alt . '" class="' . $class . '"/>';
    } else {
        return '<img src="uploads/users_profile/no-photo.png" width="' . $size . '" alt="' . $alt . '" class="' . $class . '"/>';
    }
}

function profile_photo_upload($photo, $id = 0)
{
    $handle = new Verot\Upload\Upload($photo);
    if ($handle->uploaded) {
        $handle->image_resize = true;
        $handle->image_x = 400;
        $handle->image_ratio_y = true;
        $handle->allowed = array(
            'image/jpeg',
            'image/jpg',
            'image/gif',
            'image/png',
            'image/bmp'
        );
        $handle->file_new_name_body = uniqid($id) . '_' . time();
        $handle->process('uploads/users_profile/');
        if ($handle->processed) {
            return $handle->file_dst_name;
        } else {
            return 'error : ' . $handle->error;
        }
    }
}

function count_my_posts()
{
    $CI = &get_instance();
    $user_id = getLoginUserData('user_id');
    $CI->db->where('user_id', $user_id);
    $CI->db->from('posts');
    $count = $CI->db->count_all_results();
    if ($count) {
        return '<sup>' . $count . '</sup>';
    } else {
        return '';
    }
}

function count_approve_now($user_id)
{
    $CI = &get_instance();
    $mindate = date("Y-m-d", strtotime("-1 month", strtotime(date('Y-m-d'))));
    $results = $CI->db->select('post_id')->get_where('post_like_unlike', ['user_id' => $user_id, 'like_unlike' => 0])->result();

    if ($results) {
        $array = [];
        foreach ($results as $value) {
            $array2 = [$value->post_id];
            $array = array_merge($array, $array2);
        }

        $CI->db->from('posts');
        $CI->db->where('modified >=', $mindate);
        $CI->db->where('modified <=', date('Y-m-d'));
        $CI->db->where('journalist !=', 0);
        $CI->db->where_in('id', $array);
        $count = $CI->db->count_all_results();
        if ($count) {
            return '<sup>' . $count . '</sup>';
        } else {
            return '';
        }
    } else {
        return FALSE;
    }
}

function getJournalistsProfileSidebar($user_id)
{
    $html = '';
    $CI = &get_instance();
    $user = $CI->db->get_where('users', ['id' => $user_id])->row();

    if ($user) {
        if (!empty($user->profile_photo)) {
            $photo = '<img src="uploads/users_profile/' . $user->profile_photo . '" class="img-responsive" alt="Profile image">';
        } else {
            $photo = '<img src="uploads/cms_photos/no-thumb.png" style="width:100%" alt="no image">';
        }

        $html .= '<div class="row mobilejournalistphoto">' . $photo . '</div>
                <div class="row mobilejournalistprodes">
                    <h3>' . $user->title . ' ' . $user->first_name . ' ' . $user->last_name . '</h3>
                    <div style="text-align:justify">' . getShortContent($user->biography, 60) . '</div>
                </div><div class="clearfix" style="margin-bottom:20px;"></div>';
    }
    return $html;
}




function getChildComments($parent_id, $post_id)
{
    $html = '';
    $CI = &get_instance();

    $comments = $CI->db->get_where('post_comments', ['parent_id' => $parent_id, 'post_id' => $post_id])->result();
    if ($comments) {
        $html .= '<div class="all_comment_list"><ul>';
        foreach ($comments as $comment) {
            $html .= '<li><div class="col-md-12 no-padding">
                <div class="col-md-1  no-padding">' . getCommentAvatar($comment->user_id) . '</div>
                <div class="col-md-11">
                    <div class="comment-box">
                    <div class="col-md-12 no-padding">
                    <div class="comment-head">
                    <span>Written By ' . getUserNameById($comment->user_id) . '</span><span>' . timePassed($comment->created) . '</span> <span data-parent_id="' . $comment->id . '" class="comment_reply"><b></b></span>';

            if (getLoginUserData('user_id')) {
                $html .= '<span 
                        class="comment_unlike_box comment_unlike_box_' . $comment->id . '" 
                        data-user_id="' . getLoginUserData('user_id') . '" 
                        data-post_id="' . $post_id . '" 
                        data-comment_id="' . $comment->id . '" 
                        data-comment_count_value="' . $comment->unlike_count . '" 
                        data-comment_like_unlike="0"
                    >
                        <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                        <em>' . $comment->unlike_count . '</em>
                    </span>
                    <span 
                        class="comment_like_box comment_like_box_' . $comment->id . '" 
                        data-user_id="' . getLoginUserData('user_id') . '" 
                        data-post_id="' . $post_id . '" 
                        data-comment_id="' . $comment->id . '" 
                        data-comment_count_value="' . $comment->like_count . '" 
                        data-comment_like_unlike="1"
                    >
                        <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                        <em>' . $comment->like_count . '</em>
                    </span>';
            } else {
                $html .= '
                    <span data-toggle="modal" data-target="#myModal" data-user_id="' . getLoginUserData('user_id') . '" data-post_id="' . $comment->post_id . '"><i class="fa fa-thumbs-down" aria-hidden="true"></i> <em>' . $comment->unlike_count . '</em></span>                    
                    <span data-toggle="modal" data-target="#myModal" data-user_id="' . getLoginUserData('user_id') . '" data-post_id="' . $comment->post_id . '"><i class="fa fa-thumbs-up" aria-hidden="true"></i> <em>' . $comment->like_count . '</em></span>
                ';
            }

            $html .= '</div></div>
                    <div class="col-md-12 no-padding">
                    <div class="comment-content">
                    <p> ' . $comment->description . '</p>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
                    ' . getChildComments($comment->id, $comment->post_id) . '
                </li>';
        }
        $html .= '</ul></div>';
    }
    return $html;
}

function getCommentAvatar($user_id)
{
    return getUserProfilePhotoByID($user_id, 'img-responsive img-circle');
}

function count_posts()
{
//    $role_id = getLoginUserData('role_id');
    $CI = &get_instance();

//    if (!in_array($role_id, [1, 2])) {
    $CI->db->where('user_id', getLoginUserData('user_id'));
//    }

    $CI->db->from('posts');
    $count = $CI->db->count_all_results();

    return '<span class="pull-right-container">
              <small class="label pull-right bg-yellow">' . $count . '</small>
            </span>';
}

function uploadPostPhoto($photo_post)
{
    $photo = '';
    $handle = new Verot\Upload\Upload($photo_post);
    $patch = 'uploads/posts/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
    if ($handle->uploaded) {
        $handle->file_name_body_pre = '';
        $handle->file_new_name_body = 'post-' . date('H-i-s-') . rand(0, 9);
        $handle->allowed = array('image/*');
        $handle->image_resize = true;
        $handle->image_x = 1000;
        $handle->image_ratio_y = true;
        $photo = $handle->file_new_name_body . '.' . $handle->file_src_name_ext;
        $handle->process($patch);
        if ($handle->processed) {
            $photo = $patch . $photo;
            $handle->clean();
        } else {
            $photo = '';
        }
    }
    return $photo;
}

function removeFile($photo = null)
{
    $filename = dirname(APPPATH) . '/' . $photo;
    if ($photo && file_exists($filename)) {
        unlink($filename);
    }
    return TRUE;
}

function unread_notifications_with_badge()
{
    $CI = &get_instance();
    $id = getLoginUserData('user_id');
    $count_notification = $CI->db->select('id')->get_where('notifications', ['reciever_id' => $id, 'status' => 'Unread'])->num_rows();
    if ($count_notification == 0) {
        return FALSE;
    } else {
        return '<span class="badge bg-green">' . $count_notification . '</span>';
    }
}

function getCommentList($post_id = 0)
{
    $CI = &get_instance();
    $login_user_id = getLoginUserData('user_id');
    $comment_count = postCommentsCount($post_id);
    if ($comment_count == 0) {
        $title = '';
    } else if ($comment_count == 1) {
        $title = 'One Comment';
    } else {
        $title = $comment_count . ' Comments';
    }

    $CI->db->where(['post_id' => $post_id, 'parent_id' => 0]);
    $CI->db->order_by('like_count', 'DESC');
    $CI->db->order_by('created', 'DESC');
    $CI->db->from('post_comments');
    $comments = $CI->db->get()->result();

//    pp($comments);

    $html = '';
    if ($comments) {
        $html .= '<div class="all_comment_list"><h2>' . $title . '</h2><hr />';
        foreach ($comments as $comment) {
            if ($login_user_id) {
                $like_btn = '<span 
                        onClick="commentLikeUnlike(' . $post_id . ', ' . $login_user_id . ', ' . $comment->id . ', 1);" 
                        class="comment-like-unlike-btn comment_like_box_' . $comment->id . '">
                        <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                        <em>' . $comment->like_count . '</em>
                    </span>';
                $like_btn .= '<span 
                        onClick="commentLikeUnlike(' . $post_id . ', ' . $login_user_id . ', ' . $comment->id . ', 0);" 
                        class="comment-like-unlike-btn comment_unlike_box_' . $comment->id . '">
                        <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                        <em>' . $comment->unlike_count . '</em>
                    </span>';
            } else {
                $like_btn = '<span class="comment-like-unlike-btn" data-toggle="modal" data-target="#loginBoxModal" data-user_id="' . $login_user_id . '" data-post_id="' . $comment->post_id . '"><i class="fa fa-thumbs-up" aria-hidden="true"></i> <em>' . $comment->like_count . '</em></span>'
                    . '<span class="comment-like-unlike-btn" data-toggle="modal" data-target="#loginBoxModal" data-user_id="' . $login_user_id . '" data-post_id="' . $comment->post_id . '"><i class="fa fa-thumbs-down" aria-hidden="true"></i> <em>' . $comment->unlike_count . '</em></span>';
            }

            $html .= '<div class="comnt_box" id="comment_id_' . $comment->id . '">
                        <div class="col-md-1 no-padding">' . getCommentAvatar($comment->user_id) . '</div>
                        <div class="col-md-11">
                            <h3>' . getUserNameById($comment->user_id) . '
                                <span class="datepost cmnt_date pull-right">' . timePassed($comment->created) . '</span>
                                <span class="datepost cmnt_date pull-right">' . $like_btn . '</span>
                                <div class="clearfix"></div>
                            </h3>
                            <p>' . $comment->description . '</p>
                            <div class="clearfix"></div>';

            if (in_array(getLoginUserData('role_id'), [1, 2])) {
                $html .= '<div class="delete_comment_btn" onClick="deleteComment(' . $comment->id . ');">Delete</div>';
            }

            $html .= '</div>
                        <div class="clearfix"></div>
                    </div>';

        }
        $html .= '</div>';
    } else {
        $html .= '<p class="ajax_notice">Comment not found.</p>';
    }
    return $html;
}

function postCommentsCount($post_id = 0)
{
    $CI = &get_instance();
    $count = $CI->db->where(['post_id' => $post_id])->count_all_results('post_comments');
    if ($count) {
        return $count;
    } else {
        return 0;
    }
}

function showMoreTxtBtn($text, $limit = 200, $id = 1, $link = 'faq')
{
    $html = '';
    $plain_txt = strip_tags($text);
    $leanth = strlen($plain_txt);
    $short_txt = substr($plain_txt, 0, $limit);

    if ($leanth > $limit) {
        $html .= $short_txt;
        $html .= '....&nbsp;<a class="btn btn-info btn-xs" href="' . site_url($link . '/' . $id) . '">Read Details &rarr;</a>';
    } else {
        $html .= $short_txt;;
    }

    return $html;
}

function getFirstNameByUserId($id)
{
    $CI = &get_instance();
    $user = $CI->db->select('first_name,last_name')->get_where('users', ['id' => $id])->row();
    if ($user) {
        return $user->first_name;
    } else {
        return 'Guest';
    }
}

function getTagsList($select = [])
{
    $ci = &get_instance();
    $query = $ci->db->get('tags')->result();
    $options = '';
    foreach ($query as $row) {
        $options .= '<option value="' . $row->id . '" ';
        $options .= in_array($row->id, $select) ? 'selected="selected"' : '';
        $options .= '>' . $row->name . '</option>';
    }
    return $options;
}

function getTagNameById($id = 0)
{
    $CI = &get_instance();
    $tag = $CI->db->select('name')->from('tags')->where('id', $id)->get()->row();
    return isset($tag) ? $tag->name : '';
}

function getTagNameBySlug($slug = '')
{
    $CI = &get_instance();
    $tag = $CI->db->select('name')->from('tags')->where('slug', $slug)->get()->row();
    return isset($tag) ? $tag->name : '';
}

function getMetaInfoBySlug($slug = '')
{
    $CI = &get_instance();
    $tag = $CI->db->select('heading, meta_description')->from('tags')->where('slug', $slug)->get()->row();
    return isset($tag) ? $tag : '';
}

function getTagIdBySlug($slug = '')
{
    $CI = &get_instance();
    $tag = $CI->db->select('id')->from('tags')->where('slug', $slug)->get()->row();
    return isset($tag) ? (int)$tag->id : '';
}

function getSlugByTagName($name = '')
{
    $CI = &get_instance();
    $tag = $CI->db->select('slug')->from('tags')->like('name', $name)->get()->row();
    return isset($tag) ? $tag->slug : '';
}

function customDateFormat($date)
{
    $carbon = new \Carbon\Carbon($date);
    return $carbon->format("M d, Y");
}

function getPostUser($id)
{
    $ci = &get_instance();
    $ci->db->select('first_name, last_name');
    $ci->db->from('users');
    $ci->db->where('id', $id);
    $data = $ci->db->get()->row();

    if ($data) {
        return $data->first_name . " " . $data->last_name;
    }

    return 'Anonymous';
}

function getAuthPosts($user_id = 0)
{
    $CI = &get_instance();
    $html = '';

    $CI->db->from('posts');
    $CI->db->select('id, comment_count, post_image, post_url, title, modified, created, description');
    $CI->db->where_in('post_show', ['Frontend']);
    if ($user_id) {
        $CI->db->where('user_id', $user_id);
    }
    $CI->db->where('status', 'Publish');
    $CI->db->order_by('id', 'DESC');
    $posts = $CI->db->get()->result();

    return $posts;
}

function getAllComments($postId = 0)
{
    $CI = &get_instance();
    $CI->db->select("post_comments.*, CONCAT(users.first_name, ' ', users.last_name) as name, users.profile_photo, users.oauth_provider, reply.first_name as reply_name");
    $CI->db->where(['post_id' => $postId]);
    $CI->db->where(['post_comments.status' => "Approved"]);
    //$CI->db->order_by('like_count', 'DESC');
    $CI->db->order_by('id', 'DESC');
    $CI->db->from('post_comments');
    $CI->db->join('users', "users.id = post_comments.user_id", 'LEFT');
    $CI->db->join('users as reply', "reply.id = post_comments.reply_to", 'LEFT');

    return $CI->db->get()->result_array();
}

function getAllCommentReplies($postId = 0)
{
    $CI = &get_instance();
    $CI->db->where(['parent_id' => $postId]);
    $CI->db->join('users', "users.id = post_comments.user_id");
    $CI->db->from('post_comments');

    return $CI->db->get()->result_array();
}

function gender($input)
{
    $output = [
        1 => 'Male',
        2 => 'Female',
        3 => 'Others',
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

function getPostsCaretoryNameById($cat = 0)
{
	$html = '';
	$CI = &get_instance();
	if ($cat == 0) {
		$html .= 'Uncategorized';
	} else {
		$category = $CI->db->get_where('post_category', ['id' => $cat])->row();
		if ($category) {
			$html .= $category->name;
		} else {
			$html .= 'Uncategorized';
		}
	}
	return $html;
}


