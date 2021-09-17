@extends('layouts.master')
@section('title', 'Transaction')
@section('content')

<!-- BEGIN::: content transaction -->
    <div class="m-content">
        <!--Start::Section-->
            <div class="m-portlet__body">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a onclick="transactionTable(1);" class="btn custom-radius m-btn--air btn-secondary m-btn--boldest active" id="custom-tabs-one-new-tab"
                            data-toggle="pill" href="#custom-tabs-one-new" role="tab" aria-controls="custom-tabs-one-new"
                            aria-selected="true" data-status = "5">
                            pending <span class="badge bg-warning" id="length_new_order">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a onclick="transactionTable(5);" class="btn custom-radius m-btn--air btn-secondary m-btn--boldest" id="custom-tabs-one-process-tab"
                            data-toggle="pill" href="#custom-tabs-one-process" role="tab"
                            aria-controls="custom-tabs-one-process" aria-selected="false" data-status = "7">
                            In Process <span class="badge bg-primary" id="length_need_process_order">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a onclick="transactionTable(3);" class="btn custom-radius m-btn--air btn-secondary m-btn--boldest" id="custom-tabs-one-finished-tab"
                            data-toggle="pill" href="#custom-tabs-one-finished" role="tab"
                            aria-controls="custom-tabs-one-finished" aria-selected="false">
                            Settlement <span class="badge bg-success" id="length_completed_order">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a onclick="transactionTable(6);" class="btn custom-radius m-btn--air btn-secondary m-btn--boldest" id="custom-tabs-one-unpaid-tab"
                            data-toggle="pill" href="#custom-tabs-one-unpaid" role="tab" aria-controls="custom-tabs-one-unpaid"
                            aria-selected="false">
                            Delivery <span class="badge bg-info" id="length_unpaid_order">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a onclick="transactionTable(12);" class="btn custom-radius m-btn--air btn-secondary m-btn--boldest" id="custom-tabs-one-cancelled-tab"
                            data-toggle="pill" href="#custom-tabs-one-cancelled" role="tab"
                            aria-controls="custom-tabs-one-cancelled" aria-selected="false">
                            Delivered <span class="badge bg-danger" id="length_cancelled_order">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a onclick="transactionTable(7);" class="btn custom-radius m-btn--air btn-secondary m-btn--boldest" id="custom-tabs-one-cancelled-tab"
                            data-toggle="pill" href="#custom-tabs-one-cancelled" role="tab"
                            aria-controls="custom-tabs-one-cancelled" aria-selected="false">
                            Completed <span class="badge bg-danger" id="length_cancelled_order">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a onclick="transactionTable(8);" class="btn custom-radius m-btn--air btn-secondary m-btn--boldest" id="custom-tabs-one-cancelled-tab"
                            data-toggle="pill" href="#custom-tabs-one-cancelled" role="tab"
                            aria-controls="custom-tabs-one-cancelled" aria-selected="false">
                            Cancelled <span class="badge bg-danger" id="length_cancelled_order">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a onclick="transactionTable(13);" class="btn custom-radius m-btn--air btn-secondary m-btn--boldest" id="custom-tabs-one-cancelled-tab"
                            data-toggle="pill" href="#custom-tabs-one-cancelled" role="tab"
                            aria-controls="custom-tabs-one-cancelled" aria-selected="false">
                            Complained <span class="badge bg-danger" id="length_cancelled_order">0</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- BEGIN::: Datatable -->
                <div class="m-portlet m-portlet--tab">
                    <div class="m-portlet__body" id="datatable_container">
                        <div class="form-group m-form__group">
                            <a class="btn custom-radius m-btn--air btn-secondary m-btn--boldest ml-auto"
                                style="background: #77AC1D; color: white;" href="/transactionExport">
                                <i class="fas fa-file-export" style="color:white; margin-right: 10px;"></i> Export File
                            </a>
                        </div>

                        <div class="form-group m-form__group">
                            <label for="searchUsername">Search</label>
                            <input class="form-control m-input m-input--air col-md-4" type="text" id="search_name"
                                name="search_name" placeholder="Search by Transaction ID, name ..." autocomplete="off">
                        </div>

                        <div class="form-group m-form__group">
                            <label for="searchDateRange">Date Range: </label>
                            <div class="input-group date col-md-5" style="padding-left:0px;">
                                <input class="form-control m-input m-input--air col-md-9" placeholder="From Date to Date..."
                                    name="search_daterange" id="search_daterange" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group m-form__group">
                            <button type="submit" class="btn custom-radius m-btn--air btn-secondary m-btn--boldest mr-auto" style="background: #77AC1D; color: white;" onclick="searchTransaction()">
                                <i class="fa fa-search" style="color:white; margin-right: 10px;"></i> Search 
                            </button>
                        </div>                     
                    <!!--table:: tab content -->
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-one-new" role="tabpanel" aria-labelledby="custom-tabs-one-new-tab">
                                    <table class="table table-striped- table-bordered table-hover table-checkable" id="transactionTable"></table>
                                </div>
                            </div>
                    <!!--endTable:: tab content -->
                    </div>
                </div>
            <!-- END::: Datatable -->
        <!--END:::Section-->
    </div>
