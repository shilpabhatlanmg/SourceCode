<section class="app_screen">
    <div class="container">
        <div class="row">
            <span class="heading">{{ !empty($app_screen) && is_object($app_screen) && !empty($app_screen->title) ? $app_screen->title : '' }}</span>
            <span class="txt"> {!! !empty($app_screen) && is_object($app_screen) && !empty($app_screen->content) ? $app_screen->content : '' !!}</span>
            <div class="app-slider">
                <div class="mobileScreen">
                    <img class="" src="{{ asset('public/assets/images/mobile-bg.png') }}" alt="client">
                </div>
                <div class="slider-nav">
                    <div class="item"><img src="{{ asset('public/assets/images/app-slider-1.jpg') }}"></div>
                    <div class="item"><img src="{{ asset('public/assets/images/app-slider-2.png') }}"></div>
                    <div class="item"><img src="{{ asset('public/assets/images/app-slider-3.png') }}"></div>
                    <div class="item"><img src="{{ asset('public/assets/images/app-slider-4.png') }}"></div>
                    <div class="item"><img src="{{ asset('public/assets/images/app-slider-5.png') }}"></div>
                    <div class="item"><img src="{{ asset('public/assets/images/app-slider-6.png') }}"></div>
                </div>


            </div>
        </div>
    </div>
</section>
