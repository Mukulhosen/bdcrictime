<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo 'BDCrictime'; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <base id="base_url" href="<?php echo base_url(); ?>"/>
        
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="assets/lib/bootstrap/css/bootstrap.min.css?<?php echo time(); ?>">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="assets/lib/font-awesome/font-awesome.min.css?<?php echo time(); ?>">
        <!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css?<?php echo time(); ?>">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css?<?php echo time(); ?>">-->      

        <!-- Theme style -->
        <link rel="stylesheet" href="assets/admin/dist/css/AdminLTE.min.css?<?php echo time(); ?>">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="assets/admin/dist/css/skins/_all-skins.min.css?<?php echo time(); ?>">

        <!-- Date Picker -->
        <link rel="stylesheet" href="assets/lib/plugins/datepicker/datepicker3.css?<?php echo time(); ?>">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="assets/lib/plugins/daterangepicker/daterangepicker.css?<?php echo time(); ?>">   
        
        <!-- Select2 -->
        <link rel="stylesheet" href="assets/lib/plugins/select2/dist/css/select2.min.css?<?php echo time(); ?>">
        
        <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyBZynEb5eLxgCV-vL0hdkd9fFC3TQWHuPc"></script>

        <!-- jQuery 2.2.3 -->
        <script src="assets/lib/plugins/jQuery/jquery-2.2.3.min.js?<?php echo time(); ?>"></script>
        <script src="assets/lib/plugins/jQueryUI/jquery-ui.min.js?<?php echo time(); ?>" type="text/javascript"></script>
        
        <!-- Bootstrap 3.3.6 -->
        <script src="assets/lib/bootstrap/js/bootstrap.min.js?<?php echo time(); ?>"></script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link rel="stylesheet" href="assets/admin/dist/css/style.css?<?php echo time(); ?>">
        <link rel="stylesheet" href="assets/admin/dist/css/dashboard.css?<?php echo time(); ?>">
        <link rel="stylesheet" href="assets/lib/ajax.css?<?php echo time(); ?>">
    </head>
    <?php 
        $role_id = getLoginUserData('role_id');
    ?>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="admin" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini">BDC</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg">BDCrictime</span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <span class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </span>

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">

                            <!-- User Account: style can be found in dropdown.less -->
<!--                            <li><a href="--><?php //echo base_url(); ?><!--" target="_blank"> <i class="fa fa-globe"></i> View Frontend</a> </li>-->
                            <?php
                            // Making Profile img dynamic
                            $pic = getLoginUserData('photo');
                            $profile_pic_url = 'assets/images/profile.svg';
                            if (!empty($pic) ) {
                                $profile_pic_url = get_profile_img($pic, getLoginUserData('oauth_provider'));
                            }
                            ?>
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?=$profile_pic_url?>" id="profile-img-1" class="user-image" alt="User Image">
                                    <span class="hidden-xs"><?php echo getLoginUserData('name'); ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="<?=$profile_pic_url?>" id="profile-img-2" class="img-circle" alt="User Image">
                                        <p> 
                                            <?php echo getLoginUserData('name'); ?> - <?php echo getRoleName($role_id); ?> 
                                            <small><?php echo getLoginUserData('user_mail'); ?></small>
                                        </p>
                                    </li>

                                    <!-- Menu Footer-->
                                    <li class="user-footer" style="background-color: #1b6d9c;">
                                        <div class="pull-left">
                                            <a href="<?php echo ((!in_array($role_id,[6,7])) ? site_url('admin/profile') : site_url('my-account/profile')); ?>" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>                   
                        </ul>
                    </div>
                </nav>
            </header>
<!--            --><?php //if (!empty($this->session->flashdata('message'))){ ?>
<!--                <div class="js_update_respond">-->
<!--                    <p class="ajax_success" style="margin-left: 230px;">--><?php //echo $this->session->flashdata('message'); ?><!--</p>-->
<!--                </div>-->
<!--            --><?php //} ?>
<!--            --><?php //if (!empty($this->session->flashdata('error'))){ ?>
<!--                <div class="js_update_respond">-->
<!--                    <p class="ajax_error" style="margin-left: 230px;">--><?php //echo $this->session->flashdata('error'); ?><!--</p>-->
<!--                </div>-->
<!--            --><?php //} ?>
