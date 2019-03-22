<!DOCTYPE HTML>
<html>
<head>
    <title>Forgot Password</title>
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

            <h2 class="title1">Forgot Password</h2>
            <div class="widget-shadow">
                <div class="login-body">

                    @include('common.admin.flash-message')
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

                    {!!
                        Form::open(
                        array(
                        'name' => 'admin_forgot_pass',
                        'id' => 'admin_forgot_pass',
                        'route' => 'admin.password.email',
                        'autocomplete' => 'on',
                        'class' => 'form-horizontal'
                        )
                        )
                        !!}
                        {{ Form::email('email', '', ['class' => 'user', 'placeholder' => 'Enter email address *']) }}
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>

                        <input type="submit" name="submits" value="Send">
                        <div class="registration"> Login ? <a class="" href="{{ route('admin.login') }}"> Click here for Log In </a> </div>
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