"use strict";

var requestFileURL;

if (requestFileURL !== null){
    requestFileURL = null;
}

requestFileURL = $('#url_fileList').val();

function searchFile(){
    var date = $('#search_daterange').val();
    var type = $('#search_type').children("option:selected").val();

    var newUrl = requestFileURL + '?search_daterange=' + date +'&search_type=' + type;
    requestFileURL = newUrl;
    
    requestFileTable();
}

requestFileTable();

function requestFileTable() {
    setTimeout(function(){ initDataTable('requestedFile', requestFileURL);},500);
}