<!-- END:: content transaction -->

<div class="modal fade" id="modal-edit" data-menu-id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 9999999">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="form-group modal-title">
                <h3 class="m--font-boldest engine_content_select_icon_title"  style="margin-bottom: 15px;">Choose Agent</h3>
                <div class="line"></div>
            </div>
            <div class="modal-body" id="modal-body">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover" id="table-transaction-detail">
                        <thead>
                            <th>Choose Agent</th>
                            <th>No</th>
                            <th>City</th>
                            <th>Agent Name</th>
                            <th>Contact Name</th>
                            <th>Contact Phone Number</th>
                        </thead>
                        <tbody id="list_agent">
                            
                        </tbody>
                    </table>
                </div>

                <div style="margin-bottom: 15px; text-align: right;">
                    <div class="m-accordion m-accordion--default" id="use_type" role="tablist">
                        <div role="tab" id="use_type_head" aria-expanded="false">
                            <button class="btn custom-radius m-btn--air btn-secondary m-btn m-btn--custom m-btn--label-primary m-btn--boldest" id="btn-pickup-request" style="margin-right: 30px; color: #77AC1D;">
                                Submit                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" disabled id="url_delete" value="{{env('APP_URL')}}" + "/api/deleteTransaction">
<input type="hidden" disabled id="url_status_change" value="{{env('APP_URL')}}" + "/api/editStatus">

<!-- BEGIN:: sub_header  graph -->
    <div class="m-sub_header ">
        <h3 class="m-sub_header__title m-sub_header__title--separator" style="border-right: 0px;">Transaction Graph</h3>
        <button class="btn btn-light" data-toggle="dropdown" style="float: right;">
            <i class="fas fa-ellipsis-h"></i>
        </button>
        <ul class="nav nav-tabs" id="tabs-one-tab" role="tablist">
            <ul class="dropdown-menu" id="tabs-one-tab">
                <li>
                    <a onclick="transactionGraph(1);" class="active" id="tabs-one-transaction-tab" data-toggle="pill" href="#tabs-one-transaction" role="tab" aria-controls="tabs-one-transaction" aria-selected="true">
                        <i class="fas fa-clipboard-check ml-1"></i>Completed transaction
                    </a>
                </li>
                <li>
                    <a onclick="transactionGraph(2);" id="tabs-one-cancel-tab" data-toggle="pill" href="#tabs-one-cancel" role="tab" aria-controls="tabs-one-cancel" aria-selected="false">
                        <i class="fas fa-handshake-slash ml-1"></i>Orders cancelled
                    </a>
                </li>
            </ul>
        </ul>
    </div>
<!-- END:: sub_header  graph -->
<!-- BEGIN:: For content graph -->
    <div class="m-content">
        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__body">
                <div class="form-group m-form__group">
                    <div class="form-group m-form__group">
                        <label for="searchDateRange">Date Range: </label>
                        <div class="input-group date col-md-6" style="padding-left:0px;">
                            <input class="form-control m-input m-input--air col-md-9" placeholder="From Date to Date..."
                                name="search_daterange" id="search_daterange2" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
    
                            <button class="btn custom-radius m-btn--air btn-secondary m-btn--boldest ml-4" style="background: #77AC1D; color: white;" onclick="searchGraph()">
                                <i class="fa fa-search" style="color:white; margin-right: 10px;"></i> Search 
                            </button>
                        </div>
                    </div>     
                </div>
                
                <div class="card-body">
                    <div class="tab-content" id="tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="tabs-one-transaction" role="tabpanel" aria-labelledby="tabs-one-transaction-tab">
                            <h4 id="graphLabel"></h4>
                            <p id="precentageGraph"></p>
                            <div id="transactionChart"></div>
                            <!--BEGIN:: loading box -->
                                <div id="graphLoading" style="padding: 60px 0">
                                    <center>
                                        <div class="loading-box tab">
                                            <div class="row">
                                                <div class="col-sm-9 tab">
                                                    <h7 class="tab">Please wait...</h7>
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
<!-- END:: For content graph -->

<!--BEGIN:::Load JS -->
    <!--BEGIN:::: load javascript -->
        @extends('layouts.master_loadJs')
    <!--END:::: load javascript -->
        {{--  <script src="custom/js/table/transactionTable.js?n=1"></script>  --}}
        <script src="custom/js/table/tableT.js?n=1"></script>
        <script src="custom/js/graph/transaction.js?n=2"></script>

        <script>
            
        </script>
<!--END:::Load JS -->
@endsection