@extends('layouts.master')
@section('title', 'Things to do')
@section('content')

<link href="{{ asset('custom/css/dashboard_responsive.css') }}" rel="stylesheet" type="text/css" />

<!-- Begin sub_header transaction-->
    <div class="m-sub_header ">
        <div class="row tab" style="margin-top:20px; ">
            <div align="left" class="col-md-6">
                <h3 class="m-sub_header__title " style="border-right: 0px;">
                    @lang('content.dashboard.ThingsToDo')
                </h3>
            </div>
            <div align="right" class="col-md-6">
                <a class="more-btn" href="https://dev-engine.compro.co.id/app-management/shopping/transaction/61373">@lang('content.dashboard.More')</a>
            </div>
        </div>
    </div>
<!-- End sub_header things to do-->

<!-- Begin content transaction-->
    @include('status.green')
<!-- End content things to do-->

<!-- Begin sub_header store info-->
    <div class="m-sub_header ">
        <div class="row tab" style="margin-top:20px; ">
            <div align="left" class="col-md-6">
                <h3 class="m-sub_header__title " style="border-right: 0px;">@lang('content.dashboard.StoreInformation')
                </h3>
            </div>
            <div align="right" class="col-md-6">
            <a class="more-btn" href="/storeinfo/{{$company_id}}">@lang('content.dashboard.More')</a>
            </div>
        </div>
    </div>
<!-- End sub_header store info-->

<!-- Begin content store info-->
    <div class="m-content">
        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__body" id='m-portlet__body_storeinfo'>
                <div class="row" id="m_sortable_portlets_storeinfo">
                    <div class="col-md-4" id="strinfo">
                        <!-- left side -->
                        <div id="salesToday_block">
                            <div style="margin-top:20px" class="row">
                                <h4>@lang('content.dashboard.SalesToday')</h4>
                            </div>
                            <div style="margin-top:10px" class="row">
                                <div class="col-md-12">
                                    <h1 id="tPenjualan" >
                                        @lang('content.loading.Loading')...
                                    </h1>
                                    <div style="margin-top:20px; margin-left:5px" class="row">
                                        <h6 style="margin-right:50px" id='vsYesterday'>@lang('content.dashboard.vsYesterday')</h6>
                                        <h6 id="pPenjualan">@lang('content.loading.Loading')...</h6>
                                    </div>
                                    <div style=" margin-left:5px" class="row">
                                        <h7 id="lPenjualan"></h7>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1" id="storeinfo_divide"></div>
                        <div id="transactionsToday_block">
                            <div style="margin-top:20px" class="row">
                                <h4>@lang('content.dashboard.TransactionsToday')</h4>
                            </div>
                            <div style="margin-top:10px" class="row">
                                <div class="col-md-12">
                                    <h1 id="tPesanan" >
                                        @lang('content.loading.Loading')...
                                    </h1>
                                    <div style="margin-top:20px; margin-left:5px" class="row">
                                        <h6 style="margin-right:50px" id='vsYesterday'>@lang('content.dashboard.vsYesterday')</h6>
                                        <h6 id="pPesanan">@lang('content.loading.Loading')...</h6>
                                    </div>
                                    <div style="margin-left:5px" class="row">
                                        <h7 id="lPesanan"></h7>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8" id='storeinfo_graph_block'>
                        <div id="storeInfoChart_top"></div>
                        <div id="storeInfoChart"></div>
                        <div id="chartLoading" style="padding: 60px 0">
                            <center>
                                <div class="loading-box tab">
                                    <div class="row">
                                        <div class="col-sm-9 tab">
                                            <h7 class="tab">@lang('content.loading.PleaseWait')...</h7>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="loader"></div>
                                        </div>
                                    </div>
                                </div>
                            </center>
                        </div>
                        <center>
                            <h6 id="periodeLabel"></h6>
                        </center>
                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- End content store info-->

<!-- Begin sub_header Graph-->
    <div class="m-sub_header ">
        <h3 class="m-sub_header__title " style="border-right: 0px;">@lang('content.dashboard.Graph')</h3>
    </div>
