<section id="home" class="home bg-black fix">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="main_home">
                <div class="col-md-12">
                    <div class="hello_slid">
                        <div class="slid_item xs-text-center">
                            <div class="col-sm-4">
                                <img src="{{ asset('public/assets/images/hello-phone.png') }}" alt="" />
                            </div>
                            <div class="col-sm-8">
                                <div class="home_text xs-m-top-30">

                                    @php

                                    $home_content = !empty($banner_content) && is_object($banner_content) && !empty($banner_content->content) ? $banner_content->content : '';

                                    $home_content = strtr($home_content, [
                                    '{{LOGO}}' => '<span class="actual-logo sz-bn"><img src="' . asset("public/assets/images/actual-logo-white.png") .'"></span>',
                                    '{{LOGO_WHITE}}' => '<span class="actual-logo sz-bn"><img src="' . asset("public/assets/images/actual-logo-white.png") .'"></span>'
                                    ]);

                                    @endphp

                                    {!! !empty($home_content) && isset($home_content) ? $home_content : '' !!}

                                    <!--<h1 class="text-white">Welcome to <span class="actual-logo sz-bn"><img src="{{ asset('public/assets/images/actual-logo-white.png') }}"></span></h1>
                                    <h2 class="text-white">Enhancing Group Security</h2>
                                    <p class="text-white">Innovating Security at the <strong>Touch</strong> of a Button.  </p>-->
                                </div>

                                <div class="home_btns m-top-40">
                                    <a href="#business" class="btn btn-primary m-top-20">Download Now</a>
                                    <a href="{{ route('faq') }}" class="btn btn-default m-top-20">Learn More</a>
                                </div>
                            </div>
                        </div><!-- End off slid item -->

                    </div>
                </div>
            </div>
        </div><!--End off row-->
    </div><!--End off container -->
</section> <!--End off Home Sections-->
