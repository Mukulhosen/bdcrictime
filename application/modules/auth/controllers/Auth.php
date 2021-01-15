<?php

defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2016-10-13
 */

class Auth extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('form_validation');
        $this->load->helper('cookie');
    }

    public function auth_logout()
    {
        $current_status = array('current_status' => 'Offline');
        $this->db->where('id', getLoginUserData('user_id'));
        $this->db->update('users', $current_status);
        if (getLoginUserData('oauth_provider') == 'facebook') {
            $this->facebook->destroy_session();
        }
        $cookie = [
            'name' => 'login_data',
            'value' => false,
            'expire' => -84000,
            'secure' => false
        ];

        $this->input->set_cookie($cookie);
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('value');
        $this->session->unset_userdata('expire');
        $this->session->unset_userdata('secure');
    }

    public function login()
    {
        ajaxAuthorized();
        $validCaptcha = checkRecaptcha();
        if (!$validCaptcha) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Please verify you are not a robot</p>');
            die();
        }
        //sleep(1);
        /*
         * Stop Brute Force Attract   // by sleeping now... 
         * will add account deactivation letter      
         */
        $data = $this->session->flashdata('login');
        if ($this->session->flashdata('login')) {
            $username = $data['username'];
            $password = $data['password'];
        } else {
            $username = $this->security->xss_clean($this->input->post('username'));
            $password = $this->security->xss_clean($this->input->post('password'));
        }

        $remember = ($this->input->post('remember')) ? (60 * 60 * 24 * 7) : 0;

        if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Please enter a valid user name</p>');
            exit;
        }
        
        if ($username) {
            $userdata = $this->Auth_model->validateUser($username);
            if ($userdata) {
                if (empty($userdata->is_deleted)) {
                    if ($userdata->role_id == 6 || $userdata->role_id == 7) {
                        if (password_verify($password, $userdata->password)) {
                            if ($userdata->status === 'Active') {

                                $cookie_data = json_encode([
                                    'user_id' => $userdata->id,
                                    'user_mail' => $userdata->email,
                                    'role_id' => $userdata->role_id,
                                    'name' => $userdata->first_name . ' ' . $userdata->last_name,
                                    'photo' => $userdata->profile_photo,
                                    'oauth_uid' => $userdata->oauth_uid,
                                    'oauth_provider' => $userdata->oauth_provider
                                ]);

                                $cookie = [
                                    'name' => 'login_data',
                                    'value' => base64_encode($cookie_data),
                                    'expire' => $remember,
                                    'secure' => false
                                ];

                                $this->input->set_cookie($cookie);
                                $this->session->set_userdata($cookie);

                                $current_status = array(
                                    'current_status' => 'Online',
                                    'last_access' => (time() + 120),
                                );

                                $this->db->where('id', $userdata->id);
                                $this->db->update('users', $current_status);
                                //
                                addCommentFromCookie(0, $userdata->id);
                                echo ajaxRespond('OK', '<p class="ajax_success">Login Success</p>');

                                // $this->session->unset_flashdata('login');
                                // Save Session and refresh
                            } else {
                                echo ajaxRespond('Fail', '<p class="ajax_error">Your account is inactive.</p>');
                            }
                        } else {
                            echo ajaxRespond('Fail', '<p class="ajax_error">Incorrect Password!</p>');
                        }
                    } else {
                        echo ajaxRespond('Fail', '<p class="ajax_error">You are not authorized to login from this page!</p>');
                        exit;
                    }
                } else {
                    echo ajaxRespond('Fail', '<p class="ajax_error">This account is deleted!</p>');
                }
            } else {
                echo ajaxRespond('Fail', '<p class="ajax_error">Incorrect Username!</p>');
            }
        } else {
            echo ajaxRespond('Fail', '<p class="ajax_error">Please enter valid username!</p>');
        }
    }

    public function admin_login()
    {
        ajaxAuthorized();
//        $validCaptcha = checkRecaptcha();
//        if (!$validCaptcha) {
//            echo ajaxRespond('Fail', '<p class="ajax_error">Please verify you are not a robot</p>');
//            die();
//        }
        //sleep(1);
        /*
         * Stop Brute Force Attract   // by sleeping now... 
         * will add account deactivation letter      
         */
        $data = $this->session->flashdata('login');
        if ($this->session->flashdata('login')) {
            $username = $data['username'];
            $password = $data['password'];
        } else {
            $username = $this->security->xss_clean($this->input->post('username'));
            $password = $this->security->xss_clean($this->input->post('password'));
        }

        $remember = ($this->input->post('remember')) ? (60 * 60 * 24 * 7) : 0;

        if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Please enter a valid user name</p>');
            exit;
        }

        if ($username) {
            $userdata = $this->Auth_model->validateUser($username);

            if ($userdata) {
                if (empty($userdata->is_deleted)) {
                    if ($userdata->role_id == 6 || $userdata->role_id == 7) {
                        echo ajaxRespond('Fail', '<p class="ajax_error">You are not authorized to login from this page!</p>');
                        exit;
                    }
                    if (password_verify($password, $userdata->password)) {
                        if ($userdata->status === 'Active') {

                            $cookie_data = json_encode([
                                'user_id' => $userdata->id,
                                'user_mail' => $userdata->email,
                                'role_id' => $userdata->role_id,
                                'name' => $userdata->first_name . ' ' . $userdata->last_name,
                                'photo' => $userdata->profile_photo,
                                'oauth_uid' => $userdata->oauth_uid,
                                'oauth_provider' => $userdata->oauth_provider
                            ]);

                            $cookie = [
                                'name' => 'login_data',
                                'value' => base64_encode($cookie_data),
                                'expire' => $remember,
                                'secure' => false
                            ];
                            $this->input->set_cookie('login_data', base64_encode($cookie_data), $remember);
                            $this->session->set_userdata($cookie);


                            echo ajaxRespond('OK', '<p class="ajax_success">Login Success</p>');

                            // $this->session->unset_flashdata('login');
                            // Save Session and refresh
                        } else {
                            echo ajaxRespond('Fail', '<p class="ajax_error">Your account is inactive.</p>');
                        }
                    } else {
                        echo ajaxRespond('Fail', '<p class="ajax_error">Incorrect Password!</p>');
                    }
                } else {
                    echo ajaxRespond('Fail', '<p class="ajax_error">This account is deleted!</p>');
                }
            } else {
                echo ajaxRespond('Fail', '<p class="ajax_error">Incorrect Username!</p>');
            }
        } else {
            echo ajaxRespond('Fail', '<p class="ajax_error">Please enter valid username!</p>');
        }
    }

    public function sign_up()
    {
        ajaxAuthorized();
        $validCaptcha = checkRecaptcha();
        if (!$validCaptcha) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Please verify you are not a robot</p>');
            die();
        }
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $valid = [
                'first_name' => form_error('first_name'),
                'your_email' => form_error('your_email'),
                'password' => form_error('password'),
                'passconf' => form_error('passconf'),
                'role_id' => form_error('role_id'),
            ];
            echo(json_encode($valid));
        } else {
            $user_id = $this->create();
            if (empty($user_id)) {
                echo ajaxRespond('Fail', '<p class="ajax_error">Please change you name. We already have an user having same name<p>');
                return false;
            }
            echo $this->auto_login($user_id);
        }
    }

    private function notification_email()
    {
        $data = [
            'role_id' => $this->input->post('role_id', TRUE),
            'first_name' => $this->input->post('first_name', TRUE),
            'last_name' => $this->input->post('last_name', TRUE),
            // 'user_id'       => $this->db->insert_id()
        ];

        $this->session->set_flashdata('data', $data);
        redirect('mail/welcome_mail');
    }

    private function auto_login($user_id)
    {
        $username = $this->security->xss_clean($this->input->post('your_email'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $remember = (60 * 60 * 24 * 7);

        $cookie_data = json_encode([
            'user_id' => $user_id,
            'role_id' => intval($this->input->post('role_id')),
            'user_mail' => $username,
            'name' => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
            'photo' => 'no-photo.gif'
        ]);

        $cookie = [
            'name' => 'login_data',
            'value' => base64_encode($cookie_data),
            'expire' => $remember,
            'secure' => false
        ];

        $this->input->set_cookie($cookie);
        $this->session->set_userdata($cookie);

        return ajaxRespond('OK', '<p class="ajax_error">Auto login success</p>');
    }

    public function logout()
    {
        $current_status = array('current_status' => 'Offline');
        $this->db->where('id', getLoginUserData('user_id'));
        $this->db->update('users', $current_status);
        if (getLoginUserData('oauth_provider') == 'facebook') {
            $this->facebook->destroy_session();
        }
        $cookie = [
            'name' => 'login_data',
            'value' => false,
            'expire' => -84000,
            'secure' => false
        ];

        $this->input->set_cookie($cookie);
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('value');
        $this->session->unset_userdata('expire');
        $this->session->unset_userdata('secure');

        redirect(site_url(), 'refresh');
    }

    public function login_form()
    {
        $this->load->view('auth/login');
    }

    private function create()
    {
        $profileSlug = slugify($this->input->post('first_name', TRUE) . ' ' . $this->input->post('last_name', TRUE));
        $hasSlug = $this->db->where('profile_slug', $profileSlug)->from('users')->get()->row();
        if (!empty($hasSlug)) {
            return false;
        }
        $dob = $this->input->post('dob_yy', TRUE) . '-' . $this->input->post('dob_mm', TRUE) . '-' . $this->input->post('dob_dd', TRUE);
        $user_data = [
            'role_id' => $this->input->post('role_id', TRUE),
            'first_name' => $this->input->post('first_name', TRUE),
            'last_name' => $this->input->post('last_name', TRUE),
            'gender' => $this->input->post('gender', TRUE),
            'email' => $this->input->post('your_email', TRUE),
            'location' => $this->input->post('location', TRUE),
            'lat' => $this->input->post('lat', TRUE),
            'lng' => $this->input->post('lng', TRUE),
            'dob' => $dob,
            'password' => password_encription($this->input->post('password', TRUE)),
            'status' => 'Active',
            'created' => date('Y-m-d H:i:s'),
            'profile_slug' => $profileSlug,
        ];
        $this->Auth_model->sign_up($user_data);

        $user_id = $this->db->insert_id();
        $user_data['user_id'] = $user_id;

        $userData = [
            'role_id' => $this->input->post('role_id', TRUE),
            'first_name' => $this->input->post('first_name', TRUE),
            'last_name' => $this->input->post('last_name', TRUE),
            'email' => $this->input->post('your_email', TRUE),
            'raw_pass' => $this->input->post('password', TRUE),
            'user_id' => $user_id,
        ];

        Modules::run('mail/welcome_mails', $userData);
        return $user_id;
    }

    public function _rules()
    {
        $this->form_validation->set_rules('first_name', 'first name', 'trim|required');
        $this->form_validation->set_rules('your_email', 'your email', 'trim|valid_email|required|is_unique[users.email]',
            ['is_unique' => 'This email already in used', 'valid_email' => 'Please enter a valid email address']);

        $this->form_validation->set_rules('role_id', 'role_id', 'required|less_than[25]|greater_than[5]');
        $this->form_validation->set_rules('password', 'password field', 'required');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');
        $this->form_validation->set_error_delimiters('<p class="ajax_error">', '</p>');
    }

    public function forgot_pass()
    {

        ajaxAuthorized();
        $email = $this->input->post('forgot_mail');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Invalid email address!</p>');
            exit;
        }

        $is_exist = $this->db->get_where('users', ['email' => $email, 'oauth_provider' => 'web', 'is_deleted' => 0]);

        if ($is_exist->num_rows() > 0) {

            $hash_email = password_encription($email);

            $array = [
                'Status' => 'OK',
                '_token' => $hash_email,
                'Msg' => '<p class="ajax_success">Reset password link sent to your email </p>'
            ];
            Modules::run('mail/send_pwd_mail', $email, $hash_email);
            echo json_encode($array);
        } else {
            echo ajaxRespond('Fail', '<p class="ajax_error">Email address not found!</p>');
        }
    }

    public function reset_password()
    {
        $this->load->view('frontend/header');
        $this->load->view('my_account/reset_pass');
        $this->load->view('frontend/footer');
    }

    public function reset_password_action()
    {

        $reset_token = $this->input->post('verify_token');
        $email = $this->input->post('email');

        $new_pass = trim($this->input->post('new_password'));
        $re_pass = trim($this->input->post('retype_password'));

        if (!password_verify($email, $reset_token)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Token Not Match</p>');
            exit;
        }

        if ($new_pass != $re_pass) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Not Match</p>');
            exit;
        }

        $hash_pass = password_encription($new_pass);

        $this->db->set('password', $hash_pass);
        $this->db->where('email', $email);
        $this->db->update('users');

        // Run Auto Login here
        echo ajaxRespond('OK', '<p class="ajax_success">Successfully updated</p>');

//        $newdata = array(
//            'username' => $email,
//            'password' => $new_pass
//        );
//        $this->session->set_flashdata('login', $newdata);
//        redirect('auth/login');
    }

    public function current_status_check()
    {
        $user_id = $this->input->post('user_id');
        if ($user_id) {
            $data = [
                'last_access' => (time() + 600),
            ];
            $this->db->where('id', $user_id);
            $this->db->update('users', $data);
        }
    }

}










