@extends('adminLayout')
@section('content')
@section('pageTitle')
{{ (isset($title) && !empty($title) ? $title : '') }}
@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">
        <div class="forms">
            <h2 class="title1"> {{(!empty($objData->id))?'Edit Security':'Create Security' }}</h2>
            <!--<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Open modal for @mdo</button>-->

            <div class="form-three widget-shadow">

                {!!
                    Form::open(
                    array(
                    'name' => 'frmsave',
                    'id' => 'admin_security',
                    'url' => 'admin/security/'.(!empty($objData) && is_object($objData) && !empty($objData->id) ? \Crypt::encryptString($objData->id) : ''),
                    'autocomplete' => 'off',
                    'class' => 'form-horizontal',
                    'files' => true
                    )
                    )
                    !!}
                    @section('editMethod')
                    @show


                    @if($roles->name == \Config::get('constants.PLATFORM_ADMIN'))
                    <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                        <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Organization Name</label>
                        <div class="col-sm-8 col-md-9 col-xs-12">
                            {{ Form::select('organization_id',['' => '--select--']+@$adminorganisation->pluck('name','id')->toArray(), ( !empty($objData) && is_object($objData) && !empty($objData->organization_id) ? $objData->organization_id : ''),['id'=> 'organization_id', 'class'=>'form-control org_id chosen-select'])}}                            
                            @if ($errors->has('organization_id'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('organization_id') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                        <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Name</label>
                        <div class="col-sm-8 col-md-9 col-xs-12">
                            {{ Form::text('name', (!empty($objData) && is_object($objData) && !empty($objData->name) ? $objData->name : ''), ['placeholder'=>'Enter name...', 'class'=>'form-control']) }}

                            @if ($errors->has('name'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                        <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Email</label>
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
                        <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Contact Number *</label>
                        <div class="col-sm-2 col-md-2 col-xs-12">
                            {{ Form::select('country_code',['+1' => '+1', '+91' => '+91'], (!empty($objData) && is_object($objData) && !empty($objData->country_code) ? $objData->country_code : ''),['id'=> 'country_code', 'class'=>'form-control country_code'])}}
                        </div>

                        <div class="col-sm-6 col-md-6 col-xs-12">
                            {{ Form::text('contact_number', (!empty($objData) && is_object($objData) && !empty($objData->contact_number) ? $objData->contact_number : ''), ['class' => 'form-control user', 'placeholder' => 'Enter contact number']) }}
                            @if ($errors->has('contact_number'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('contact_number') }}</strong>
                            </span>
                            @endif
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Profile Image</label>
                        

                        {{ Form::hidden('user_type', '1') }}

                        {{ Form::hidden('profile_image_edit', (!empty($objData) && is_object($objData) && !empty($objData->profile_image) ? $objData->profile_image : '')) }}


                        <div class="col-sm-8 col-md-9 col-xs-12">
                            <div class="up_choose">
                                <div class="upload_choose">
                                   <label class="custom-file-upload">
                                       {{ Form::file('profile_image', ['id' => 'image', 'class'=>'']) }}
                                       Choose File
                                   </label>
                               </div>
                               <div class="profile_image_btn">
                                
                                @php

                                $path = 'public/storage/admin_assets/images/profile_image/' . (!empty($objData) && is_object($objData) && !empty($objData->profile_image) ? $objData->profile_image : '');

                                @endphp

                                @if(!empty($objData) && is_object($objData) && !empty($objData->profile_image) && file_exists($path))

                                {{ Html::image($path, 'image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview1', 'title' => (!empty($objData) && is_object($objData) && !empty($objData->profile_image) ? $objData->profile_image : '' ), 'class' => 'img_prvew')) }}

                                @else

                                {{ Html::image(asset('/public/admin_assets/images/user-profile.jpg'), 'no-image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview1', 'title' => 'Image Preview', 'class' => 'img_prvew')) }}


                                @endif

                                {{ Form::hidden('author_image_edit', (!empty($objData) && is_object($objData) && !empty($objData->profile_image) ? $objData->profile_image : '')) }}


                            </div>
                        </div>
                    </div>


                </div>

                <div class="col-sm-offset-0">

                    {{ Form::hidden('user_id', (!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : '')) }}
                    
                    
                    @if(isset($objData) && !empty($objData) && is_object($objData) && !empty($objData->id))
                    <button class="btn btn-primary">Update</button>
                    @else
                    <button class="btn btn-primary">Save</button>
                    @endif

                    <a href="{{route('security.index')}}">
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

<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/organization.js')}}"></script>

<script>

    $(document).ready(function () {
        var timezone_offset_minutes = new Date().getTimezoneOffset();
        timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
        $('input[name="zip_code"]').mask('00000', {placeholder: "_____"});
        $('input[name="phone"]').mask('(000) 000-0000', {placeholder: "(___) ___-____"});
        $('input[name="poc_contact_no[]"]').mask('(000) 000-0000', {placeholder: "(___) ___-____"});
        $('input[name="timezone"]').val(timezone_offset_minutes);
    });

    $(document).ready(function(){

        $("#image").change(function(){
            headshotPreview(this);
        });


    });

    function headshotPreview(input)
    {
        if(input.files && input.files[0])
        {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#headshot_preview1').attr('src', e.target.result);
                $('#headshot_preview1').addClass('profile_pic_upload');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }




</script>
@endsection