<!-- End sub_header Graph-->
<!-- BEGIN: For content graph -->
    <div class="m-content" >
        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__body">
                <div class="form-group m-form__group">
                    <div class="row" id="">
                        <div class="tab col-md-1">@lang('content.dashboard.Periode')</div>
                            <input class="form-control m-input dropdown-toggle col-md-4 m_selectpicker" id="periode" data-toggle="dropdown" style="cursor:pointer;"  readonly/>
                            <ul class="dropdown-menu col-md-3" role="menu" aria-labelledby="periode" id="periodeDropdown" >
                              <li class="list-select" id='realtime'><a class="dropdown-item active" href="" data-toggle="tooltip" data-placement="right" title="">@lang('content.calendar.Realtime')</a></li>
                              <li class="list-select" id='yesterday'><a class="dropdown-item" href="" data-toggle="tooltip" data-placement="right" title="">@lang('content.calendar.Yesterday')</a></li>
                              <li class="list-select" id='7days'><a class="dropdown-item"  href="" data-toggle="tooltip" data-placement="right" title="">@lang('content.calendar.Last7Days')</a></li>
                              <li class="list-select" id='30days'><a class="dropdown-item"  href="" data-toggle="tooltip" data-placement="right" title="">@lang('content.calendar.Last30Days')</a></li>
                              <li class="dropdown-divider"></li>
                              <li class="list-select-calendar" id='day'><a class="dropdown-item"  href="">@lang('content.calendar.Day')</a></li>
                              <li class="list-select-calendar" id='week'><a class="dropdown-item"  href="">@lang('content.calendar.Week')</a></li>
                              <li class="list-select-calendar" id='month'><a class="dropdown-item"  href="">@lang('content.calendar.Month')</a></li>
                              <li class="list-select-calendar" id='year'><a class="dropdown-item"  href="">@lang('content.calendar.Year')</a></li>
                            </ul>
                          
                    </div>
                      
                        <div id='dp'                    class="calendarHodler col-md-"></div>
                        <div id='week-picker-wrapper'   class="calendarHodler col-md-"></div>
                        <div id='mp'                    class="calendarHodler col-md-"></div>
                        <div id='yp'                    class="calendarHodler col-md-"></div>

                    <div class="row" id="" style="margin-top: 2%;">
                        <div class="tab col-md-1" >@lang('content.dashboard.Category')</div>
                        <select id="category"class="category form-control m-input m-input--air col-md-4 m_selectpicker" style="cursor: pointer;" name="category">
                            <option value="1" selected>@lang('content.dashboard.UserRegister')</option>
                            <option value="2">@lang('content.dashboard.SendPushNotification')</option>
                            <option value="3">@lang('content.dashboard.SendEmail')</option>
                            <option value="4">@lang('content.dashboard.StorageUsage')</option>
                        </select>
                    </div>
                    
                    <div class="card-body" style="margin-top: 2%" id="dashboard_graph">
                        <div class="tab-content" id="tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tabs-one-user" role="tabpanel" aria-labelledby="tabs-one-user-tab">
                                <div class="row">
                                    <h4 id="graphLabel" class="col-md-6"></h4>
                                    <h5 id="graph_total_data" align='right' class="col-md-6"></h5>
                                </div>
                                <div id="graph"></div>
                                <!--BEGIN:: loading box -->
                                    <div id="graphLoading" style="padding: 60px 0">
                                        <center>
                                            <div class="loading-box tab">
                                                <div class="row">
                                                    <div class="col-sm-9 tab">
                                                        <h7 class="tab">@lang('content.loading.PleaseWait')...</h7>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <div class="loader"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </center>
                                    </div>
                                <!--END:: loading box -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- END: For content graph -->

