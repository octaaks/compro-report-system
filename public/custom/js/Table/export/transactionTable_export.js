function transactionExport(){
    var date = $('#search_daterange').val();
    var status = $('#searchStatus').children("option:selected").val();
    var radios = document.getElementsByName('file_type');
    for (var i = 0, length = radios.length; i < length; i++) {
        if (radios[i].checked) {
            var fileType = radios[i].value;
        }
    }
   
    var input={
        dateRange: date,
        status: status,
        type: fileType
    }

    ajax_post(appUrl+'/api/transactionExport',input, Success, Fail);
    function Success(data){
            var message = data.message;
                sweetAlert({
                    title: data.type,
                    html: "Success: " + message,
                    type: data.type,
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
    }

    function Fail(data){
        if(data.message != null){
            sweetAlert({
                title: 'Failed',
                html: "error: " + data.message,
                type: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        }else{
        var message = 'Oops something went wrong... make sure all option are not empty';
            sweetAlert({
                title: 'Failed',
                html: "Error: " + message,
                type: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        }
    }
}
