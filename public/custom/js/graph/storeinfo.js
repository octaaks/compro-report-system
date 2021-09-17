var options;
var chart;
var category = [];
var categoryLabel = [];
var tick = 0;
var tickPlace = 'on';
var company_id = $('#company_id').val();
var pctLabel = 'vs yesterday';
var animationEnabled = false;

//url
var api = 'realtime';
var status = 0;
var date = moment().format('YYYY-MM-DD');

//moment js language
var locale = document.getElementsByTagName("html")[0].getAttribute("lang");
console.log('lang: ' + locale);

if (locale == 'zh-Hans') {
    moment.locale('zh-cn');
} else {
    moment.locale(locale);
}

//status FILTER
$(function () {
    $("#status").change(function () {
        var selectedValue = $(this).find("option:selected").val();
        // var selectedText = $(this).find("option:selected").text();
        // var selectedValue = $(this).val();

        status = selectedValue;
        console.log(status);
        console.log(url);
        showGraph(url);
    });
});

graphRealtime(moment().format('YYYY-MM-DD'));
showGraphLoading("graphLoading");

console.log(moment().add(-30, 'days').from(moment()));

function graphRealtime(p_date) {
    date = p_date;
    api = 'realtime';

    pctLabel = 'vs ' + moment().add(-1, 'days').from(moment());
    categoryLabel = ["00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00", "23:59"];
    periodeLabel = moment(date).format('DD MMMM YYYY');
    tick = 3;
    tickPlace = 'on';
    showGraph(url);
}

function graphLastweek(startDate, endDate) {
    date = startDate + ' - ' + endDate;
    api = 'lastweek';

    pctLabel = 'vs ' + moment().add(-1, 'weeks').from(moment());
    categoryLabel = getDatesLabel(startDate, endDate);
    periodeLabel = moment(startDate).format('DD MMMM') + ' - ' + moment(endDate).format('DD MMMM YYYY');
    tick = 7;
    tickPlace = 'on';
    animationEnabled = true;
    showGraph(url);
}

function graphLastmonth(startDate, endDate) {
    date = startDate + ' - ' + endDate;
    api = 'lastmonth';

    pctLabel = 'vs ' + moment().add(-1, 'months').from(moment());
    categoryLabel = getDatesLabel(startDate, endDate);
    periodeLabel = moment(startDate).format('DD MMMM') + ' - ' + moment(endDate).format('DD MMMM YYYY');
    tick = 14;
    tickPlace = 'on';
    animationEnabled = true;
    showGraph(url);
}

function graphDay(p_date) {
    date = p_date;
    api = 'day';

    pctLabel = 'vs ' + moment().add(-1, 'days').from(moment());
    categoryLabel = ["00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00", "23:59"];
    periodeLabel = moment(date).format('DD MMMM YYYY');
    tick = 3;
    tickPlace = 'on';
    showGraph(url);
}

function graphWeek(startDate, endDate) {
    date = startDate + ' - ' + endDate;
    api = 'week';

    pctLabel = 'vs ' + moment().add(-1, 'weeks').from(moment());
    categoryLabel = getDatesLabel(startDate, endDate);
    periodeLabel = moment(startDate).format('DD MMMM') + ' - ' + moment(endDate).format('DD MMMM YYYY');
    tick = 7;
    tickPlace = 'on';
    animationEnabled = false;
    showGraph(url);
}

function graphMonth(startDate, endDate) {
    date = startDate + ' - ' + endDate;
    api = 'month';

    pctLabel = 'vs ' + moment().add(-1, 'months').from(moment());
    categoryLabel = getDatesLabel(startDate, endDate);
    periodeLabel = moment(startDate).format('DD') + ' - ' + moment(endDate).format('DD MMMM YYYY');
    tick = 14;
    tickPlace = 'on';
    animationEnabled = false;
    showGraph(url);
}

function graphYear(startDate, endDate) {
    date = startDate + ' - ' + endDate;
    api = 'year';

    pctLabel = 'vs ' + moment(startDate).from(moment(endDate));
    categoryLabel = getMonthsLabel(startDate, endDate);
    periodeLabel = moment(startDate).format('YYYY');
    tick = 12;
    tickPlace = 'on';
    animationEnabled = false;
    showGraph(url);
}

