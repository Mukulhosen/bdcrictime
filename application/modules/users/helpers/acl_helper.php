<?php

class Acl {
    
    public  static function Delete( $id = 1, $status = 'Unlocked'){
        
        if($status == 'Unlocked'){
            return '<span onClick="delete_role('.$id.')" class="btn btn-danger btn-sm"> <i class="fa fa-trash-o"></i> Delete</span>';
        } else {
            return '<span class="btn btn-default btn-sm disabled"> <i class="fa fa-lock"></i> Locked</span>';
        }
        
    }
}