@php
if(Request::is('about-us', 'privacy-policy', 'terms-conditions','join-us', 'plan-detail/*', 'activate-user', 'payment', 'faq', 'thankyou/*')):
$inner = "inner-nav";
else:
$inner = "";
endif;
@endphp

<nav class="navbar navbar-default bootsnav navbar-fixed {{ !empty($inner) && isset($inner) ? $inner : '' }}  white">

<div class="container">


        <!-- Start Header Navigation -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{{ route('welcome') }}">
                <img src="{{ asset('public/assets/images/logo.png') }}" class="logo" alt="">
                <img src="{{ asset('public/assets/images/logo2.png') }}" class="logo2" alt="">
            </a>

        </div>
        <!-- End Header Navigation -->

        <!-- navbar menu -->
        <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="nav navbar-nav navbar-right">

                @if(Request::is('about-us', 'privacy-policy', 'terms-conditions', 'join-us', 'plan-detail/*', 'activate-user', 'payment', 'faq', 'thankyou/*'))

                <li><a href="{{ route('welcome') }}"><i class="fa fa-angle-left" aria-hidden="true"></i> Back To Home</a></li>

                @else

                <li><a href="#home">Home</a></li>
                <li><a href="#features">Who uses ProtectApp™</a></li>
                <li><a href="#about">Features </a></li>
                <li><a href="#test">Our Clients</a></li>
                <li><a href="#contact"> Contact Us</a></li>
                <li><a href="{{ route('joinus') }}">Sign up</a></li>
                <li><a href="{{ route('admin.login')  }}">Login</a></li>

                @endif

                </ul>
        </div><!-- /.navbar-collapse -->
    </div>

</nav>
