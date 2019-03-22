@extends('adminLayout')

@section('pageTitle')

    {{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

@section('content')
<!-- main content start-->
<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">
        <h2 class="title1">View Profile</h2>
        <div class="blank-page widget-shadow scroll" id="style-2 div1">

            @include('common.admin.flash-message')

            <div class="row">
                <!--<div class="col-md-3">
                    <div class="user-img">

                        <?php $path = 'public/storage/admin/profile_image/' . (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) ? Auth::guard('admin')->user()->user_image : ''); ?>

                        @if(!empty(Auth::guard('admin')->user()->user_image) && file_exists($path))
                        <img src="{{ asset($path) }}" class="img-responsive" height="50" width="50" alt="{{ (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) ? Auth::guard('admin')->user()->user_image : '') }}">
                        @else
                        <img src="{{ asset('public/admin_assets/images/user-img.png') }}" height="50" width="50" class="img-responsive" alt="no-image">
                        @endif
                    </div>
                </div>-->
                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">

                            @php
                             if($roles->name == \Config::get('constants.PLATFORM_ADMIN')){

                                $title = 'Name';
                             } else {
                                    $title = 'Organization Name';
                                }

                            @endphp

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>{{ $title }}</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">
                           
                            {{ (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->name) ? Auth::guard('admin')->user()->name : '') }}
                           
                            
                        </div>
                    </div>

                        <!--<div class="row">
                        <div class="col-md-3"><strong>Gender</strong></div>
                        <?php
                        if (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && Auth::guard('admin')->user()->gender == 'M') {
                            $gender = "Male";
                        } else if (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && Auth::guard('admin')->user()->gender == 'F') {
                            $gender = "Female";
                        } else if (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && Auth::guard('admin')->user()->gender == 'O') {
                            $gender = "Other";
                        } else {
                            $gender = 'N/A';
                        }

                        ?>
                        <div class="col-md-9">{{ $gender }}</div>
                    </div>-->

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>Address</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">
                            {{ (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->address) ? Auth::guard('admin')->user()->address : '') }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>Country</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">
                            {{ (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->countryName->name) ? Auth::guard('admin')->user()->countryName->name : '') }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>State</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">

                            {{ (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->stateName->name) ? Auth::guard('admin')->user()->stateName->name : '') }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>City</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">

                            {{ (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->cityName->name) ? Auth::guard('admin')->user()->cityName->name : '') }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>Zip code</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">
                            {{ (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->zip_code) ? Auth::guard('admin')->user()->zip_code : '') }}
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>Primary email id</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">
                            {{ (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->email) ? Auth::guard('admin')->user()->email : '') }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 pd-none"><strong>Primary Contact number</strong></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 pd-none">
                            {{ (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->phone) ? Auth::guard('admin')->user()->phone : '') }}
                        </div>
                    </div>
                        
                        
                    <hr>
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

                                    {{ (!empty($poc_data) && is_object($poc_data) && !empty($poc_data->poc_name) ? $poc_data->poc_name : '') }}
                                </div>
                            </div>

                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-12 pd-none">
                            @if($x == 1)
                            <label class="lb-head ">POC contact number</label>
                            @endif
                            <div class="col-xs-12 pd-less p-number">

                                {{ (!empty($poc_data) && is_object($poc_data) && !empty($poc_data->poc_contact_no) ? $poc_data->poc_contact_no : '') }}
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-12 pd-none">
                            @if($x == 1)
                            <label class="lb-head ">POC email id:</label>
                            @endif
                            <div class="col-xs-12 pd-less p-mail ">

                                {{ (!empty($poc_data) && is_object($poc_data) && !empty($poc_data->poc_email) ? $poc_data->poc_email : '') }}
                            </div>
                        </div>
                    </div>
                    <hr>

                    @php 
                    $x++;
                    @endphp

                    @endforeach

                    @endif
                    

                    <div class="save-changes">
                        <a href="{{ route('admin.edit.profile') }}" class="btn btn-warning">Edit Profile</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('addtional_css')
@endsection

@section('jscript')
@endsection