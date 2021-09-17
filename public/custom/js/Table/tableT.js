"use strict";

var transactionUrl;
var table;
var sts;
var selectStatus = null;
var sn = '';
var sd = '';

function searchTransaction() {
    sn = $('#search_name').val();
    sd = $('#search_daterange').val();

    // var newUrl = appUrl + '/api/transaction?status='+sts()+'&search_name=' + sn + '&search_daterange=' + sd;
    // transactionUrl = newUrl;
    // console.log(transactionUrl);
    transactionTable(selectStatus);
}

transactionTable(1);

function transactionTable(sts) {
    if (transactionUrl != null) {
        transactionUrl = null;
    }

    transactionUrl = appUrl + '/api/transaction/61373?status=' + sts + '&search_name=' + sn + '&search_daterange=' + sd;
    transactionBadge(1); //new order
    transactionBadge(5); //need process order
    transactionBadge(7); //completed order
    transactionBadge(10); //unpaid order
    transactionBadge(8); //cancelled order
    console.log(transactionUrl);
    setTimeout(function () {
        initDataTable('transactionTable', transactionUrl).reload();
    }, 1000);
    selectStatus = sts;
}
