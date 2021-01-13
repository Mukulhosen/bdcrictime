<?php  defined('BASEPATH') OR exit('No direct script access allowed');

define('app_secret', 'BDCRICTIMEALLAPIRESPONSESECURITY');

function get_user_by_token($token){
    $ci = &get_instance();
    return $ci->db->query("
                        SELECT
                        users.*
                    FROM
                        users
                        INNER JOIN
                        user_tokens
                        ON 
                            users.id = user_tokens.user_id
                    WHERE
                        user_tokens.token = '$token'
    ")->row();
}

/**
 * @param $post_id
 * sending push from it using library
 * Android And Ios Both
 */
function send_push_to_topic($post_id){
    $ci = &get_instance();
    $ci->db->select('posts.title, posts.description, posts.post_url, post_category.slug');
    $ci->db->from('posts');
    $ci->db->join('post_category', 'posts.category_id = post_category.id', 'INNER');
    $ci->db->where('posts.id', $post_id);
    $data = $ci->db->get('')->row();

    if (isset($data) && !empty($data)){
        $to = $data->slug;
        $payload = array(
            'post_url' => $data->post_url
        );
        $ci->load->library('fcm');
        $ci->fcm->setTitle(html_entity_decode($data->title));
        $ci->fcm->setMessage(html_entity_decode(getShortContent(strip_tags($data->description), 200)));
        $ci->fcm->setPayload($payload);
        $json = $ci->fcm->getPush();
        $ci->fcm->sendToTopic($to, $json);
    }
}

