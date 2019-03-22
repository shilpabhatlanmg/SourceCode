  <section id="business" class="business bg-blue roomy-70">
        <div class="business_overlay"></div>
        <div class="container">
            <div class="row">
                <div class="main_business">

                            <!-- <div class="left">
                                <img src="assets/images/p-app.png">
                            </div> -->
                            <div class="right">
                                <div class="business_item">

                                    @php

                                    $design_title = !empty($design_group) && is_object($design_group) && !empty($design_group->title) ? $design_group->title : '';

                                    $design_title_coent = strtr($design_title, [
                                    '{{LOGO}}' => '<span class="actual-logo sz-grp"><img src="' . asset("public/assets/images/actual-logo-white.png") .'"></span>',
                                    '{{LOGO_WHITE}}' => '<span class="actual-logo sz-grp"><img src="' . asset("public/assets/images/actual-logo-white.png") .'"></span>'
                                    ]);

                                    @endphp

                                    <h2>{!! !empty($design_title_coent) && isset($design_title_coent) ? $design_title_coent : '' !!}</h2>

                                    @php

                                    $design_group_content = !empty($design_group) && is_object($design_group) && !empty($design_group->content) ? $design_group->content : '';

                                    $design_group_content = strtr($design_group_content, [
                                    '{{LOGO}}' => '<span class="actual-logo"><img src="' . asset("public/assets/images/actual-logo.png") .'"></span>',
                                    '{{LOGO_WHITE}}' => '<span class="actual-logo"><img src="' . asset("public/assets/images/actual-logo-white.png") .'"></span>'
                                    ]);

                                    @endphp

                                    {!! !empty($design_group_content) && isset($design_group_content) ? $design_group_content : '' !!}
                                    
                                    <!--<h2><span class="actual-logo sz-grp"><img src="{{ asset('public/assets/images/actual-logo-white.png') }}"></span> is designed for groups</h2>

                                    <p >We designed <span class="actual-logo sz-grp2"><img src="{{ asset('public/assets/images/actual-logo-white.png') }}"></span> to compliment groups with security teams (volunteers or professionals) in place.  When <span class="actual-logo sz-grp2"><img src="{{ asset('public/assets/images/actual-logo-white.png') }}"></span> is used by your group, the entire group becomes the eyes and ears of the security team.  When aid is requested, a discreet text message is sent to the security team, who just as discreetly responds to the situation.  ProtectApp incorporates your local emergency numbers into the app, speeding up professional response times by connecting you directly to your local dispatchers.  Bluetooth beacons are required for <span class="actual-logo sz-grp2"><img src="{{ asset('public/assets/images/actual-logo-white.png') }}"></span> to provide location services.</p>
                                    <div class="design-mob"><img src="{{ asset('public/assets/images/p-app.png') }}"></div>-->

                                    <ul>
                                        <li><i class="fa fa-check" aria-hidden="true"></i> Clean &amp; Modern Design</li>
                                        <li><i class="fa fa-check" aria-hidden="true"></i>Compatible with Android and iPhones</li>
                                        <li><i class="fa fa-check" aria-hidden="true"></i>Download at:</li>
                                    </ul>
                                    <div class="bx1">
                                        <a href="#"><div class="first"></div></a>
                                        <a href="#"><div class="second"></div></a>    

                                    </div>
                                    

                                </div>
                            </div>

                            

                        </div>
                    </div>
                </div>
            </section><!-- End off Business section -->
