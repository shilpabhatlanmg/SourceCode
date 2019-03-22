@extends('adminLayout')

@section('content')

@section('pageTitle')
{{ (isset($title) && !empty($title) ? $title : '') }}
@endsection

<!-- main content start-->
<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">

        <h2 class="title1">Subscription Plan</h2>

        <div class="blank-page widget-shadow scroll" id="style-2 div1">
            @include('common.admin.flash-message')
            
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12 pd-none2">

                    <h3 class="divider-sec text-right">Current Plan</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Plan Name</th>
                                    <th>Renewed Date</th>
                                    <th>Expire Date</th>
                                    <th>Number Of Security Accounts</th>
                                    <th>Number Of Buildings</th>
                                    <th>Days For Which Subscription Is Valid</th>
                                    <th style="width: 143px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($currentPlanArrData) && is_object($currentPlanArrData) && count($currentPlanArrData) > 0)
                                <tr class="trCate">
                                    <td>{{ ((!empty($currentPlanArrData) && is_object($currentPlanArrData)) && !empty($currentPlanArrData->getSubscriptionDetail) && is_object($currentPlanArrData->getSubscriptionDetail) && isset($currentPlanArrData->getSubscriptionDetail->plan_name) ? $currentPlanArrData->getSubscriptionDetail->plan_name : '') }}</td>

                                    <td>{{ ((!empty($currentPlanArrData) && is_object($currentPlanArrData)) &&  isset($currentPlanArrData->created_at) ? $currentPlanArrData->created_at : '') }}</td>

                                    <td>{{ ((!empty($currentPlanArrData) && is_object($currentPlanArrData)) &&  isset($currentPlanArrData->expiry_date) ? $currentPlanArrData->expiry_date : '') }}</td>

                                    <td>{{ ((!empty($currentPlanArrData) && is_object($currentPlanArrData)) && !empty($currentPlanArrData->getSubscriptionDetail) && is_object($currentPlanArrData->getSubscriptionDetail) && isset($currentPlanArrData->getSubscriptionDetail->people_allow) ? $currentPlanArrData->getSubscriptionDetail->people_allow : '') }}</td>

                                    <td>{{ ((!empty($currentPlanArrData) && is_object($currentPlanArrData)) && !empty($currentPlanArrData->getSubscriptionDetail) && is_object($currentPlanArrData->getSubscriptionDetail) && isset($currentPlanArrData->getSubscriptionDetail->premises_allow) ? $currentPlanArrData->getSubscriptionDetail->premises_allow : '') }}</td>

                                    <td>{{ ((!empty($currentPlanArrData) && is_object($currentPlanArrData)) && !empty($currentPlanArrData->getSubscriptionDetail) && is_object($currentPlanArrData->getSubscriptionDetail) && isset($currentPlanArrData->getSubscriptionDetail->duration) ? $currentPlanArrData->getSubscriptionDetail->duration. ' '.$currentPlanArrData->getSubscriptionDetail->type : '') }}</td>

                                    @php
                                  
                                        $encryptId = \Crypt::encryptString($currentPlanArrData->id);

                                    @endphp
                                    
                                    <td class="actions center">
                                        <a href="{{ url('admin/subscriptions/card-detail/'.$encryptId) }}">
                                            <i class="fa fa-eye" aria-hidden="true" title="View Payment Method"></i>
                                        </a>
                                        |
                                        <a href="{{ url('admin/subscriptions/card/'.$encryptId) }}">
                                            <i class="fa fa-pencil" aria-hidden="true" title="Edit Payment Method"></i>
                                        </a>
                                        |
                                        <a href="#">
                                            <i class="fa fa-trash-o cancel_subscription" data-id="{{ !empty($currentPlanArrData) && is_object($currentPlanArrData) && !empty($currentPlanArrData->id) ? $encryptId : '' }}" data-type="cancelSubscription" aria-hidden="true" title="Cancel Subscription"></i>
                                        </a>
                                        |
                                        <a href="{{ url('admin/subscriptions/change/') }}">
                                            <i class="fa fa-exchange" aria-hidden="true" title="Change Subscription"></i>
                                        </a>
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="7" align='center'>
                                        <span class="label label-danger">No Current Plan Available !!!</span>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if(!empty($futurePlanArrData) && is_object($futurePlanArrData) && count($futurePlanArrData) > 0)

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12 pd-none2">

                    <h3 class="divider-sec text-right">Future Plan</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Plan Name</th>
                                    <th>Renewed Date</th>
                                    <th>Expire Date</th>
                                    <th>Number Of Security Accounts</th>
                                    <th>Number Of Buildings</th>
                                    <th>Days For Which Subscription Is Valid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($futurePlanArrData) && is_object($futurePlanArrData) && count($futurePlanArrData) > 0)
                                <tr class="trCate">

                                    <td>{{ ((!empty($futurePlanArrData) && is_object($futurePlanArrData)) && !empty($futurePlanArrData->getSubscriptionDetail) && is_object($futurePlanArrData->getSubscriptionDetail) && isset($futurePlanArrData->getSubscriptionDetail->plan_name) ? $futurePlanArrData->getSubscriptionDetail->plan_name : '') }}</td>

                                    <td>{{ ((!empty($futurePlanArrData) && is_object($futurePlanArrData)) &&  isset($futurePlanArrData->created_at) ? $futurePlanArrData->created_at : '') }}</td>

                                    <td>{{ ((!empty($futurePlanArrData) && is_object($futurePlanArrData)) &&  isset($futurePlanArrData->expiry_date) ? $futurePlanArrData->expiry_date : '') }}</td>

                                    <td>{{ ((!empty($futurePlanArrData) && is_object($futurePlanArrData)) && !empty($futurePlanArrData->getSubscriptionDetail) && is_object($futurePlanArrData->getSubscriptionDetail) && isset($futurePlanArrData->getSubscriptionDetail->people_allow) ? $futurePlanArrData->getSubscriptionDetail->people_allow : '') }}</td>

                                    <td>{{ ((!empty($futurePlanArrData) && is_object($futurePlanArrData)) && !empty($futurePlanArrData->getSubscriptionDetail) && is_object($futurePlanArrData->getSubscriptionDetail) && isset($futurePlanArrData->getSubscriptionDetail->premises_allow) ? $futurePlanArrData->getSubscriptionDetail->premises_allow : '') }}</td>

                                    <td>{{ ((!empty($futurePlanArrData) && is_object($futurePlanArrData)) && !empty($futurePlanArrData->getSubscriptionDetail) && is_object($futurePlanArrData->getSubscriptionDetail) && isset($futurePlanArrData->getSubscriptionDetail->duration) ? $futurePlanArrData->getSubscriptionDetail->duration. ' '.$futurePlanArrData->getSubscriptionDetail->type : '') }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="7" align='center'>
                                        <span class="label label-danger">No Future Plan Available !!!</span>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @endif
            
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12 pd-none2">

                    <h3 class="divider-sec text-right">Old Plan(s)</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered m-n" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Plan Name</th>
                                    <th>Expired Date</th>
                                    <th>Number Of Security Accounts</th>
                                    <th>Number Of Premises</th>
                                    <th>Days For Which Subscription Is Valid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($oldPlanArrData) && is_object($oldPlanArrData) && count($oldPlanArrData) > 0)
                                @foreach($oldPlanArrData as $key => $var)

                                <tr class="trCate">
                                    <td>{{ ($oldPlanArrData->currentpage()-1) * $oldPlanArrData->perpage() + $key + 1 }}</td>

                                    <td>
                                        {{ ((!empty($var) && is_object($var)) && !empty($var->getSubscriptionDetail) && is_object($var->getSubscriptionDetail) && isset($var->getSubscriptionDetail->plan_name) ? $var->getSubscriptionDetail->plan_name : '') }}
                                    </td>

                                    <td>{{ ((!empty($var) && is_object($var)) &&  isset($var->expiry_date) ? $var->expiry_date : '') }}</td>

                                    <td>{{ ((!empty($var) && is_object($var)) && !empty($var->getSubscriptionDetail) && is_object($var->getSubscriptionDetail) && isset($var->getSubscriptionDetail->people_allow) ? $var->getSubscriptionDetail->people_allow : '') }}</td>

                                    <td>{{ ((!empty($var) && is_object($var)) && !empty($var->getSubscriptionDetail) && is_object($var->getSubscriptionDetail) && isset($var->getSubscriptionDetail->premises_allow) ? $var->getSubscriptionDetail->premises_allow : '') }}</td>

                                    <td>{{ ((!empty($var) && is_object($var)) && !empty($var->getSubscriptionDetail) && is_object($var->getSubscriptionDetail) && isset($var->getSubscriptionDetail->duration) ? $var->getSubscriptionDetail->duration. ' '.$var->getSubscriptionDetail->type : '') }}</td>
                                </tr>

                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6" align='center'>
                                        <span class="label label-danger">No Old Plan Available !!!</span>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                {!! $oldPlanArrData->appends($_GET)->links() !!}
            </div>

        </div>
    </div>
</div>

@endsection



@section('addtional_css')
@endsection

@section('jscript')
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/delete-confirm.js')}}"></script>
@endsection
