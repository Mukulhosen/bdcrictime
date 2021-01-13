<?php load_module_asset('my_account', 'css'); ?>

<div class="my_account">
    <div class="container">

        <div class="col-md-12">
            <h2><small>Welcome</small> <?php echo getLoginUserData('name'); ?> </h2>
        </div>
        <div class="row">
            <?php
            $user_id = getLoginUserData('user_id');
            $user   = Modules::run('my_account/profile_info_view', $user_id);
            
            $dob = explode('-', $user->dob);            
            ?> 

            <div class="col-md-9 pull-right force_mobile_width">

                <div class="col-md-12 no-padding">

                    <div class="panel panel-default no-padding">
                        <div class="panel-heading">
                            <h4 class="title">Update My Information </h4>
                        </div>

                        <div class="panel-body">
                            <form method="post" id="update_profile_info" enctype="multipart/form-data">
                                <div class="col-md-12"> <div id="ajax_respond"></div> </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user->first_name; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user->last_name; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $user->email; ?>" readonly="">
                                    </div>
                                    <div class="form-group">
                                        <label for="photo">Profile Photo</label>
                                        <input type="file" name="profile_photo" accept="image/*" class="form-control" onchange="instantShowUploadImage(this, '.upload_image')"/>
                                    </div>

                                    <div class="form-group upload_image" id="remove_photo">
                                        <?php echo getUserPhoto($user->profile_photo, 120); ?>
                                        <div class="clearfix"></div>
                                        <?php if ($user->profile_photo) { ?>
                                            <div class="btn btn-danger btn-xs" onclick="removeProfilePhoto(<?php echo $user->id; ?>)">Remove</div>
                                        <?php } ?>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location">Resident city or town</label>
                                        <input type="text" name="location" id="autocomplete" value="<?php echo $user->location; ?>" placeholder="City / Town / Postcode" class="form-control input-md"> 
                                        <input type="hidden" name="lat" id="latitude" value="<?php echo $user->lat; ?>">
                                        <input type="hidden" name="lng" id="longitude" value="<?php echo $user->lng; ?>">
                                    </div> 
                                    
                                    <div class="form-group mypropadding">
                                        <label class="control-label" for="dob">Date of Birth</label>
                                        <div class="input-group"> <span class="input-group-addon">DD</span>
                                            <select class="form-control input-md" name="dob_dd">
                                                <?php echo numericDropDown(1, 31, 1, $dob[2]); ?>
                                            </select>
                                            <span class="input-group-addon">MM</span>
                                            <select class="form-control input-md" name="dob_mm">
                                                <?php echo numericDropDown(1, 12, 1, $dob[1]); ?>
                                            </select>
                                            <span class="input-group-addon"></span>                                            
                                            <select name="dob_yy" class="form-control">
                                                <option>YYYY</option>
                                                <?php echo numericDropDown( date( 'Y', strtotime('-50 years')), date('Y'),1,0); ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group">
                                        <label for="gender">Gender : </label>
                                        <?php echo htmlRadio('gender', $user->gender, ['Male' => 'Male', 'Female' => 'Female']); ?>
                                    </div>                                    

                                    <div class="form-group">
                                        <label for="role_id">Account Type : <?php echo getUserRoleNameById($user->role_id); ?></label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 pull-right">
                                        <button type="button" onclick="update_profile();"  class="pull-right btn btn-primary">Update My Profile</button>
                                    </div>                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 pull-left force_mobile_width"> 
                <?php echo Modules::run('my_account/menu'); ?> 
            </div>
            
        </div>
    </div>
</div>
</div>
<?php load_module_asset('my_account', 'js'); ?>
<script>

    function instantShowUploadImage(input, target) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(target + ' img').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
        $(target).show();
    }

    function update_profile() {
        var formData = new FormData(document.getElementById("update_profile_info"));
        jQuery.ajax({
            url: 'my_account/update_user_profile',
            type: "post",
            dataType: 'json',
            data: formData,
            enctype: 'multipart/form-data',
            beforeSend: function () {
                jQuery('#ajax_respond')
                        .html('<p class="ajax_processing">Updating...</p>')
                        .css('display', 'block');
            },
            success: function (jsonRespond) {
                jQuery('#ajax_respond').html(jsonRespond.Msg);
                if (jsonRespond.Status === 'OK') {
                    setTimeout(function () {
                        jQuery('#ajax_respond').slideUp('slow');
                    }, 2000);
                }
            },
            processData: false,
            contentType: false,
        });
        return false;
    }

    // Remove user photo
    function removeProfilePhoto(id) {
        var con = confirm('Are you sure to remove your photo?');
        if (con == true) {
            jQuery.ajax({
                url: 'my_account/remove_user_photo',
                type: "POST",
                dataType: 'json',
                data: {id: id},
                beforeSend: function () {
                    jQuery('#ajax_respond')
                            .html('<p class="ajax_processing">Processing...</p>')
                            .css('display', 'block');
                },
                success: function (jsonRespond) {
                    if (jsonRespond.Status === 'OK') {
                        jQuery('#ajax_respond').html(jsonRespond.Msg);
                        setTimeout(function () {
                            jQuery('#ajax_respond').slideUp( );
                            jQuery('#remove_photo').slideUp( );
                        }, 2000);
                    } else {
                        jQuery('#ajax_respond').html(jsonRespond.Msg);
                    }
                }
            });
            return false;
        } else {
            return false;
        }
    }


    // google location  track

    jQuery("#autocomplete").on('focus', function () {
        geolocate();
    });

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