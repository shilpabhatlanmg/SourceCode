@extends('adminLayout')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

@section('content')
<!-- main content start-->
<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">
        <h2 class="title1">{{ (isset($title) && !empty($title) ? $title : '') }}</h2>
        <div class="blank-page widget-shadow scroll" id="style-2 div1">
            @include('common.admin.flash-message')
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">

                    {!! Form::open(array('name' => 'frmLogin',
                                        'id' => 'admin_user',
                                        'url' => 'admin/admin-user/'.(!empty($objData) && !empty($objData->id) ? \Crypt::encryptString($objData->id) : ''),
                                        'autocomplete' => 'on',
                                        'class' => 'form-horizontal',
                                        'files' => true))!!}
                    <div class="row">
                        <div class="col-md-3"><strong>Name</strong></div>
                        <div class="col-md-6">
                            {{ Form::text('name', (!empty($objData) && !empty($objData->name) ? $objData->name : ''), ['class' => 'form-control user', 'placeholder' => 'User Name']) }}
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Email </strong></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 pd-none">

                            {{ Form::email('email', (!empty($objData) && !empty($objData->email) ? $objData->email : ''), ['class' => 'form-control user', 'placeholder' => 'Enter your email address']) }}

                            @if ($errors->has('email'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Street</strong></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 pd-none">
                            {{ Form::text('street', (!empty($objData) && !empty($objData->address) ? $objData->address : ''), ['class' => 'form-control user', 'placeholder' => 'Enter Street Address']) }}

                            @if ($errors->has('street'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('street') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Country</strong></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 pd-none">

                            {{Form::select('country_id',[]+@$arrCountry->pluck('name','id')->toArray(), (!empty($objData) && is_object($objData) && !empty($objData->country_id) ? $objData->country_id : ''),['id'=> 'country_id', 'class'=>'form-control country_id'])}}

                            @if ($errors->has('country_id'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('country_id') }}</strong>
                            </span>
                            @endif

                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>State</strong></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 pd-none">

                            {{Form::select('state_id',[''=>'Select State']+@$arrState->pluck('name','id')->toArray(), (!empty($objData) && is_object($objData) && !empty($objData->state_id) ? $objData->state_id : ''),['id'=> 'state_id','data-type'=>'city-not-required', 'class'=>'form-control state_id chosen-select'])}}

                            @if ($errors->has('state_id'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('state_id') }}</strong>
                            </span>
                            @endif

                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>City</strong></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 pd-none">

                            @if(isset($arrCityData) && !empty($arrCityData))
                            {{Form::select('city_id',[''=>'Select city']+@$arrCityData->pluck('name','id')->toArray(), (!empty($objData) && is_object($objData) && !empty($objData->city_id) ? $objData->city_id : ''),['id'=> 'address_city_id', 'class'=>'form-control address_city_id chosen-select'])}}
                            @else
                            {{Form::select('city_id',[''=>'Select city'], (!empty($objData) && is_object($objData) && !empty($objData->city_id) ? $objData->city_id : ''),['id'=> 'address_city_id', 'class'=>'form-control address_city_id chosen-select'])}}
                            @endif

                            @if ($errors->has('city_id'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('city_id') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Zip Code</strong></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 pd-none">
                            {{ Form::text('address_zip', (!empty($objData) &&  !empty($objData->zip_code) ? $objData->zip_code : ''), ['class' => 'form-control user', 'placeholder' => 'Enter address zip']) }}

                            @if ($errors->has('address_zip'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('address_zip') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Phone</strong></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 pd-none">

                            {{ Form::text('address_phone', (!empty($objData) && !empty($objData->phone) ? $objData->phone : ''), ['class' => 'form-control user', 'placeholder' => 'Enter address phone']) }}

                            @if ($errors->has('address_phone'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('address_phone') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 pd-left">
                            <p>
                                <input type="submit" value="Save" class="btn btn-warning">
                                <a href="{{route('admins.index')}}">
                                    <button class="btn btn-danger" type="button">Cancel</button>
                                </a>
                        </div>
                    </div>
                    {!! Form::close()!!}
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
@section('addtional_css')
@endsection

@section('jscript')
<script type="text/javascript" src="{{asset('public/admin_assets/js/app-js/city.js')}}"></script>

<script>

$(document).ready(function () {

    $('input[name="address_zip"]').mask('00000', {placeholder: "_____"});
    $('input[name="address_phone"]').mask('(000) 000-0000', {placeholder: "(___) ___-____"});


});
</script>
@endsection
