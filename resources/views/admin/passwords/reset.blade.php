<!DOCTYPE HTML>
<html>
<head>
    <title>Reset Password</title>
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

            <h2 class="title1">Reset Password</h2>
            <div class="widget-shadow">
                <div class="login-body">

                    {!!
                        Form::open(
                        array(
                        'name' => 'admin_reset_password',
                        'id' => 'admin_reset_password',
                        'url' => 'admin-password/reset',
                        'autocomplete' => 'on',
                        'class' => 'form-horizontal'
                        )
                        )
                        !!}
                        <input type="hidden" name="token" value="{{ $token }}">
                        {{ Form::email('email', '', ['class' => 'user', 'placeholder' => 'Enter email address *']) }}
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>

                        {{ Form::password('password', ['class' => 'lock', 'id' => 'passwords', 'placeholder' => 'Enter password *']) }}
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        
                        {{ Form::password('password_confirmation', ['class' => 'lock', 'placeholder' => 'Enter Reconfirm password *']) }}
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>

                        <input type="submit" name="submits" value="Reset Password">
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