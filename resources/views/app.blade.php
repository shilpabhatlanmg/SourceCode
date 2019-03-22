<!DOCTYPE html>
<html>
<head>
    @include('common.head')
</head>
<body>

   <!-- Preloader -->
   <div id="loading">
    <div id="loading-center">
        <div id="loading-center-absolute">
            <div class="object" id="object_one"></div>
            <div class="object" id="object_two"></div>
            <div class="object" id="object_three"></div>
            <div class="object" id="object_four"></div>
        </div>
    </div>
</div><!--End off Preloader -->


<div class="culmn">
    <!--Home page style-->

    @include('common.menu')
    

    @yield('content')

    @include('common.footer')

</div>
<!--======================csrf toke generate for ajax request=========================-->
<script> var csrf_token = "{{ csrf_token() }}"</script>

<!-- JS includes -->

<script type="text/javascript" src="{{ asset('/public/assets/js/jquery-1.11.2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/assets/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/assets/js/jquery.magnific-popup.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/assets/js/jquery.easing.1.3.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/assets/js/bootsnav.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/assets/js/plugins.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/assets/js/main.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/assets/js/slick.js') }}"></script>
<script type="text/javascript" src="{{asset('public/assets/js/select-mania.js')}}"></script>
<script type="text/javascript" src="{{asset('public/assets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>

<script>
    $(function () {   
        $('.slider-nav').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            dots: false,
            centerMode: true,
            autoplay: true,
            focusOnSelect: true,
            responsive: [
            {
              breakpoint: 992,
              settings: {

                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 1,
                slidesToScroll:1
            }
        },
        {
          breakpoint: 480,
          settings: {
            centerMode: true,
            centerPadding: '40px',
            slidesToShow: 1
        }
       } 
    ]
});     
    });
</script>

<!-----------loader handling js-------------->
<script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/jquery.loading.block.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/loader.function.js') }}"></script>

<!-----------confirmation popup handling js-------------->
<script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/jquery.confirm.js') }}"></script>

<!-----------validation handling js-------------->
<script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/jquery.validate.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/jquery.mask.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/additional-methods.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/assets/js/app-js/client-validation.js') }}"></script>
@yield('jscript')
</body>
</html>
