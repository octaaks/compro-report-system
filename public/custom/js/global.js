$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
    }
});

function showLoadingPage(text) {
    mApp.blockPage({
        overlayColor: "#000000",
        type: "loader",
        state: "primary",
        message: (text ? text : $('#lang_PleaseWait').val() + "...")
    });
}

function hideLoadingPage() {
    mApp.unblockPage();
}

function showLoading(content) {
    mApp.block(content, {
        overlayColor: "#000000",
        type: "loader",
        state: "primary",
        message: "Loading..."
    });
}

function hideLoading(content) {
    mApp.unblock(content);
}

function sweetAlert(data, callbackOk = null, callbackCancel = null) {
    var swalObj = data;

    swal(swalObj).then((result) => {
        if (result.value) {
            if (callbackOk != null) {
                callbackOk();
            }
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            if (callbackCancel != null) {
                callbackCancel();
            }
        }
    });
}

function selectChangeShowLoading(text) {
    showLoadingPage(text);
}

function selectChangeHideLoading() {
    hideLoadingPage();
}

function capitalizeFirstCharacter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function ajax_post(url, data, callbackSuccess, callbackError) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Add master_admin_id value
    // only for root_admin
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            // set new token
            if (data['result'] == 'success') {
                if (callbackSuccess) {
                    callbackSuccess(data);
                } else {
                    window.location.reload(true);
                }
            } else {
                if (callbackError)
                    callbackError(data);
            }

        },
        error: function (x, t, m) {
            if (x.status == '500') {
                var message = 'Oops something went wrong... please try again or call our support'
                sweetAlert({
                    title: 'Failed',
                    html: "Error: " + message,
                    type: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
            } else if (callbackError) {
                callbackError(data);
            }
        },
        cache: false,
        timeout: 300000
    });
}

function ajax_get(url, callbackSuccess, callbackError) {
    // console.log("!!! TOKEN: " + localStorage.getItem("token") + " !!!");
    var ajaxParams = {
        url: url,
        type: 'GET',
        success: function (data, status, xhr) {
            if (data.code == '200' || data.result == 'success' || xhr.getResponseHeader('Content-Type') == 'application/zip') {
                callbackSuccess(data);
            } else {
                callbackError(data);
            }

        },
        error: function (data) {
            callbackError(data);
        },
        cache: true,
        timeout: 15000,
        processData: false
    };

    $.ajax(ajaxParams);
}

function ajax_delete(url, callbackSuccess, callbackError) {
    $.ajax({
        url: url,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            // set_refresh_token(data.token);
            callbackSuccess(data);
        },
        error: function (data) {
            console.log(data);
            callbackError(data);
        },
    });
}

