@extends('layouts.master')
@section('title', 'Out of stock')
@section('content')

<div class="m-content">
    <!--Start::Section-->
    <!-- Begin Datatable -->
    <div class="m-portlet m-portlet--tab">
        <div class="m-portlet__body" id="datatable_container">
            <div class="form-group m-form__group">
                <div class="form-group m-form__group">
                    <div class="col-md-6" style="padding-left:0px;">
                        <label for="searchDateRange">@lang('content.outofstock.Name'): </label>
                    </div>
                    <div class="input-group date col-md-6" style="padding-left:0px">
                        <input class="form-control m-input m-input--air col-md-9" type="text" id="search_name" name="search_name" placeholder="@lang('content.outofstock.Searchbyproductname')" autocomplete="off">
                    </div>
                    <div class="col-md-6" style="padding-left:0px; padding-top:15px;">
                        <button class="btn custom-radius m-btn--air btn-secondary m-btn--boldest" style="background: #77AC1D; color: white;" onclick="searchProduct()">
                            <i class="fa fa-search" style="color:white;"></i> @lang('content.outofstock.Search')
                        </button>
                    </div>
                </div>     
            </div>
        <!!--table::content -->
            <table class="datatable datatable-bordered datatable-head-custom" id="outofstockList"></table>
        <!!--endTable::content -->
        </div>
    </div>
    <!-- End Datatable -->
<!--End::Section-->
</div>

<!--startinput:: language -->
    <input type="hidden" disabled id="lang_noRecord" value="@lang('content.noData.noRecord')">
    <input type="hidden" disabled id="lang_noActivities" value="@lang('content.noData.noActivities')">
    <input type="hidden" disabled id="lang_PleaseWait" value="@lang('content.loading.PleaseWait')">
<!--endinput:: language -->
<!--startinput:: API -->
    <input type="hidden" disabled id="url_productList" value="http://{{$_SERVER['SERVER_NAME']}}/api/product/{{$company_id}}">
    <input type="hidden" disabled id="url_edit" value="https://dev-engine.compro.co.id/app-management/app-content/view/detail-post/{{$company_id}}/">
    {{--  <input type="hidden" disabled id="url_edit" value="http://{{$_SERVER['SERVER_NAME']}}/api/product_log/{{$company_id}}">  --}}
<!--endinput:: API -->
<!--begin::Load JS -->
    <!--BEGIN::: load javascript -->
        @extends('layouts.master_loadJs')
    <!--END::: load javascript -->
    <script src="{{ asset('custom/js/table/productTable.js?n=1') }}"></script>
<!--end::Load JS -->
@endsection
