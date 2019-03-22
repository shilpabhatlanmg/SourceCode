@extends('adminLayout')

@section('content')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">
        <div class="forms">
            <h2 class="title1"> {{(!empty($objData->id))?'Edit Subscription':'Create Subscription' }}</h2>
            
            <div class="form-three widget-shadow">
                @include('common.admin.flash-message')

                {!!
                    Form::open(
                    array(
                    'name' => 'frmsave',
                    'id' => 'admin_subscription',
                    'url' => 'admin/subscription/'.(!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : ''),
                    'autocomplete' => 'off',
                    'class' => 'form-horizontal',
                    'files' => true
                    )
                    )
                    !!}
                    @section('editMethod')
                    @show


                    <div class="form-group">
                        <label for="focusedinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Plan Name *</label>
                        <div class="col-sm-8 col-xs-12">

                            {{ Form::text('plan_name', (!empty($objData) && is_object($objData) && !empty($objData->plan_name) ? $objData->plan_name : ''), ['class' => 'form-control', 'placeholder' => 'Enter Plan Name *']) }}

                            @if ($errors->has('plan_name'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('plan_name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="disabledinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Security People Allowed *</label>
                        <div class="col-sm-8 col-xs-12">
                            {{ Form::text('people_allow', (!empty($objData) && is_object($objData) && !empty($objData->people_allow) ? $objData->people_allow : ''), ['class' => 'form-control form-number', 'data' =>'people_allow', 'placeholder' => 'Enter Security People Allowed *']) }}

                            <span class="people_allow" style = "display:block;color:red;">

                            </span>

                            @if ($errors->has('people_allow'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('people_allow') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Building Allowed *</label>
                        <div class="col-sm-8 col-xs-12">
                            {{ Form::text('premises_allow', (!empty($objData) && is_object($objData) && !empty($objData->premises_allow) ? $objData->premises_allow : ''), ['class' => 'form-control form-number', 'data' =>'premises_allow', 'placeholder' => 'Enter Building Allowed *']) }}

                            <span class="premises_allow" style = "display:block;color:red;">

                            </span>

                            @if ($errors->has('premises_allow'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('premises_allow') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Duration *</label>
                        <div class="col-sm-4 col-xs-12 mr-bottom">
                            {{ Form::text('duration', (!empty($objData) && is_object($objData) && !empty($objData->duration) ? $objData->duration : ''), ['class' => 'form-control form-number', 'data' =>'duration', 'placeholder' => 'Enter Duration date *']) }}
                            <span class="duration" style = "display:block;color:red;">

                            </span>
                            @if ($errors->has('duration'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('duration') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="col-sm-4 col-xs-12 ">
                            {{Form::select('type',['' => '--select type--', 'monthly' => 'Monthly', 'yearly' => 'Yearly'], ( !empty($objData) && is_object($objData) && !empty($objData->type) ? $objData->type : ''),['id'=> 'address_city_id', 'class'=>'form-control address_city_id'])}}
                        </span>

                        @if ($errors->has('type'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('type') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Price *</label>
                    <div class="col-sm-8 col-xs-12">
                        {{ Form::text('price', (!empty($objData) && is_object($objData) && !empty($objData->price) ? $objData->price : ''), ['class' => 'form-control form-number', 'data' =>'price', 'placeholder' => 'Enter Price *']) }}

                        <span class="price" style = "display:block;color:red;">

                            @if ($errors->has('price'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('price') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Status *</label>
                        <div class="col-sm-8 col-xs-12">
                            {{Form::select('status',['Active' => 'Active', 'Inactive' => 'Inactive'], ( !empty($objData) && is_object($objData) && !empty($objData->status) ? $objData->status : ''),['id'=> 'status', 'class'=>'form-control address_city_id'])}}

                            @if ($errors->has('status'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('status') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-offset-2">
                     {{ Form::hidden('subscription_id', (!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : '')) }}

                     @if(isset($objData) && !empty($objData) && is_object($objData) && !empty($objData->id))
                     <button class="btn btn-primary">Update</button>
                     @else
                     <button class="btn btn-primary">Save</button>
                     @endif

                     <a href="{{route('subscription.index')}}">
                        <button class="btn btn-danger" type="button">Cancel</button>
                    </a>
                </div>

                {!! Form::close()!!}
            </div>

        </div>
    </div>
</div>

@endsection

@section('addtional_css')

@endsection

@section('jscript')

<script>
    $(function () {
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
        });
    });
</script>
@endsection
