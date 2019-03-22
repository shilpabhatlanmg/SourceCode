<!DOCTYPE HTML>
<html>
<head>
    <title>Login</title>
    @php($path = 'public/storage/fevicon/' . (isset($site_setting) && !empty($site_setting) && is_object($site_setting) && !empty($site_setting->fevicon) ? $site_setting->fevicon : ''))
    <link rel="icon" href="{{ asset($path) }}" type="image/x-icon">
    <meta http-equiv="Content-Type" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('/public/admin_assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('/public/admin_assets/css/style.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('/public/admin_assets/css/custom.css') }}" rel="stylesheet" type="text/css" media="all"/>
</head> 
<body>
    <div id="page-wrapper">

        <div class="login-logo">
            <center><a href="#">
                <img src="<?php echo env('APP_URL'); ?>/public/admin_assets/images/logo.png" alt="logo image"></a>
            </center>
        </div>

        <div class="main-page login-page ">

            <h2 class="title1">Login</h2>
            <div class="widget-shadow">
                <div class="login-body">

                    @include('common.admin.flash-message')
                    {!!
                        Form::open(
                        array(
                        'name' => 'frmLogin',
                        'id' => 'admin_login',
                        'route' => 'admin.login',
                        'autocomplete' => 'on',
                        'class' => 'form-horizontal'
                        )
                        )
                        !!}
                        {{ Form::email('email', '', ['class' => 'user', 'placeholder' => 'Enter email address *']) }}

                        @if ($errors->has('email'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                        {{ Form::password('password', ['class' => 'lock', 'placeholder' => 'Enter password *']) }}

                        @if ($errors->has('password'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                        <div class="forgot-grid">
                            <!--<label class="checkbox">
                                {{ Form::checkbox('remember', null, old('remember') ? 'checked' : '', []) }}
                                <i></i>Remember me</label>-->
                                <div class="forgot"> <a href="{{ route('admin.password.request')}}">Forgot password?</a></div>
                                <div class="clearfix"> </div>
                            </div>
                            <input type="submit" name="submits" value="Sign In">
                            <!--<div class="registration"> Don't have an account ? <a class="" href="sign-up.php"> Create an account </a> </div>-->
                            {!! Form::close()!!}
                        </div>
                    </div>

                </div>
            </div>  

            <script type="text/javascript" src="{{ asset('/public/admin_assets/js/jquery-1.11.1.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('/public/admin_assets/js/bootstrap.js') }}"></script>

            <!-----------validation handling js-------------->
            <script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/jquery.validate.js') }}"></script>
            <script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/additional-methods.min.js') }}"></script>

            <script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/client-validation.js') }}"></script>
        </body>
        </html>