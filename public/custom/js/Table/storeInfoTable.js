var url = $('#url_cityList').val();

initDataTable('storeinfo',url);

function searchStoreInfo(){
    var key = $('#search_input').val();
    url = url + '?search=' + key;
    
    initDataTable('storeinfo',url);
}
