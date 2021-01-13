<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends Fm_model{

    public $table   = 'users';
    
    function __construct(){
        parent::__construct();
    }

    /**
     * @param $username string    
     * @return array
     */
    function validateUser($username){
        return $this->db
                ->select('id,role_id,first_name,last_name,email,profile_photo,password,status,oauth_uid,oauth_provider, is_deleted')
                ->get_where($this->table, ['email' => $username] )
                ->row();
    }
    
    
            
    function sign_up($data){                
        $this->db->insert($this->table, $data);
    }

    /*
 * Insert / Update facebook profile data into the database
 * @param array the data for inserting into the table
 */
    public function checkUser($userData = array()){
        if(!empty($userData)){
            //check whether user data already exists in database with same oauth info
            $this->db->from($this->table);
            $this->db->where(array('oauth_provider'=>$userData['oauth_provider'], 'oauth_uid'=>$userData['oauth_uid']));
            $prevQuery = $this->db->get();
            $prevCheck = $prevQuery->num_rows();

            if($prevCheck > 0){
                $prevResult = $prevQuery->row();

                //update user data
                $update = $this->db->update($this->table, $userData, array('id' => $prevResult->id));

                //get user ID
                $user = $prevResult;
            }else{
                //insert user data
                $userData['created']  = date("Y-m-d H:i:s");
                $userData['role_id']        = 6;
                $userData['gender']        = 'Not Mention';
                $userData['status'] = 'Active';

                // checking name
                $hasNameCount = $this->db->where(['first_name' => $userData['first_name'], 'last_name' => $userData['last_name']])->from('users')->get()->num_rows();
                $hasNameCount = empty($hasNameCount) ? '' : $hasNameCount;
                // slugify name
                $userData['profile_slug'] = slugify($this->input->post('first_name', TRUE) . ' ' . $this->input->post('last_name', TRUE) . $hasNameCount);

                $this->db->insert($this->table, $userData);

                $user_id = $this->db->insert_id();
                //get user ID
                $user = $this->db->get_where($this->table, array('id' => $user_id))->row();
            }
        }

        //return user ID
        return $user?$user:FALSE;
    }

                     
}