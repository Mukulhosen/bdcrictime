<?php
/**
 * Created by PhpStorm.
 * User: debu
 * Date: 3/5/20
 * Time: 5:26 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemap extends CI_Controller
{
    /**
     * Index Page for this controller.
     *
     */
    function __construct()
    {
        parent::__construct();
        $this->load->library('user_agent');
        $this->load->helper('xml');
    }

    public function index()
    {
        $ci = & get_instance();
        $data['posts']= $ci->db->query("SELECT DATE(created) as post_date, max(created) as modified FROM posts 
                                        where post_show = 'Frontend' AND status = 'Publish'
                                        GROUP BY DATE(created) ORDER BY post_date desc;")->result();
        header('Content-Type: application/xml; charset=utf-8');

        $this->load->view('sitemap.xml', $data);
    }

    public function sitemapView()
    {
        $urls = [];
        $time = [];
        $priority = [];
        if (is_int(strpos($_SERVER['REQUEST_URI'], "category.xml"))) {
            $ci = & get_instance();
            $parentMenus =  $ci->db->from('post_category')->where('parent_id', 0)->order_by('menu_order', 'ASC')->get()->result();

            $ci = & get_instance();
            $subMenus =  $ci->db->select("post_category.slug as sub_slug, pm.slug as parent_slug")
                ->from('post_category')
                ->where('post_category.parent_id !=', 0)
                ->where('post_category.sub_category_id', 0)
                ->join('post_category as pm', 'pm.id=post_category.parent_id')
                ->order_by('post_category.menu_order', 'ASC')->get()->result();

            $ci = & get_instance();
            $childMenus =  $ci->db->select("post_category.slug as child_slug, pm.slug as parent_slug, sm.slug as sub_slug")
                ->from('post_category')
                ->where('post_category.parent_id !=', 0)
                ->where('post_category.sub_category_id !=', 0)
                ->join('post_category as pm', 'pm.id=post_category.parent_id')
                ->join('post_category as sm', 'sm.id=post_category.sub_category_id')
                ->order_by('post_category.menu_order', 'ASC')->get()->result();

            foreach ($parentMenus as $parentMenu) {
                $urls[] = base_url() . "category/" . $parentMenu->slug;
            }
            foreach ($subMenus as $subMenu) {
                $urls[] = base_url() . "category/" . $subMenu->parent_slug . "/" . $subMenu->sub_slug;
            }
            foreach ($childMenus as $subMenu) {
                $urls[] = base_url() . "category/" . $subMenu->parent_slug . "/" . $subMenu->sub_slug . "/" . $subMenu->child_slug;
            }
        } else {
            $urlDate = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], "post-") + 5);
            $date = explode(".", $urlDate);

            $ci = & get_instance();
            $this->db->select('p.*, sub_category.template_design as sub_cat_tem_desgin');
            $ci->db->from('posts as p');
            $ci->db->where('p.status', 'Publish');
            $ci->db->where('DATE(created)', $date[0]);
            $ci->db->order_by('p.id', 'DESC');
            $ci->db->join('post_category as sub_category', 'sub_category.id = p.sub_category_id', 'LEFT');
            $posts = $ci->db->get()->result();


            $ci = & get_instance();
            $ci->db->select('max(hit_count) as max_hit');
            $ci->db->from('posts as p');
            $ci->db->where('p.status', 'Publish');
            $ci->db->where('DATE(created)', $date[0]);
            $ci->db->order_by('p.id', 'DESC');
            $maxHit = $ci->db->get()->result();

            $maxHit = $maxHit[0]->max_hit ? $maxHit[0]->max_hit : 1;

            $tz = new \Carbon\CarbonTimeZone(1);
            foreach ($posts as $key => $post) {
                $urls[] = base_url() . getSegmentByTemplate($post->sub_cat_tem_desgin). '/' . $post->post_url;
                $time[] = Carbon\Carbon::parse($post->created)->format('Y-m-d\TH:i:s') . $tz->toOffsetName();
                $priority[] = number_format(($post->hit_count / $maxHit),1);
            }
        }
        header('Content-Type: application/xml; charset=utf-8');
        $this->load->view('sitemap_view.xml', compact('urls', 'time', 'priority'));
    }

    public function rss_feed($slug = null)
    {
        $this->db->select('id, name, slug');
        $this->db->from('post_category');
        $this->db->where('slug', $slug);
        $category = $this->db->get()->result();
        if (!empty($category)) {
            $ci = & get_instance();
            $ci->db->select('posts.title, posts.post_url, posts.description, posts.created, sub_category.template_design as sub_cat_tem_desgin');
            $ci->db->from('posts');
            $ci->db->where('category_id', $category[0]->id);
            $ci->db->where_in('post_show', ['Frontend']);
            $ci->db->where('posts.status', 'Publish');
            $ci->db->join('post_category as sub_category', 'sub_category.id = posts.sub_category_id', 'LEFT');
            $posts = $ci->db->get()->result();
            header('Content-Type: application/xml; charset=utf-8');
            $this->load->view('rss_feed.xml', compact('category', 'posts'));
        } else {
            $this->load->view('frontend/404.php');
        }
        
    }
    
}