<!-- Begin content things to do-->
<div class="m-content">
        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__body">
                <!-- ROW I -->
                <div class="row" style="cursor:pointer;">
                    <div align="center" class="col-md-3"
                        onclick="window.open('https://dev-engine.compro.co.id/app-management/shopping/transaction/{{$company_id}}');">
                        <div class="status-green">
                            <div class="count-green" id="count_transaction_1">0</div>
                            <div class="count-label-green">@lang('content.dashboard.Pending')</div>
                        </div>
                    </div>
                    <div align="center" class="col-md-3"
                        onclick="window.open('https://dev-engine.compro.co.id/app-management/shopping/transaction/{{$company_id}}');">
                        <div class="status-green">
                            <div class="count-green" id="count_transaction_2">0</div>
                            <div class="count-label-green">@lang('content.dashboard.InProcess')</div>
                        </div>
                    </div>
                    <div align="center" class="col-md-3"
                        onclick="window.open('https://dev-engine.compro.co.id/app-management/shopping/transaction/{{$company_id}}');">
                        <div class="status-green">
                            <div class="count-green" id="count_transaction_3">0</div>
                            <div class="count-label-green">@lang('content.dashboard.Settlement')</div>
                        </div>
                    </div>
                    <div align="center" class="col-md-3"
                        onclick="window.open('https://dev-engine.compro.co.id/app-management/shopping/transaction/{{$company_id}}');">
                        <div class="status-green">
                            <div class="count-green" id="count_transaction_4">0</div>
                            <div class="count-label-green">@lang('content.dashboard.Delivery')</div>
                        </div>
                    </div>
                </div>

                <!-- ROW II -->
                <div class="row" style="margin-top:30px; cursor:pointer;">
                    <div align="center" class="col-md-3" onclick="window.open('https://dev-engine.compro.co.id/app-management/shopping/transaction/{{$company_id}}');">
                        <div class="status-green">
                            <div class="count-green" id="count_transaction_5">0</div>
                            <div class="count-label-green">@lang('content.dashboard.Delivered')</div>
                        </div>
                    </div>
                    <div align="center" class="col-md-3" onclick="window.open('https://dev-engine.compro.co.id/app-management/shopping/transaction/{{$company_id}}');">
                        <div class="status-green">
                            <div class="count-green" id="count_transaction_6">0</div>
                            <div class="count-label-green">@lang('content.dashboard.Completed')</div>
                        </div>
                    </div>
                    <div align="center" class="col-md-3" onclick="window.open('https://dev-engine.compro.co.id/app-management/shopping/transaction/{{$company_id}}');">
                        <div class="status-green">
                            <div class="count-green" id="count_transaction_7">0</div>
                            <div class="count-label-green">@lang('content.dashboard.Cancelled')</div>
                        </div>
                    </div>
                    <div align="center" class="col-md-3" onclick="window.open('https://dev-engine.compro.co.id/app-management/shopping/transaction/{{$company_id}}');">
                        <div class="status-green">
                            <div class="count-green" id="count_transaction_8">0</div>
                            <div class="count-label-green">@lang('content.dashboard.Complain')</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- End content things to do-->