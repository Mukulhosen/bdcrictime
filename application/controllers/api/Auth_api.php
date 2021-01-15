<?php

defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Mukul Hosen
 * Date : 2016-10-13
 */


class Auth_api extends MX_Controller
{

    function __construct()
    {
						header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, app-secret");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    $method = $_SERVER['REQUEST_METHOD'];
    if($method == "OPTIONS") {
        die();
    }
        parent::__construct();

        $this->load->library('form_validation');
        $this->load->helper('api_helper');
    }

    public function login()
    {
        if ($this->input->method() != 'post') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));

        $username = $this->security->xss_clean($this->input->post('email'));
        $password = $this->security->xss_clean($this->input->post('password'));
        //checking right email format
        //pp($this->input->post('username'));die;
        if (!filter_var($username, FILTER_VALIDATE_EMAIL)) return apiResponse([
            'status' => false,
            'message' => 'Invalid Email Format',
            'data' => new stdClass()
        ]);
        // All Field is fill up
        if (empty($username) || empty($password)) return apiResponse([
            'status' => false,
            'message' => 'All Fields are Required',
            'data' => new stdClass()
        ]);
        // Getting UserData By Username
        $userdata = $this->db
            ->select('id,role_id,first_name,last_name,email,profile_photo,password,status,profile_slug, code, is_deleted, oauth_provider')
            ->get_where('users', ['email' => $username])
            ->row();
        // checking we are found the user by username
        if (empty($userdata)) return apiResponse([
            'status' => false,
            'message' => 'Incorrect Email!',
            'data' => new stdClass()
        ]);
        // verify password
        if (!password_verify($password, $userdata->password)) return apiResponse([
            'status' => false,
            'message' => 'Incorrect Password!',
            'data' => new stdClass()
        ]);

        if (!empty($userdata->is_deleted)) return apiResponse([
            'status' => false,
            'message' => 'This Account is Deleted!',
            'data' => new stdClass()
        ]);
        // generate user token
        $token = $this->save_token($userdata->id, $this->input->post('device_token'), $this->input->post('device_type'), $userdata->profile_slug);
        // checking user status
        if ($userdata->status == 'Unverified') {
            // regenerate Email verification code
            $code = mt_rand(100000, 999999);
            $this->db->where('email', $username);
            $this->db->update('users', ['code' => $code]);
            // sending email
            Modules::run('mail/send_email_verify_code', $username, $code);
            return apiResponse([
                'status' => true,
                'message' => 'Please Verify Your Email.',
                'data' => [
                    'email_verify' => false,
                    'token' => $token
                ]
            ]);
        }
        // checking user status
        if ($userdata->status != 'Active') return apiResponse([
            'status' => false,
            'message' => 'Your account is inactive.',
            'data' => new stdClass()
        ]);
        // making array for right data
        $user_data_array = [
            'user_id' => $userdata->id,
            'email' => $userdata->email,
            'role_id' => $userdata->role_id,
            'first_name' => $userdata->first_name,
            'last_name' => $userdata->last_name,
            'photo' => $userdata->oauth_provider ? base_url() . 'uploads/users_profile/' . $userdata->profile_photo : $userdata->profile_photo,
            'status' => $userdata->status,
            'email_verify' => true,
            'token' => $token,
            'role_name' => getRoleName($userdata->role_id)
        ];
        // updating Online status
        $current_status = array(
            'current_status' => 'Online',
            'last_access' => (time() + 120),
        );
        $this->db->where('id', $userdata->id);
        $this->db->update('users', $current_status);
        // Finally send the final result
        return apiResponse([
            'status' => true,
            'message' => "",
            'data' => $user_data_array
        ]);


    }

    /**
     * @return mixed
     * resent email token
     */
    public function resend_email()
    {
        if ($this->input->method() != 'post') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));

        $token = @$this->input->request_headers()['token'];
        $userinfo = get_user_by_token($token);
        if (empty($userinfo)) return apiResponse([
            'status' => false,
            'message' => 'Invalid Account.',
            'data' => new stdClass()
        ]);
        $code = mt_rand(100000, 999999);
        $this->db->where('email', $userinfo->email);
        $this->db->update('users', ['code' => $code]);
        Modules::run('mail/send_email_verify_code', $userinfo->email, $code);
        return apiResponse([
            'status' => true,
            'message' => 'The code sent to your email.',
            'data' => new stdClass()
        ]);
    }


    public function sign_up()
    {
        if ($this->input->method() != 'post') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));

        $this->_rules();
        // checking form rules
        if ($this->form_validation->run() == FALSE) return apiResponse([
            'status' => false,
            'message' => form_error('first_name') . form_error('email') . form_error('password') . form_error('role_id') . form_error('gender'),
            'data' => new stdClass()
        ]);
        // checking name
        $hasNameCount = $this->db->where(['first_name' => $this->input->post('first_name', TRUE), 'last_name' => $this->input->post('last_name', TRUE)])->from('users')->get()->num_rows();
        $hasNameCount = empty($hasNameCount) ? '' : $hasNameCount;
        // slugify name
        $profileSlug = slugify($this->input->post('first_name', TRUE) . ' ' . $this->input->post('last_name', TRUE) . $hasNameCount);

        if (!empty($hasSlug)) return apiResponse([
            'status' => false,
            'message' => 'Please change you name. We already have an user having same name',
            'data' => new stdClass()
        ]);
        // create code
        $code = mt_rand(100000, 999999);

        $user_data = [
            'role_id' => $this->input->post('role_id', TRUE),
            'first_name' => $this->input->post('first_name', TRUE),
            'last_name' => $this->input->post('last_name', TRUE),
            'gender' => $this->input->post('gender', TRUE),
            'email' => $this->input->post('email', TRUE),
            'location' => $this->input->post('location', TRUE),
            'lat' => $this->input->post('lat', TRUE),
            'lng' => $this->input->post('lng', TRUE),
            'dob' => $this->input->post('dob', TRUE),
            'password' => password_encription($this->input->post('password', TRUE)),
            'status' => 'Unverified',
            'created' => date('Y-m-d H:i:s'),
            'profile_slug' => $profileSlug,
            'code' => $code
        ];
        $this->db->insert('users', $user_data);

        $user_id = $this->db->insert_id();
        // arrange return data
        $WelcameEmailData = [
            'user_id' => $user_id,
            'first_name' => $this->input->post('first_name', TRUE),
            'last_name' => $this->input->post('last_name', TRUE),
            'email' => $this->input->post('email', TRUE),
            'raw_pass' => $this->input->post('password', TRUE),
        ];
        // sending welcome e-mail
        Modules::run('mail/welcome_mails', $WelcameEmailData);

        Modules::run('mail/send_email_verify_code', $user_data['email'], $code);
        // final sending response
        return apiResponse([
            'status' => true,
            'message' => "Your account has been created successfully. Please verify your email.",
            'data' => [
                'email_verify' => false,
                'token' => $this->save_token($user_id, $this->input->post('device_token', TRUE), $this->input->post('device_type', TRUE), $profileSlug)
            ]
        ]);

    }

    /**
     * @return mixed
     * confirmation the code verified
     */
    public function confirm_email_code()
    {
        if ($this->input->method() != 'post') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));


        $token = @$this->input->request_headers()['token'];
        $code = $this->input->post('code');

        if (empty($token) || empty($code)) return apiResponse([
            'status' => false,
            'message' => "All Fields Are Required",
            'data' => new stdClass()
        ]);
        $userinfo = get_user_by_token($token);

        if (empty($userinfo)) return apiResponse([
            'status' => false,
            'message' => "Invalid Account",
            'data' => new stdClass()
        ]);

        if ($userinfo->status != 'Unverified') return apiResponse([
            'status' => false,
            'message' => "Your E-mail Already Verified",
            'data' => new stdClass()
        ]);

        if ($userinfo->code != $code) return apiResponse([
            'status' => false,
            'message' => "Invalid Code",
            'data' => new stdClass()
        ]);

        $this->db->where('id', $userinfo->id);
        $this->db->update('users', ['code' => '', 'status' => 'Active']);
        $user_data_array = [
            'user_id' => $userinfo->id,
            'email' => $userinfo->email,
            'role_id' => $userinfo->role_id,
            'first_name' => $userinfo->first_name,
            'last_name' => $userinfo->last_name,
            'photo' => $userinfo->oauth_provider == 'web' ? base_url() . 'uploads/users_profile/' . $userinfo->profile_photo : $userinfo->profile_photo,
            'status' => 'Active',
            'email_verify' => true,
            'token' => $token,
            'role_name' => getRoleName($userinfo->role_id)
        ];
        return apiResponse([
            'status' => true,
            'message' => "",
            'data' => $user_data_array
        ]);
    }

    private function save_token($user_id, $device_token, $device_type = 'Android', $profileSlug)
    {
        $exit = $this->db->get_where('user_tokens', ['device_token' => $device_token, 'user_id' => $user_id, 'device_type' => $device_type])->row();
        if (!empty($exit)) return $exit->token;
        $token = base64_encode($profileSlug . '|' . time());
        $data = [
            'user_id' => $user_id,
            'token' => $token,
            'device_type' => $device_type,
            'device_token' => $device_token,
            'created' => date('Y-m-d')
        ];
        $this->db->insert('user_tokens', $data);
        return $token;
    }


    public function logout()
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

        $token = @$this->input->request_headers()['token'];
        //$token = $this->input->post('token');
        if (empty($token)) return apiResponse([
            'status' => false,
            'message' => "Invalid Request",
            'data' => new stdClass()
        ]);

        $this->db->delete('user_tokens', array('token' => $token));

        return apiResponse([
            'status' => true,
            'message' => "You successfully Logout",
            'data' => new stdClass()
        ]);
    }


    public function _rules()
    {
        $this->form_validation->set_rules('first_name', 'first name', 'trim|required');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
        $this->form_validation->set_rules('email', 'your email', 'trim|valid_email|required|is_unique[users.email]',
            ['is_unique' => 'This email already in used', 'valid_email' => 'Please enter a valid email address']);

        $this->form_validation->set_rules('role_id', 'role', 'required|less_than[14]|greater_than[5]');
        $this->form_validation->set_rules('password', 'Password field', 'required|min_length[6]');
        $this->form_validation->set_error_delimiters('', ' \n ');
    }

    public function forgot_pass()
    {
        if ($this->input->method() != 'post') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));

        $email = $this->input->post('email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return apiResponse([
            'status' => false,
            'message' => "Invalid Email Address",
            'data' => new stdClass()
        ]);

        $is_exist = $this->db->get_where('users', ['email' => $email, 'oauth_provider' => 'web', 'is_deleted' => 0])->row();

        if (empty($is_exist)) return apiResponse([
            'status' => false,
            'message' => "Email address not found!",
            'data' => new stdClass()
        ]);
        $code = mt_rand(100000, 999999);
        $insert_data = [
            'user_id' => $is_exist->id,
            'code' => $code,
            'created' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('user_forget_codes', $insert_data);

        // sending  e-mail
        Modules::run('mail/send_pwd_mail_app', $email, $code);
        return apiResponse([
            'status' => true,
            'message' => "Password Reset Code has sent to your email",
            'data' => new stdClass()
        ]);
    }


    public function reset_password_action()
    {
        if ($this->input->method() != 'post') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));

        $reset_token = trim($this->input->post('code'));
        $email = $this->input->post('email');
        $new_pass = trim($this->input->post('password'));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return apiResponse([
            'status' => false,
            'message' => "Invalid Email Address",
            'data' => new stdClass()
        ]);

        if (empty($reset_token)) return apiResponse([
            'status' => false,
            'message' => "The Code is empty",
            'data' => new stdClass()
        ]);

        $this->db->select("user_forget_codes.id, user_forget_codes.created");
        $this->db->from('user_forget_codes');
        $this->db->join('users', 'users.id = user_forget_codes.user_id', 'INNER');
        $this->db->where('users.email', $email);
        $this->db->where('user_forget_codes.code', $reset_token);
        $this->db->where('user_forget_codes.is_used', 0);
        $this->db->order_by('user_forget_codes.id', 'DESC');

        $exit_code = $this->db->get()->row();

        if (empty($exit_code)) return apiResponse([
            'status' => false,
            'message' => "Invalid Code",
            'data' => new stdClass()
        ]);
        $from_time = strtotime($exit_code->created);
        $to_time = strtotime(date('Y-m-d H:i:s'));

        $diff = round(abs($to_time - $from_time) / 60, 2);

        if ($diff > 10) return apiResponse([
            'status' => false,
            'message' => "The Code Expired",
            'data' => new stdClass()
        ]);

        $this->db->where('id', $exit_code->id);
        $this->db->update('user_forget_codes', ['is_used' => 1]);

        $hash_pass = password_encription($new_pass);

        $this->db->set('password', $hash_pass);
        $this->db->where('email', $email);
        $this->db->update('users');

        return apiResponse([
            'status' => true,
            'message' => "The Password updated. Please Sign In",
            'data' => new stdClass()
        ]);

    }

    public function role_list()
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

        return apiResponse([
            'status' => true,
            'message' => "",
            'data' => [
                ['id' => 7, 'title' => 'Contributors'],
                ['id' => 14, 'title' => 'Bloggers'],
                ['id' => 6, 'title' => 'Individual']
            ]
        ]);

    }

    public function profile()
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


        $token = @$this->input->request_headers()['token'];

        $userinfo = get_user_by_token($token);

        if (empty($userinfo)) return apiResponse([
            'status' => false,
            'message' => "Invalid Account",
            'data' => new stdClass()
        ]);

        $data = [
            'email' => $userinfo->email,
            'first_name' => $userinfo->first_name,
            'last_name' => $userinfo->last_name,
            'photo' => $userinfo->oauth_provider == 'web' ? base_url() . 'uploads/users_profile/' . $userinfo->profile_photo : $userinfo->profile_photo,
            'role_name' => getRoleName($userinfo->role_id),
            'dob' => $userinfo->dob,
            'location' => $userinfo->location,
            'biography' => $userinfo->biography,
            'facebook_link' => $userinfo->facebook_link,
            'twitter_link' => $userinfo->twitter_link,
            'instagram_link' => $userinfo->instagram_link,
            'gender' => $userinfo->gender
        ];

        return (apiResponse([
            'status' => true,
            'message' => '',
            'data' => $data
        ]));
    }

    public function profile_update()
    {
        if ($this->input->method() != 'post') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));


        $token = @$this->input->request_headers()['token'];

        $userinfo = get_user_by_token($token);

        if (empty($userinfo)) return apiResponse([
            'status' => false,
            'message' => "Invalid Account",
            'data' => new stdClass()
        ]);

        $this->form_validation->set_rules('first_name', 'first name', 'trim|required');
        $this->form_validation->set_error_delimiters('', ' \n ');

        // checking form rules
        if ($this->form_validation->run() == FALSE) return apiResponse([
            'status' => false,
            'message' => form_error('first_name'),
            'data' => new stdClass()
        ]);

        $user_data = [
            'first_name' => $this->input->post('first_name', TRUE),
            'last_name' => $this->input->post('last_name', TRUE),
            'dob' => $this->input->post('dob', TRUE),
            'location' => $this->input->post('location', TRUE),
            'biography' => $this->input->post('biography', TRUE),
            'facebook_link' => $this->input->post('facebook_link', TRUE),
            'twitter_link' => $this->input->post('twitter_link', TRUE),
            'instagram_link' => $this->input->post('instagram_link', TRUE)
        ];
        $this->db->where('id', $userinfo->id);
        $this->db->update('users', $user_data);

        return apiResponse([
            'status' => true,
            'message' => "Your Account Updated",
            'data' => new stdClass()
        ]);

    }

    public function change_password()
    {
        if ($this->input->method() != 'post') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));


        $token = @$this->input->request_headers()['token'];

        $userinfo = get_user_by_token($token);

        if (empty($userinfo)) return apiResponse([
            'status' => false,
            'message' => "Invalid Account",
            'data' => new stdClass()
        ]);

        $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]');
        $this->form_validation->set_error_delimiters('', ' \n ');

        // checking form rules
        if ($this->form_validation->run() == FALSE) return apiResponse([
            'status' => false,
            'message' => form_error('old_password') . form_error('new_password'),
            'data' => new stdClass()
        ]);
        $old_password = $this->input->post('old_password');
        $new_password = password_encription($this->input->post('new_password', TRUE));
        // verify password
        if (!password_verify($old_password, $userinfo->password)) return apiResponse([
            'status' => false,
            'message' => 'Mismatch Old Password!',
            'data' => new stdClass()
        ]);

        $this->db->where('id', $userinfo->id);
        $this->db->update('users', ['password' => $new_password]);

        return apiResponse([
            'status' => true,
            'message' => "Your Password Changed",
            'data' => new stdClass()
        ]);


    }

    public function profile_picture_change()
    {
        if ($this->input->method() != 'post') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));


        $token = @$this->input->request_headers()['token'];

        $userinfo = get_user_by_token($token);

        if (empty($userinfo)) return apiResponse([
            'status' => false,
            'message' => "Invalid Account",
            'data' => new stdClass()
        ]);

        if (empty($_FILES['photo']['name'])) return apiResponse([
            'status' => false,
            'message' => "Please Select A Image",
            'data' => new stdClass()
        ]);

        $img = profile_photo_upload($_FILES['photo'], $userinfo->id);
        if ($userinfo->oauth_provider == 'web') {
            $old_img = $userinfo->profile_photo;
            $file = dirname(BASEPATH) . '/uploads/users_profile/' . $old_img;
            if ($old_img && file_exists($file)) {
                unlink($file);
            }
        }


        $this->db->where('id', $userinfo->id);
        $this->db->update('users', ['profile_photo' => $img]);
        return apiResponse([
            'status' => true,
            'message' => "Your Profile Image Changed",
            'data' => new stdClass()
        ]);

    }

    public function social_login()
    {

        if ($this->input->method() != 'post') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => new stdClass()
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => new stdClass()
        ], 405));

        $oauth_provider = $this->input->post('oauth_provider');
        $oauth_uid = $this->input->post('oauth_uid');
        $first_name = $this->input->post('first_name');

        if (empty($oauth_provider) || empty($oauth_uid) || empty($first_name)) {
            return (apiResponse([
                'status' => false,
                'message' => 'Some Required Field Missing from your Account',
                'data' => new stdClass()
            ], 200));
        }

        $userData['oauth_provider'] = $oauth_provider;
        $userData['oauth_uid'] = $oauth_uid;
        $userData['first_name'] = $first_name;
        $userData['last_name'] = !empty($this->input->post('last_name')) ? $this->input->post('last_name') : '';
        $userData['email'] = !empty($this->input->post('email')) ? $this->input->post('email') : '';
        $userData['gender'] = !empty($this->input->post('gender')) ? $this->input->post('gender') : 'Not Mention';
        $userData['profile_photo'] = !empty($this->input->post('profile_photo')) ? $this->input->post('profile_photo') : base_url().'assets/images/user.svg';
        $userData['created'] = date("Y-m-d H:i:s");
        $userData['role_id'] = 6;
        $userData['status'] = 'Active';

        // checking name
        $hasNameCount = $this->db->where(['first_name' => $userData['first_name'], 'last_name' => $userData['last_name']])->from('users')->get()->num_rows();
        $hasNameCount = empty($hasNameCount) ? '' : $hasNameCount;

        $this->db->from('users');
        $this->db->where(array('oauth_provider' => $userData['oauth_provider'], 'oauth_uid' => $userData['oauth_uid']));
        $prevQuery = $this->db->get();
        $prevCheck = $prevQuery->num_rows();

        if ($prevCheck > 0) {
            $prevResult = $prevQuery->row();

            if (!empty($prevQuery->is_deleted)) return apiResponse([
                'status' => false,
                'message' => 'This Account is Deleted!',
                'data' => new stdClass()
            ]);
            //update user data
            $update = $this->db->update('users', $userData, array('id' => $prevResult->id));
            $user_id = $prevResult->id;
        } else {
            // slugify name
            $userData['profile_slug'] = slugify($this->input->post('first_name', TRUE) . ' ' . $this->input->post('last_name', TRUE) . $hasNameCount);

            $this->db->insert('users', $userData);
            $user_id = $this->db->insert_id();
        }

        //get user ID
        $userinfo = $this->db->get_where('users', array('id' => $user_id))->row();

        $token = $this->save_token($user_id, $this->input->post('device_token', TRUE), $this->input->post('device_type', TRUE), $userinfo->profile_slug);

        $user_data_array = [
            'user_id' => $userinfo->id,
            'email' => $userinfo->email,
            'role_id' => $userinfo->role_id,
            'first_name' => $userinfo->first_name,
            'last_name' => $userinfo->last_name,
            'photo' => $userinfo->oauth_provider == 'web' ? base_url() . 'uploads/users_profile/' . $userinfo->profile_photo : $userinfo->profile_photo,
            'status' => 'Active',
            'email_verify' => true,
            'token' => $token,
            'role_name' => getRoleName($userinfo->role_id)
        ];

        return apiResponse([
            'status' => true,
            'message' => "",
            'data' => $user_data_array
        ]);

    }

    public function user_delete(){
        if ($this->input->method() != 'get') return (apiResponse([
            'status' => false,
            'message' => 'Invalid Request',
            'data' => []
        ], 405));

        if (app_secret != @$this->input->request_headers()['app-secret']) return (apiResponse([
            'status' => false,
            'message' => 'Bad Request',
            'data' => []
        ], 405));


        $token = @$this->input->request_headers()['token'];

        $userinfo = get_user_by_token($token);

        if (empty($userinfo)) return apiResponse([
            'status' => false,
            'message' => "Invalid Account",
            'data' => []
        ]);

        $this->db->where('id', $userinfo->id);
        $this->db->update('users', ['is_deleted' => 1]);

        return (apiResponse([
            'status' => true,
            'message' => 'This account is deleted',
            'data' => []
        ], 200));
    }

}











