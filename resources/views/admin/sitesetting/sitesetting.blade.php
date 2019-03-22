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
                    
                    {!!
                        Form::open(
                        array(
                        'name' => 'frmLogin',
                        'id' => 'admin_sitesetting',
                        'url' => route('save.site.setting'),
                        'autocomplete' => 'on',
                        'class' => 'form-horizontal',
                        'files' => true
                        )
                        )
                        !!}
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-6 pd-none"><strong>Site Offline</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-6 pd-none">
                                {{ Form::checkbox('site_offline', null, (!empty($objData) && is_object($objData) && $objData->site_offline == '0' ? 'checked' : ''), []) }}

                                @if ($errors->has('site_offline_message'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('site_offline') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Site Offline Message</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-none">
                                {{ Form::textarea('site_offline_message', (!empty($objData) && is_object($objData) && !empty($objData->site_offline_message) ? $objData->site_offline_message : '') ,['class'=>'form-control', 'id' => 'site_offline_message', 'rows'=>'2', 'style' => "resize: none;", 'placeholder' => 'Enter offline message'])}}

                                @if ($errors->has('site_offline_message'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('site_offline_message') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>UUID</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-none">
                                {{ Form::text('uuid', (!empty($objData) && is_object($objData) && !empty($objData->uuid) ? $objData->uuid : ''), ['class' => 'form-control user', 'placeholder' => 'Enter UUID']) }}

                                @if ($errors->has('uuid'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('uuid') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!--<h3 class="divider-sec text-right">Stripe API Setting</h3>-->
                        <div class="row">
                            <div class="col-md-3"><strong>Stripe Public Key</strong></div>
                            <div class="col-md-6">
                                {{ Form::text('stripe_pk', (isset($objData) && !empty($objData) && is_object($objData) && !empty($objData->stripe_pk) ? $objData->stripe_pk : ''), ['class' => 'form-control user', 'placeholder' => 'Stripe Public Key']) }}
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('stripe_pk') }}</strong>
                                </span>
                            </div>
                            </div>

                            <div class="row">
                            <div class="col-md-3"><strong>Stripe Secret Key</strong></div>
                            <div class="col-md-6">
                                {{ Form::text('stripe_sk', (isset($objData) && !empty($objData) && is_object($objData) && !empty($objData->stripe_sk) ? $objData->stripe_sk : ''), ['class' => 'form-control user', 'placeholder' => 'Stripe Secret Key']) }}
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('stripe_sk') }}</strong>
                                </span>
                            </div>
                        </div>

                        <!--<h3 class="divider-sec text-right">Currency Setting</h3>
                        <div class="row">
                            <div class="col-md-3"><strong>Currency</strong></div>
                            <div class="col-md-6">
                                {{ Form::text('currency', (isset($objData) && !empty($objData) && is_object($objData) && !empty($objData->currency) ? $objData->currency : ''), ['class' => 'form-control user', 'placeholder' => 'Enter Currency']) }}
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('currency') }}</strong>
                                </span>
                            </div>
                        </div>-->

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Street</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-none">
                                {{ Form::text('street', (!empty($objData) && is_object($objData) && !empty($objData->street) ? $objData->street : ''), ['class' => 'form-control user', 'placeholder' => 'Enter Street Address']) }}

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

                                {{Form::select('state_id',[''=>'Select State']+@$arrState->pluck('name','id')->toArray(), (!empty($objData) && is_object($objData) && !empty($objData->state_id) ? $objData->state_id : ''),['id'=> 'state_id', 'class'=>'form-control state_id chosen-select'])}}

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
                                {{ Form::text('address_zip', (!empty($objData) && is_object($objData) && !empty($objData->address_zip) ? $objData->address_zip : ''), ['class' => 'form-control user', 'placeholder' => 'Enter address zip']) }}

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

                                {{ Form::text('address_phone', (!empty($objData) && is_object($objData) && !empty($objData->address_phone) ? $objData->address_phone : ''), ['class' => 'form-control user', 'placeholder' => 'Enter address phone']) }}

                                @if ($errors->has('address_phone'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('address_phone') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Email</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-none">

                                {{ Form::email('address_email', (!empty($objData) && is_object($objData) && !empty($objData->address_email) ? $objData->address_email : ''), ['class' => 'form-control user', 'placeholder' => 'Enter Address email']) }}

                                @if ($errors->has('address_email'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('address_email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Admin Email</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-none">

                                {{ Form::email('admin_email', (!empty($objData) && is_object($objData) && !empty($objData->admin_email) ? $objData->admin_email : ''), ['class' => 'form-control user', 'placeholder' => 'Enter admin email']) }}

                                @if ($errors->has('admin_email'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('admin_email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>From Email</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-none">

                                {{ Form::email('from_email', (!empty($objData) && is_object($objData) && !empty($objData->from_email) ? $objData->from_email : ''), ['class' => 'form-control user', 'placeholder' => 'Enter from email address']) }}

                                @if ($errors->has('from_email'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('from_email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>From Name</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-none">

                                {{ Form::text('from_name', (!empty($objData) && is_object($objData) && !empty($objData->from_name) ? $objData->from_name : ''), ['class' => 'form-control user', 'placeholder' => 'Enter from name']) }}

                                @if ($errors->has('from_email'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('from_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Copyright Content</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-none">

                                {{ Form::text('copyright_content', (!empty($objData) && is_object($objData) && !empty($objData->copyright_content) ? $objData->copyright_content : ''), ['class' => 'form-control user', 'placeholder' => 'Enter copyright']) }}

                                @if ($errors->has('copyright_content'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('copyright_content') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Site Logo</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-10 half-wid pd-left">

                                {{ Form::file('site_logo', ['class' => 'form-control']) }}
                                <!--<span style="color: #f00;">Image Dimension should be 45px * 45px</span>-->
                                
                                @if ($errors->has('site_logo'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('site_logo') }}</strong>
                                </span>
                                @endif
                            </div>

                            @php 
                            $path = 'public/storage/site_logo/' . (!empty($objData) && is_object($objData) && !empty($objData->site_logo) ? $objData->site_logo : '');
                            @endphp

                            @if(!empty($objData) && is_object($objData) && !empty($objData->site_logo) && file_exists($path))

                            {{ Html::image(asset($path), 'image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview', 'title' => (!empty($objData) && is_object($objData) && !empty($objData->site_logo) ? $objData->site_logo : ''), 'class' => 'img_prvew')) }}
                            
                            
                            @else

                            {{ Html::image(asset('public/assets/images/no_image.jpg'), 'no-image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview', 'title' => 'Image Preview', 'class' => 'img_prvew')) }}
                            @endif
                        </div>

                        {{ Form::hidden('site_logo_image', (!empty($objData) && is_object($objData) && !empty($objData->site_logo) ? $objData->site_logo : '')) }}
                        
                        {{ Form::hidden('favicon_image', (!empty($objData) && is_object($objData) && !empty($objData->fevicon) ? $objData->fevicon : '')) }}
                        
                        {{ Form::hidden('site_footer_image', (!empty($objData) && is_object($objData) && !empty($objData->footer_logo) ? $objData->footer_logo : '')) }}

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Site Favicon</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-10 half-wid pd-left">
                                {{ Form::file('fevicon', ['class' => 'form-control']) }}
                                <!--<span style="color: #f00;">Image Dimension should be 5px * 5px</span>-->

                                @if ($errors->has('fevicon'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('fevicon') }}</strong>
                                </span>
                                @endif
                            </div>
                            @php

                            $path = 'public/storage/fevicon/' . (!empty($objData) && is_object($objData) && !empty($objData->fevicon) ? $objData->fevicon : '');

                            @endphp

                            @if(!empty($objData) && is_object($objData) && !empty($objData->fevicon) && file_exists($path))

                            {{ Html::image(asset($path), 'image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview', 'title' => (!empty($objData) && is_object($objData) && !empty($objData->fevicon) ? $objData->fevicon : ''), 'class' => 'img_prvew')) }}

                            @else

                            {{ Html::image(asset('public/assets/images/no_image.jpg'), 'no-image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview', 'title' => 'Image Preview', 'class' => 'img_prvew')) }}

                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Footer Logo</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-10 half-wid pd-left">
                                {{ Form::file('footer_logo', ['class' => 'form-control']) }}
                                <!--<span style="color: #f00;">Image Dimension should be 45px * 45px</span>-->

                                @if ($errors->has('footer_logo'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('footer_logo') }}</strong>
                                </span>
                                @endif
                            </div>

                            @php

                            $path = 'public/storage/footer_logo/' . (!empty($objData) && is_object($objData) && !empty($objData->footer_logo) ? $objData->footer_logo : '');

                            @endphp

                            @if(!empty($objData) && is_object($objData) && !empty($objData->footer_logo) && file_exists($path))

                            {{ Html::image(asset($path), 'image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview', 'title' => (!empty($objData) && is_object($objData) && !empty($objData->footer_logo) ? $objData->footer_logo : ''), 'class' => 'img_prvew')) }}

                            
                            @else
                            
                            {{ Html::image(asset('public/assets/images/no_image.jpg'), 'no-image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview', 'title' => 'Image Preview', 'class' => 'img_prvew')) }}
                            
                            @endif
                        </div>

                        

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Facebook Link</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-none">

                                {{ Form::text('facebook_link', (!empty($objData) && is_object($objData) && !empty($objData->facebook_link) ? $objData->facebook_link : ''), ['class' => 'form-control', 'placeholder' => 'Enter facebook link']) }}

                                @if ($errors->has('facebook_link'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('facebook_link') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Twitter Name</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-none">

                                {{ Form::text('twitter_link', (!empty($objData) && is_object($objData) && !empty($objData->twitter_link) ? $objData->twitter_link : ''), ['class' => 'form-control', 'placeholder' => 'Enter twitter link']) }}

                                @if ($errors->has('twitter_link'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('twitter_link') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Linked In</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-none">
                                {{ Form::text('linked_in', (!empty($objData) && is_object($objData) && !empty($objData->linked_in) ? $objData->linked_in : ''), ['class' => 'form-control', 'placeholder' => 'Enter Linked In']) }}

                                @if ($errors->has('linked_in'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('linked_in') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Google Plus</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-none">
                                {{ Form::text('google_link', (!empty($objData) && is_object($objData) && !empty($objData->google_link) ? $objData->google_link : ''), ['class' => 'form-control', 'placeholder' => 'Enter google plus link']) }}

                                @if ($errors->has('google_link'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('google_link') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Behance</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-none">
                                {{ Form::text('behance_link', (!empty($objData) && is_object($objData) && !empty($objData->behance_link) ? $objData->behance_link : ''), ['class' => 'form-control', 'placeholder' => 'Enter Behance link']) }}

                                @if ($errors->has('behance_link'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('behance_link') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 mr_btm pd-none"><strong>Dribbble</strong></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-none">
                                {{ Form::text('dribbble_link', (!empty($objData) && is_object($objData) && !empty($objData->dribbble_link) ? $objData->dribbble_link : ''), ['class' => 'form-control', 'placeholder' => 'Enter Dribbble link']) }}

                                @if ($errors->has('dribbble_link'))
                                <span class="help-block" style = "display:block;color:red;">
                                    <strong>{{ $errors->first('dribbble_link') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 pd-left">
                                <p>
                                    <input type="submit" value="Save Setting" class="btn btn-warning">
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
    
$(document).ready(function(){

    $('input[name="address_zip"]').mask('00000', {placeholder: "_____"});
    $('input[name="address_phone"]').mask('(000) 000-0000', {placeholder: "(___) ___-____"});
    

    });
</script>
        @endsection
