"use strict";

var productUrl;

if (productUrl !== null) {
    productUrl = null;
}

productUrl = $('#url_productList').val();

productTable(productUrl);

function searchProduct() {
    var sn = $('#search_name').val();
    var newUrl = productUrl + '?search_name=' + sn;

    productTable(newUrl);
}

function productTable(url) {
    setTimeout(function () {
        initDataTable('outofstockList', url);
    }, 500);
}
