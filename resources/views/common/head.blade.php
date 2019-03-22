<meta charset="utf-8">
<!--<title>ProtectApp™</title>-->
<title>@yield('pageTitle') | ProtectApp™</title>
@yield('addtional_css')
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
@php($path = 'public/storage/fevicon/' . (isset($site_setting) && !empty($site_setting) && is_object($site_setting) && !empty($site_setting->fevicon) ? $site_setting->fevicon : ''))
<link rel="icon" href="{{ asset($path) }}" type="image/png">

<!--Google Font link-->
@if(Request::is('join-us', 'plan-detail/*', 'payment', 'thankyou/*'))
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
@else
<link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
@endif


<link href="{{ asset('/public/assets/css/animate.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/public/assets/css/iconfont.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/public/assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/public/assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/public/assets/css/magnific-popup.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/public/assets/css/bootsnav.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="{{asset('public/assets/css/select-mania.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/css/jquery.mCustomScrollbar.css')}}">


<!--For Plugins external css-->
<link href="{{ asset('/public/assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />

<!--Theme custom css -->
<link href="{{ asset('/public/assets/css/style.css') }}" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" href="assets/css/colors/maron.css">-->

<!--Theme Responsive css-->
<link href="{{ asset('/public/assets/css/responsive.css') }}" rel="stylesheet" type="text/css" />

<!-- slick -->
<link href="{{ asset('/public/assets/css/slick.css') }}" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="{{ asset('/public/assets/js/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>

<script>
	var siteURL = "<?php echo env('APP_URL'); ?>";
</script>
