@extends('adminLayout')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

@section('content')
<!-- main content start-->

<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">
        <div class="forms">
            <h2 class="title1">Change Password</h2>

            <div class="form-three widget-shadow">

                @include('common.admin.flash-message')
                {!!
                    Form::open(
                    array(
                    'name' => 'admin_pass_change',
                    'id' => 'admin_pass_change',
                    'route' => 'admin.update.password',
                    'autocomplete' => 'on',
                    'class' => 'form-horizontal'
                    )
                    )
                    !!}


                    <div class="form-group">
                        <label for="focusedinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Old Password *</label>
                        <div class="col-sm-8 col-xs-12">

                            {{ Form::password('current_password', ['class' => 'form-control lock', 'placeholder' => 'Old password *']) }}

                            @if ($errors->has('current_password'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('current_password') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="disabledinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">New Password *</label>
                        <div class="col-sm-8 col-xs-12">
                            {{ Form::password('password', ['class' => 'form-control lock', 'id' => 'passwords', 'placeholder' => 'New Password *']) }}

                            @if ($errors->has('password'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Confirm New Password *</label>
                        <div class="col-sm-8 col-xs-12">
                            {{ Form::password('password_confirmation', ['class' => 'form-control lock', 'placeholder' => 'Confirm New Password *']) }}

                            @if ($errors->has('password_confirmation'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    
                 <div class="col-sm-offset-2">

                     <button class="btn btn-primary">Change Password</button>

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
 @endsection

