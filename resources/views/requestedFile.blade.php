@extends('layouts.master')
@section('title', 'Requested File Progress')
@section('content')
<div class="m-content">
    <div class="m-portlet m-portlet--tab">
        <div class="m-portlet__body table-responsive" id="datatable_container">
            <div class="form-group m-form__group">
                <label for="searchUsername">Date Range: </label>
                <div class="input-group date col-md-4" style="padding-left:0px;">
                    <input class="form-control m-input" placeholder="From Date to Date..." name="search_daterange" id="search_daterange" readonly>
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                    </div>
                </div>
            </div>

            <div class="form-group m-form__group">
                <label for="searchUsername">Type: </label>
                <select class="form-control m-input m-input--air col-md-4" name="search_type" id="search_type">
                    <option value="all" selected>All</option>
                    <option value="xls">Excel Spreadsheet (xls)</option>
                    <option value="csv">CSV</option>
                </select>
            </div>

            <div class="form-group m-form__group">
                <button class="btn custom-radius m-btn--air btn-secondary m-btn--boldest" style="background: #77AC1D; color: white;" onclick="searchFile()">
                    <i class="fa fa-search" style="color:white; margin-right: 10px;"></i> Search            </button>
            </div>
        
            <table class="table table-bordered custom-table-css" id="requestedFile" style="display: block;"></table>
        </div>
    </div>
</div>
<!-- End Datatable -->

<input type="hidden" disabled id="url_fileList" value="http://{{$_SERVER['SERVER_NAME']}}/api/requestedFile/{{$company_id}}">
<input type="hidden" disabled id="url_delete" value="http://{{$_SERVER['SERVER_NAME']}}/api/delete_requestedFile/{{$company_id}}">
<input type="hidden" disabled id="url_download" value="http://{{$_SERVER['SERVER_NAME']}}/api/download_requestedFile/{{$company_id}}">
<input type="hidden" disabled id="no_record_img_url" value="https://d181jp1bcm67qh.cloudfront.net/images/content/no-record-found.svg">

<!--BEGIN:::: load javascript -->
@extends('layouts.master_loadJs')
<!--END:::: load javascript -->
<script src="{{ asset('custom/js/table/requestedFileTable.js?n=1') }}"></script>
@endsection