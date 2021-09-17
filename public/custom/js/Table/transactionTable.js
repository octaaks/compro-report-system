"use strict";

var transactionUrl;
var table;
if (transactionUrl != null) {
    transactionUrl = null;
}
var st = '';
transactionUrl = appUrl + '/api/transaction??status=' + $st;

function searchTransaction() {
    var sn = $('#search_name').val();
    var sd = $('#search_daterange').val();

    var newUrl = appUrl + '/api/transaction/61373?status=' + $st + '&search_name=' + sn + '&search_daterange=' + sd;
    transactionUrl = newUrl;
    console.log(transactionUrl);
    transactionTable();
}

transactionTable();

function transactionTable(y) {
    transactionBadge(1); //new order
    transactionBadge(5); //need process order
    transactionBadge(7); //completed order
    transactionBadge(10); //unpaid order
    transactionBadge(8); //cancelled order

    function drawTable(callback) {
        switch (y) {
            case 1:
                callback('transactionTable1', transactionUrl, 1);
                break;

            case 2:
                callback('transactionTable2', transactionUrl, 5);
                break;

            case 3:
                callback('transactionTable3', transactionUrl, 7);
                break;

            case 4:
                callback('transactionTable4', transactionUrl, 10);
                break;

            case 5:
                callback('transactionTable5', transactionUrl, 8);
                break;

            default:
                callback('transactionTable1', transactionUrl, 1);
                break;
        }
    }

    drawTable(function (id, transactionUrlApi, category) {
        setTimeout(function () {
            initDataTable('transactionTable1', transactionUrlApi, category);
        }, 500);
    });
}
