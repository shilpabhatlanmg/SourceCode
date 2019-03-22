@extends('adminLayout')

@section('content')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')

    <div class="main-page">
        <div class="tables">

            <h4>Payment History</h4>

            <div class="table-responsive bs-example widget-shadow">


                <div class="table-responsive">
                    <table class="table table-bordered table-condensed" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Transaction id</th>
                                <th>Plan Name</th>
                                <th>Date/Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($arrData)>0 && !empty($arrData))

                            @foreach($arrData as $key => $var)

                            <tr class="trCate">
                                <td>{{ ($arrData->currentpage()-1) * $arrData->perpage() + $key + 1 }}</td>

                                <td>{{ !empty($var) && is_object($var) && !empty($var->getPaymentDetail->transaction_id) ? $var->getPaymentDetail->transaction_id : '' }}</td>



                                <td>{{ !empty($var) && is_object($var) && !empty($var->getSubscriptionDetail->plan_name) ? $var->getSubscriptionDetail->plan_name . ' (' . ucfirst($var->getSubscriptionDetail->type) .')' : '' }}</td>

                                <td>{{ (!empty($var) && is_object($var) && !empty($var->created_at) ?(($var->created_at != null)? \Carbon\Carbon::parse($var->created_at)->format('d-M-Y h:i:s'):\Carbon\Carbon::parse($var->created_at)->format('d-M-Y h:i:s')) : '---') }}</td>

                                <td data-title="Action" class="actions fr-btn center action-center">

                                    <a href="{{ url('admin/invoice/detail/'. \Crypt::encrypt($var->getPaymentDetail->transaction_id)) }}">
                                        <i class="fa fa-eye" aria-hidden="true" title="View Subscription Detail"></i>
                                    </a>

                                </td>
                            </tr>

                            @endforeach
                            @else
                            <tr>
                                <td colspan="9" align='center'>
                                    <span class="label label-danger">No Record Found !!!</span>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $arrData->render() }}
        </div>
    </div>
</div>

@endsection

@section('addtional_css')
@endsection

@section('jscript')
@endsection