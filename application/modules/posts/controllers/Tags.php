<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 08 Feb 2019 @11:31 am
 */

class Tags extends Admin_controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Tags_model');
        $this->load->helper('tags');
        $this->load->library('form_validation');
    }

    public function index() {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = Backend_URL . 'posts/tags/?q=' . urlencode($q);
            $config['first_url'] = Backend_URL . 'posts/tags/?q=' . urlencode($q);
        } else {
            $config['base_url'] = Backend_URL . 'posts/tags/';
            $config['first_url'] = Backend_URL . 'posts/tags/';
        }

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Tags_model->total_rows($q);
        $tags = $this->Tags_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tags' => $tags,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->viewAdminContent('posts/tags/index', $data);
    }

    public function create() {
        $data = array(
            'button' => 'Create',
            'action' => site_url(Backend_URL . 'posts/tags/create_action'),
            'id' => set_value('id'),
            'name' => set_value('name'),
            'slug' => set_value('slug'),
        );
        $this->viewAdminContent('posts/tags/create', $data);
    }

    public function create_action() {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $slug = $this->tagCount(slugify($this->input->post('name', TRUE)));
            if($slug != 0){
                $this->session->set_flashdata('message', '<p class="ajax_error">'.$this->input->post('name', TRUE).' tag already exists!</p>');
                redirect(site_url(Backend_URL . 'posts/tags'));
                return false;
            }
        
            $data = array(
                'name' => $this->input->post('name', TRUE),
                'slug' => slugify($this->input->post('name', TRUE)),
                'heading' => $this->input->post('name', TRUE) . ' News - Latest Breaking news and top headlines',
                'meta_description' => 'catch up with all the latest news , breaking stories, top headlines and opinion about ' . $this->input->post('name', TRUE)
            );
            $this->Tags_model->insert($data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Create Record Success</p>');
            redirect(site_url(Backend_URL . 'posts/tags'));
        }
    }
    
    private function tagCount($slug = null, $id = null){
        if ($id) {
            $this->db->where('id !=', $id);
        }
        $this->db->where('slug', $slug);
        return $this->db->count_all_results('tags');
    }

    public function update($id) {
        $row = $this->Tags_model->get_by_id($id);
        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url(Backend_URL . 'posts/tags/update_action'),
                'id' => set_value('id', $row->id),
                'name' => set_value('name', $row->name),
                'slug' => set_value('slug', $row->slug),
                'heading' => set_value('heading', $row->heading),
                'meta_description' => set_value('meta_description', $row->meta_description)
            );
            $this->viewAdminContent('posts/tags/update', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Record Not Found</p>');
            redirect(site_url(Backend_URL . 'posts/tags'));
        }
    }

    public function update_action() {
        $this->_rules();

        $id = $this->input->post('id', TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
        } else {
            $slug = $this->tagCount(slugify($this->input->post('name', TRUE)), $id);
            if($slug != 0){
                $this->session->set_flashdata('message', '<p class="ajax_error">'.$this->input->post('name', TRUE).' tag already exists!</p>');
                redirect(site_url(Backend_URL . 'posts/tags'));
                return false;
            }
            
            $data = array(
                'name' => $this->input->post('name', TRUE),
                'slug' => slugify($this->input->post('name', TRUE)),
                'heading' => $this->input->post('heading', TRUE),
                'meta_description' => $this->input->post('meta_description', TRUE)
            );

            $this->Tags_model->update($id, $data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Data Updated successfully</p>');
            redirect(site_url(Backend_URL . 'posts/tags/'));
        }
    }

    public function delete_action($id) {
        $row = $this->Tags_model->get_by_id($id);

        if ($row) {
            $this->Tags_model->delete($id);
            $this->session->set_flashdata('message', '<p class="ajax_success">Delete Record Success</p>');
            redirect(site_url(Backend_URL . 'posts/tags'));
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Record Not Found</p>');
            redirect(site_url(Backend_URL . 'posts/tags'));
        }
    }

    public function _rules() {
        $this->form_validation->set_rules('name', 'name', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}
