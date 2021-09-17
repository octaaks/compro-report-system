"use strict";

var options, label, range, graph, x, z, startDate, endDate;
var start_date, end_date;
var urlGraph = null;
var api_url = $('#url_graph').val();
var api_storage_url = $('#url_storage_usage').val();
var year;
var windowSize = window.matchMedia("(max-width: 768px)");
var windowSize2 = window.matchMedia("(max-width: 375px)");
var windowSize3 = window.matchMedia("(max-width: 280px)");

if (lang == 'zh-Hans') {
    moment.locale('zh-cn');
} else {
    moment.locale(lang);
}

loadGraph(1);

$(".category").change(function () {
    x = document.getElementById("category").value;
    x = $(this).find("option:selected").val();
    x = parseInt(x, 10);

    graph.destroy();
    showGraphLoading("graphLoading");

    if (x <= 3) {
        $('#periode').removeAttr("disabled");
        loadGraph(x);
    } else {
        $('#periode').attr('disabled', true);
        loadStorageUsage();
    }
});

$(function () {

    function hideCalendar() {
        $('#dp').hide();
        $('#week-picker-wrapper').hide();
        $('#mp').hide();
        $('#yp').hide();
        dropdown_size_change(0);
    }

    function dropdown_size_change(option) {
        if (option == 1) {
            $("#periodeDropdown").removeClass("col-md-3");
            $("#periodeDropdown").addClass("col-md-2");
        } else {
            $("#periodeDropdown").removeClass("col-md-1");
            $("#periodeDropdown").addClass("col-md-3");
        }
    }

    hideCalendar();

    $(document).click(function (e) {
        hideCalendar();
    });

    function periodeActive(id) {
        $('#periodeDropdown li').parent().find('a.dropdown-item.active').removeClass('active');
        $('#periodeDropdown li#' + id + ' a.dropdown-item').addClass('active');
    }

    var date_today = moment().format("MM/DD/YYYY");
    var date_yesterday = moment().subtract(1, 'days').format("MM/DD/YYYY");
    var date_last7day = moment().subtract(7, 'days').format("MM/DD/YYYY");
    var date_last30day = moment().subtract(30, 'days').format("MM/DD/YYYY");

    var extLang = $('#periodeDropdown li.list-select#realtime').text();

    $('#periode').val(extLang + ' : ' + $('#lang_Today').val() + ' - ' + moment().utcOffset('+0700').set({
        minute: 0,
        second: 0,
        millisecond: 0
    }).format("H:mm") + ' (GMT+7)');

    function periodeHover() {
        $('#periodeDropdown li').hover(function (e) {
            e.preventDefault();

            var p_li_id = $(this).attr('id');

            if (p_li_id == 'realtime') {
                hideCalendar();

                $('#periodeDropdown li#' + p_li_id + ' a').attr('title', date_today);
                $('#periodeDropdown li#' + p_li_id + ' a').tooltip({
                    html: true
                });

            } else if (p_li_id == 'yesterday') {
                hideCalendar();

                $('#periodeDropdown li#' + p_li_id + ' a').attr('title', date_yesterday);
                $('#periodeDropdown li#' + p_li_id + ' a').tooltip({
                    html: true
                });

            } else if (p_li_id == '7days') {
                hideCalendar();

                $('#periodeDropdown li#' + p_li_id + ' a').attr('title', date_last7day + '-' + date_yesterday);
                $('#periodeDropdown li#' + p_li_id + ' a').tooltip({
                    html: true
                });

            } else if (p_li_id == '30days') {
                hideCalendar();

                $('#periodeDropdown li#' + p_li_id + ' a').attr('title', date_last30day + '-' + date_yesterday);
                $('#periodeDropdown li#' + p_li_id + ' a').tooltip({
                    html: true
                });

            } else if (p_li_id == 'day') {
                dropdown_size_change(1);

                $('#dp').show();
                $('#week-picker-wrapper').hide();
                $('#mp').hide();
                $('#yp').hide();

            } else if (p_li_id == 'week') {
                dropdown_size_change(1);

                $('#dp').hide();
                $('#week-picker-wrapper').show();
                $('#mp').hide();
                $('#yp').hide();

            } else if (p_li_id == 'month') {
                dropdown_size_change(1);
                $('#dp').hide();
                $('#week-picker-wrapper').hide();
                $('#mp').show();
                $('#yp').hide();

            } else if (p_li_id == 'year') {
                dropdown_size_change(1);
                $('#dp').hide();
                $('#week-picker-wrapper').hide();
                $('#mp').hide();
                $('#yp').show();
            }
        });
    }

    if (!windowSize.matches && !windowSize2.matches && !windowSize3.matches) {
        periodeHover();
    }

    $(window).resize(function () {
        if (!windowSize.matches && !windowSize2.matches && !windowSize3.matches) {
            periodeHover();
        }
    });

    $('#periodeDropdown li.list-select').click(function (e) {
        e.preventDefault();

        var p_li_id = $(this).attr('id');
        var lbl = $(this).text();

        if (p_li_id == 'realtime') {
            hideCalendar();

            $('#periode').val(lbl + ' : ' + 'today - ' + moment().utcOffset('+0700').set({
                minute: 0,
                second: 0,
                millisecond: 0
            }).format("H:mm") + ' (GMT+7)');
            z = date_today + "%20-%20" + date_today;
        } else if (p_li_id == 'yesterday') {
            hideCalendar();

            $('#periode').val(lbl + ' : ' + date_yesterday + ' (GMT+7)');
            z = date_yesterday + "%20-%20" + date_yesterday;

        } else if (p_li_id == '7days') {
            hideCalendar();

            $('#periode').val(lbl + ' : ' + date_last7day + " - " + date_yesterday + ' (GMT+7)');
            z = date_last7day + "%20-%20" + date_yesterday;

        } else if (p_li_id == '30days') {
            hideCalendar();

            $('#periode').val(lbl + ' : ' + date_last30day + " - " + date_yesterday + ' (GMT+7)');
            z = date_last30day + "%20-%20" + date_yesterday;
        }

        loadGraph(x);
        periodeActive(p_li_id);
        hideCalendar();

        $('#dp').datepicker('hide');
        $('#week-picker-wrapper').datepicker('hide');
        $('#mp').datepicker('hide');
        $('#yp').datepicker('hide');
    });

    $('#periodeDropdown li.list-select-calendar').click(function (e) {
        e.preventDefault();

        var p_li_id = $(this).attr('id');
        var lbl = $(this).text();

        if (p_li_id == 'day') {
            e.stopPropagation();

            dropdown_size_change(1);

            $('#dp').show(null);
            $('#week-picker-wrapper').hide();
            $('#mp').hide();
            $('#yp').hide();

        } else if (p_li_id == 'week') {
            e.stopPropagation();

            dropdown_size_change(1);

            $('#dp').hide();
            $('#week-picker-wrapper').show(null);
            $('#mp').hide();
            $('#yp').hide();

        } else if (p_li_id == 'month') {
            e.stopPropagation();

            dropdown_size_change(1);

            $('#dp').hide();
            $('#week-picker-wrapper').hide();
            $('#mp').show(null);
            $('#yp').hide();

        } else if (p_li_id == 'year') {
            e.stopPropagation();

            dropdown_size_change(1);

            $('#dp').hide();
            $('#week-picker-wrapper').hide();
            $('#mp').hide();
            $('#yp').show(null);
        }
    });

    start_date = date_today;
    endDate = date_today;

    //day picker
    function set_day_picker(date) {
        start_date = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        end_date = new Date(date.getFullYear(), date.getMonth(), date.getDate());

        $('#dp').datepicker('update', start_date);

        if (moment(start_date).format("MM/DD/YYYY") == date_today) {
            $('#periode').val($('#lang_Realtime').val() + ' : ' + $('#lang_Today').val() + ' - ' + moment().format("H:mm") + ' (GMT+7)');
        } else {
            $('#periode').val($('#lang_Day').val() + ' : ' + moment(start_date).format('MM/DD/YYYY') + ' (GMT+7)');
        }

        z = moment(start_date).format('MM/DD/YYYY') + '%20-%20' + moment(end_date).format('MM/DD/YYYY');

        loadGraph(x);
        periodeActive('dp');
        hideCalendar();

        $('#dp').datepicker('hide');
        $("#periodeDropdown").dropdown("toggle");
    }

    $('#dp').datepicker({
        autoclose: true,
        forceParse: false,
        showWeek: true,
        todayHighlight: true,
        startDate: new Date(new Date().getFullYear() - 2, '0', '01'),
        endDate: new Date(new Date().getFullYear(), moment().month(), moment().date()),
    }).on("changeDate", function (e) {
        set_day_picker(e.date);
    });

    //week picker
    function set_week_picker(date) {
        start_date = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
        end_date = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);

        $('#week-picker-wrapper').datepicker('update', start_date);

        if (end_date > moment()) {
            $('#periode').val($('#lang_Week').val() + ' : ' + moment(start_date).format('MM/DD/YYYY') + ' - ' + (moment().subtract(1, 'day').format('MM/DD/YYYY')) + ' (GMT+7)');
        } else {
            $('#periode').val($('#lang_Week').val() + ' : ' + moment(start_date).format('MM/DD/YYYY') + ' - ' + moment(end_date).format('MM/DD/YYYY') + ' (GMT+7)');
        }

        z = moment(start_date).format('MM/DD/YYYY') + '%20-%20' + moment(end_date).format('MM/DD/YYYY');

        loadGraph(x);
        periodeActive('wp');
        hideCalendar();

        $('#week-picker-wrapper').datepicker('hide');
        $("#periodeDropdown").dropdown("toggle");
    }

    $('#week-picker-wrapper').datepicker({
        autoclose: true,
        forceParse: false,
        showWeek: true,
        todayHighlight: true,
        startDate: new Date(new Date().getFullYear() - 1, '0', '01'),
        endDate: new Date(new Date().getFullYear(), moment().month(), moment().date() - 1),
        container: '#week-picker-wrapper',
    }).on("changeDate", function (e) {
        set_week_picker(e.date);
    });

    //month picker
    function set_month_picker(date) {
        start_date = new Date(date.getFullYear(), date.getMonth(), 1);
        end_date = new Date(date.getFullYear(), date.getMonth() + 1, 0);

        $('#mp').datepicker('update', start_date);
        $('#periode').val($('#lang_Month').val() + ' : ' + moment(start_date).format('MM/DD/YYYY') + ' - ' + moment(end_date).format('MM/DD/YYYY') + ' (GMT+7)');

        z = moment(start_date).format('MM/DD/YYYY') + '%20-%20' + moment(end_date).format('MM/DD/YYYY');

        loadGraph(x);
        periodeActive('mp');
        hideCalendar();

        $('#mp').datepicker('hide');
        $("#periodeDropdown").dropdown("toggle");
    }
    $('#mp').datepicker({
        autoclose: true,
        forceParse: false,
        todayHighlight: true,
        minViewMode: 1,
        startDate: new Date(new Date().getFullYear() - 1, '0', '01'),
        endDate: new Date(new Date().getFullYear(), moment().month(), moment().date()),
    }).on("changeDate", function (e) {
        set_month_picker(e.date);
    });

    //Year picker
    function set_year_picker(date) {
        start_date = new Date(date.getFullYear(), date.getMonth(), 1);
        end_date = new Date(date.getFullYear() + 1, date.getMonth() - 1, 0);

        $('#yp').datepicker('update', start_date);
        $('#periode').val($('#lang_Year').val() + ' : ' + start_date.getFullYear() + ' (GMT+7)');

        z = moment(new Date(start_date)).startOf('year').format('MM/DD/YYYY') + '%20-%20' + moment(new Date(start_date)).endOf('year').format('MM/DD/YYYY');
        year = true;
        loadGraph(x);
        periodeActive('yp');
        hideCalendar();

        $('#yp').datepicker('hide');
        $("#periodeDropdown").dropdown("toggle");
    }
    $('#yp').datepicker({
        autoclose: true,
        forceParse: false,
        todayHighlight: true,
        minViewMode: 2,
        startDate: new Date(new Date().getFullYear() - 1, '0', '01'),
        endDate: new Date(new Date().getFullYear(), moment().month(), moment().date()),
    }).on("changeDate", function (e) {
        set_year_picker(e.date);
    });
});

