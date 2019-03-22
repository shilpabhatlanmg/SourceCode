@extends('adminLayout')

@section('content')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')

    <div class="main-page">
        <div class="tables">

            <h4>Plan Select</h4>

            <div class="membership_bx">

                @if(count($allPlanArrData)>0 && !empty($allPlanArrData))
                @foreach($allPlanArrData as $key => $var)

                {!!
                    Form::open(
                    array(
                    'name' => 'frmsave',
                    'id' => 'select-plan',
                    'url' => 'admin/subscriptions/payment',
                    'autocomplete' => 'off',
                    'class' => 'form-horizontal',
                    'files' => false,
                    'method' => 'get'
                    )
                    )
                    !!}

                    <div class="col-md-4 col-sm-6 col-xs-6 bx">
                        <div class="full-bx">

                            <span class="head">{{ ((!empty($var) && is_object($var)) && !empty($var->plan_name) && isset($var->plan_name) ? $var->plan_name : '') }}</span>
                            <ul>
                                <li>Allows {{ ((!empty($var) && is_object($var)) && !empty($var->people_allow) && isset($var->people_allow) ? $var->people_allow : '') }} Security Team Members.</li>

                                <li>Allows {{ ((!empty($var) && is_object($var)) && !empty($var->premises_allow) && isset($var->premises_allow) ? $var->premises_allow : '') }} Buildings.</li>

                                <li>{{ ((!empty($var) && is_object($var)) && !empty($var->duration) && isset($var->duration) ? $var->duration. ' '.$var->type : '') }} Payments.</li>

                            </ul>

                            <div class="price_txt">${{ ((!empty($var) && is_object($var)) && !empty($var->price) && isset($var->price) ? $var->price : '') }}</div>
                            {{ Form::hidden('plan_id', (!empty($var) && is_object($var) && !empty($var->id) ? \Crypt::encryptString($var->id) : '')) }}

                            {{ Form::hidden('organization_subscription_id', (!empty($organization_subscription_id ) ? $organization_subscription_id : '')) }}
                            <button class="selt_txt">Select</button>
                        </div>
                    </div>

                    {!! Form::close()!!}

                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('addtional_css')
    @endsection

    @section('jscript')

    @endsection