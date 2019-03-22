@extends('adminLayout')

@section('content')

@section('pageTitle')
{{ $title }}
@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')

    <div class="main-page">
        <div class="tables">

            <h4>Transactions List</h4>

            <div class="table-responsive bs-example widget-shadow">
                <h4>Transactions List</h4>

                @include('common.admin.flash-message')
                <div class="table-responsive">

                    <div class="row">

                        {{ Form::open(array('method' => 'get', 'autocomplete' => 'off', 'id'=>'organisation_id')) }}

                        <div class="col-md-2 col-sm-2 col-xs-6 pd-left-rq full-wid">

                            {{ Form::text('search', app('request')->input('search'), ['placeholder'=>'Transaction Search...', 'class'=>'form-control']) }}
                        </div>

                        <div class="col-md-2 col-sm-2 col-xs-6 pd-left-rq full-wid">

                            {{ Form::text('from_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'Enter From Date *']) }}
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('from_date') }}</strong>
                            </span>
                        </div>

                        <div class="col-md-2 col-sm-2 col-xs-6 pd-left-rq full-wid">

                            {{ Form::text('to_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'Enter To Date *']) }}
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('to_date') }}</strong>
                            </span>
                        </div>
                        <div class="col-lg-2  col-md-2 col-xs-6 col-sm-4 pd-btn full-wid2">
                            {{ Form::submit('Submit', ['class'=>'btn btn-primary']) }}
                            <a href="{{url('/admin/transaction-details')}}" class="btn btn-default">Reset</a>
                        </div>

                        {{Form::close()}}

                    </div>


                    <table class="table table-bordered table-condensed" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>


                                <th>Transaction Id</th>
                                <th>Organization Name</th>
                                <th>Plan Name</th>
                                <th>Amount</th>
                                <th>@sortablelink('created_at', 'Created at')</th>
                                <th>Status</th>
                                <th>Action</th>
                               </tr>
                        </thead>
                        <tbody>
                            @if(count($transactionList)>0 && !empty($transactionList))
                            @foreach($transactionList as $key => $var)

                            <tr class="trCate">
                                <td data-id="{{$var->id}}">{{ ($transactionList->currentpage()-1) * $transactionList->perpage() + $key + 1 }}</td>

                                <td>
                                    {{ (!empty($var->txn_id) ? $var->txn_id : '') }}
                                </td>

                                <td>
                                    {{ (!empty($var->getOrganizationName->name) ? $var->getOrganizationName->name : '') }}
                                </td>

                                <td>
                                    <div class ="nowrap">
                                        {{(!empty($var->getSubscriptionName->plan_name) ? $var->getSubscriptionName->plan_name : '') }}
                                    </div>

                                </td>

                                <td>
                                    <div class ="nowrap">
                                        {{(!empty($var->getSubscriptionName->price)  ? $var->getSubscriptionName->price : '') }}
                                    </div>
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($var->created_at)->format('d-M-Y h:i:s')}}
                                </td>
                                <td>
                                   {{(!empty($var->status)  ? $var->status : '') }}
                                </td>
                                
                                <td>
                                    <a href="{{ url('admin/invoice/detail/'. \Crypt::encrypt($var->txn_id)) }}" class="btns" title="View Subscription Detail">
                                        <i class="fa fa-eye" aria-hidden="true">&nbsp;</i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach
                            @else
                            <tr>
                                <td colspan="8" align='center'>
                                    <span class="label label-danger">No Record Found !!!</span>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {!! $transactionList->appends($_GET)->links() !!}

        </div>
    </div>
</div>
@endsection

@section('addtional_css')
<style>

    td.details-control {
        background: url('../admin_assets/images/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.details td.details-control {
        background: url('../admin_assets/images/details_close.png') no-repeat center center;
    }

</style>
@endsection

@section('jscript')
<!-----------datatable handling js-------------->
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/datatable/buttons.html5.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js"></script>

<!-----------newsletter listing  js-------------->
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/aid-request.js')}}"></script>

<script>
$(function () {
    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
});

$(document).ready(function () {
    $('input[name="from_date"]').mask('0000-00-00', {placeholder: "YYYY-MM-DD"});
    $('input[name="to_date"]').mask('0000-00-00', {placeholder: "YYYY-MM-DD"});
});
</script>
@endsection
