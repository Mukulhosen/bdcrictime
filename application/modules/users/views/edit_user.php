<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    load_module_asset('users', 'css');
    load_module_asset('users', 'js');
    require_once ( __DIR__ . '/tabs.php');
?>


    <form action="" method="post" id="user_form" class="form-horizontal" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-9">
                <div class="box no-border">
                    <div class="box-body">
                        <div id="success_report"></div>
                        
                        <input type="hidden" value="<?php echo $id; ?>" name="id" />
                        <input type="hidden" value="<?php echo $profile_photo; ?>" name="old_img" />

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role_id" class="col-sm-3 control-label">Role<sup>*</sup></label>
                                    <div class="col-sm-9">
                                        <select name="role_id" class="form-control" id="role_id">
                                            <?php echo Users_helper::getDropDownRoleName($role_id); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="first_name" class="col-sm-3 control-label">First Name<sup>*</sup></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="first_name" value="<?php echo $first_name; ?>" id="first_name"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="last_name" class="col-sm-3 control-label">Last Name<sup>*</sup></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo $last_name; ?>"/>
                                    </div> 
                                </div>
                                <div class="form-group" >
                                    <label for="your_email" class="col-sm-3 control-label">Email<sup>*</sup></label>
                                    <div class="col-sm-9">
                                        <input autocomplete="off" type="text" class="form-control" readonly="readonly" value="<?php echo $email; ?>" name="your_email" id="your_email" placeholder="Valid & Unique  Email Address" />
                                    </div>   
                                </div>
                                <div class="form-group" >
                                    <label class="col-sm-3 control-label" for="dob">Date of Birth</label>
                                    <div class="col-sm-9">
                                        <div class="">
                                            <div class="col-md-4 no-padding">
                                                <select id="dob" name="dd" class="form-control">
                                                    <option>DD</option>
                                                    <?php echo numericDropDown(1, 31, 1, 0); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 no-padding">
                                                <select  name="mm" class="form-control">
                                                    <option>MM</option>
                                                    <?php echo numericDropDown(1, 12, 1, 0); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 no-padding">
                                                <select  name="yy" class="form-control">
                                                    <option>YYYY</option>
                                                    <?php echo numericDropDown(date('Y', strtotime('-50 years')), date('Y'), 1, 0); ?>
                                                </select>
                                            </div>                                                                                                                        
                                        </div>                                                             
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dob_town" class="col-sm-3 control-label">Town of Birth</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="dob_town" id="dob_town" value="<?php echo $dob_town; ?>"/>
                                    </div> 
                                </div>
                                <div class="form-group">
                                    <label for="current_city" class="col-sm-3 control-label">Current City</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="current_city" id="current_city" value="<?php echo $current_city; ?>"/>
                                    </div> 
                                </div>
                                <div class="form-group">
                                    <label for="school_name" class="col-sm-3 control-label">School Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="school_name" id="school_name" value="<?php echo $school_name; ?>"/>
                                    </div> 
                                </div>
                                <div class="form-group">
                                    <label for="qualification" class="col-sm-3 control-label">Qualification</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="qualification" id="qualification" value="<?php echo $qualification; ?>"/>
                                    </div> 
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact" class="col-sm-3 control-label">Contact</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="contact" id="contact" value="<?php echo $contact; ?>" onKeyPress="return DegitOnly(event);"/>
                                    </div> 
                                </div>
                                <div class="form-group">
                                    <label for="location" class="col-sm-3 control-label">Map Location</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="location" id="autocomplete" value="<?php echo $location; ?>" placeholder="Google Map Location"/>
                                        <input type="hidden" name="lat" id="latitude" value="<?php echo $lat; ?>">
                                        <input type="hidden" name="lng" id="longitude" value="<?php echo $lng; ?>">
                                    </div>   
                                </div>
                                <div class="form-group">
                                    <label for="add_line1" class="col-sm-3 control-label">Address Line1</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="add_line1" id="add_line1" value="<?php echo $add_line1; ?>"/>
                                    </div>   
                                </div>
                                <div class="form-group">
                                    <label for="add_line2" class="col-sm-3 control-label">Address Line2</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="add_line2" id="add_line2" value="<?php echo $add_line2; ?>"/>
                                    </div>   
                                </div>
                                <div class="form-group">
                                    <label for="city" class="col-sm-3 control-label">City</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="city" id="city" value="<?php echo $city; ?>"/>
                                    </div>
                                </div>
