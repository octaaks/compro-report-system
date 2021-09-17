var options;
var chart;
var category = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23];
var categoryLabel = ["00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00", "23:59"];
// var url = appUrl + '/api/realtime/' + company_id + '?date=' + moment().format('YYYY/MM/DD');
var url = $('#url_graph_storeInfo').val() + '?date=' + moment().format('YYYY/MM/DD');

if (lang == 'zh-Hans') {
    locale = 'zh-cn';
} else {
    locale = lang;
}

function drawChart(callback) {

    ajax_get(url, success, fail);

    function success(data) {
        showGraphLoading("chartLoading");

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
    }

    function fail(data) {
        console.log(data);
    }
}

drawChart(function (t_sales, t_order, t_avg, tb_sales, tb_order, tb_avg, category, sales_array, count_array, avg_array) {

    category = ["00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00", "23:59"];

    pPenjualan = getPercentageChange(tb_sales, t_sales).toFixed(2); //percIncrease(t_sales, tb_sales).toFixed(2);
    pPesanan = getPercentageChange(tb_order, t_order).toFixed(2); //percIncrease(t_order, tb_order).toFixed(2);

    //total penjualan - pesanan
    document.getElementById("tPenjualan").innerHTML = "Rp. " + parseInt(t_sales).toLocaleString("ro-RO");
    document.getElementById("tPesanan").innerHTML = "" + t_order.toLocaleString('ita');

    //persentase penjualan-pesanan
    document.getElementById("pPenjualan").innerHTML = pPenjualan + "%";
    document.getElementById("pPesanan").innerHTML = pPesanan + "%";

    //ganti warna jika value naik atau turun
    document.getElementById("pPenjualan").style.color = getColor(pPenjualan);
    document.getElementById("pPesanan").style.color = getColor(pPesanan);

    document.getElementById("periodeLabel").innerHTML = moment().locale(locale).format('DD MMMM YYYY');
    document.getElementById("lPenjualan").innerHTML = $('#lang_At').val() + " 00:00 - " + moment().locale(locale).add(-1, 'h').hour() + ":00";
    document.getElementById("lPesanan").innerHTML = $('#lang_At').val() + " 00:00 - " + moment().locale(locale).add(-1, 'h').hour() + ":00";

    options = {
        series: [{
                name: $('#lang_Sales').val(),
                type: "area",
                data: sales_array
            },
            {
                name: $('#lang_Transactions').val(),
                type: "area",
                data: count_array
            },
        ],
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
        chart: {
            animations: {
                enabled: false,
                easing: 'linear',
                speed: 1000,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            },
            height: 300,
            type: "line",
            stacked: false
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
            tickPlacement: 'on',
            tickAmount: 3,

        },
        yaxis: [{
                show: true,
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
                    }
                },
                title: {
                    text: " ",
                    style: {
                        color: "#008FFB"
                    }
                },
                tooltip: {
                    enabled: true
                }
            },
            {
                show: true,
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
                    }
                },
                title: {
                    text: " ",
                    style: {
                        color: "#00E396"
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
            show: true,
            horizontalAlign: 'center',
            position: 'bottom',
            floating: false,
            offsetX: 0,
        },
        responsive: [{
            breakpoint: 768,
            options: {
                markers: {
                    size: 0
                },
                xaxis: {
                    tickPlacement: 'off',
                },
                chart:{
                    // height: 200,
                },
            }
        },{
            breakpoint: 1366,
            options: {
                markers: {
                    size: 2.5
                },
                chart: {
                    height: 300,
                },
            }
        }]
    };

    chart = new ApexCharts(document.querySelector("#storeInfoChart"), options);
    chart.render();

    hideGraphLoading("chartLoading");
});
