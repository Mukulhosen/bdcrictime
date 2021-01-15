<?php defined('BASEPATH') OR exit('No direct script access allowed');  ?>

<section class="content-header">
    <h2>User Details <small>of</small> <?php echo $first_name . ' ' . $last_name; ?> </h2>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Admin </a></li>        
        <li class="active">User list</li>
    </ol>
</section>

<section class="content"> 
    <?php 
        // Create Tabs
        $id = $this->uri->segment('4');
        echo Users_helper::makeTab($id, 'update');
    ?>

    
    