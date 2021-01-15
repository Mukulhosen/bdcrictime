<!-- Content Header (Page header) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/highcharts/7.2.0/css/stocktools/gui.css"></link>
<section class="content-header">
  <h2 class="mt-0"> Dashboard</h2>
  <ol class="breadcrumb">
    <li><a href="<?php echo Backend_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol>
</section>
<div class="col-md-12 no-padding"><?php echo $this->session->flashdata('message'); ?></div>

<!-- Main content -->
<!--<section class="content" style="min-height: 542px;">-->
<!--    <div class="featured-area">-->
<!--        <div class="row">-->
<!--            <div class="col-lg-4 col-md-6 col-xs-12">-->
<!--                <div class="featured-wrap featured-wrap-post">-->
<!--                    <div class="featured-content">-->
<!--                        <p>TOTAL  POST</p>-->
<!--                        <h4>115004</h4>-->
<!--                    </div>-->
<!--                    <div class="featured-icon">-->
<!--                        <img src="--><?php //echo base_url(); ?><!--assets/admin/icons/featured/icon1.svg" alt="">-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-lg-4 col-md-6 col-xs-12">-->
<!--                <div class="featured-wrap featured-wrap-views">-->
<!--                    <div class="featured-content">-->
<!--                        <p>TOTAL  HIT</p>-->
<!--                        <h4>115004</h4>-->
<!--                    </div>-->
<!--                    <div class="featured-icon">-->
<!--                        <img src="--><?php //echo base_url(); ?><!--assets/admin/icons/featured/icon2.svg" alt="">-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-lg-4 col-md-6 col-xs-12">-->
<!--                <div class="featured-wrap featured-wrap-earning">-->
<!--                    <div class="featured-content">-->
<!--                        <p>TOTAL  EARNING</p>-->
<!--                        <h4>115004</h4>-->
<!--                    </div>-->
<!--                    <div class="featured-icon">-->
<!--                        <img src="--><?php //echo base_url(); ?><!--assets/admin/icons/featured/icon3.svg" alt="">-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="top-earning-area">-->
<!--        <div class="row">-->
<!--            <div class="col-md-6 col-xs-12">-->
<!--                <div class="top-earning-wrap top-hits-wrap">-->
<!--                    <div class="top-earning-header">-->
<!--                        <h4>Highest Hitted Post</h4>-->
<!--                        <p>Hits <a href="#" target="_blank"><i class="fa fa-eye"></i></a></p>-->
<!--                    </div>-->
<!--                    <div class="top-earning-content">-->
<!--                        <h4><a href="#">Buhari Tasks Soldiers On Respect Of Human Rights</a></h4>-->
<!--                        <h2>1505</h2>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-6 col-xs-12">-->
<!--                <div class="top-earning-wrap">-->
<!--                    <div class="top-earning-header">-->
<!--                        <h4>Highest Earning Post </h4>-->
<!--                        <p>Earning <a href="#" target="_blank"><i class="fa fa-eye"></i></a></p>-->
<!--                    </div>-->
<!--                    <div class="top-earning-content">-->
<!--                        <h4><a href="#">Buhari Tasks Soldiers On Respect Of Human Rights</a></h4>-->
<!--                        <h2>1505</h2>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="post-overview-area">-->
<!--        <div class="row">-->
<!--            <div class="col-xs-12">-->
<!--                <div class="post-overview-wrap">-->
<!--                    <div class="post-overview-header">-->
<!--                        <h2>post overview</h2>-->
<!--                        <ul class="post-overview-tabs">-->
<!--                            <li class="active"><a class="monthly" data-toggle="tab" href="#monthly"><span></span> Monthly</a></li>-->
<!--                            <li><a data-toggle="tab" class="weekly" href="#weekly"><span></span> Weekly</a></li>-->
<!--                            <li><a data-toggle="tab" class="today" href="#today"><span></span> Today</a></li>-->
<!--                            <li><a data-toggle="tab" class="average" href="#average"><span></span> Average</a></li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                    <ul class="post-overview-list">-->
<!--                        <li><span class="post"></span> Total Monthly Post: 450</li>-->
<!--                        <li><span class="hit"></span> Total Monthly Hits: 450</li>-->
<!--                        <li><span class="earning"></span> Total Monthly Earning: $500</li>-->
<!--                    </ul>-->
<!--                    <div class="tab-content">-->
<!--                        <div id="monthly" class="tab-pane fade in active">-->
<!--                            <div class="post-overview" id="monthly_overview"> </div>-->
<!--                        </div>-->
<!--                        <div id="weekly" class="tab-pane fade">-->
<!--                            <div class="post-overview" id="weekly_overview"> </div>-->
<!--                        </div>-->
<!--                        <div id="today" class="tab-pane fade">-->
<!--                            <div class="post-overview" id="today_overview"> </div>-->
<!---->
<!--                        </div>-->
<!--                        <div id="average" class="tab-pane fade">-->
<!--                            <div class="post-overview" id="average_overview"> </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="redeem-payment-area">-->
<!--        <div class="row">-->
<!--            <div class="col-md-6 col-xs-12">-->
<!--                <div class="redeem-payment-card">-->
<!--                    <h4>Redeem your Payment</h4>-->
<!--                    <form action="#">-->
<!--                        <input type="text">-->
<!--                        <button>Redeem</button>-->
<!--                    </form>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-xs-12">-->
<!--                <div class="redeem-payment-table">-->
<!--                    <h3>Redeem History</h3>-->
<!--                    <div class="responsive-table">-->
<!--                        <table>-->
<!--                            <thead>-->
<!--                                <tr>-->
<!--                                    <th>Date</th>-->
<!--                                    <th>Amount</th>-->
<!--                                    <th>Status</th>-->
<!--                                    <th>Action</th>-->
<!--                                </tr>-->
<!--                            </thead>-->
<!--                            <tbody>-->
<!--                                <tr>-->
<!--                                    <td>25 Jan 2019</td>-->
<!--                                    <td>$50000</td>-->
<!--                                    <td>Pending</td>-->
<!--                                    <td>-->
<!--                                        <ul class="redeem-payment-action">-->
<!--                                            <li><i class="fa fa-pencil"></i></li>-->
<!--                                            <li><i class="fa fa-times-circle"></i></li>-->
<!--                                            <li><i class="fa fa-trash"></i></li>-->
<!--                                        </ul>-->
<!--                                    </td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <td>25 Jan 2019</td>-->
<!--                                    <td>$50000</td>-->
<!--                                    <td>Pending</td>-->
<!--                                    <td>-->
<!--                                        <ul class="redeem-payment-action">-->
<!--                                            <li><i class="fa fa-pencil"></i></li>-->
<!--                                            <li><i class="fa fa-times-circle"></i></li>-->
<!--                                            <li><i class="fa fa-trash"></i></li>-->
<!--                                        </ul>-->
<!--                                    </td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <td>25 Jan 2019</td>-->
<!--                                    <td>$50000</td>-->
<!--                                    <td>Pending</td>-->
<!--                                    <td>-->
<!--                                        <ul class="redeem-payment-action">-->
<!--                                            <li><i class="fa fa-pencil"></i></li>-->
<!--                                            <li><i class="fa fa-times-circle"></i></li>-->
<!--                                            <li><i class="fa fa-trash"></i></li>-->
<!--                                        </ul>-->
<!--                                    </td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <td>25 Jan 2019</td>-->
<!--                                    <td>$50000</td>-->
<!--                                    <td>Pending</td>-->
<!--                                    <td>-->
<!--                                        <ul class="redeem-payment-action">-->
<!--                                            <li><i class="fa fa-pencil"></i></li>-->
<!--                                            <li><i class="fa fa-times-circle"></i></li>-->
<!--                                            <li><i class="fa fa-trash"></i></li>-->
<!--                                        </ul>-->
<!--                                    </td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <td>25 Jan 2019</td>-->
<!--                                    <td>$50000</td>-->
<!--                                    <td>Pending</td>-->
<!--                                    <td>-->
<!--                                        <ul class="redeem-payment-action">-->
<!--                                            <li><i class="fa fa-pencil"></i></li>-->
<!--                                            <li><i class="fa fa-times-circle"></i></li>-->
<!--                                            <li><i class="fa fa-trash"></i></li>-->
<!--                                        </ul>-->
<!--                                    </td>-->
<!--                                </tr>-->
<!--                            </tbody>-->
<!--                        </table>-->
<!--                    </div>-->
<!--                 -->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->
<!--<script src="https://code.highcharts.com/highcharts.js"></script>-->
<!-- <script>-->
<!--    Highcharts.chart('monthly_overview', {-->
<!--        chart: {-->
<!--            type: 'spline',-->
<!--            height: '350px',-->
<!--        },-->
<!--        title: false,-->
<!--        xAxis: {-->
<!--            categories: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,14,18,19,20,21,22,23,24,25,26,27,28,29,30],-->
<!--            crosshair: true,-->
<!--            labels: {-->
<!--                style: {-->
<!--                    color: '#676B79',-->
<!--                    fontSize: '14px',-->
<!--                    fontWeight: "400",-->
<!--                }-->
<!--            },-->
<!--            lineColor: '#EAEAF5',-->
<!--            lineWidth: 1,-->
<!--        },-->
<!--        legend: {-->
<!--            enabled: false,-->
<!--            itemStyle: {-->
<!--                color: "#676B79",-->
<!--                fontSize: "14px",-->
<!--                fontWeight: "400",-->
<!--            },-->
<!---->
<!--            itemHoverStyle: {-->
<!--                color: "#0171F5",-->
<!--            },-->
<!--        },-->
<!--        yAxis: {-->
<!--            min: 0,-->
<!--            title: false,-->
<!--            gridLineColor: '#DCE0EE',-->
<!--            gridLineWidth: 1,-->
<!--            labels: {-->
<!--                style: {-->
<!--                    color: '#676B79',-->
<!--                    fontSize: '14px',-->
<!--                    fontWeight: "400",-->
<!--                }-->
<!--            }-->
<!--        },-->
<!---->
<!--        tooltip: {-->
<!--            className: 'heighChartTooltip',-->
<!--            headerFormat: '<h4 className="tooltipTitle">{point.key}</h4><ul className="chatTooltip">',-->
<!--            pointFormat: '<li><span style="color:{series.color};padding:0">{series.name}: </span>' +-->
<!--                '<span style="padding:0"><b>{point.y:.1f} $</b></span></li>',-->
<!--            footerFormat: '</ul>',-->
<!--            shared: true,-->
<!--            useHTML: true-->
<!--        },-->
<!--        plotOptions: {-->
<!--            spline: {-->
<!--                lineWidth: 2,-->
<!--                states: {-->
<!--                    hover: {-->
<!--                        lineWidth: 2-->
<!--                    }-->
<!--                },-->
<!--            }-->
<!--        },-->
<!--        colors: ['#01B59A', '#3289E8','#FB3186'],-->
<!--        series: [{-->
<!--            name: 'Posts',-->
<!--            data: [1452, 1450, 3540, 4501, 1450, 6540, 1450, 6541, 3654, 3654, 7552, 4123,1452, 1450, 3540, 4501, 1450, 6540, 1450, 6541, 3654, 3654, 7552, 4123, 1450, 6541, 3654, 3654, 7552, 4123],-->
<!--            shadow: {-->
<!--                color: 'rgba(103, 103, 103, 0.25)',-->
<!--                offsetX: 3,-->
<!--                offsetY: 5,-->
<!--                opacity: '.1',-->
<!--                width: 5-->
<!--            },-->
<!--        }, {-->
<!--            name: 'Hits',-->
<!--            data: [564, 7545, 3540, 2211, 1450, 5252, 8952, 3654, 5211, 4523, 14825, 7513,564, 7545, 3540, 2211, 1450, 5252, 8952, 3654, 5211, 4523, 14825, 7513, 8952, 3654, 5211, 4523, 14825, 7513],-->
<!--            shadow: {-->
<!--                color: 'rgba(103, 103, 103, 0.25)',-->
<!--                offsetX: 3,-->
<!--                offsetY: 5,-->
<!--                opacity: '.1',-->
<!--                width: 5-->
<!--            },-->
<!--        },{-->
<!--            name: 'Earning',-->
<!--            data: [1401, 1450, 3540, 8974, 1450, 6540, 14456, 6541, 3654, 475, 3245, 4123,1401, 1450, 3540, 8974, 1450, 6540, 14456, 6541, 3654, 475, 3245, 4123,14456, 6541, 3654, 475, 3245, 4123],-->
<!--            shadow: {-->
<!--                color: 'rgba(103, 103, 103, 0.25)',-->
<!--                offsetX: 3,-->
<!--                offsetY: 5,-->
<!--                opacity: '.1',-->
<!--                width: 5-->
<!--            },-->
<!--        },],-->
<!--    });-->
<!--    Highcharts.chart('weekly_overview', {-->
<!--        chart: {-->
<!--            type: 'spline',-->
<!--            height: '350px',-->
<!--        },-->
<!--        title: false,-->
<!--        xAxis: {-->
<!--            categories: [-->
<!--                'Sat',-->
<!--                'Sun',-->
<!--                'Mon',-->
<!--                'Tue',-->
<!--                'Wed',-->
<!--                'Thu',-->
<!--                'Fri',-->
<!--            ],-->
<!--            crosshair: true,-->
<!--            labels: {-->
<!--                style: {-->
<!--                    color: '#676B79',-->
<!--                    fontSize: '14px',-->
<!--                    fontWeight: "400",-->
<!--                }-->
<!--            },-->
<!--            lineColor: '#EAEAF5',-->
<!--            lineWidth: 1,-->
<!--        },-->
<!--        legend: {-->
<!--            enabled: false,-->
<!--            itemStyle: {-->
<!--                color: "#676B79",-->
<!--                fontSize: "14px",-->
<!--                fontWeight: "400",-->
<!--            },-->
<!---->
<!--            itemHoverStyle: {-->
<!--                color: "#0171F5",-->
<!--            },-->
<!--        },-->
<!--        yAxis: {-->
<!--            min: 0,-->
<!--            title: false,-->
<!--            gridLineColor: '#DCE0EE',-->
<!--            gridLineWidth: 1,-->
<!--            labels: {-->
<!--                style: {-->
<!--                    color: '#676B79',-->
<!--                    fontSize: '14px',-->
<!--                    fontWeight: "400",-->
<!--                }-->
<!--            }-->
<!--        },-->
<!---->
<!--        tooltip: {-->
<!--            className: 'heighChartTooltip',-->
<!--            headerFormat: '<h4 className="tooltipTitle">{point.key}</h4><ul className="chatTooltip">',-->
<!--            pointFormat: '<li><span style="color:{series.color};padding:0">{series.name}: </span>' +-->
<!--                '<span style="padding:0"><b>{point.y:.1f} $</b></span></li>',-->
<!--            footerFormat: '</ul>',-->
<!--            shared: true,-->
<!--            useHTML: true-->
<!--        },-->
<!--        plotOptions: {-->
<!--            spline: {-->
<!--                lineWidth: 2,-->
<!--                states: {-->
<!--                    hover: {-->
<!--                        lineWidth: 2-->
<!--                    }-->
<!--                },-->
<!--            }-->
<!--        },-->
<!--        colors: ['#01B59A', '#3289E8','#FB3186'],-->
<!--        series: [{-->
<!--            name: 'Posts',-->
<!--            data: [1452, 1450, 3540, 4501, 1450, 6540, 1450],-->
<!--            shadow: {-->
<!--                color: 'rgba(103, 103, 103, 0.25)',-->
<!--                offsetX: 3,-->
<!--                offsetY: 5,-->
<!--                opacity: '.1',-->
<!--                width: 5-->
<!--            },-->
<!--        }, {-->
<!--            name: 'Hits',-->
<!--            data: [564, 7545, 3540, 2211, 1450, 5252, 8952],-->
<!--            shadow: {-->
<!--                color: 'rgba(103, 103, 103, 0.25)',-->
<!--                offsetX: 3,-->
<!--                offsetY: 5,-->
<!--                opacity: '.1',-->
<!--                width: 5-->
<!--            },-->
<!--        },{-->
<!--            name: 'Earning',-->
<!--            data: [1401, 1450, 3540, 8974, 1450, 6540, 14456],-->
<!--            shadow: {-->
<!--                color: 'rgba(103, 103, 103, 0.25)',-->
<!--                offsetX: 3,-->
<!--                offsetY: 5,-->
<!--                opacity: '.1',-->
<!--                width: 5-->
<!--            },-->
<!--        },],-->
<!--    });-->
<!--    Highcharts.chart('today_overview', {-->
<!--        chart: {-->
<!--            type: 'spline',-->
<!--            height: '350px',-->
<!--        },-->
<!--        title: false,-->
<!--        xAxis: {-->
<!--            categories: [01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24],-->
<!--            crosshair: true,-->
<!--            labels: {-->
<!--                style: {-->
<!--                    color: '#676B79',-->
<!--                    fontSize: '14px',-->
<!--                    fontWeight: "400",-->
<!--                }-->
<!--            },-->
<!--            lineColor: '#EAEAF5',-->
<!--            lineWidth: 1,-->
<!--        },-->
<!--        legend: {-->
<!--            enabled: false,-->
<!--            itemStyle: {-->
<!--                color: "#676B79",-->
<!--                fontSize: "14px",-->
<!--                fontWeight: "400",-->
<!--            },-->
<!---->
<!--            itemHoverStyle: {-->
<!--                color: "#0171F5",-->
<!--            },-->
<!--        },-->
<!--        yAxis: {-->
<!--            min: 0,-->
<!--            title: false,-->
<!--            gridLineColor: '#DCE0EE',-->
<!--            gridLineWidth: 1,-->
<!--            labels: {-->
<!--                style: {-->
<!--                    color: '#676B79',-->
<!--                    fontSize: '14px',-->
<!--                    fontWeight: "400",-->
<!--                }-->
<!--            }-->
<!--        },-->
<!---->
<!--        tooltip: {-->
<!--            className: 'heighChartTooltip',-->
<!--            headerFormat: '<h4 className="tooltipTitle">{point.key}</h4><ul className="chatTooltip">',-->
<!--            pointFormat: '<li><span style="color:{series.color};padding:0">{series.name}: </span>' +-->
<!--                '<span style="padding:0"><b>{point.y:.1f} $</b></span></li>',-->
<!--            footerFormat: '</ul>',-->
<!--            shared: true,-->
<!--            useHTML: true-->
<!--        },-->
<!--        plotOptions: {-->
<!--            spline: {-->
<!--                lineWidth: 2,-->
<!--                states: {-->
<!--                    hover: {-->
<!--                        lineWidth: 2-->
<!--                    }-->
<!--                },-->
<!--            }-->
<!--        },-->
<!--        colors: ['#01B59A', '#3289E8','#FB3186'],-->
<!--        series: [{-->
<!--            name: 'Posts',-->
<!--            data: [1452, 1450, 3540, 4501, 1450, 6540, 1450, 6541, 3654, 3654, 7552, 4123,1452, 1450, 3540, 4501, 1450, 6540, 1450, 6541, 3654, 3654, 7552, 4123],-->
<!--            shadow: {-->
<!--                color: 'rgba(103, 103, 103, 0.25)',-->
<!--                offsetX: 3,-->
<!--                offsetY: 5,-->
<!--                opacity: '.1',-->
<!--                width: 5-->
<!--            },-->
<!--        }, {-->
<!--            name: 'Hits',-->
<!--            data: [564, 7545, 3540, 2211, 1450, 5252, 8952, 3654, 5211, 4523, 14825, 7513,564, 7545, 3540, 2211, 1450, 5252, 8952, 3654, 5211, 4523, 14825, 7513],-->
<!--            shadow: {-->
<!--                color: 'rgba(103, 103, 103, 0.25)',-->
<!--                offsetX: 3,-->
<!--                offsetY: 5,-->
<!--                opacity: '.1',-->
<!--                width: 5-->
<!--            },-->
<!--        },{-->
<!--            name: 'Earning',-->
<!--            data: [1401, 1450, 3540, 8974, 1450, 6540, 14456, 6541, 3654, 475, 3245, 4123,1401, 1450, 3540, 8974, 1450, 6540, 14456, 6541, 3654, 475, 3245, 4123],-->
<!--            shadow: {-->
<!--                color: 'rgba(103, 103, 103, 0.25)',-->
<!--                offsetX: 3,-->
<!--                offsetY: 5,-->
<!--                opacity: '.1',-->
<!--                width: 5-->
<!--            },-->
<!--        },],-->
<!--    });-->
<!--    Highcharts.chart('average_overview', {-->
<!--        chart: {-->
<!--            type: 'spline',-->
<!--            height: '350px',-->
<!--        },-->
<!--        title: false,-->
<!--        xAxis: {-->
<!--            categories: [01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24],-->
<!--            crosshair: true,-->
<!--            labels: {-->
<!--                style: {-->
<!--                    color: '#676B79',-->
<!--                    fontSize: '14px',-->
<!--                    fontWeight: "400",-->
<!--                }-->
<!--            },-->
<!--            lineColor: '#EAEAF5',-->
<!--            lineWidth: 1,-->
<!--        },-->
<!--        legend: {-->
<!--            enabled: false,-->
<!--            itemStyle: {-->
<!--                color: "#676B79",-->
<!--                fontSize: "14px",-->
<!--                fontWeight: "400",-->
<!--            },-->
<!---->
<!--            itemHoverStyle: {-->
<!--                color: "#0171F5",-->
<!--            },-->
<!--        },-->
<!--        yAxis: {-->
<!--            min: 0,-->
<!--            title: false,-->
<!--            gridLineColor: '#DCE0EE',-->
<!--            gridLineWidth: 1,-->
<!--            labels: {-->
<!--                style: {-->
<!--                    color: '#676B79',-->
<!--                    fontSize: '14px',-->
<!--                    fontWeight: "400",-->
<!--                }-->
<!--            }-->
<!--        },-->
<!---->
<!--        tooltip: {-->
<!--            className: 'heighChartTooltip',-->
<!--            pointFormat: '<li><span style="color:{series.color};padding:0">{series.name}: </span>' +-->
<!--                '<span style="padding:0"><b>{point.y:.1f} $</b></span></li>',-->
<!--            footerFormat: '</ul>',-->
<!--            shared: true,-->
<!--            useHTML: true-->
<!--        },-->
<!--        plotOptions: {-->
<!--            spline: {-->
<!--                lineWidth: 2,-->
<!--                states: {-->
<!--                    hover: {-->
<!--                        lineWidth: 2-->
<!--                    }-->
<!--                },-->
<!--            }-->
<!--        },-->
<!--        colors: ['#00bf2a'],-->
<!--        series: [{-->
<!--            name: 'Average',-->
<!--            data: [1452, 1450, 3540, 4501, 1450, 6540, 1450, 6541, 3654, 3654, 7552, 4123,1452, 1450, 3540, 4501, 1450, 6540, 1450, 6541, 3654, 3654, 7552, 4123],-->
<!--            shadow: {-->
<!--                color: 'rgba(103, 103, 103, 0.25)',-->
<!--                offsetX: 3,-->
<!--                offsetY: 5,-->
<!--                opacity: '.1',-->
<!--                width: 5-->
<!--            },-->
<!--        }],-->
<!--    });-->
<!--</script> -->
