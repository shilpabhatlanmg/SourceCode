@extends('adminLayout')
@section('content')
@section('pageTitle')
{{ (isset($title) && !empty($title) ? $title : '') }}
@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">
        <div class="forms">
            <h2 class="title1">Invoice Detail</h2>

            @if(!empty($invoiceData) && count($invoiceData) > 0)

            <div class="form-three widget-shadow"  style="overflow:hidden">
                @foreach($invoiceData as $invoiceData)

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Amount</label>
                    <div class="col-sm-8 col-md-9 col-xs-12">
                        {{ (!empty($invoiceData->amount_paid) ? \Config::get('constants.CURRENCY').$invoiceData->amount_paid/100 : '') }}
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Currency</label>
                    <div class="col-sm-8 col-md-9 col-xs-12">
                        {{ (!empty($invoiceData->currency) ? $invoiceData->currency : '') }}
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Status </label>
                    <div class="col-sm-8 col-md-9 col-xs-12">
                        {{ (!empty($invoiceData->paid) &&  $invoiceData->paid == 'true'? 'Paid' : 'N/A') }}
                    </div>
                </div>

                
                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Start Date </label>
                    <div class="col-sm-8 col-md-9 col-xs-12">


                        {{ (!empty($invoiceData->period_start) ?  date('d-M-Y h:i:s', $invoiceData->period_start) : '') }}
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">End Date </label>
                    <div class="col-sm-8 col-md-9 col-xs-12">
                        {{ (!empty($invoiceData->period_end) ?  date('d-M-Y h:i:s', $invoiceData->period_end) : '') }}
                    </div>
                </div>
                
                <div class="br_btm" ></div>

                @endforeach  

            </div>
            @else
            <div><center>
                <span class="label label-danger">No Invoice Found</span></center></div>
            @endif

        </div>
    </div>
</div>

@endsection
@section('addtional_css')
@endsection

@section('jscript')

@endsection