function ajax_post_multipart(url, formData, callbackSuccess, callbackError, additionalFormData = null, container = null) {

    var data = new FormData(formData);
    showLoadingPage("Uploading data...");

    $.ajax({
        url: url,
        method: "POST",
        data: data,
        // dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            hideLoadingPage();

            if (data.result === 'success') {
                if (callbackSuccess)
                    callbackSuccess(data);
            } else {
                if (callbackError)
                    callbackError(data);
            }
        },
        error: function (data) {
            hideLoadingPage();
            if (callbackError) {
                callbackError(data);
            } else if (data.status == '500') {
                var message = 'Oops something went wrong... please try again or call our support'
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
    });
}

function data_table(data) {
    let category = data['category'];
    $("#" + data['table_id']).mDatatable({
        data: {
            type: "remote",
            source: {
                read: {
                    url: data['url'],
                    method: 'GET',
                    headers: {
                        'Authorization': data['token']
                    },
                    map: function (raw) {
                        // if (raw.result == 'success' && compareString(data['table_id'],'transaction')>0.5) {
                        if (raw.result == 'success') {
                            var arr = [];
                            for (var i = 0; i < raw.data.length; i++) {
                                // raw.data[i].date_time = moment(raw.data[i].date_time).format("MMM D, YYYY H:mm");
                                if (raw.data.created_at !== 'undefined') {
                                    raw.data[i].created_at = moment(raw.data[i].created_at).format("MMM D, YYYY H:mm");
                                }
                                arr.push(raw.data[i]);
                            }
                            //     var arr = [];
                            //     for (var i = 0; i < raw.data.length; i++) {
                            //         if (category == raw.data[i].status) {
                            //             raw.data[i].date_time = moment(raw.data[i].date_time).format("MMM D, YYYY H:mm");
                            //             arr.push(raw.data[i]);
                            //         }
                            //     }
                            //     if (category == 'new5') {
                            //         arr = [];
                            //         for (var i = 0; i < raw.data.length; i++) {
                            //             if (raw.data[i].status == 1) {
                            //                 raw.data[i].date_time = moment(raw.data[i].date_time).format("MMM D, YYYY H:mm");
                            //                 if (arr.length < 5)
                            //                 arr.push(raw.data[i]);
                            //             }
                            //         }
                            //     }
                            return arr;
                        } else {
                            if (raw.result == 'success') {
                                if (raw.data.created_at !== 'undefined') {
                                    arr = [];
                                    for (var i = 0; i < raw.data.length; i++) {
                                        raw.data[i].created_at = moment(raw.data[i].created_at).format("MMM D, YYYY H:mm");
                                        arr.push(raw.data[i]);
                                    }
                                    return arr;
                                } else {
                                    return raw.data;
                                }
                            } else {
                                return raw.data;
                            }
                        }
                    }
                }
            },
            autoColumns: true,
            pageSize: 10,
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true,
            saveState: {
                cookie: false,
                webstorage: false,
            }
        },
        layout: {
            scroll: true,
            footer: !1,
            height: 700,
        },
        sortable: true,
        pagination: true,
        toolbar: {
            items: {
                pagination: {
                    pageSizeSelect: [1, 5, 10, 20, 30, 50, 100]
                }
            }
        },
        search: {
            input: $("#generalSearch")
        },
        translate: {
            records: {
                processing: $('#lang_PleaseWait').val() + '...',
                noRecords: `<div class="col-xs-10 col-sm-8 col-md-6 col-lg-5" style="display: block; margin: auto; height: auto; margin-top:2em">
                                <img src="` + data['no_record_img_url'] + `" style="display: block; margin: auto; width: 70%; height: auto;">
                                <div style="text-align:center; margin-top:10px; margin-bottom:20px;">
                                <p  id="title" style="color: #77AC1D;">` +
                    $('#lang_noRecord').val() +
                    `</p>
                                </div>
                            </div>`
            },
            toolbar: {
                pagination: {
                    items: {
                        default: {
                            first: 'First',
                            prev: 'Previous',
                            next: 'Next',
                            last: 'Last',
                            more: 'More pages',
                            input: 'Page number',
                            select: 'Select page size'
                        },
                        info: 'Displaying {{start}} - {{end}} of {{total}} records'
                    }
                }
            }
        },
        columns: data['field'],
        rows: data['row']
    });
}

function toasterSuccess(data) {
    toastr.success(data.message, "Success");
}

function toasterFail(data) {
    if (typeof data.message === "string") {
        toastr.error(data.message, "Failed");
    } else {
        for (var key in data.message) {
            toastr.error(data.message[key][0], "Failed");
        }
    }
}

function selectImage(target, callbackOnload) {
    var input = '#' + target;

    $(input).off('change');
    $(input).click();
    $(input).on('change', function () {
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#' + target + '-src').attr("src", e.target.result);

            };

            reader.readAsDataURL(input.files[0]);

            if (callbackOnload != null) {
                callbackOnload();
            }
        }
    });
}

