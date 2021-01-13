<?php load_module_asset('profile', 'css'); ?>
<?php load_module_asset('profile', 'js'); ?>

<section class="content-header">
    <input type="hidden" id="upload_url" value="<?php echo base_url() . 'ajax/load_file_to_server'; ?>"/>
    <h2>My Account <small>Update Profile</small>  </h2>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-user"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL . 'profile' ?>"><i class="fa fa-dashboard"></i> Profile</a></li>
        <li class="active">Update Profile</li>
    </ol>
</section>

<section class="content">    
    <?php echo Profile_helper::makeTab('#'); ?>
    <div class="box no-border">       
        <div class="box-body">
            <div class="col-md-12"><div id="ajax_respond"></div></div>
            <form method="post" id="update_profile_info" enctype="multipart/form-data">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name">First Name <sup>*</sup></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $first_name; ?>" required="required">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name <sup>*</sup></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $last_name; ?>" required="required">
                    </div>
                    <div class="form-group">
                        <label for="email">Email <sup>*</sup></label>
                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>" readonly="readonly" required="required">
                    </div>
                    
                    <?php $dob = explode('-', $dob); ?>
                    <div class="form-group">
                        <label class="control-label" for="dob">Date of Birth</label>
                        <div class="input-group"> <span class="input-group-addon" style="min-width: 50px;">DD</span>
                            <select class="form-control input-md" name="dob_dd">
                                <?php echo numericDropDown(1, 31, 1, $dob[2]); ?>
                            </select>
                            <span class="input-group-addon" style="min-width: 50px;">MM</span>
                            <select class="form-control input-md" name="dob_mm">
                                <?php echo numericDropDown(1, 12, 1, $dob[1]); ?>
                            </select>
                            <span class="input-group-addon" style="min-width: 50px;">YYYY</span>
                            <select name="dob_yy" class="form-control">
                                <?php echo numericDropDown(date('Y', strtotime('-60 years')), date('Y'), 1, $dob[0]); ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="dob_town">Town of Birth City</label>
                        <input type="text" class="form-control" id="dob_town" name="dob_town" value="<?php echo $dob_town; ?>">
                    </div>
                    <div class="form-group">
                        <label for="current_city">Current City</label>
                        <input type="text" class="form-control" id="current_city" name="current_city" value="<?php echo $current_city; ?>">
                    </div>
                    <div class="form-group">
                        <label for="school_name">School Name</label>
                        <input type="text" class="form-control" id="school_name" name="school_name" value="<?php echo $school_name; ?>">
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender: &nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <?php echo htmlRadio('gender', $gender, ['Male'=>'Male', 'Female'=>'Female', 'Not Mention'=>'Not Mention']); ?>             
                    </div>
                    
                    <div class="form-group">
                        <label>Account Type: &nbsp;&nbsp;&nbsp;&nbsp;<?php echo getRoleName($role_id); ?></label>
                    </div>
                    
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="location">Resident city or town</label>
                        <input type="text" name="location" id="autocomplete" value="<?php echo $location; ?>" placeholder="City / Town / Postcode" class="form-control input-md"> 
                        <input type="hidden" name="lat" id="latitude" value="<?php echo $lat; ?>">
                        <input type="hidden" name="lng" id="longitude" value="<?php echo $lng; ?>">
                    </div>
                    <div class="form-group">
                        <label for="facebook_link">Facebook Link</label>
                        <input type="text" class="form-control" id="facebook_link" name="facebook_link" value="<?php echo $facebook_link; ?>">
                    </div>
                    <div class="form-group">
                        <label for="twitter_link">Twitter Link</label>
                        <input type="text" class="form-control" id="twitter_link" name="twitter_link" value="<?php echo $twitter_link; ?>">
                    </div>
                    <div class="form-group">
                        <label for="instagram_link">Instagram Link</label>
                        <input type="text" class="form-control" id="instagram_link" name="instagram_link" value="<?php echo $instagram_link; ?>">
                    </div>
                    <div class="form-group">     
                        <label for="biography">Biography</label>
                        <textarea class="form-control" rows="3" name="biography" id="biography" required="required"><?php echo $biography; ?></textarea>
                        <?php echo form_error('biography') ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="profilePic">Profile Photo</label>
                        <input type="hidden" name="old_img" value="<?php echo $profile_photo; ?>" />                           

                        <div class="thumbnail upload_image" style="border:0!important;">
                            <?php echo getPhoto([
                                'attr'  => '',
                                'folder' => 'users_profile',
                                'photo' => $profile_photo,
                            ]); ?>

                            <div class="btn btn-default btn-file" style="margin: 10px auto 0;">
                                <i class="fa fa-picture-o"></i> Set Profile Photo
                                <input type="file" name="profile_photo" class="file_select" accept="image/*" onchange="instantShowUploadImage(this, '.upload_image')">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                         <div class="form-group col-md-6"></div>
                        <div class="col-md-6 pull-right">
                            <button type="button" onclick="update_profile('admin/profile/update');"  class="pull-right btn btn-success"><i class="fa fa-save"></i> Update</button>
                        </div>                                    
                    </div>
            </form>
        </div>
    </div>
</section>

<script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
<script>
    CKEDITOR.replace('biography', {
        toolbar: [
            {items: ['Copy', 'Cut', 'Paste', 'Undo', 'Redo', 'SelectAll',
                    'Bold', 'Italic', 'Underline', 'RemoveFormat', 'Image',"NumberedList","BulletedList",
                    'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Link', 'Unlink', 'Table',
                    'FontSize', 'Format', 'TextColor', 'BGColor', 'Print', 'Source', 'Preview', 'Maximize', 'Smiley',
                ]}
        ],
        height: ['250px'],
        customConfig: '<?php echo site_url(); ?>assets/lib/plugins/ckeditor/config.js',
    });
    
    
    // google location  track
    $("#autocomplete").on('focus', function () {  geolocate(); });

    var placeSearch, autocomplete;
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
    };

    function initialize() {
        autocomplete = new google.maps.places.Autocomplete(
             (document.getElementById('autocomplete')), {
            types: ['geocode']
        });                            
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            fillInAddress();
        });
    }
    function fillInAddress() {
        var place = autocomplete.getPlace();

        document.getElementById("latitude").value = place.geometry.location.lat();
        document.getElementById("longitude").value = place.geometry.location.lng();

        for (var component in componentForm) {
            document.getElementById(component).value = '';
            document.getElementById(component).disabled = false;
        }
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                document.getElementById(addressType).value = val;
            }
        }
    }

    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var geolocation = new google.maps.LatLng(
                        position.coords.latitude, position.coords.longitude);
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;
                document.getElementById("latitude").value = latitude;
                document.getElementById("longitude").value = longitude;
                autocomplete.setBounds(new google.maps.LatLngBounds(geolocation, geolocation));
            });
        }
    }
    initialize();
</script>
