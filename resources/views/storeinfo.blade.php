@extends('layouts.master')
@section('title', 'Store Information')
@section('content')

<link href="{{ asset('custom/css/storeinfo_responsive.css') }}" rel="stylesheet" type="text/css" />

<html lang="{{ config('app.locale') }}">
<!-- <input type="text" data-bind="daterangepicker: dateRange, daterangepickerOptions: { maxDate: moment() }"/> -->
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    
    <div class="m-sub_header ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-sub_header__title " style=" color:#074E59;">@lang("content.storeinfo.StoreInformation")</h3>
            </div>
        </div>
    </div>
    <div class="m-content">
        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__body" id="datatable_container">
                
                <div class="row tab filter-item" id="m_sortable_portlets">

                    <div class="col-5">
                        <div class="row filter-item-periode">
                            <div class="tab col-4 periode-label">@lang("content.dashboard.Periode")</div>

                            <input class=" form-control m-input dropdown-toggle col-md-8 m_selectpicker periode-dropdown" id="periode" data-toggle="dropdown" style="cursor:pointer;"  readonly/>
                            <ul class="dropdown-menu col-md-3" role="menu" aria-labelledby="periode" id="periodeDropdown" >
                                <li class="list-select" id='realtime'><a class="dropdown-item active" href="" data-toggle="tooltip" data-placement="right" title="">@lang("content.calendar.Realtime")</a></li>
                                <li class="list-select" id='yesterday'><a class="dropdown-item" href="" data-toggle="tooltip" data-placement="right" title="">@lang("content.calendar.Yesterday")</a></li>
                                <li class="list-select" id='7days'><a class="dropdown-item"  href="" data-toggle="tooltip" data-placement="right" title="">@lang("content.calendar.Last7Days")</a></li>
                                <li class="list-select" id='30days'><a class="dropdown-item"  href="" data-toggle="tooltip" data-placement="right" title="">@lang("content.calendar.Last30Days")</a></li>
                                
                                <li class="dropdown-divider"></li>
                                
                                <li class="list-select-calendar" id='day'><a class="dropdown-item" href="">@lang("content.calendar.Day")</a></li>
                                <li class="list-select-calendar" id='week'><a class="dropdown-item" href="">@lang("content.calendar.Week")</a></li>
                                <li class="list-select-calendar" id='month'><a class="dropdown-item" href="">@lang("content.calendar.Month")</a></li>
                                <li class="list-select-calendar" id='year'><a class="dropdown-item" href="">@lang("content.calendar.Year")</a></li>
                            </ul>
                            
                            <div id='dp'                    class="calendarHolder" style=""></div>
                            <div id='week-picker-wrapper'   class="calendarHolder" style=""></div>
                            <div id='mp'                    class="calendarHolder" style=""></div>
                            <div id='yp'                    class="calendarHolder" style=""></div>
                        </div>
                    </div>
                    
                    <div class="col-4">
                        <div class="row filter-item-status">
                            <div class="tab col-md-5 status-label">Status</div>

                            <select style="z-index: 0" id="status" class="form-control m-input m-input--air col-md-7 m_selectpicker status-dropdown">
                                <option class="dropdown-item" selected="selected" value="0">@lang("content.storeinfo.All")</option>
                                <option class="dropdown-item" value="7">@lang("content.storeinfo.Completed")</option>
                                <option class="dropdown-item" value="5">@lang("content.storeinfo.In Process")</option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="col-3">
                        
                        <div class="row filter-item-download">
                            <a class="btn" style="background: #77AC1D; color: white;"  href="/transactionExport">
                                <div class="download-label">
                                    <div class="download-text">
                                        <i class="fas fa-file-export" style="color:white; margin-right: 10px;"></i>
                                        @lang("content.storeinfo.Download")
                                    </div>
                                </div>
                                <div class="download-icon">
                                    <i class="fas fa-file-export" style="color:white;"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__body" id="datatable_container">
                <div class="row">
                    <!-- PENJUALAN -->
                    <div class="col-sm-4">
                        <div style="border-color:#008FFB" class="storeinfo">
                            <div >
                                <h5 style="color:#777777">@lang("content.storeinfo.Sales")</h5>
                            </div>
                            <div >
                                <h3 id="tPenjualan">@lang('content.loading.Loading')</h3>
                            </div>
                            <div>
                                <div style="" class="storeinfo-content row tab">
                                <div class="col-sm-8" ><h6 class="labelPeriode"  id="label">@lang('content.loading.Loading')</h6></div>
                                <div align="right" class="col-sm-4"><h6 style="font-weight: bold;" id="xPenjualan">@lang('content.loading.Loading')</h6></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- PESANAN -->
                    <div class="col-sm-4">
                        <div style="border-color:#00E396" class="storeinfo">
                            <div >
                                <h5 style="color:#777777">@lang("content.storeinfo.Orders")</h5>
                            </div>
                            <div >
                                <h3 id="tPesanan">@lang('content.loading.Loading')</h3>
                            </div>
                            <div>
                                <div style="" class="storeinfo-content row tab">
                                <div class="col-sm-8"><h6 class="labelPeriode" id="label">@lang('content.loading.Loading')</h6></div>
                                <div align="right" class="col-sm-4"><h6 style="font-weight: bold;" id="xPesanan">@lang('content.loading.Loading')</h6></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- PENJUALAN PER PESANAN -->
                    <div class="col-sm-4">
                        <div style="border-color:#FEB019" class="storeinfo">
                            <div >
                                <h5 style="color:#777777">@lang("content.storeinfo.Sales")/@lang("content.storeinfo.Orders")</h5>
                            </div>
                            <div >
                                <h3 id="tPpp">@lang('content.loading.Loading')</h3>
                            </div>
                            <div>
                                <div style="" class="storeinfo-content row tab">
                                <div class="col-sm-8"><h6 class="labelPeriode" id="label">@lang('content.loading.Loading')</h6></div>
                                <div align="right" class="col-sm-4"><h6 style="font-weight: bold;" id="xPpp">@lang('content.loading.Loading')</h6></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="" class="graph-holder row">
                    <div class="card-body">
                        <h4 id="periodeLabel"></h4>
                        <!-- loading div area -->
                        <div id="graphLoading" style="padding: 60px 0">
                            <!-- loading box -->
                            <center>
                                <div class="loading-box tab">
                                    <div class="row">
                                        <div class="col-sm-9 tab">
                                            <h7 class="tab">@lang('content.loading.PleaseWait')</h7>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="loader"></div>
                                        </div>
                                    </div>
                                </div>
                            </center>
                        </div>
                        <div id="chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--startinput:: language -->
    <input type="hidden" disabled id="lang_Sales" value="@lang('content.storeinfo.Sales')">
    <input type="hidden" disabled id="lang_Orders" value="@lang('content.storeinfo.Orders')">
    <input type="hidden" disabled id="lang_vsYesterday" value="@lang('content.storeinfo.vsYesterday')">
    <input type="hidden" disabled id="lang_vsLastWeek" value="@lang('content.storeinfo.vsLastWeek')">
    <input type="hidden" disabled id="lang_vsLastMonth" value="@lang('content.storeinfo.vsLastMonth')">
    <input type="hidden" disabled id="lang_vsLastYear" value="@lang('content.storeinfo.vsLastYear')">
    <input type="hidden" disabled id="lang_noRecord" value="@lang('content.noData.noRecord')">
    <input type="hidden" disabled id="lang_noActivities" value="@lang('content.noData.noActivities')">
    <input type="hidden" disabled id="lang_PleaseWait" value="@lang('content.loading.PleaseWait')">
    <input type="hidden" disabled id="lang_Today" value="@lang('content.calendar.Today')">
    <input type="hidden" disabled id="lang_Realtime" value="@lang('content.calendar.Realtime')">
    <input type="hidden" disabled id="lang_Day" value="@lang('content.calendar.Day')">
    <input type="hidden" disabled id="lang_Week" value="@lang('content.calendar.Week')">
    <input type="hidden" disabled id="lang_Month" value="@lang('content.calendar.Month')">
    <input type="hidden" disabled id="lang_Year" value="@lang('content.calendar.Year')">
    <input type="hidden" disabled id="lang_Transaction" value="@lang('content.storeinfo.Transaction')">
    <input type="hidden" disabled id="lang_Transactions" value="@lang('content.storeinfo.Transactions')">