<!--                                <div class="form-group">-->
<!--                                    <label for="state_id" class="col-sm-3 control-label">State</label>-->
<!--                                    <div class="col-sm-9">-->
<!--                                        <select class="form-control" id="state_id" name="state_id">-->
<!--                                            <option value="0">--Select State--</option>-->
<!--                                            --><?php //echo getLocationList($state_id, 2, 1); ?>
<!--                                        </select>-->
<!--                                    </div>-->
<!--                                </div>-->
                                <div class="form-group">
                                    <label for="postcode" class="col-sm-3 control-label">Postcode</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="postcode" id="postcode" value="<?php echo $postcode; ?>"/>
                                    </div>
                                </div>
<!--                                <div class="form-group">-->
<!--                                    <label for="country_id" class="col-sm-3 control-label">Country</label>-->
<!--                                    <div class="col-sm-9">-->
<!--                                        <select name="country_id" class="form-control" id="country_id">-->
<!--                                            --><?php //echo getDropDownCountries($country_id); ?>
<!--                                        </select>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="form-group">-->
<!--                                    <label for="status" class="col-sm-3 control-label">Status --><?php //echo form_error('status') ?><!--</label>-->
<!--                                    <div class="col-sm-5">-->
<!--                                        <select name="status" class="form-control" id="status">-->
<!--                                            --><?php //echo userStatus($status) ?>
<!--                                        </select>-->
<!--                                    </div>  -->
<!--                                </div>-->
                            </div>
                            
                            <div class="col-md-12" style="padding: 0px 30px;">
                                <div class="form-group">     
                                    <label for="biography">Biography</label>
                                    <textarea class="form-control" rows="3" name="biography" id="biography"><?php echo $biography; ?></textarea>
                                    <?php echo form_error('biography') ?>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="box no-border">
                    <div class="box-header">
                        <h3 class="box-title">Profile photo</h3>
                    </div>
                    <div class="box-body box-profile">
                        <div class="thumbnail upload_image">
                            <img src="<?php echo getPhoto3($profile_photo, 'upload/users_profile'); ?>" alt="thumbnil" class="img-responsive" />
                        </div>
                        <div class="btn btn-default btn-file">
                            <i class="fa fa-picture-o"></i> Change Profile Photo 
                            <input type="file" name="profile_photo" class="file_select" onchange="instantShowUploadImage(this, '.upload_image')">
                        </div>
                        <p><em><br/>Please click save button after change</em></p>
                    </div>
                </div>
                
                <div class="clearfix"></div>
                
                <div class="text-right">
                    <a href="<?php echo site_url(Backend_URL . 'users') ?>" class="btn btn-default"><i class="fa fa-long-arrow-left" ></i> Cancel</a>
                    <button type="button" class="btn btn-success" onclick="updateUser();"><i class="fa fa-save"></i> Update</button> 
                </div>
            </div>        
        </div>
        
    </form>
    

<script src="//cdn.ckeditor.com/4.7.1/full/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('biography', {
        toolbar: [
            { items: ['Copy', 'Cut', 'Paste', 'Undo', 'Redo', 'SelectAll', 
                    'Bold', 'Italic', 'Underline', 'RemoveFormat', 
                    'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Link', 'Unlink',
                    'FontSize', 'Format', 'TextColor', 'BGColor', 'Print', 'Source', 'Preview', 'Maximize'] }            
        ],
        height: ['250px'],
    });
    
    function updateUser(){    
        CKupdate();
        var formData = new FormData(document.getElementById("user_form"));
        var error = 0;

        var first_name = $('[name=first_name]').val();
        if (!first_name) {
            $('[name=first_name]').addClass('required');
            error = 1;
        } else {
            $('[name=first_name]').removeClass('required');
        }

        var last_name = $('[name=last_name]').val();
        if (!last_name) {
            $('[name=last_name]').addClass('required');
            error = 1;
        } else {
            $('[name=last_name]').removeClass('required');
        }

        if (!error) {
            $.ajax({
                url: "<?php echo Backend_URL; ?>users/update_action",
                type: "POST",
                dataType: "json",
                data: formData,
                enctype: 'multipart/form-data',
                cache: false,
                beforeSend: function () {
                    $('#success_report').html('<p class="ajax_processing"> Loading...</p>').css('display', 'block');
                },
                success: function (jsonRespond) {
                    if (jsonRespond.Status === 'OK') {
                        $('#success_report').html(jsonRespond.Msg);
                        document.getElementById("user_form").reset();
                        setTimeout(function () {
                            $('#success_report').slideUp('slow');
                        }, 4000);
                        location.reload();
                    } else {
                        $('#success_report').html(jsonRespond.Msg);
                    }
                },
                processData: false, // tell jQuery not to process the data
                contentType: false   // tell jQuery not to set contentType
            });
        }
    }
    
    function CKupdate() {
        for (instance in CKEDITOR.instances){
            CKEDITOR.instances[instance].updateElement();
        }
    }
    
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
                var geolocation = new google.maps.LatLng( position.coords.latitude, position.coords.longitude );
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