function getMonthsLabel(startDate, stopDate) {
    var dateArray = [];
    var currentDate = moment(startDate);
    var stopDate = moment(stopDate);

    while (currentDate <= stopDate) {
        dateArray.push(moment(currentDate).format("MMM"));
        currentDate = moment(currentDate).add(1, "months");
    }
    return dateArray;
}

function loadGraph(x) {

    if (z == undefined) {
        z = moment().format('MM/D/YYYY') + '%20-%20' + moment().format('MM/D/YYYY');
    }

    urlGraph = api_url + '?category=' + x + '&periode=' + z;

    if (graph != null) {
        graph.destroy();
        showGraphLoading("graphLoading");
    }

    function drawChart(callback) {
        ajax_get(urlGraph, success, fail);

        function success(data) {
            var value = data.data.value;

            if (year) {
                var periode = getMonthsLabel(start_date);
            } else {
                var periode = data.data.periode;
            }

            switch (x) {
                case 1:
                    label = $('.category').find("option:selected").text();
                    callback(value, periode, label);
                    break;

                case 2:
                    label = $('.category').find("option:selected").text();
                    callback(value, periode, label);
                    break;

                case 3:
                    label = $('.category').find("option:selected").text();
                    callback(value, periode, label);
                    break;

                default:
                    label = $('.category').find("option:selected").text();
                    callback(value, periode, label);
                    break;
            }

            document.getElementById("graphLabel").innerHTML = "" + label;
        }

        function fail(data) {
            console.log(data);
        }
    }

    drawChart(function (a, b, label) {

        options = {
            series: [{
                name: label,
                data: a
            }],
            markers: {
                size: 2.5,
                colors: undefined,
                strokeColors: '#fff',
                strokeWidth: 1.5,
                strokeOpacity: 0.9,
                strokeDashArray: 0,
                fillOpacity: 1,
                discrete: [],
                shape: "circle",
                radius: 2,
                offsetX: 0,
                offsetY: 0,
                onClick: undefined,
                onDblClick: undefined,
                showNullDataPoints: true,
                hover: {
                    size: undefined,
                    sizeOffset: 3
                }
            },
            animations: {
                enabled: true,
                easing: 'linear',
                speed: 1000,
                animateGradually: {
                    enabled: false,
                    delay: 100
                },
                dynamicAnimation: {
                    enabled: false,
                    speed: 400
                }
            },
            chart: {
                height: 400,
                type: 'area',
                animations: {
                    enabled: true,
                    easing: 'linear',
                    speed: 500,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                },
                toolbar: {
                    show: true,
                    offsetX: 0,
                    offsetY: 0,
                    tools: {
                      download: true,
                      selection: true,
                      zoom: true,
                      zoomin: true,
                      zoomout: true,
                      pan: true,
                      reset: true | '<img src="/static/icons/reset.png" width="20">',
                      customIcons: []
                    },
                    export: {
                      csv: {
                        filename: undefined,
                        columnDelimiter: ',',
                        headerCategory: 'category',
                        headerValue: 'value',
                        dateFormatter(timestamp) {
                          return new Date(timestamp).toDateString()
                        }
                      }
                    },
                    autoSelected: 'zoom' 
                  },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                categories: b,
                labels: {
                    rotate: 0
                },
                hideOverlappingLabels: true,
                axisTicks: {
                    show: true,
                    borderType: 'solid',
                    color: '#212121',
                    height: 6,
                    offsetX: 0,
                    offsetY: 0
                },
                tickPlacement: 'on',
                tickAmount: 3,
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return val.toFixed(0);
                    }
                },
            },
            tooltip: {
                fixed: {
                    enabled: false,
                    position: "topLeft", // topRight, topLeft, bottomRight, bottomLeft
                    offsetY: 30,
                    offsetX: 60
                }
            },
            colors: ['#77AC1D'],
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: {
                        height: 300,
                    },
                    markers: {
                        size: 0
                    },
                }
            },
            {
                breakpoint: 1366,
                options: {
                    markers: {
                        size: 2.5
                    },
                    chart: {
                        height: 400,
                    },
                }
            }]
        };

        graph = new ApexCharts(document.querySelector("#graph"), options);
        graph.render();

        var sum = a.reduce(function(val1, val2){
            return val1 + val2;
        }, 0);

        $("h5#graph_total_data").html(`total: <span style="color: #FEB019;">`+ sum +`<span>`);
        hideGraphLoading("graphLoading");
    });
}

function loadStorageUsage() {

    if (graph != null) {
        graph.destroy();
        showGraphLoading("graphLoading");
    }

    ajax_get(api_storage_url, success, fail);

    function success(data) {
        var used = data.data.used[0];
        var available = data.data.available[0];
        var size = data.data.size;

        document.getElementById("graphLabel").innerHTML = $('.category').find("option:selected").text() + ` (`+size+`)`;

        options = {
            series: [used , available],
            chart: {
                height: 300,
                type: 'pie',
            },
            labels: ['Used', 'Available Storage'],
            legend: {
                fontSize: '18px',
                floating: true,
            },
            responsive: [{
                breakpoint: 768,
                options: {
                    legend: {
                        position: 'bottom',
                        fontSize: '14px',
                        floating: false,
                    }
                }
            }]
        };

        graph = new ApexCharts(document.querySelector("#graph"), options);
        graph.render();
        $("h5#graph_total_data").html(``);
        hideGraphLoading("graphLoading");
    }

    function fail(data) {
        console.log(data);
    }
}