<!-- Begin sub_header Activities-->
    <div class="m-sub_header ">
        <h3 class="m-sub_header__title m-sub_header__title--separator" style="border-right: 0px;">@lang('content.dashboard.Activities')</h3>
        <button class="btn btn-light" data-toggle="dropdown" style="float: right;"> 
            <i class="fas fa-ellipsis-h"></i>
        </button>
        <ul class="nav nav-tabs" id="tabs-two-tab" role="tablist">
            <!-- dropdown pilihan activities -->
            <ul class="dropdown-menu" id="tabs-two-tab">
                <li><a onclick="loadActivities(1);" class="dropdown-item active" id="tabs-two-transaction-tab" data-toggle="pill" href="# " role="tab" aria-controls="tabs-two-transaction" aria-selected="true"><i class="fa fa-luggage-cart ml-1"></i>@lang('content.dashboard.Transaction')</a></li>
                <li><a onclick="loadActivities(2);" class="dropdown-item" id="tabs-two-user-tab" data-toggle="pill" href="# " role="tab" aria-controls="tabs-two-user" aria-selected="false"><i class="fas fa-users ml-1"></i>@lang('content.dashboard.Users')</a></li>
                <li><a onclick="loadActivities(3);" class="dropdown-item" id="tabs-two-member-tab" data-toggle="pill" href="# " role="tab" aria-controls="tabs-two-member" aria-selected="false"><i class="fas fa-address-book ml-1"></i>@lang('content.dashboard.Membership')</a></li>
                <li><a onclick="loadActivities(4);" class="dropdown-item" id="tabs-two-reservation-tab" data-toggle="pill" href="# " role="tab" aria-controls="tabs-two-reservation" aria-selected="false"><i class="fas fa-clipboard-list ml-1"></i>@lang('content.dashboard.Reservations')</a></li>
                <li><a onclick="loadActivities(5);" class="dropdown-item" id="tabs-two-feedback-tab" data-toggle="pill" href="# " role="tab" aria-controls="tabs-two-feedback" aria-selected="false"><i class="fab fa-wpforms ml-1"></i></i>@lang('content.dashboard.FormFeedbacks')</a></li>
                <li><a onclick="loadActivities(6);" class="dropdown-item" id="tabs-two-comment-tab" data-toggle="pill" href="# " role="tab" aria-controls="tabs-two-comment" aria-selected="false"><i class="fas fa-comments ml-1"></i>@lang('content.dashboard.CommentsOnPosts')</a></li>
            </ul>
        </ul>
    </div>
<!-- End sub_header -->
<!-- BEGIN: For content Activities -->
    <div class="m-content">
        <div class="m-portlet">
            <div class="m-portlet__body">
                <!--begin::Body-->
                <div class="card-body pt-4">
                    <!--begin::Timeline-->
                    <!--begin::tab content-->
                    <div class="tab-content" id="tabs-two-tabContent">
                        <div class="tab-pane fade show active" id="tabs-two-transaction" role="tabpanel" aria-labelledby="tabs-two-transaction-tab">
                            <div style="margin-bottom:35px">
                                <h5 id="activityLabel">Transaction</h5>
                            </div>
                            <div id="activity" class="timeline timeline-6 mt-3">
                                <!-- //diisi melalui javascript activities.js -->
                            </div>
                        </div>

                        <!--BEGIN:: loading box -->
                            <div id="activityLoading" style="padding: 60px 0">
                                <center>
                                    <div class="loading-box tab">
                                        <div class="row">
                                            <div class="col-sm-9 tab">
                                                <h7 class="tab">@lang('content.loading.PleaseWait')...</h7>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="loader"></div>
                                            </div>
                                        </div>
                                    </div>
                                </center>
                            </div>
                        <!--END:: loading box -->

                        <hr class="rounded">
                        <div align="center">
                            <a href="https://dev-engine.compro.co.id/app-management/activities/{{$company_id}}" target="_blank" style="color:#77ac1d;">
                                <h5>@lang('content.dashboard.LoadMore')</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Tab content-->
            <!--end::Timeline-->
        </div>
        <!--end: Card Body-->
    </div>
<!-- END: For content Activities -->