<!--endinput:: language -->
<!--startinput:: API -->
    <input type="hidden" disabled id="url_cityList" value="http://{{$_SERVER['SERVER_NAME']}}/api/citiesTransaction/{{$company_id}}">
    <input type="hidden" disabled id="url_cityList_export" value="http://{{$_SERVER['SERVER_NAME']}}/api/citiesExport/{{$company_id}}">
    <input type="hidden" disabled id="url_home" value="http://{{$_SERVER['SERVER_NAME']}}">
    <input type="hidden" disabled id="company_id" value="{{$company_id}}">
    
<!--endinput:: API -->

@extends('layouts.master_loadJs')

<script>
    var appUrl = '{{env('APP_URL')}}';
</script>
<!-- storeinfo graph config apex chart -->
<script src="{{ asset('custom/js/table/storeinfoTable.js') }}"></script>
<script src="{{ asset('custom/js/moment-with-locales.js') }}"></script>
<script src="{{ asset('custom/js/graph/storeinfo.js') }}"></script>
{{--  <!-- <script src="custom/js/graph/storeinfo copy.js"></script> -->  --}}
<script src="{{ asset('custom/js/table/export/citiesTable_export.js?n=1') }}"></script>
<script src="{{ asset('daterangepicker/newpicker.js') }}"></script>

<script>
    $(function() {
    });
</script>

</html>
@endsection