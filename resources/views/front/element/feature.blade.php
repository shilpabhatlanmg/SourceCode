<section id="features" class="features bg-grey">
    <div class="container">
        <div class="row">
            <div class="main_features fix roomy-70">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="features_item sm-m-top-30">
                        <div class="f_item_icon">
                            <i class="icon icon-school"></i>
                        </div>
                        <div class="f_item_text">
                            <h2>{{ !empty($business) && is_object($business) && !empty($business->title) ? $business->title : '' }}</h2>

                            @php

                            $business_content = !empty($business) && is_object($business) && !empty($business->content) ? $business->content : '';

                            $business_content = strtr($business_content, [
                            '{{LOGO}}' => '<span class="actual-logo"><img src="' . asset("public/assets/images/actual-logo.png") .'"></span>',
                            '{{LOGO_WHITE}}' => '<span class="actual-logo"><img src="' . asset("public/assets/images/actual-logo-white.png") .'"></span>'
                            ]);

                            @endphp

                            {!! !empty($business_content) && isset($business_content) ? $business_content : '' !!}

                            <!--<p>Any business can use<span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span>to summon aid at the touch of a button. Our technology is discreet and eliminates confusion by providing indoor location services.                                        </p>-->

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="features_item sm-m-top-30">
                        <div class="f_item_icon">
                            <i class="icon icon-building"></i>
                        </div>
                        <div class="f_item_text">
                            <h2>{{ !empty($congregations) && is_object($congregations) && !empty($congregations->title) ? $congregations->title : '' }}</h2>

                            @php

                            $congregations_content = !empty($congregations) && is_object($congregations) && !empty($congregations->content) ? $congregations->content : '';

                            $congregations_content = strtr($congregations_content, [
                            '{{LOGO}}' => '<span class="actual-logo"><img src="' . asset("public/assets/images/actual-logo.png") .'"></span>',
                            '{{LOGO_WHITE}}' => '<span class="actual-logo"><img src="' . asset("public/assets/images/actual-logo-white.png") .'"></span>'
                            ]);

                            @endphp

                            {!! !empty($congregations_content) && isset($congregations_content) ? $congregations_content : '' !!}

                            <!--<p>Originally designed with church security in mind,<span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span>turns your entire congregation into eyes and ears for the security team. If you don't have a security team,<span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span>can still help protect your congregation.</p>-->


                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="features_item sm-m-top-30">
                        <div class="f_item_icon">
                            <i class="icon icon-people"></i>
                        </div>
                        <div class="f_item_text">
                            <h2>{{ !empty($schools) && is_object($schools) && !empty($schools->title) ? $schools->title : '' }}</h2>

                            @php

                            $schools_content = !empty($schools) && is_object($schools) && !empty($schools->content) ? $schools->content : '';

                            $schools_content = strtr($schools_content, [
                            '{{LOGO}}' => '<span class="actual-logo"><img src="' . asset("public/assets/images/actual-logo.png") .'"></span>',
                            '{{LOGO_WHITE}}' => '<span class="actual-logo"><img src="' . asset("public/assets/images/actual-logo-white.png") .'"></span>'
                            ]);

                            @endphp

                            {!! !empty($schools_content) && isset($schools_content) ? $schools_content : '' !!}

                            <!--<p><span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span>helps identify where an incident is occuring and gets help there fast. Imrpove your school's security with<span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span>.</p>-->

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="features_item sm-m-top-30">
                        <div class="f_item_icon">
                            <i class="icon icon-tablet2"></i>
                        </div>
                        <div class="f_item_text">
                            <h2>{{ !empty($better_security_coverage) && is_object($better_security_coverage) && !empty($better_security_coverage->title) ? $better_security_coverage->title : '' }}</h2>

                            @php

                            $better_security_coverage_content = !empty($better_security_coverage) && is_object($better_security_coverage) && !empty($better_security_coverage->content) ? $better_security_coverage->content : '';

                            $better_security_coverage_content = strtr($better_security_coverage_content, [
                            '{{LOGO}}' => '<span class="actual-logo"><img src="' . asset("public/assets/images/actual-logo.png") .'"></span>',
                            '{{LOGO_WHITE}}' => '<span class="actual-logo"><img src="' . asset("public/assets/images/actual-logo-white.png") .'"></span>'
                            ]);

                            @endphp

                            {!! !empty($better_security_coverage_content) && isset($better_security_coverage_content) ? $better_security_coverage_content : '' !!}

<!--<p><span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span>uses low energy Bluetooth beacons for indoor/outdoor location services.  First Responders are provided a basic alert and the location of the person requesting aid.</p>-->
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- End off row -->
    </div><!-- End off container -->
</section><!-- End off Featured Section-->



<section id="about" class="about_bx">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-sm-7 col-xs-12 left">

                <span class="heading">{{ !empty($feature) && is_object($feature) && !empty($feature->title) ? $feature->title : '' }}</span>

                @php
                $feature_content = !empty($feature) && is_object($feature) && !empty($feature->content) ? $feature->content : '';
                $feature_content = strtr($feature_content, [
                '{{LOGO}}' => '<span class="actual-logo"><img src="' . asset("public/assets/images/actual-logo.png") .'"></span>',
                '{{LOGO_WHITE}}' => '<span class="actual-logo"><img src="' . asset("public/assets/images/actual-logo-white.png") .'"></span>'
                ]);

                @endphp

                {!! !empty($feature_content) ? $feature_content : '' !!}





<!--<span class="heading">Our Features </span>
<p>An orci nullam tempor sapien, eget gravida integer donec ipsum porta justo at odio integer congue magna undo auctor gravida velna magna orci lacus odio ac risus auctor faucibus orci ligula massa luctus et ultrices posuere cubilia.</p>
<p>gravida velna magna orci lacus odio ac risus auctor faucibus orci ligula massa luctus et ultrices posuere cubilia. </p>-->
            </div>
            <div class="col-md-5 col-sm-5 col-xs-12 right">
                <img src="{{ asset('public/assets/images/about.png') }}">
            </div>
        </div>
    </div>
</section>
