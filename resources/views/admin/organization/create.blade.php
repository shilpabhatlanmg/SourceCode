@extends('adminLayout')
@section('content')
@section('pageTitle')
{{ (isset($title) && !empty($title) ? $title : '') }}
@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">
        <div class="forms">
            <h2 class="title1"> {{(!empty($objData->id))?'Edit Organization':'Create Organization' }}</h2>
            <!--<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Open modal for @mdo</button>-->

            <div class="form-three widget-shadow">

                {!!
                Form::open(
                array(
                'name' => 'frmsave',
                'id' => 'admin_organization',
                'url' => 'admin/organization/'.(!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : ''),
                'autocomplete' => 'off',
                'class' => 'form-horizontal',
                'files' => false
                )
                )
                !!}
                @section('editMethod')
                @show


                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Organization Name</label>
                    <div class="col-sm-8 col-md-9 col-xs-12">
                        {{ Form::text('name', (!empty($objData) && is_object($objData) && !empty($objData->name) ? $objData->name : '') ,['class'=>'form-control','rows'=>'2', 'placeholder' => 'Enter Organization Name'])}}
                        @if ($errors->has('name'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Email *</label>
                    <div class="col-sm-8 col-md-9 col-xs-12">
                        {{ Form::email('email', (!empty($objData) && is_object($objData) && !empty($objData->email) ? $objData->email : '') ,['class'=>'form-control','rows'=>'2', 'placeholder' => 'Enter Email ID *'])}}
                        @if ($errors->has('email'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Address *</label>
                    <div class="col-sm-8 col-md-9 col-xs-12">
                        {{ Form::textarea('address', (!empty($objData) && is_object($objData) && !empty($objData->address) ? $objData->address : '') ,['class'=>'form-control', 'style' => "resize: none;",'rows'=>'2', 'placeholder' => 'Enter Address *'])}}
                        {{ Form::hidden('timezone', (!empty($objData) && is_object($objData) && !empty($objData->timezone) ? $objData->timezone : '') ,[])}}
                        @if ($errors->has('address'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('address') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Country *</label>
                    <div class="col-sm-8 col-md-9 col-xs-12">
                        {{Form::select('country_id',[]+@$arrCountry->pluck('name','id')->toArray(), (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->country_id) ? Auth::guard('admin')->user()->country_id : ''),['id'=> 'country_id', 'class'=>'form-control country_id'])}}

                        @if ($errors->has('country_id'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('country_id') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">State *</label>
                    <div class="col-sm-8 col-md-9 col-xs-12">
                        {{ Form::select('state_id',[''=>'Select State *']+@$arrState->pluck('name','id')->toArray(), (!empty($objData) && is_object($objData) && !empty($objData->state_id) && isset($objData->state_id) ? $objData->state_id : ''),['id'=> 'state_id', 'class'=>'form-control state_id chosen-select'])}}

                        @if ($errors->has('state_id'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('state_id') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">City *</label>
                    <div class="col-sm-8 col-md-9 col-xs-12">
                        @if(isset($arrCityData) && !empty($arrCityData) && count($arrCityData) > 0)
                        {{Form::select('city_id',[''=>'Select city *']+@$arrCityData->pluck('name','id')->toArray(), (!empty($objData) && is_object($objData) && !empty($objData->city_id) && isset($objData->city_id) ? $objData->city_id : ''),['id'=> 'address_city_id', 'class'=>'form-control address_city_id chosen-select'])}}
                        @else
                        {{Form::select('city_id',[''=>'Select city *'], (isset($objData) && !empty($objData) && is_object($objData) && !empty($objData->city_id) ? $objData->city_id : ''),['id'=> 'address_city_id', 'class'=>'form-control address_city_id chosen-select'])}}
                        @endif

                        @if ($errors->has('city_id'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('city_id') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Zip Code *</label>
                    <div class="col-sm-8 col-md-9 col-xs-12">
                        {{ Form::text('zip_code', (!empty($objData) && is_object($objData) && !empty($objData->zip_code) && isset($objData->zip_code) ? $objData->zip_code : ''), ['class' => 'form-control user form-number', 'data' => 'zip_code', 'placeholder' => 'Enter zip code *']) }}

                        <span class="zip_code" style = "display:block;color:red;">

                        </span>

                        @if ($errors->has('zip_code'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('zip_code') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Contact Number *</label>
                    <div class="col-sm-8 col-md-9 col-xs-12">
                        {{ Form::text('phone', (!empty($objData) && is_object($objData) && !empty($objData->phone) && isset($objData->phone) ? $objData->phone : ''), ['class' => 'form-control user form-number', 'data' => 'phone', 'placeholder' => 'Enter Contact no *']) }}

                        <span class="phone" style = "display:block;color:red;">

                        </span>

                        @if ($errors->has('phone'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Emergency Contact Number *</label>
                    <div class="col-sm-8 col-md-9 col-xs-12">
                        {{ Form::text('emergency_contact', (!empty($objData) && is_object($objData) && !empty($objData->emergency_contact) && isset($objData->emergency_contact) ? $objData->emergency_contact : ''), ['class' => 'form-control user form-number', 'data' => 'emergency_contact', 'placeholder' => 'Enter Emergency Contact no *']) }}

                        <span class="phone" style = "display:block;color:red;">

                        </span>

                        @if ($errors->has('phone'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">
                        {{ (!empty($objData) && is_object($objData) ? 'Password' : 'Password *') }}</label>
                    <div class="col-sm-8 col-md-9 col-xs-12">

                        @php
                        if(!empty($objData) && is_object($objData))
                        $cls = 'form-control lock';
                        else
                        $cls = 'form-control lock required';

                        @endphp


                        {!! Form::password('password', ['class' => $cls, 'id' => 'passwords', 'placeholder' => '']) !!}

                        @if ($errors->has('password'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                    <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">{{ (!empty($objData) && is_object($objData) ? 'Confirm Password' : 'Confirm Password *') }}</label>
                    <div class="col-sm-8 col-md-9 col-xs-12">
                        @php
                        if(!empty($objData) && is_object($objData))
                        $cls = 'form-control lock';
                        else
                        $cls = 'form-control lock required';

                        @endphp

                        {!! Form::password('password_confirmation', ['class' => $cls, 'placeholder' => '']) !!}

                        @if ($errors->has('password_confirmation'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <span class="detail_txt">Main POC details</span>

                @if(!empty($arr_poc_detail) && is_object($arr_poc_detail) && count($arr_poc_detail) > 0)
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

                    {{ Form::input('hidden', 'search', !empty(request()->get('search')) ? request()->get('search') : '', ['readonly' => 'readonly']) }}

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

                <div class="clearfix"></div>

                <span class="dsh_add_more"> <a href="javascript:void(0)" >Add More</a></span>

                <div class="col-sm-offset-0">

                    {{ Form::hidden('organization_id', (!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : '')) }}
                    <?php $i = 0; ?>
                    <input type='hidden' class="org-option" value="{{$i}}">

                    @if(isset($objData) && !empty($objData) && is_object($objData) && !empty($objData->id))
                    <button class="btn btn-primary">Update</button>
                    @else
                    <button class="btn btn-primary">Save</button>
                    @endif

                    <a href="{{route('organization.index')}}">
                        <button class="btn btn-danger" type="button">Cancel</button>
                    </a>
                </div>

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
<!--<script type="text/javascript" src="{{asset('public/admin_assets/js/app-js/organization.js')}}"></script>-->
<script type="text/javascript" src="{{asset('public/admin_assets/js/app-js/city.js')}}"></script>

<script>



$(".dsh_add_more").on('click', function (e) {
    
    var value = parseInt($(".org-option").val()) + 1;
   
$('input[name="poc_contact_no[' + value + ']"]').mask('(000) 000-0000', {placeholder: "(___) ___-____"});

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

$(document).on('click', '.poc-remove', function (e) {

    var value = parseInt($(".org-option").val()) - 1;
    $('.org-option').val(value);

    e.preventDefault();
    //console.log($(this).parents(".parentss").attr('class'));
    $(this).parents(".parentss").remove();
});
</script>

<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/organization.js')}}"></script>

<script>

$(document).ready(function () {
    var timezone_offset_minutes = new Date().getTimezoneOffset();
    timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
    $('input[name="zip_code"]').mask('00000', {placeholder: "_____"});
    $('input[name="phone"]').mask('(000) 000-0000', {placeholder: "(___) ___-____"});
    $('input[name="emergency_contact"]').mask('(000) 000-0000', {placeholder: "(___) ___-____"});
    $('input[name="poc_contact_no[]"]').mask('(000) 000-0000', {placeholder: "(___) ___-____"});
    $('input[name="timezone"]').val(timezone_offset_minutes);
});




</script>
@endsection
