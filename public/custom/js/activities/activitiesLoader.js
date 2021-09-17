"use strict";

var type;
var label;
var locale;

loadActivities(1);

if (lang == 'zh-Hans') {
    locale = 'zh-cn';
} else {
    locale = lang;
}

function loadActivities(x) {
    showGraphLoading("activityLoading");
    document.getElementById("activity").innerHTML = '';
    document.getElementById("activityLabel").innerHTML = '';

    var url_activity = $('#url_activity').val();

    function drawActivity(callback) {
        switch (x) {
            case 1:
                type = 'transaction';
                label = $("[aria-controls='tabs-two-transaction']").text();
                callback(type, label);
                break;

            case 2:
                type = 'user';
                label = $("[aria-controls='tabs-two-user']").text();
                callback(type, label);
                break;

            case 3:
                type = 'membership';
                label = $("[aria-controls='tabs-two-member']").text();
                callback(type, label);
                break;

            case 4:
                type = 'reservation';
                label = $("[aria-controls='tabs-two-reservation']").text();
                callback(type, label);
                break;

            case 5:
                type = 'form-feedback';
                label = $("[aria-controls='tabs-two-feedback']").text();
                callback(type, label);
                break;

            case 6:
                type = 'user-post';
                label = $("[aria-controls='tabs-two-comment']").text();
                callback(type, label);
                break;

            default:
                type = 'transaction';
                label = $("[aria-controls='tabs-two-transaction']").text();
                callback(type, label);
                break;
        }
    }

    drawActivity(function (type, label) {
        ajax_get(url_activity + '?type=' + type, success, fail);

        function success(data) {
            if (data.data.length == 0) {
                var no_activities_img = $('#no_record_img_url').attr('value');
                $('#activity').removeAttr("class");
                document.getElementById("activity").innerHTML +=
                    `<div class="col-xs-10 col-sm-8 col-md-6 col-lg-5" style="display: block; margin: auto; height: auto; margin-top:2em">
                                <img src="` + no_activities_img + `" style="display: block; margin: auto; width: 70%; height: auto;">
                                <div style="text-align:center; margin-top:10px; margin-bottom:20px;">
                                <p  id="title" style="color: #77AC1D;">`
                                    +$('#lang_noActivities').val()+
                                `</p>
                                </div>
                            </div>`;

            } else {
                if (!$("#activity").hasClass("timeline timeline-6 mt-3")) {
                    $('#activity').addClass('timeline timeline-6 mt-3');
                }

                for (var i = 0; i < data.data.length; i++) {
                    document.getElementById("activity").innerHTML += '<div class="timeline-item align-items-start"> <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg">' + moment(data.data[i].created_at).locale(locale).fromNow() + '</div> <div class="timeline-badge"> <i class="fa fa-genderless  icon-xl" style="color:' + getRandomColor() + '; margin-right:50%;"></i> </div> <div class="font-weight-mormal font-size-lg timeline-content text-muted pl-3"> ' + data.data[i].content + '</div></div>';
                }
            }

            document.getElementById("activityLabel").innerHTML = label;
            hideGraphLoading("activityLoading");
        }

        function fail(data) {
            console.log(data);
        }
    });
}
