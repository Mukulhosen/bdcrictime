<?php 
    $role_id = getLoginUserData('role_id');
?>
<aside class="main-sidebar">
    <section class="sidebar">                             
        <ul class="sidebar-menu"> 
            <?php if(!in_array($role_id,[6,7]) ) { ?>           
                <li><a href="admin"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                <?php
                    echo Modules::run('posts/sidebarMenus');
                    echo Modules::run('users/_menu');
//                    echo Modules::run('cms/menu');
                    echo Modules::run('posts/menu');
//                    echo Modules::run('advertisement/menu');

                    

        //            echo add_main_menu('FAQ', 'admin/help', 'help', 'fa-list-alt');
//                    echo add_main_menu('Settings', 'admin/settings', 'settings', 'fa-gear');
                    echo Modules::run('module/menu');
//                    echo add_main_menu('About Us Settings', 'admin/about_us', 'about_us', 'fa-info');
                    echo add_main_menu('My Account', 'admin/profile', 'profile', 'fa-user');
//                    echo add_main_menu('DB Backup & Restore', 'admin/db_sync', 'db_sync', 'fa-columns');
                ?>
            <?php } else { ?>
                <li><a href="my-account"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
<!--                <li><a href="--><?php //echo site_url('search'); ?><!--" target="_blank"><i class="fa fa-search"></i><span>Find Friends</span></a></li>-->
            <?php } ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- Body Content Start -->
<div class="content-wrapper">
    <div id="ajaxContent">