function selectFile(target, callbackOnload) {
    var input = '#' + target;
    // console.log(input);
    var imgUrl = $('#img-your-file').val();

    $(input).off('change');
    $(input).click();
    $(input).on('change', function () {
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#' + target + '-src').attr("src", imgUrl);
                $('#' + target + '-name').text(input.files[0].name);
                console.log(input.files[0].name);
            };

            reader.readAsDataURL(input.files[0]);

            if (callbackOnload != null) {
                callbackOnload();
            }
        }

    });
}

function saveAdminData(url, data) {
    Metronic.blockUI({
        boxed: true
    });
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        //                dataType: 'JSON',
        success: function (data) {
            if (data.indexOf('Error') == 0) {
                showError(err_title, data);
            } else {
                showSuccess('Success', data);
            }
            Metronic.unblockUI();
        },
        error: function (x, t, m) {
            if (t === "timeout") {
                showError(err_title, "Request Timed out. Please try again.");
            } else {
                showError(err_title, m);
            }
            Metronic.unblockUI();
        },
        cache: false,
        timeout: time_out
    });
}

function IsJsonString(str) {
    try {
        var json = JSON.parse(str);
        return (typeof json === 'object');
    } catch (e) {
        return false;
    }
}

function submitForm(btnSubmit, msgSuccess, msgFailed, reload, callbackSuccess, serverMessage, callbackError) {
    var form = '#' + $(btnSubmit).attr('form');
    var url = $(form).attr('action');
    var formData = $(form)[0];
    msgSuccess = msgSuccess ? msgSuccess : "Successfully added content.";
    msgFailed = msgFailed ? msgFailed : "Failed to add content.";
    reload = reload ? reload : 'true';
    var serverMessage = serverMessage ? serverMessage : false;
    ajax_post_multipart(url, formData, function (data) {
        sweetAlert({
            title: serverMessage ? data.message : msgSuccess,
            text: "",
            type: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK',
            allowOutsideClick: false
        }, function () {
            if (callbackSuccess) {
                callbackSuccess(data);
            } else if (reload == 'true') {
                window.location.reload();
            }
        });

    }, function (data) {
        console.log(data);
        if (data.status == '500') {
            var message = 'Oops something went wrong... please try again or call our support'
            sweetAlert({
                title: msgFailed,
                html: "Error: " + message,
                type: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        } else if (callbackError) {
            callbackError(data);
        } else {
            var message = data.message ? data.message : data.responseText;
            sweetAlert({
                title: msgFailed,
                html: "Error: " + message,
                type: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        }

    });
}

function humanize(str) {
    var frags = str.split('_');
    for (i = 0; i < frags.length; i++) {
        frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1);
    }
    return frags.join(' ');
}

function setHomeActionInput() {
    var val = $('select[name="meta[actionlink_value]"]').last().find('option:selected').val();
    var termVal = $('select[name="meta[actionlink_value]"]').first().find('option:selected').val();
    var menu = val.split('_');
    var postMenuTitle = $("#postMenuTitle").val();
    var actionLinkTitle = $("#action-link-title").val();

    if (menu.length > 1) {
        var type = menu[0];
        var id = menu[1];
        var templateName = $("#termTemplateNameForHomeActionButton").val();

        // load selected menu term
        var term = termVal.split('_');
        var termId = term[1];

        var actionButtonName = $('#action_button_name').val();
        var termTemplateName = templateName;
        var targetMenu = termTemplateName !== id ? (termTemplateName + "/" + id) : (termTemplateName + "/" + "-1");

        var internalLinkInput = $('input[name="meta[actionlink_value]"].internal-link');
        var internalTitleInput = $('input[name="meta[actionlink_title]"]');
        internalLinkInput.val(targetMenu);
        var titleMenu = id;

        if (type == "post") {
            if (postMenuTitle == "") {
                titleMenu = actionLinkTitle
            } else {
                var postMenu = JSON.parse(postMenuTitle);
                if (typeof (postMenu) == 'object' && postMenu[id]) {
                    titleMenu = postMenu[id].title ? postMenu[id].title : id
                } else {
                    titleMenu = postMenu.title;
                }
            }
        } else if (type == "term") {
            titleMenu = menus[id].title ? menus[id].title : id;
        }

        if ($('select[name="meta[actionlink_type]"]').val() == 'home_internal_link')
            internalTitleInput.val(titleMenu);
    }
}

function setDateTimeRangePicker(inputName = null) {
    var inputName = inputName != null ? inputName : 'search_daterange';

    $('input[name="' + inputName + '"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="' + inputName + '"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('input[name="' + inputName + '"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });

    var inputName2 = inputName2 != null ? inputName2 : 'search_daterange2';

    $('input[name="' + inputName2 + '"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="' + inputName2 + '"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('input[name="' + inputName2 + '"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
}

function setDateTimeRangePickerForExcludePrice(inputName = null) {

    var inputName = inputName != null ? inputName : 'exclude_price_date_range[]';

    var today = new Date();
    var current = (today.getMonth() + 1) + '/' + today.getDate() + '/' + today.getFullYear();

    $('input[name="' + inputName + '"]').daterangepicker({
        autoUpdateInput: false,
        minDate: current,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="' + inputName + '"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('input[name="' + inputName + '"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
}

function triggerButtonEnter(el) {
    $(el).on("keyup", function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            $('#btn_search_data').click();
        }
    });
}

var dataTable = null;
var data = [];

function initDataTable(tableId, url, table_category) {
    data['token'] = 'Bearer ' + '{{ csrf_token() }}';
    data['table_id'] = tableId;
    data['url'] = url;
    data['category'] = table_category;
    data['no_record_img_url'] = $('#no_record_img_url').attr('value');
    data['field'] = [{
        "field": "action",
        "title": "Action",
        template: function (data) {
            var urlEdit = $('#url_edit').attr('value');
            var urlDelete = $('#url_delete').attr('value') + "?id=" + data.id + "&_method=DELETE";

            console.log('data.id = ' + data.id);

            var btnAction = `<button
            class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill m--font-boldest">
            <a href="`+ urlEdit + data.id +`" target="_blank">
            <i class="fas fa-plus-circle"></i></a>
                    </button>`;

            // var btnAction = `<button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill m--font-boldest"  data-toggle="collapse" 
            // href="#stockId` + data.id + `" role="button" aria-expanded="false" aria-controls="stockId` + data.id + `">
            //         <i class="fas fa-plus-circle"></i>
            //         </button>`;

            // var addOption = `
            //         <div class="collapse multi-collapse" id="stockId` + data.id + `">
            //         <div class="form-group m-form__group">
            //         <label for="stock">insert stock: </label>
            //         <input class="form-control m-input" type="number" id="stockChange` + data.id + `" name="stock" min="1">
            //         </div>
            //         <button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--pill m--font-boldest" 
            //         data-toggle="m-popover" data-placement="top" data-content="View" aria-expanded="false" 
            //         onclick="stock_changed('` + urlEdit + `','` + data.id + `')">
            //             <i class='fa fa-save' style="color: lightgreen;"></i> save
            //         </button>
            //         </div>`;
            // btnAction += addOption;

            return btnAction;
        }
    }];

    if (compareString(tableId, 'requested') > 0.5) {
        data['field'][0] = {
            "field": "action",
            "title": "Action",
            template: function (data) {
                var urlDownload = $('#url_download').attr('value') + "?id=" + data.id;
                var urlDelete = $('#url_delete').attr('value') + "?id=" + data.id + "&_method=DELETE";

                var btnAction =
                    `<button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill m--font-boldest" data-toggle="m-popover" data-placement="top" data-content="View" aria-expanded="false" onclick="table_delete('` + urlDelete + `')">
                        <i class="la la-trash"></i>
                        </button> ` + `<button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill m--font-boldest" data-toggle="m-popover" data-placement="top" data-content="View" aria-expanded="false" onclick="table_download('` + urlDownload + `')">
                        <i class="la la-download"></i>
                        </button>   `;
                return btnAction;
            }
        };
    }

    if (compareString(tableId, 'transaction') > 0.5 || compareString(tableId, 'requested') > 0.5) {
        data['field'][1] = {
            "field": "status",
            "title": "Status",
            template: function (data) {
                if (!data.status) {
                    return '';
                } else {
                    var url_change_status = $('#url_status_change').attr('value');
                    var statusBadge = '<span class="m-badge m-badge--brand m-badge--wide"><strong> ' + data.status + '</strong></span>';
                    var statusBadge = getStringStatus(data.status);

                    var finalHtml = ``;
                    var btnEdit = `
                            <a class="btnEditData element-link-casted" data-toggle="collapse" href="#statusId` + data.id + `" 
                                role="button" aria-expanded="false" aria-controls="statusId` + data.id + `">
                                <i class='far fa-edit'></i> Edit
                            </a>
                        `;
                    var statusOption = `
                <div class="collapse multi-collapse" id="statusId` + data.id + `">
                
                <div class="form-group m-form__group">
                
                <label for="searchUsername">select new Status: </label>
                <select class="form-control m-input m-input--air col-md-12" name="status" id="status` + data.id + `">
                                        <option value="5">In Process</option>
                                        <option value="7">Completed</option>
                                        <option value="10">Payment In Process</option>
                                        <option value="8">Cancelled</option>
                                </select>
                </div>
                <button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--pill m--font-boldest" 
                data-toggle="m-popover" data-placement="top" data-content="View" aria-expanded="false" 
                onclick="status_changed('` + url_change_status + `','` + data.id + `')">
                    <i class='fa fa-save' style="color: lightgreen;"></i> save
                </button>
                </div>`;

                    if ((data.status == 7) || (data.status == 8)) {
                        btnEdit = ``;
                    }

                    finalHtml += btnEdit;
                    finalHtml += statusOption;

                    finalHtml += `
                            <div class="txtDataDisabled" style="margin-top: 5px;">
                                ` + statusBadge + `
                            </div>
                        `;

                    return finalHtml;
                }
            }
        };
    }
    dataTable = data_table(data);
    // return dataTable;
    $("#" + tableId).data('datatable', dataTable);
}


function getStringStatus(status) {
    if (status == 8) {
        statusBadge = '<span class="m-badge m-badge--danger m-badge--wide"><strong> ' + 'cancelled' + '</strong></span>';
    } else if (status == 7) {
        statusBadge = '<span class="m-badge m-badge--success m-badge--wide"><strong> ' + 'completed' + '</strong></span>';
    } else if (status == 5) {
        statusBadge = '<span class="m-badge m-badge--warning m-badge--wide"><strong> ' + 'need process' + '</strong></span>';
    } else if (status == 10) {
        statusBadge = '<span class="m-badge m-badge--wide" style="background: #9000ff;"><strong> ' + 'unpaid' + '</strong></span>';
    } else if (status == 1) {
        statusBadge = '<span class="m-badge m-badge--wide" style="background: #087856;"><strong> ' + 'pending' + '</strong></span>';
    } else {
        statusBadge = '<span class="m-badge m-badge--wide" style="background: #808080;"><strong> ' + status + '</strong></span>';
    }
    return statusBadge;
}

function table_delete(url) {
    ajax_delete(url, Success, Fail);
}

function stock_changed(url, id) {
    var input = {
        id: id,
        stock: $('#stockChange' + id).val()
    }
    ajax_post(url, input, Success, Fail);
}

function status_changed(url, id) {
    var input = {
        id: id,
        status: $('#status' + id).children("option:selected").val()
    }
    ajax_post(url, input, Success, Fail);
}

function table_download(url) {
    ajax_get(url, success_download, Fail);

    function success_download(data) {
        if (data.type == 'csv') {
            ConvertToCSV(data.data);
        }
        if (data.type == 'xls') {
            var myTestXML = new myExcelXML(data.data);
            myTestXML.downLoad();
        }
    }
}

function Success(data) {
    var message = data.message;

    sweetAlert({
        title: 'Success',
        html: "Success: " + message,
        type: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'OK'
    }, clickOk);
}

function clickOk() {
    // transactionGraph(1);
    window.location.reload();
}

function Fail(data) {
    var message = 'Oops something went wrong... please try again or call our support';
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


function compareString(strA, strB) {
    for (var result = 0, i = strA.length; i--;) {
        if (typeof strB[i] == 'undefined' || strA[i] == strB[i]);
        else if (strA[i].toLowerCase() == strB[i].toLowerCase())
            result++;
        else
            result += 4;
    }
    return 1 - (result + 4 * Math.abs(strA.length - strB.length)) / (2 * (strA.length + strB.length));
}

function transactionBadge(id, status) {
    var url_count_transaction = $('#url_count_transaction').val();
    ajax_get(url_count_transaction + '?status=' + status, bscs, bfail);

    function bscs(raw) {
        $('#' + id).text(raw.data);
    }

    function bfail(data) {
        return 'error';
    }
}

function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

function showGraphLoading(id) {
    var x = document.getElementById("" + id);
    x.style.display = "block";
}

function hideGraphLoading(id) {
    var x = document.getElementById("" + id);
    x.style.display = "none";
}

function percIncrease(a, b) {
    let percent;
    if (b != 0) {
        if (a != 0) {
            percent = (b - a) / a * 100;
        } else {
            percent = b * 100;
        }
    } else {
        percent = -a * 100;
    }
    return Math.floor(percent);
}

function getPercentageChange(oldNumber, newNumber) {

    var increased = newNumber - oldNumber;

    if (newNumber <= 0 && oldNumber <= 0) {
        return 0;
    } else if (newNumber <= 0 && oldNumber > 0) {
        return -100;
    } else if (newNumber > 0 && oldNumber <= 0) {
        return 100;
    } else {
        return (increased / oldNumber) * 100;
    }
}

function getColor(number) {
    var color = "";
    if (number > 0) {
        color = "#77AC1D";
    } else if (number == 0) {
        color = "black";
    } else {
        color = "red";
    }
    return color;
}

function ConvertToCSV(objArray) {
    var items = objArray;
    const replacer = (key, value) => value === null ? '' : value; // specify how you want to handle null values here
    const header = Object.keys(items[0]);
    let csv = items.map(row => header.map(fieldName => JSON.stringify(row[fieldName], replacer)).join(','));
    csv.unshift(header.join(','));
    csv = csv.join('\r\n');

    //Download the file as CSV
    var downloadLink = document.createElement("a");
    var blob = new Blob(["\ufeff", csv]);
    var url = URL.createObjectURL(blob);
    downloadLink.href = url;
    downloadLink.download = "transaction.csv"; //Name the file here
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}


let myExcelXML = (function () {
    let Workbook, WorkbookStart = '<?xml version="1.0"?><ss:Workbook  xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">';
    const WorkbookEnd = '</ss:Workbook>';
    let fs, SheetName = 'SHEET 1',
        styleID = 1,
        columnWidth = 80,
        fileName = "transaction_list",
        uri, link;

    class myExcelXML {
        constructor(o) {
            let respArray = o;
            let finalDataArray = [];

            for (let i = 0; i < respArray.length; i++) {
                finalDataArray.push(flatten(respArray[i]));
            }

            let s = JSON.stringify(finalDataArray);
            fs = s.replace(/&/gi, '&amp;');
        }

        downLoad() {
            const Worksheet = myXMLWorkSheet(SheetName, fs);

            WorkbookStart += myXMLStyles(styleID);

            Workbook = WorkbookStart + Worksheet + WorkbookEnd;

            uri = 'data:text/xls;charset=utf-8,' + encodeURIComponent(Workbook);
            link = document.createElement("a");
            link.href = uri;
            link.style = "visibility:hidden";
            link.download = fileName + ".xls";

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        get fileName() {
            return fileName;
        }

        set fileName(n) {
            fileName = n;
        }

        get SheetName() {
            return SheetName;
        }

        set SheetName(n) {
            SheetName = n;
        }

        get styleID() {
            return styleID;
        }

        set styleID(n) {
            styleID = n;
        }
    }

    const myXMLStyles = function (id) {
        let Styles = '<ss:Styles><ss:Style ss:ID="' + id + '"><ss:Font ss:Bold="1"/></ss:Style></ss:Styles>';

        return Styles;
    }

    const myXMLWorkSheet = function (name, o) {
        const Table = myXMLTable(o);
        let WorksheetStart = '<ss:Worksheet ss:Name="' + name + '">';
        const WorksheetEnd = '</ss:Worksheet>';

        return WorksheetStart + Table + WorksheetEnd;
    }

    const myXMLTable = function (o) {
        let TableStart = '<ss:Table>';
        const TableEnd = '</ss:Table>';

        const tableData = JSON.parse(o);

        if (tableData.length > 0) {
            const columnHeader = Object.keys(tableData[0]);
            let rowData;
            for (let i = 0; i < columnHeader.length; i++) {
                TableStart += myXMLColumn(columnWidth);

            }
            for (let j = 0; j < tableData.length; j++) {
                rowData += myXMLRow(tableData[j], columnHeader);
            }
            TableStart += myXMLHead(1, columnHeader);
            TableStart += rowData;
        }

        return TableStart + TableEnd;
    }

    const myXMLColumn = function (w) {
        return '<ss:Column ss:AutoFitWidth="0" ss:Width="' + w + '"/>';
    }


    const myXMLHead = function (id, h) {
        let HeadStart = '<ss:Row ss:StyleID="' + id + '">';
        const HeadEnd = '</ss:Row>';

        for (let i = 0; i < h.length; i++) {
            const Cell = myXMLCell(h[i].toUpperCase());
            HeadStart += Cell;
        }

        return HeadStart + HeadEnd;
    }

    const myXMLRow = function (r, h) {
        let RowStart = '<ss:Row>';
        const RowEnd = '</ss:Row>';
        for (let i = 0; i < h.length; i++) {
            const Cell = myXMLCell(r[h[i]]);
            RowStart += Cell;
        }

        return RowStart + RowEnd;
    }

    const myXMLCell = function (n) {
        let CellStart = '<ss:Cell>';
        const CellEnd = '</ss:Cell>';

        const Data = myXMLData(n);
        CellStart += Data;

        return CellStart + CellEnd;
    }

    const myXMLData = function (d) {
        let DataStart = '<ss:Data ss:Type="String">';
        const DataEnd = '</ss:Data>';

        return DataStart + d + DataEnd;
    }

    const flatten = function (obj) {
        var obj1 = JSON.parse(JSON.stringify(obj));
        const obj2 = JSON.parse(JSON.stringify(obj));
        if (typeof obj === 'object') {
            for (var k1 in obj2) {
                if (obj2.hasOwnProperty(k1)) {
                    if (typeof obj2[k1] === 'object' && obj2[k1] !== null) {
                        delete obj1[k1]
                        for (var k2 in obj2[k1]) {
                            if (obj2[k1].hasOwnProperty(k2)) {
                                obj1[k1 + '-' + k2] = obj2[k1][k2];
                            }
                        }
                    }
                }
            }
            var hasObject = false;
            for (var key in obj1) {
                if (obj1.hasOwnProperty(key)) {
                    if (typeof obj1[key] === 'object' && obj1[key] !== null) {
                        hasObject = true;
                    }
                }
            }
            if (hasObject) {
                return flatten(obj1);
            } else {
                return obj1;
            }
        } else {
            return obj1;
        }
    }

    return myExcelXML;
})();