<!--startinput:: language -->
    <input type="hidden" disabled id="lang_Sales" value="@lang('content.storeinfo.Sales')">
    <input type="hidden" disabled id="lang_Transactions" value="@lang('content.dashboard.Transactions')">
    <input type="hidden" disabled id="lang_noRecord" value="@lang('content.noData.noRecord')">
    <input type="hidden" disabled id="lang_noActivities" value="@lang('content.noData.noActivities')">
    <input type="hidden" disabled id="lang_Today" value="@lang('content.calendar.Today')">
    <input type="hidden" disabled id="lang_Realtime" value="@lang('content.calendar.Realtime')">
    <input type="hidden" disabled id="lang_Day" value="@lang('content.calendar.Day')">
    <input type="hidden" disabled id="lang_Week" value="@lang('content.calendar.Week')">
    <input type="hidden" disabled id="lang_Month" value="@lang('content.calendar.Month')">
    <input type="hidden" disabled id="lang_Year" value="@lang('content.calendar.Year')">
    <input type="hidden" disabled id="lang_At" value="@lang('content.dashboard.At')">
<!--endinput:: language -->

<!--startinput:: API -->
    <input type="hidden" disabled id="url_count_transaction" value="http://{{$_SERVER['SERVER_NAME']}}/api/countTransaction/{{$company_id}}">
    <input type="hidden" disabled id="url_graph_storeInfo" value="http://{{$_SERVER['SERVER_NAME']}}/api/realtime/{{$company_id}}">
    <input type="hidden" disabled id="url_graph" value="http://{{$_SERVER['SERVER_NAME']}}/api/dashboardGraph/{{$company_id}}">
    <input type="hidden" disabled id="url_activity" value="http://{{$_SERVER['SERVER_NAME']}}/api/activity/{{$company_id}}">
    <input type="hidden" disabled id="url_storage_usage" value="http://{{$_SERVER['SERVER_NAME']}}/api/getStorageUsage/{{$company_id}}">
<!--endinput:: API -->

<!--begin::Load JS -->
    <!--BEGIN::: load javascript -->
        @extends('layouts.master_loadJs')
    <!--END::: load javascript -->
        <script src="{{ asset('custom/js/graph/dashboard/dashboardHighlights.js') }}"></script>
        <script src="custom/js/activities/activitiesLoader.js"></script>

        <!-- apex chart js script -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="{{ asset('custom/js/moment-with-locales.js') }}"></script>
        <script src="{{ asset('custom/js/graph/dashboard/dashboardGraphsLoader.js') }}"></script>
        <script src="{{ asset('custom/js/graph/dashboard/dashboardStoreInfo.js') }}"></script>

<!--end::Load JS -->
<script>
    $(function() {
        var x = window.matchMedia("(max-width: 768px)");
        var x2 = window.matchMedia("(max-width: 375px)");
        var x3 = window.matchMedia("(max-width: 280px)");

        window.onresize = function(event) { 
            //location.reload();
            storeinfo_mediasize_change(x.matches, x2.matches, x3.matches);
        };

        function storeinfo_mediasize_change(sizeMatch, sizeMatch2, sizeMatch3){
            if (sizeMatch || sizeMatch2 || sizeMatch3){
                if ($("div#m_sortable_portlets_storeinfo div#strinfo.col-md-4").length > 0) {
                    $('div#salesToday_block').unwrap();
                }

                if ($("div#m_sortable_portlets_storeinfo div#wrap.col-md-6").length == 0) {
                    $('div#salesToday_block').addClass('storeinfo col-md-5').css('border-color','#3737FF');
                    $('div#transactionsToday_block').addClass('storeinfo col-md-5').css({'border-color':'#00E396', 'margin-left':'5px'});
                }

                $('div#storeInfoChart_top').css('padding-top','30px');
                $('div#storeinfo_graph_block').removeAttr('class').addClass('col-md-12').css('padding','0px');
            } else {
                $('div#transactionsToday_block, div#salesToday_block').removeAttr('class').removeAttr('style');
                $('div#storeinfo_graph_block').removeAttr('class').addClass('col-md-8').removeAttr('style');
                $('div#storeInfoChart_top').removeAttr('style');

                if ($("div#m_sortable_portlets_storeinfo div#strinfo.col-md-4").length == 0){
                    $('div#salesToday_block, div#storeinfo_divide, div#transactionsToday_block').wrapAll(`<div id="strinfo" class="col-md-4" style="width:252.27px;height:336.5px;"></div>`);
                }
            }
        }
        storeinfo_mediasize_change(x.matches);
    });
</script>
@endsection