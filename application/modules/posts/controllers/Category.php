<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2017-07-12
 */

class Category extends Admin_controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Category_model');
        $this->load->helper('categories');
        $this->load->library('form_validation');
    }

    public function index() {
        $start = intval($this->input->get('start'));
        $config['page_query_string'] = TRUE;
        $config['base_url'] = Backend_URL . 'posts/category/';
        $config['first_url'] = Backend_URL . 'posts/category/';
        $config['per_page'] = 50;

        $config['total_rows'] = $this->Category_model->total_rows();
        $categories = $this->Category_model->get_limit_data($config['per_page'], $start);

        $new_tree = array();
        foreach ($categories as $category) {
            $new_tree[$category->id] = (array) $category;
            $new_tree[$category->id]['name'] = $category->name;
            $new_tree[$category->id]['child'] = $this->butildCategoryTree($category->id);
            $new_tree[$category->id]['slug'] = $category->slug;
            $new_tree[$category->id]['menu_order'] = $category->menu_order;
            $new_tree[$category->id]['menu_position'] = $category->menu_position;
        }
        //dd( $new_tree );

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'categories' => $new_tree,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->viewAdminContent('posts/category/index', $data);
    }

    private function butildCategoryTree($parent_id = 0) {
        $this->db->where('parent_id', $parent_id)->where('sub_category_id', 0);
        return $this->db->get('post_category')->result();
    }

    public function read($id) {
        $row = $this->Category_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'parent_id' => $row->parent_id,
                'sub_category_id' => $row->sub_category_id,
                'name' => $row->name,
                'slug' => $row->slug,
                'status' => $row->status,
                'menu_order' => $row->menu_order,
                'menu_position' => $row->menu_position,
                'seo_title' => $row->seo_title,
                'seo_keyword' => $row->seo_keyword,
                'seo_description' => $row->seo_description,
            );
            $this->viewAdminContent('posts/category/read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url(Backend_URL . 'posts/category'));
        }
    }

    public function create() {
        $data = array(
            'button' => 'Create',
            'action' => site_url(Backend_URL . 'posts/category/create_action'),
            'id' => set_value('id'),
            'parent_id' => set_value('parent_id'),
            'sub_category_id' => set_value('sub_category_id'),
            'name' => set_value('name'),
            'slug' => set_value('slug'),
//            'status' => set_value('status'),
//            'menu_order' => set_value('menu_order'),
//            'menu_position' => set_value('menu_position'),
            'seo_title' => set_value('seo_title'),
            'seo_keyword' => set_value('seo_keyword'),
            'seo_description' => set_value('seo_description'),
//            'template_design' => set_value('template_design'),
//            'game_type' => set_value('game_type'),
//            'description' => set_value('description'),
//            'state_id' => set_value('state_id'),
        );
        $this->viewAdminContent('posts/category/create', $data);
    }

    public function create_action() {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $slug = slugify($this->input->post('slug', TRUE));
            $this->db->from('post_category');
            $this->db->where('slug', $slug);
            $categories = $this->db->get()->result();
            if (count($categories) > 0) {
                $this->session->set_flashdata('danger', 'This slug is already used');
                redirect(site_url(Backend_URL . 'posts/category'));
            }
            $data = array(
                'parent_id' => $this->input->post('parent_id', TRUE),
                'sub_category_id' => $this->input->post('sub_category_id', TRUE),
                'name' => $this->input->post('name', TRUE),
                'slug' => slugify($this->input->post('slug', TRUE)),
//                'status' => $this->input->post('status', TRUE),
//                'menu_order' => $this->input->post('menu_order', TRUE),
//                'menu_position' => $this->input->post('menu_position', TRUE),
                'seo_title' => $this->input->post('seo_title', TRUE),
                'seo_keyword' => $this->input->post('seo_keyword', TRUE),
                'seo_description' => $this->input->post('seo_description', TRUE),
//                'template_design' => $this->input->post('template_design', TRUE),
//                'description' => $this->input->post('description', TRUE),
//                'state_id' => $this->input->post('state_id', TRUE),
            );


            $this->Category_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url(Backend_URL . 'posts/category'));
        }
    }

    public function update($id) {
        $row = $this->Category_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url(Backend_URL . 'posts/category/update_action'),
                'id' => set_value('id', $row->id),
                'parent_id' => set_value('parent_id', $row->parent_id),
                'sub_category_id' => set_value('sub_category_id', $row->sub_category_id),
                'name' => set_value('name', $row->name),
                'slug' => set_value('slug', $row->slug),
//                'status' => set_value('status', $row->status),
//                'menu_order' => set_value('menu_order', $row->menu_order),
//                'menu_position' => set_value('menu_position', $row->menu_position),
                'seo_title' => set_value('seo_title', $row->seo_title),
                'seo_keyword' => set_value('seo_keyword', $row->seo_keyword),
                'seo_description' => set_value('seo_description', $row->seo_description),
//                'template_design' => set_value('seo_description', $row->template_design),
//                'game_type' => set_value('seo_description', $row->game_type),
//                'description' => set_value('description', $row->description),
//                'state_id' => set_value('state_id', $row->state_id),
            );
            $this->viewAdminContent('posts/category/create', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url(Backend_URL . 'posts/category'));
        }
    }

    public function update_action() {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $slug = slugify($this->input->post('slug', TRUE));
            $this->db->from('post_category');
            $this->db->where('slug', $slug);
            $this->db->where('id !=', $this->input->post('id', TRUE));
            $categories = $this->db->get()->result();
            if (count($categories) > 0) {
                $this->session->set_flashdata('danger', 'This slug is already used');
                redirect(site_url(Backend_URL . 'posts/category'));
            }
            $data = array(
                'parent_id' => $this->input->post('parent_id', TRUE),
                'sub_category_id' => $this->input->post('sub_category_id', TRUE),
                'name' => $this->input->post('name', TRUE),
                'slug' => slugify($this->input->post('slug', TRUE)),
//                'status' => $this->input->post('status', TRUE),
//                'menu_order' => $this->input->post('menu_order', TRUE),
//                'menu_position' => $this->input->post('menu_position', TRUE),
                'seo_title' => $this->input->post('seo_title', TRUE),
                'seo_keyword' => $this->input->post('seo_keyword', TRUE),
                'seo_description' => $this->input->post('seo_description', TRUE),
//                'template_design' => $this->input->post('template_design', TRUE),
//                'description' => $this->input->post('description', TRUE),
//                'state_id' => $this->input->post('state_id', TRUE),
            );

            $this->Category_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url(Backend_URL . 'posts/category'));
        }
    }

    public function delete($id) {
        $row = $this->Category_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'parent_id' => $row->parent_id,
                'sub_category_id' => $row->sub_category_id,
                'name' => $row->name,
                'slug' => $row->slug,
                'status' => $row->status,
                'menu_order' => $row->menu_order,
                'menu_position' => $row->menu_position,
                'seo_title' => $row->seo_title,
                'seo_keyword' => $row->seo_keyword,
                'seo_description' => $row->seo_description,
            );
            $this->viewAdminContent('posts/category/delete', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url(Backend_URL . 'posts/category'));
        }
    }

    public function delete_action($id) {
        $row = $this->Category_model->get_by_id($id);
        if ($row) {
            $this->Category_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url(Backend_URL . 'posts/category'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url(Backend_URL . 'posts/category'));
        }
    }

    public function _rules() {
        $this->form_validation->set_rules('parent_id', 'parent id', 'trim|required');
        $this->form_validation->set_rules('name', 'name', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function category_by_parent_id() {
        ajaxAuthorized();
        $cat_id = $this->input->post('cat_id', TRUE);
        $categories = $this->db->get_where('post_category', ['parent_id' => $cat_id])->result();

        $options = '';
        if (count($categories) == 0) {
            $options .= '<option value="0"> No Category Found</option>';
        } else {
            $options .= '<option value="0">--Select Sub Category--</option>';
        }

        foreach ($categories as $category) {
            $options .= '<option value="' . $category->id . '" ';
            $options .= '>' . $category->name . '</option>';
        }
        echo $options;
    }
    
    public function posts_category_by_parent_id() {
        ajaxAuthorized();
        $category_id = $this->input->post('category_id', TRUE);
        $categories = [];
        $options = '';
        if ($category_id) {
            $this->db->from('post_category');
            $this->db->where('parent_id', $category_id);
            $this->db->where('sub_category_id', 0);
            $this->db->where_not_in('template_design', ['39', '44', '46']);
            $categories = $this->db->get()->result();

            if (count($categories) == 0) {
                $options .= '<option value="0"> No Category Found</option>';
            } else {
                $options .= '<option value="0">-- Select Sub Category --</option>';
            }
        } else {
            $options .= '<option value="0">-- Select Category First --</option>';
        }



        foreach ($categories as $category) {
            $options .= '<option value="' . $category->id . '" ';
            $options .= '>' . $category->name . '</option>';
        }
        echo $options;
    }

    public function posts_category_by_parent_id_from_tech() {
        ajaxAuthorized();
        $category_id = $this->input->post('category_id', TRUE);
        $categories = [];
        $options = '';
        if ($category_id) {
            $this->db->from('post_category');
            $this->db->where('parent_id', $category_id);
            $this->db->where('sub_category_id', 0);
            $categories = $this->db->get()->result();

            if (count($categories) == 0) {
                $options .= '<option value="0"> No Category Found</option>';
            } else {
                $options .= '<option value="0">-- Select Sub Category --</option>';
            }
        } else {
            $options .= '<option value="0">-- Select Category First --</option>';
        }



        foreach ($categories as $category) {
            $options .= '<option value="' . $category->id . '" ';
            $options .= '>' . $category->name . '</option>';
        }
        echo $options;
    }

    public function get_sub_categories($id) {
        ajaxAuthorized();
        $this->db->from('post_category');
        $this->db->where('parent_id', $id);
        $this->db->where('sub_category_id', 0);

        $categories = $this->db->get()->result();
//        $categories = $this->db->get_where('post_category', ['parent_id' => $id])->result();

        echo json_encode(['categories' => $categories]);
    }

    public function child_category_by_sub_category_id() {
        ajaxAuthorized();
        $category_id = $this->input->post('category_id', TRUE);
        $categories = [];
        $options = '';
        if ($category_id) {
            $this->db->where('sub_category_id', $category_id);
            $this->db->where_not_in('template_design', ['28']);
            $categories = $this->db->get('post_category')->result();

            if (count($categories) == 0) {
                $options .= '<option value="0"> No Category Found</option>';
            } else {
                $options .= '<option value="0">--Select Child Category--</option>';
            }
        } else {
            $options .= '<option value="0">--Select Sub Category First--</option>';

        }

        foreach ($categories as $category) {
            $options .= '<option data-template="' . $category->template_design . '" value="' . $category->id . '" ';
            $options .= '>' . $category->name . '</option>';
        }
        echo $options;
    }


}
