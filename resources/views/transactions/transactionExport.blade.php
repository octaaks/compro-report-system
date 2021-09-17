@extends('layouts.master')
@section('title', 'Export Data Transaction')
@section('content')
<!-- BEGIN: sub_header -->
<!-- Begin sub_header -->
<div class="m-sub_header ">
    <div class="form-group m-form__group">
        <a href="/transaction" style="text-decoration:none;">
            <button id='' type="button" class="btn_submit btn-change btn btn-success">
                <i class="fa fa-arrow-left" style="margin-right: 10px;"></i>Back
            </button>
        </a>
    </div>
</div>
<!-- End sub_header -->
<!-- END: sub_header -->
<div class="m-content">

    <!-- BEGIN: For content multilanguage -->
    <!-- END: For content multilanguage -->

    <!--Start::Section-->
    <!-- Begin Datatable -->

    <div class="m-portlet m-portlet--tab" id="custom_delivery_estimation">
        <div class="m-portlet__body">

            <div style="padding-left: 15px;">
                <div class="col-md-12" style="margin-top:30px">
                        <div class="form-group m-form__group">
                            <label for="searchDateRange">Date Range (max 30 days)<span class="text-danger">*</span> </label>
                            <div class="input-group date col-md-4" style="padding-left:0px;">
                                <input class="form-control m-input" placeholder="From Date to Date..."  name="search_daterange" id="search_daterange" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group m-form__group">
                            <label for="searchUsername">Status <span class="text-danger">*</span> </label>
                            <select class="form-control m-input m-input--air col-md-4" name="status" id="searchStatus">
                                <option value="all" selected>All</option>
                                <option value="new_order">New Orders</option>
                                <option value="need_process_order">In Process Orders</option>
                                <option value="completed_order">Completed Orders</option>
                                <option value="unpaid_order">Unpaid Orders</option>
                                <option value="cancelled_order">Cancelled Orders</option>
                            </select>
                        </div>
                        <!-- <div class="m-form__group form-group">
                            <label for="" class=''>Select File Type</label>
                            <div class="m-radio-list">
                                <label class="m-radio" class=''>
                                    <input type="radio" name="file_type" value="xls" checked> excel spreadsheet (xls)
                                    <span></span>
                                </label>
                                <label class="m-radio" class=''>
                                    <input type="radio" name="file_type" value="csv"> csv
                                    <span></span>
                                </label>
                            </div>
                        </div> -->
                    <div class="form-group m-form__group" style="text-align: right; margin-top:10px;">
                        <button class="btn_submit btn-change btn btn-primary" onclick="transactionExport()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--BEGIN:::: load javascript -->
        @extends('layouts.master_loadJs')
    <!--END:::: load javascript -->
    <script src="custom/js/table/export/transactionTable_export.js?n=1"></script>
@endsection