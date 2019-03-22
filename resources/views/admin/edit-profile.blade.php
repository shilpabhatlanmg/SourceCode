@extends('adminLayout')

@section('pageTitle')

    {{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

@section('content')

<!-- main content start-->
<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">
        <h2 class="title1">Edit Profile</h2>
        <div class="blank-page widget-shadow scroll" id="style-2 div1">

            <div class="row">
                <!--<div class="col-md-3">
                    <?php $path = 'public/storage/admin/profile_image/' . (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) ? Auth::guard('admin')->user()->user_image : ''); ?>

                    @if(!empty(Auth::guard('admin')->user()->user_image) && file_exists($path))
                    <img src="{{ asset($path) }}" class="img-responsive" height="50" width="50" alt="{{ (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) ? Auth::guard('admin')->user()->user_image : '') }}">
                    @else
                    <img src="{{ asset('public/admin_assets/images/user-img.png') }}" height="50" width="50" class="img-responsive" alt="no-image">
                    @endif
                </div>-->
                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    @include('common.admin.flash-message')
                    {!!
                        Form::open(
                        array(
                        'name' => 'admin_edit_profile',
                        'id' => 'admin_edit_profile',
                        'route' => 'admin.update.profile',
                        'autocomplete' => 'on',
                        'class' => 'form-horizontal',
                        'files' => true
                        )
                        )
                        !!}
                        <div class="row">

                            @php
                             if($roles->name == \Config::get('constants.PLATFORM_ADMIN')){

                                $title = 'Name';
                             } else {
                                    $title = 'Organization Name';
                                }

                            @endphp



                            <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>{{ $title }} *</strong></div>
                            <div class="col-md-9 col-sm-8 col-xs-12 pd-none">
                                
                                {{ Form::text('name', (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->name) ? Auth::guard('admin')->user()->name : ''), ['class' => 'form-control user', 'placeholder' => 'Enter name *']) }}

                                @if ($errors->has('name'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                    <!--<div class="row">
                        <div class="col-md-3"><strong>Gender</strong></div>
                        <div class="col-md-6">
                            {{ Form::radio('gender', 'M', (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && Auth::guard('admin')->user()->gender == 'M' ? true : '')) }}
                            <span  >Male</span>

                            {{ Form::radio('gender', 'F', (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && Auth::guard('admin')->user()->gender == 'F' ? true : '')) }}
                            <span >Female</span> 

                            {{ Form::radio('gender', 'O', (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && Auth::guard('admin')->user()->gender == 'O' ? true : '')) }}
                            <span >Other</span> 
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('gender') }}</strong>
                            </span>
                        </div>
                    </div>-->

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>Address *</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">

                            {{ Form::textarea('address',(!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->address) ? Auth::guard('admin')->user()->address : ''),['class'=>'form-control','rows'=>'2']) }}

                            @if ($errors->has('address'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>Country</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">

                            {{Form::select('country_id',[]+@$arrCountry->pluck('name','id')->toArray(), (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->country_id) ? Auth::guard('admin')->user()->country_id : ''),['id'=> 'country_id', 'class'=>'form-control country_id'])}}

                            @if ($errors->has('country_id'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('country_id') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>State *</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">

                            {{Form::select('state_id',[''=>'Select State *']+@$arrState->pluck('name','id')->toArray(), (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->state_id) ? Auth::guard('admin')->user()->state_id : ''),['id'=> 'state_id', 'class'=>'form-control state_id chosen-select'])}}
                            
                            @if ($errors->has('state_id'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('state_id') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>City *</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">

                            @if(isset($arrCityData) && !empty($arrCityData) && count($arrCityData) > 0)
                            {{Form::select('city_id',[''=>'Select city *']+@$arrCityData->pluck('name','id')->toArray(), (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->city_id) ? Auth::guard('admin')->user()->city_id : ''),['id'=> 'address_city_id', 'class'=>'form-control address_city_id chosen-select'])}}
                            @else
                            {{Form::select('city_id',[''=>'Select city *'], (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->city_id) ? Auth::guard('admin')->user()->city_id : ''),['id'=> 'address_city_id', 'class'=>'form-control address_city_id chosen-select'])}}
                            @endif

                            @if ($errors->has('city_id'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('city_id') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>Zip code *</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">
                            {{ Form::text('zip_code', (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->zip_code) ? Auth::guard('admin')->user()->zip_code : ''), ['class' => 'form-control user', 'placeholder' => 'Enter zip code *']) }}
                            
                            @if ($errors->has('zip_code'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('zip_code') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>Primary email id</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">
                            {{ Form::email('email', (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->email) ? Auth::guard('admin')->user()->email : ''), ['class' => 'form-control user', 'placeholder' => 'Enter email address', 'readonly' => true]) }}

                            @if ($errors->has('email'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>Primary Contact number *</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">
                            {{ Form::text('phone', (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->phone) ? Auth::guard('admin')->user()->phone : ''), ['class' => 'form-control user', 'placeholder' => 'Enter mobile no *']) }}

                            @if ($errors->has('phone'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <!--<div class="row">
                        <div class="col-md-3"><strong>Image Upload</strong></div>
                        <div class="col-md-6">
                            {{ Form::file('user_image', ['class' => 'form-control']) }}
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('user_image') }}</strong>
                            </span>
                        </div>
                    </div>-->
                        
                    @if(!empty($arr_poc_detail) && is_object($arr_poc_detail) && count($arr_poc_detail) > 0 && ($roles->name == \Config::get('constants.ORGANIZATION_ADMIN') || $roles->name == \Config::get('constants.PLATFORM_ADMIN')))
                    @php 
                    $x = 1;
                    @endphp

                    @foreach($arr_poc_detail as $poc_data)
                    <div class="row parentss">
                        <div class="col-md-4 col-sm-4 col-xs-12 pd-none ">
                         @if($x == 1)
                         <label class="lb-head">POC name</label>
                         @endif

                         <div class="col-xs-12 pd-less p-name">
                            <div class="row">
                                {!! Form::text('poc_name[]', (!empty($poc_data) && is_object($poc_data) && !empty($poc_data->poc_name) > 0 ? $poc_data->poc_name : ''), ['class' => 'form-control', 'placeholder' => 'Enter name']) !!}
                                
                                @if ($errors->has('poc_name.0'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('poc_name.0') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-12 pd-none ">
                        @if($x == 1)
                        <label class="lb-head ">POC contact number</label>
                        @endif
                        <div class="col-xs-12 pd-less p-number">

                            {!! Form::text('poc_contact_no[]', (!empty($poc_data) && is_object($poc_data) && !empty($poc_data->poc_contact_no) ? $poc_data->poc_contact_no : ''), ['class' => 'form-control', 'placeholder' => 'Enter contact number']) !!}

                            @if ($errors->has('poc_contact_no.0'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('poc_contact_no.0') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-12 pd-none">
                        @if($x == 1)
                        <label class="lb-head ">POC email id:</label>
                        @endif
                        <div class="col-xs-12 pd-less p-mail">

                            {!! Form::email('poc_email[]', (!empty($poc_data) && is_object($poc_data) && !empty($poc_data->poc_email) ? $poc_data->poc_email : ''), ['class' => 'form-control', 'placeholder' => 'Enter email']) !!}

                            @if ($errors->has('poc_email.0'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('poc_email.0') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    @if($x != 1)
                    <a href="javascript:void(0)" style="margin: 0px 0px 0px 554px" p_id="{{ $poc_data->id }}" class="delete-poc label label-danger">Remove</a>
                    {{ Form::input('hidden', 'poc_id[]', (!empty($poc_data) && is_object($poc_data) && !empty($poc_data->id) ? $poc_data->id : ''), ['readonly' => 'readonly']) }}

                    @elseif($x==1)

                    {{ Form::input('hidden', 'poc_id[]', (!empty($poc_data) && is_object($poc_data) && !empty($poc_data->id) ? $poc_data->id : ''), ['readonly' => 'readonly']) }}

                    @endif

                    @php 
                    $x++;
                    @endphp


                </div>
                
                @endforeach

                @else

                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12 pd-none ">
                        <label class="lb-head">POC name</label>

                        <div class="col-xs-12 pd-less p-name">
                            <div class="row">
                                {!! Form::text('poc_name[]', null, ['class' => 'form-control', 'placeholder' => 'Enter name']) !!}

                                @if ($errors->has('poc_name.0'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('poc_name.0') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-12 pd-none">
                       <label class="lb-head ">POC contact number</label>
                       <div class="col-xs-12 pd-less p-number">

                        {!! Form::text('poc_contact_no[]', null, ['class' => 'form-control', 'placeholder' => 'Enter contact number']) !!}

                        @if ($errors->has('poc_contact_no.0'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('poc_contact_no.0') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-4 col-sm-4 col-xs-12 pd-none">
                    <label class="lb-head ">POC email id:</label>
                    <div class="col-xs-12 pd-less p-mail">
                        {!! Form::email('poc_email[]', null, ['class' => 'form-control', 'placeholder' => 'Enter email']) !!}

                        @if ($errors->has('poc_email.0'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('poc_email.0') }}</strong>
                        </span>
                        @endif
                    </div>

                </div>
            </div>

            @endif
            

            <span class="dsh_add_more"> <a href="javascript:void(0)" >Add More</a></span>

            <div class="save-changes">
                <input type="submit" name="submits" class="btn btn-warning" value="Save Changes">
            </div>

            {!! Form::close()!!}
        </div>
                 <?php $i = 0; ?>
                    <input type='hidden' class="org-option" value="{{$i}}">
                    
    </div>

</div>
</div>
</div>

@endsection

@section('addtional_css')
@endsection

@section('jscript')
<script type="text/javascript" src="{{asset('/admin_assets/js/app-js/city.js')}}"></script>
<script>
    /*var poc_temp = '<div class="row parentss">'+
    '<div class="col-md-4 col-sm-4 col-xs-12 pd-none">'+
    '<div class="col-xs-12 pd-less p-name">'+
    '<div class="row">'+
    '{!! Form::text('poc_name[]', null, ['class' => 'form-control', 'placeholder' => 'Enter name']) !!}'+
    '</div>'+
    '</div>'+
    '</div>'+

    '<div class="col-md-4 col-sm-4 col-xs-12 pd-none">'+
    '<div class="col-xs-12 pd-less p-number">'+

    '{!! Form::text('poc_contact_no[]', null, ['class' => 'form-control', 'placeholder' => 'Enter contact number']) !!}'+
    '</div>'+
    '</div>'+

    '<div class="col-md-4 col-sm-4 col-xs-12 pd-none">'+
    '<div class="col-xs-12 pd-less p-mail">'+
    '{!! Form::email('poc_email[]', null, ['class' => 'form-control', 'placeholder' => 'Enter email']) !!}'+
    '</div>'+
    '</div>'+
    '<a href="javascript:void(0)" style="margin: 0px 0px 0px 554px" class="poc-remove delete-poc label label-danger">Remove</a>'+
    '{{ Form::input('hidden', 'poc_id[]',null, ['readonly' => 'readonly']) }}'+
    '</div>';*/


    $(".dsh_add_more").on('click', function(e){
        var value = parseInt($(".org-option").val()) + 1;
        var poc_temp = '<div class="row parentss">' +
            '<div class="col-md-4 col-sm-4 col-xs-12 pd-none">' +
            '<div class="col-xs-12 pd-less p-name">' +
            '<div class="row">' +
            '<input class="form-control lettersonly" placeholder="Enter name" name="poc_name['+ value + ']" type="text">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-4 col-sm-4 col-xs-12 pd-none">' +
            '<div class="col-xs-12 pd-less p-number">' +
            '<input class="form-control " minlength="14" placeholder="(___) ___-____" name="poc_contact_no[' + value + ']" type="text" maxlength="14">' +
            '</div>' +
            '</div>' +
            '<div class="col-md-4 col-sm-4 col-xs-12 pd-none">' +
            '<div class="col-xs-12 pd-less p-mail">' +
            '<input class="form-control email" placeholder="Enter email" name="poc_email[' + value + ']" type="email">' +
            '</div>' +
            '</div>' +
            '<a href="javascript:void(0)" style="margin: 0px 0px 0px 554px" class="poc-remove delete-poc label label-danger">Remove</a>' +
            '<input readonly="readonly" name="poc_id['+ value + ']" type="hidden"></div>';
        e.preventDefault();
         $('.org-option').val(value);
        $(this).before(poc_temp);
    });
</script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/organization.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin_assets/js/app-js/city.js')}}"></script>

<script>

$(document).ready(function(){

    $('input[name="zip_code"]').mask('00000', {placeholder: "_____"});
    $('input[name="phone"]').mask('(000) 000-0000', {placeholder: "(___) ___-____"});
    $('input[name="poc_contact_no[]"]').mask('(000) 000-0000', {placeholder: "(___) ___-____"});

    });

</script>

@endsection