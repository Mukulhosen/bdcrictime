<?php

use Carbon\Carbon;

defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Mukul Hosen
 * Date : 2020-10-13
 */


class Common_api extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('api_helper');
    }

    function test_push()
    {
        $post = $this->input->get('post');
        send_push_to_topic($post);
    }

	private function getLatestNews($limit = 5, $offset = 0)
	{
		$ci = &get_instance();
		$ci->db->select("c.name as category_name, c.id as category_id, c.slug as category_slug , p.title, p.post_url, p.modified, p.created, p.id");
		$ci->db->select("CONCAT(u.first_name, ' ', u.last_name) AS author_name");
		$ci->db->select("IF(p.post_image IS null OR p.post_image = '' , '', CONCAT('" . base_url() . "',p.post_image)) as post_image");
		$ci->db->where('p.status', 'Publish');
		$ci->db->where_in('p.post_show', ['Frontend']);
		$ci->db->from('posts as p');
		$ci->db->order_by('p.created', 'DESC');
		$ci->db->join('users as u', 'u.id = p.user_id', 'LEFT');
		$ci->db->join('post_category as c', 'c.id = p.category_id');
		$ci->db->limit($limit, $offset);

		return $ci->db->get()->result();
	}


	function latest_news()
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

		return apiResponse([
			'status' => true,
			'message' => "",
			'data' => $this->getLatestNews($limit, $offset)
		]);

	}

}