function graphHourly(date, mode) {

    if (mode == 0) {
        pctLabel = $('#lang_vsYesterday').val();
        animationEnabled = false;
    } else {
        pctLabel = $('#lang_vsYesterday').val();
        animationEnabled = true;
    }

    var url = url_hourly + '?search_date=' + date;

    category = [00, 01, 02, 03, 04, 05, 06, 07, 08, 09, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23];
    categoryLabel = ["00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00", "23:59"];
    periodeLabel = moment(date).format('DD MMMM YYYY');
    tick = 3;
    tickPlace = 'on';
    showGraph(url, mode);
}

//get all date in range for graph x label
function getDatesLabel(startDate, stopDate) {
    var dateArray = [];
    var currentDate = moment(startDate);
    var stopDate = moment(stopDate);

    while (currentDate <= stopDate) {
        dateArray.push(moment(currentDate).format("DD"));
        currentDate = moment(currentDate).add(1, "days");
    }
    return dateArray;
}

//get all date in range for graph x label
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

function showGraph(link) {

    var link = $('#url_home').val() + '/api/' + api + '/' + company_id + '?date=' + date + '&status=' + status;

    if (chart != null) {
        chart.destroy();
        showGraphLoading("graphLoading");
    } else {
        hideGraphLoading("graphLoading");
    }

    function drawChart(callback) {
        $.getJSON(link, function (data) {

            var t_sales = data.t_sales;
            var t_order = data.t_order;
            var t_avg = data.t_avg;

            var tb_sales = data.tb_sales;
            var tb_order = data.tb_order;
            var tb_avg = data.tb_avg;

            var category = data.category;
            var sales_array = data.sales;
            var count_array = data.count;
            var avg_array = data.avg;

            callback(t_sales, t_order, t_avg, tb_sales, tb_order, tb_avg, category, sales_array, count_array, avg_array);
        });
    }

    drawChart(function (t_sales, t_order, t_avg, tb_sales, tb_order, tb_avg, category, sales_array, count_array, avg_array) {

        //sum
        document.getElementById("tPenjualan").innerHTML = "Rp. " + parseInt(t_sales).toLocaleString("ro-RO");
        document.getElementById("tPesanan").innerHTML = "" + parseInt(t_order);
        document.getElementById("tPpp").innerHTML = "Rp. " + parseInt(t_avg).toLocaleString("ro-RO");

        var pct_sales = getPercentageChange(tb_sales, t_sales).toFixed(0);
        var pct_order = getPercentageChange(tb_order, t_order).toFixed(0);
        var pct_avg = getPercentageChange(parseInt(tb_avg), parseInt(t_avg)).toFixed(0);

        // label 'vs blablabla'        
        //-------------------------------
        var p = document.getElementsByClassName("labelPeriode");
        for (i = 0; i < p.length; i++) {
            p[i].innerHTML = pctLabel;
        }

        //persentase perubahan
        document.getElementById("xPenjualan").innerHTML = "" + pct_sales + "%";
        document.getElementById("xPesanan").innerHTML = "" + pct_order + "%";
        document.getElementById("xPpp").innerHTML = "" + pct_avg + "%";

        //ganti warna jika value naik atau turun
        document.getElementById("xPenjualan").style.color = getColor(pct_sales);
        document.getElementById("xPesanan").style.color = getColor(pct_order);
        document.getElementById("xPpp").style.color = getColor(pct_avg);

        //periodeLabel
        document.getElementById("periodeLabel").innerHTML = periodeLabel;

        category = categoryLabel;

        options = {
            series: [{
                    name: $('#lang_Sales').val(),
                    type: "area",
                    // data: {
                    //     formatter: function (sales_array) {
                    //         return sales_array + "$";
                    //     }
                    // }
                    data: sales_array
                },
                {
                    name: $('#lang_Orders').val(),
                    type: "area",
                    data: count_array
                },
                {
                    name: $('#lang_Sales').val() + ' / ' + $('#lang_Orders').val(),
                    type: "area",
                    // data: function (avg_array) {
                    //     return "Rp. " + parseInt(avg_array).toLocaleString("ro-RO");
                    // }
                    data: avg_array
                }
            ],
            responsive: [{
                breakpoint: 480,
                options: {
                    markers: {
                        size: 0
                    },
                    chart: {
                        height: 200,
                        type: "line",
                        stacked: false,
                        toolbar: {
                            show: true,
                            offsetX: -0,
                            offsetY: 0,
                            tools: {
                                download: true,
                            },
                            export: {
                                csv: {
                                    filename: undefined,
                                    columnDelimiter: ', ',
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
                    xaxis: {
                        tickPlacement: tickPlace,
                        tickAmount: tick / 3,
                    },
                }
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
                enabled: animationEnabled,
                easing: 'linear',
                speed: 500,
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
                height: 300,
                type: "line",
                stacked: false,
                toolbar: {
                    show: true,
                    offsetX: -0,
                    offsetY: 0,
                    tools: {
                        download: false,
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
                curve: 'smooth',
                width: [3, 3, 3]
            },
            title: {
                text: " ",
                align: "left",
                offsetX: 0
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.2,
                    opacityTo: 0.25
                }
            },
            xaxis: {
                labels: {
                    rotate: 0
                },
                categories: category,

                hideOverlappingLabels: true,
                axisTicks: {
                    show: true,
                    borderType: 'solid',
                    color: '#212121',
                    height: 6,
                    offsetX: 0,
                    offsetY: 0
                },
                tickPlacement: tickPlace,
                tickAmount: tick,

            },
            yaxis: [{
                    show: false,
                    axisTicks: {
                        show: true
                    },
                    axisBorder: {
                        show: true,
                        color: "#008FFB"
                    },
                    labels: {
                        style: {
                            colors: "#008FFB"
                        },
                        formatter: function (value) {
                            return "Rp. " + parseInt(value).toLocaleString("ro-RO");
                        }
                    },
                    title: {
                        text: "Income (thousand crores)",
                        style: {
                            color: "#008FFB"
                        }
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                {
                    show: false,
                    seriesName: "Income",
                    opposite: true,
                    axisTicks: {
                        show: true
                    },
                    axisBorder: {
                        show: true,
                        color: "#00E396"
                    },
                    labels: {
                        style: {
                            colors: "#00E396"
                        },
                        formatter: function (value) {
                            if (value <= 1) {
                                return value.toFixed(0) + " " + $('#lang_Transaction').val();
                            } else {
                                return value.toFixed(0) + " " + $('#lang_Transactions').val();
                            }
                        }
                    },
                    title: {
                        text: "Operating Cashflow (thousand crores)",
                        style: {
                            color: "#00E396"
                        }
                    }
                },
                {
                    show: false,
                    seriesName: "Revenue",
                    opposite: true,
                    axisTicks: {
                        show: true
                    },
                    axisBorder: {
                        show: true,
                        color: "#FEB019"
                    },
                    labels: {
                        style: {
                            colors: "#FEB019"
                        },
                        formatter: function (value) {
                            return "Rp. " + parseInt(value).toLocaleString("ro-RO");
                        }
                    },
                    title: {
                        text: "Revenue (thousand crores)",
                        style: {
                            color: "#FEB019"
                        }
                    }
                }
            ],
            tooltip: {
                fixed: {
                    enabled: false,
                    position: "topLeft", // topRight, topLeft, bottomRight, bottomLeft
                    offsetY: 30,
                    offsetX: 60
                }
            },
            legend: {
                horizontalAlign: "right",
                offsetX: 0,
                position: 'bottom',
            }
        };
        chart = new ApexCharts(document.querySelector("#chart"), options);

        chart.render();
        hideGraphLoading("graphLoading");
    });
}

$(window).resize(function () {

    if (screen.width >= 768) {

    } else {

    }

    // chart.destroy();
    // showGraph(url);
    console.log("Your screen resolution is: " + screen.width + "x" + screen.height);
});
