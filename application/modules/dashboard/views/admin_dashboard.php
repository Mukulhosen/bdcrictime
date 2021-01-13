<link rel="stylesheet" href="assets/css/jquery.scrollbar.css">

<!-- dashboard-area start -->
<div class="dashboard-area">
    <div class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="dashboard-card dashboard-top-card">
                    <div class="row">
                        <div class="col-lg-8">
                            <h2 class="dashboard-card-title mr-0">
                                <span class="titleContent">Posts Overview</span>
                                <ul class="post-overview-review">
                                    <li><span><img src="assets/images/total-post.svg" alt=""> </span>Total Post: <span id="totalPost">0</span></li>
                                    <li><span><img src="assets/images/view.svg" alt=""></span> Total Views: <span id="totalView">0</span></li>
                                </ul>
                            </h2>

                            <div class="post_overview-responsive">
                                <div id="post_overview"></div>
                            </div>
                            <div class="post-overview-bottom">
                                <ul class="post-overview-tabs">
                                    <li class="active" id="this-month"><a href="javascript:void(0)"><span></span> This Month</a></li>
                                    <li class="selectYear">
                                        <a href="javascript:void(0)" id="select-yesubar"><span></span> Select Year</a>
                                        <select name="" id="chartYear">
                                            <?php echo getYearRange(2019)?>
                                        </select>
                                    </li>
                                </ul>
                                <span class="postOverviewDate" id="postOverviewDate"></span>
                            </div>
                           
                        </div>
                        <div class="col-lg-4">
                            <h2 class="dashboard-card-title ml-0">
                                <span class="titleContent">Highest Viewed Post</span>
                                <strong><?php echo isset($highestViewedPost[0]) ? $highestViewedPost[0]->hit_count : '' ?></strong>
                            </h2>
							<?php if (!empty($highestViewedPost[0])) {?>
                            <div class="highest-view-post-items">
                                <a target="_blank" href="<?= getSegmentByTemplate($highestViewedPost[0]->sub_cat_tem_desgin) ?>/<?php echo isset($highestViewedPost[0]) ? $highestViewedPost[0]->post_url : '' ?>"><?php echo isset($highestViewedPost[0]) ? $highestViewedPost[0]->title : '' ?>
                                    <span><img src="assets/images/view.svg" alt=""></span>
                                </a>
                            </div>
							<?php } ?>
                            <h2 class="dashboard-card-title ml-0">
                                <span class="titleContent">Recent Drafts</span>
                            </h2>
                            <ul class="recent-draft-post-items">
                                <?php foreach (draftPosts(getLoginUserData('user_id')) as $post) { ?>
                                    <li class="recent-draft-post-item">
                                        <a class="title" href="<?= getSegmentByTemplate($post->sub_cat_tem_desgin) ?>/<?=$post->post_url?>"><?php echo getShortContent($post->title, 60); ?></a>
                                        <a class="edit" href="admin/posts/update_post/<?=$post->id?>">Edit</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="dashboard-card">
                    <h2 class="dashboard-card-title">
                        <span class="titleContent">Pending Approval</span>
                    </h2>
                    <div class="scrollbar-inner">
                        <ul class="pending-post-items">
                            <?php foreach (pendingAndSchedulePosts() as $post) { ?>
                                <li class="pending-post-item">
                                    <span><?php echo timePassed($post->modified); ?> - <?php echo $post->name; ?></span>
                                    <a href="<?= getSegmentByTemplate($post->sub_cat_tem_desgin) ?>/<?=$post->post_url?>"><?php echo $post->title?></a>
                                    <ul class="pending-post-action">
                                        <li><a class="edit" href="admin/posts/update_post/<?=$post->id?>">Edit</a></li>
                                        <?php if($post->status != 'Schedule_Publish') { ?>
                                            <li><a href="admin/posts/approve_post/<?=$post->id?>">Approve</a></li>
                                        <?php } ?>
                                        <li><a target="_blank" href="news/<?=$post->post_url?>">Preview</a></li>
                                        <?php if($post->status == 'Schedule') { ?>
                                            <li><span class="badge label-info">Scheduled</span></li>
                                        <?php } elseif($post->status == 'Schedule_Publish') { ?>
                                            <li><span class="badge label-success">Scheduled Publish</span></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="dashboard-card">
                    <h2 class="dashboard-card-title">
                        <span class="titleContent">Top 10 Most Viewed Posts</span>
                        <a class="viewBtn" >Views</a>
                    </h2>
                    <div class="scrollbar-inner">
                        <ul class="topview-post-items">
                            <?php foreach (highestViewedPosts() as $post) { ?>
                                <li class="topview-post-item">
                                    <a target="_blank" href="<?= getSegmentByTemplate($post->sub_cat_tem_desgin) ?>/<?php echo $post->post_url?>"><?php echo getShortContent($post->title, 60)?></a>
                                    <span><?php echo $post->hit_count; ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="dashboard-card">
                    <h2 class="dashboard-card-title">
                        <span class="titleContent">Recently Published</span>
                    </h2>
                    <div class="scrollbar-inner">
                        <ul class="pending-post-items">
                            <?php foreach (recentlyPublished() as $post) {?>
                                <li class="pending-post-item">
                                <span><?php echo timePassed($post->modified); ?> - <?php echo $post->name; ?></span>
                                    <a target="_blank" href="<?= getSegmentByTemplate($post->sub_cat_tem_desgin) ?>/<?=$post->post_url?>"><?php echo $post->title?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="dashboard-card">
                    <h2 class="dashboard-card-title">
                        <span class="titleContent">Recent  Post Comments</span>
                    </h2>
                    <div class="scrollbar-inner">
                        <ul class="pending-post-items">
                            <?php foreach (recentComments() as $post) { ?>
                                <li class="pending-post-item">
                                    <h3><a target="_blank" href="<?= getSegmentByTemplate($post->sub_cat_tem_desgin) ?>/<?=$post->post_url?>"><?php echo $post->title ?></a></h3>
                                    <span style="font-style:italic"><?php echo $post->description; ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="dashboard-card">
                    <h2 class="dashboard-card-title">
                        <ul class="dashboard-card-tab">
                            <li class="titleContent active" data-type="posts">Posts</li>
                            <li class="titleContent" data-type="views">Views</li>
                        </ul>
                        <select id="jurnalisFiltar" name="">
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="last_7_days">Last 7 days</option>
                            <option value="last_30_days">Last 30 Days</option>
                        </select>
                    </h2>
                    <div class="scrollbar-inner">
                        <ul class="journalist-posts-items" id="journalist-posts-count-items">
                            <?php
                                $max = maxJournalistPosts();
                                foreach (journalistPosts() as $post) {
                                    $percent = ($post->total_posts / $max->total_posts) * 100;
                                    $color = '#3289E8';
                                    if ($post->total_posts < 5) {
                                        $color = '#FF3754';
                                    }
                            ?>
                                <li class="journalist-posts-item">
                                    <div class="journalist-posts-content">
                                        <a href="author/<?=$post->profile_slug?>"><?php echo $post->name; ?></a>
                                        <span style="background: <?=$color?>;"><?php echo $post->total_posts ?></span>
                                    </div>
                                    <div class="journalist-posts-progress">
                                        <div style="width:<?=$percent?>%;background: <?=$color?>;" class="journalist-posts-progress-bar"></div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- dashboard-area end -->
<script src="assets/js/jquery.scrollbar.min.js?<?php echo time(); ?>"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>

<script>
    let contentLength = $('.journalist-posts-content span').text().length
    console.log(contentLength)
    $('.scrollbar-inner').scrollbar()
    $('.post-overview-tabs li a').on('click',function(){
        $('.post-overview-tabs li').removeClass('active')
        $(this).parent().addClass('active')
        $('.selectYear select').hide()
        $('.postOverviewDate').show()
        if($(this).parent('.selectYear').hasClass('active')){
            $('.selectYear select').show();
            $('.postOverviewDate').hide();
        }
    })
    $('.dashboard-card-tab li').on('click',function(){
        $('.dashboard-card-tab li').removeClass('active')
        $(this).addClass('active')
    })
    $(function () {
        'use strict';
        (function(factory) {
            if(typeof module === 'object' && module.exports) {
                module.exports = factory;
            } else {
                factory(Highcharts);
            }
        }(function(Highcharts) {
            (function(H) {
                H.wrap(H.seriesTypes.column.prototype, 'translate', function(proceed) {
                    const options = this.options;
                    const topMargin = options.topMargin || 0;
                    const bottomMargin = options.bottomMargin || 0;

                    proceed.call(this);

                    H.each(this.points, function(point) {
                        if(options.borderRadiusTopLeft || options.borderRadiusTopRight || options.borderRadiusBottomRight || options.borderRadiusBottomLeft) {
                            const w = point.shapeArgs.width;
                            const h = point.shapeArgs.height;
                            const x = point.shapeArgs.x;
                            const y = point.shapeArgs.y;

                            let radiusTopLeft = H.relativeLength(options.borderRadiusTopLeft || 0, w);
                            let radiusTopRight = H.relativeLength(options.borderRadiusTopRight || 0, w);
                            let radiusBottomRight = H.relativeLength(options.borderRadiusBottomRight || 0, w);
                            let radiusBottomLeft = H.relativeLength(options.borderRadiusBottomLeft || 0, w);

                            const maxR = Math.min(w, h) / 2

                            radiusTopLeft = radiusTopLeft > maxR ? maxR : radiusTopLeft;
                            radiusTopRight = radiusTopRight > maxR ? maxR : radiusTopRight;
                            radiusBottomRight = radiusBottomRight > maxR ? maxR : radiusBottomRight;
                            radiusBottomLeft = radiusBottomLeft > maxR ? maxR : radiusBottomLeft;

                            point.dlBox = point.shapeArgs;

                            point.shapeType = 'path';
                            point.shapeArgs = {
                                d: [
                                    'M', x + radiusTopLeft, y + topMargin,
                                    'L', x + w - radiusTopRight, y + topMargin,
                                    'C', x + w - radiusTopRight / 2, y, x + w, y + radiusTopRight / 2, x + w, y + radiusTopRight,
                                    'L', x + w, y + h - radiusBottomRight,
                                    'C', x + w, y + h - radiusBottomRight / 2, x + w - radiusBottomRight / 2, y + h, x + w - radiusBottomRight, y + h + bottomMargin,
                                    'L', x + radiusBottomLeft, y + h + bottomMargin,
                                    'C', x + radiusBottomLeft / 2, y + h, x, y + h - radiusBottomLeft / 2, x, y + h - radiusBottomLeft,
                                    'L', x, y + radiusTopLeft,
                                    'C', x, y + radiusTopLeft / 2, x + radiusTopLeft / 2, y, x + radiusTopLeft, y,
                                    'Z'
                                ]
                            };
                        }

                    });
                });
            }(Highcharts));
        }));

});
</script>

<script>
    $(document).ready(function () {
        getMonthlyChart();
        $("#select-year").click(function () {
            getYearlyChart()
        })
        $("#this-month").click(function () {
            getMonthlyChart();
        })
        $("#chartYear").change(function () {
            getYearlyChart()
        });
    })

</script>

<script>
    function getMonthlyChart()
    {
        $.ajax({
            type: "GET",
            url: "ajax/loadAdminChartMonthlyData",
            data: {user_id: 0},
            dataType: 'json',
            success: function (data) {
                $('#postOverviewDate').text(data.currentMonth);
                $('#totalPost').text(data.totalCount.total_post);
                $('#totalView').text(data.totalCount.total_view);
                loadChart(data);
            }
        });
    }

    function getYearlyChart()
    {
        let year = $("#chartYear").val();
        $.ajax({
            type: "GET",
            url: "ajax/loadAdminChartYearlyData",
            data: {user_id: 0, year: year},
            dataType: 'json',
            success: function (data) {
                $('#totalPost').text(data.totalCount.total_post);
                $('#totalView').text(data.totalCount.total_view);
                loadChart(data);
            }
        });
    }
</script>

<script>
    function loadChart(data) {
        Highcharts.chart('post_overview', {
            chart: {
                type: 'column',
                height: '350px',
            },
            title: false,
            xAxis: {
                categories: data.dates,
                crosshair: true,
                labels: {
                    style: {
                        color: '#676B79',
                        fontSize: '14px',
                        fontWeight: "400",
                    }
                },
                lineColor: '#E9EEF4',
                lineWidth: 1,
            },
            legend: {
                enabled: false,
                itemStyle: {
                    color: "#676B79",
                    fontSize: "14px",
                    fontWeight: "400",
                },

                itemHoverStyle: {
                    color: "#0171F5",
                },
            },

            yAxis: [{}, {
                min: 0,
                max: 1,
                tickPositions: [],
                allowDecimals: false,
                labels: {
                    style: {
                        color: '#676B79',
                        fontSize: '14px',
                        fontWeight: "400",
                    }
                },
                title: false,
                lineColor: '#E9EEF4',
                lineWidth: 1,
            }],
            tooltip: {
                className: 'heighChartTooltip',
                headerFormat: '<ul className="chatTooltip">',
                pointFormat: '<li><span style="color:{series.color};padding:0 ;font-size:15px;line-height:18px;font-weight:600">{series.name}: </span>' +
                '<span style="padding:0 ;font-size:15px;line-height:18px;font-weight:600"><b>{point.y:.0f}</b></span></li>',
                footerFormat: '</ul><h4 style="font-size:14px;margin:0px">Day: {point.key}</h4>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    grouping: false,
                    borderRadiusTopLeft: 10,
                    borderRadiusTopRight: 10
                }
            },
            colors: ['#3289E8'],
            series: [ {
                name:"Total Post",
                data: data.chartDataTotalPost
            },{
                name:"View",
                yAxis: 1,
                data: data.chartDataTotalView,
            }],
        },function(chart){

            var max = 10;

            $.each(chart.series[0].data,function(i,data){

                if(data.y < max)
                    data.update({
                        color:'red'
                    });

            });

        });
    }
</script>

<script>
    $("#jurnalisFiltar").change(function () {
        loadJournalistPosts();
        let contentLength = $('.journalist-posts-content span').text().length
        console.log(contentLength)
    });
    $('.dashboard-card-tab').click(function () {
        loadJournalistPosts();
    });

    function loadJournalistPosts() {
        let time = $("#jurnalisFiltar").val();
        let type = $('.dashboard-card-tab').find('.titleContent.active').data('type');
        $.ajax({
            type: "GET",
            url: "ajax/loadJournalistPostCountData",
            data: {time: time, type: type},
            dataType: 'json',
            success: function (data) {
                $("#journalist-posts-count-items").empty();
                $("#journalist-posts-count-items").html(data.html);
            }
        });
    }
</script>
