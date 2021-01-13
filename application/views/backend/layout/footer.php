	</div>
</div> 
<!-- Body Content End -->

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        Loading Time <b>{elapsed_time}</b> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CI Version <b>' . CI_VERSION . '</b>' : '' ?>      
    </div>
    <b>Copyright  &copy; <script>document.write(new Date().getFullYear());</script> Perfectedge.</b> All rights reserved.
</footer>

 
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!--<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>-->
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<!-- Select2 -->
<script src="assets/lib/plugins/select2/dist/js/select2.full.min.js?<?php echo time(); ?>"></script>

<script src="assets/admin/attrvalidate.jquery.js?<?php echo time(); ?>" type="text/javascript"></script>

<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js?<?php echo time(); ?>"></script>
<script src="assets/lib/plugins/daterangepicker/daterangepicker.js?<?php echo time(); ?>"></script>
<!-- datepicker -->

<!-- DataTables -->
<script src="assets/js/popper.min.js?<?php echo time(); ?>"></script>
<script src="assets/js/datatables.min.js?<?php echo time(); ?>"></script>
<script src="assets/js/dataTables.responsive.min.js?<?php echo time(); ?>"></script>
<script src="assets/lib/plugins/datepicker/bootstrap-datepicker.js?<?php echo time(); ?>"></script>
<!-- AdminLTE App -->
<script src="assets/admin/dist/js/app.min.js?<?php echo time(); ?>"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="assets/admin/dist/js/pages/dashboard.js"></script> -->
<script src="assets/js/jquery.scrollbar.min.js?<?php echo time(); ?>"></script>

<!-- AdminLTE for demo purposes -->
<script src="assets/admin/dist/js/demo.js?<?php echo time(); ?>"></script>
<script src="assets/admin/custom_scripts.js?<?php echo time(); ?>" type="text/javascript"></script>
<script src="assets/admin/dist/js/custom.js?<?php echo time(); ?>"></script>

<script>
    $(document).ready(function() {
        $('.table-condensed').DataTable( {
            "searching": false,
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "bAutoWidth": false,
            "responsive": true,
            "aaSorting": []
        });
    } );
    var base_url = "<?php echo base_url() ?>";
    $("#select2Select").select2({
        multiple: true,
        tokenSeparators: [','],
        minimumInputLength: 1,
        minimumResultsForSearch: 10,
        ajax: {
            url: base_url+'ajax/get_tag',
            dataType: "json",
            type: "GET",
            data: function (params) {

                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id,
                            value : item.id
                        }
                    })
                };
            }
        }
    });


    jQuery(document).ready(function() {
        jQuery('.js_datepicker').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true           
        });       
        jQuery('#user_id').select2();
    });
    
    
     setInterval(function(){
      auto_logout();
    }, 1200000);
    //
    function auto_logout(){
        var user_id = $.isNumeric('<?php echo getLoginUserData('user_id'); ?>');
        // alert(user_id);
        if(user_id){
            jQuery.ajax({
                type: "POST",
                url: "auth/current_status_check",
                dataType: 'json',
                data: { user_id: user_id }
            });
        } 
       // 
    }
    
    
</script>
    <script>
        $(window).on("load", function() {
            var user_data = <?php echo json_encode(empty(get_cookie('login_data', TRUE)) ? [] : base64_decode(get_cookie('login_data', TRUE))) ?>;
            if (!Array.isArray(user_data)) {
                user_data = JSON.parse(user_data);
            }
            if (user_data.user_id) {
	            $.ajax({
	                type: "POST",
	                url: "ajax/force_logout",
	                dataType: 'json',
	                data: {
	                    'user_id': user_data.user_id
	                },
	                success: function(data) {
	                    if (!Array.isArray(data)) {
	                        var base = '<?php echo base_url() ?>';
	                        var storedData = JSON.parse(localStorage.getItem("logout"));

	                        if (localStorage.getItem("logout") == null || storedData.id != data.id) {
	                            localStorage.setItem("logout", JSON.stringify(data));
	                            window.location.href = `${base}auth/logout`;
	                        }
	                    }
	                }
	            });
	        }
        });
    </script>
    <script>
        var base_url = "<?php echo base_url() ?>";
        $("#select2Select1").select2({
            tags: false,
            multiple: true,
            minimumInputLength: 1,
            minimumResultsForSearch: 10,
            ajax: {
                url: base_url+'ajax/get_celebrity',
                dataType: "json",
                type: "GET",
                data: function (params) {

                    var queryParameters = {
                        term: params.term
                    }
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id,
                                value : item.id
                            }
                        })
                    };
                }
            }
        });

        $("#select2Select2").select2({
            tags: true,
            multiple: true,
            tokenSeparators: [','],
            minimumInputLength: 1,
            minimumResultsForSearch: 10,
            ajax: {
                url: base_url+'ajax/get_movie_tag',
                dataType: "json",
                type: "GET",
                data: function (params) {

                    var queryParameters = {
                        term: params.term
                    }
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id,
                                value : item.id
                            }
                        })
                    };
                }
            }
        });

        $("#select2Select3").select2({
            tags: false,
            multiple: true,
            tokenSeparators: [','],
            minimumInputLength: 1,
            minimumResultsForSearch: 10,
            ajax: {
                url: base_url+'ajax/get_celebrity',
                dataType: "json",
                type: "GET",
                data: function (params) {

                    var queryParameters = {
                        term: params.term
                    }
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id,
                                value : item.id
                            }
                        })
                    };
                }
            }
        });

        $("#select2Select4").select2({
            tags: false,
            multiple: true,
            tokenSeparators: [','],
            minimumInputLength: 1,
            minimumResultsForSearch: 10,
            ajax: {
                url: base_url+'ajax/get_celebrity',
                dataType: "json",
                type: "GET",
                data: function (params) {

                    var queryParameters = {
                        term: params.term
                    }
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id,
                                value : item.id
                            }
                        })
                    };
                }
            }
        });
    </script>

<!--    Code for tech-->
    <script>
        $("#review_1").select2({
            multiple: false,
            tokenSeparators: [','],
            minimumInputLength: 1,
            minimumResultsForSearch: 1,
            ajax: {
                url: base_url+'ajax/get_compare_post',
                dataType: "json",
                type: "GET",
                data: function (params) {

                    var queryParameters = {
                        term: params.term,
                        category_id : $('#category_id').val(),
                        sub_category_id : $('#sub_category_id').val(),
                        device_id : $('#device_id').val()
                    }
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.title,
                                id: item.id,
                                value : item.id
                            }
                        })
                    };
                }
            }
        });

        $("#review_2").select2({
            multiple: false,
            tokenSeparators: [','],
            minimumInputLength: 1,
            minimumResultsForSearch: 1,
            ajax: {
                url: base_url+'ajax/get_compare_post',
                dataType: "json",
                type: "GET",
                data: function (params) {

                    var queryParameters = {
                        term: params.term,
                        category_id : $('#category_id').val(),
                        sub_category_id : $('#sub_category_id').val(),
                        device_id : $('#device_id').val()
                    }
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.title,
                                id: item.id,
                                value : item.id
                            }
                        })
                    };
                }
            }
        });
    </script>
</body>
</html>