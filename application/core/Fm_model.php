<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Flick Base App
 * Model File
 * @package   Flick Base App
 * @author    Khairul Azam (Flick Team)  
 */

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Capsule\Manager as DB;


class Fm_model extends CI_Model{
    protected $user_id;
    protected $role_id;
    
    public function __construct() {
        parent::__construct();
        
        $this->user_id = getLoginUserData('user_id');
        $this->role_id = getLoginUserData('role_id');
    }		
}
