"use strict";

var options;
var url;
var label;
var chart;
var opsi;
var precentage;
var sdg;

var transaction_today;
var transaction_last;
var periode;
var periode_last;

var transaction_today2;
var transaction_last2;
var periode2;
var periode_last2;
// if(url !== null){
//     url=null;
// }

url = appUrl +'/api/transactionGraph';//urlGraph;

function searchGraph(){
    sdg = $('#search_daterange2').val();
    var newUrl = appUrl + "/api/transactionGraph?search_daterange=" + sdg;
    url = newUrl;
    transactionGraph(); 
}

transactionGraph();

function transactionGraph(opsi) {
    if (chart != null) {
        chart.destroy();
        showGraphLoading("graphLoading");
    }
    function drawChart(callback) {
        ajax_get(url, success, fail);
                
            function success(data){
                transaction_today = data.data.today_value;
                transaction_today2 = data.data.today_value2;
                transaction_last = data.data.yesterday_value;
                transaction_last2 = data.data.yesterday_value2;
                var date_categories = data.data.periode;
                var date_categories2 = data.data.periode2;
                var tt_length = transaction_today.reduce((partial_sum, a) => partial_sum + a,0);
                var tl_length = transaction_last.reduce((partial_sum, a) => partial_sum + a,0);
                var tt2_length = transaction_today2.reduce((partial_sum, a) => partial_sum + a,0);
                var tl2_length = transaction_last2.reduce((partial_sum, a) => partial_sum + a,0);
                switch (opsi) {
                    case 1:
                        label = "Completed Transaction";
                        precentage = precentageGraph(tt_length,tl_length);
                        callback(transaction_today, transaction_last, date_categories,'transactionChart');
                        break;
    
                    case 2:
                        label = "cancelled Orders";
                        precentage = precentageGraph(tt2_length,tl2_length);
                        callback(transaction_today2, transaction_last2, date_categories2,'transactionChart2');
                        break;
    
                    default:
                        label = "Completed Transaction";
                        precentage = precentageGraph(tt_length,tl_length);
                        callback(transaction_today, transaction_last, date_categories,'transactionChart');
                        break;
                }
    
                document.getElementById("graphLabel").innerHTML = "" + label;
                // document.getElementById("precentageGraph").innerHTML = `today vs (yesterday or range date data's)<br><div style="color:#074E59;">` 
                // + precentage+`<div>`;
                document.getElementById("precentageGraph").innerHTML = `today vs (yesterday or range date data's)<br>` + precentage;
            }
            
            function fail(data){
                alert('fail load graph');
                console.log(data);
            }
    }

    drawChart(function (a, b, c, id) {
        if(sdg == null || sdg == ''){
            var seriesb = 'Yesterday';
            var format = 'HH:mm';
        }else{
            var seriesb = sdg;
            var format = 'dd/MM/yy';
        }
        options = {
            series: [{
                name: 'Today',
                data: a
            }, {
                name: seriesb,
                data: b
            }],
            chart: {
                height: 400,
                type: 'area',
                noData: {
                    text: 'Loading...'
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                labels:{
                    rotate:0
                },
                hideOverlappingLabels:true,
                axisTicks:{
                    show:true,
                    borderType: 'solid',
                    color: '#212121',
                    height:6,
                    offsetX:0,
                    offsetY:0
                },
                tickAmount:3,
                categories: c
            },
            tooltip: {
                x: {
                    format: format
                },
            }
        };
            chart = new ApexCharts(document.querySelector("#transactionChart"), options);
            showGraphLoading("graphLoading");
            chart.render();
            hideGraphLoading("graphLoading");
    });
}

function precentageGraph(data1, data2){
    var new_data = data1;
    var old_data = data2;
    if(new_data == 0){
        new_data = 0.1;
    }if(old_data == 0){
        old_data = 0.1;
    }
    var result = null;
    var desc = null;
    
    if((new_data >= old_data)||(new_data >= old_data)){
        result = ((new_data-old_data)/old_data * 100).toFixed(2);
        // desc = 'increase by';
        desc = '<div style="color: #77AC1D;">';
    }else if(new_data <= old_data){
        result = ((old_data-new_data)/old_data * 100).toFixed(2);
        // desc = 'decrease by';
        desc = '<div style="color: #dc3545;"> -';
    }
    // return desc+' '+result+'%';
    return desc+result+'%'+'</div>';
}

