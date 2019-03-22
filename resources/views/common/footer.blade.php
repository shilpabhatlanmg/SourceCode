<footer  class="footer action-lage bg-black p-top-80">
    <!--<div class="action-lage"></div>-->
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-6 mr-foot-mb">
                <div class="widget_brand">
                   <a href="#" ><span class="actual-logo sz-ft"><img src="{{ asset('public/assets/images/actual-logo-grey.png') }}"></span></a>
                    @php
                        $footer_left_content = '';
                        $footer_left_content = App\Helpers\Helper::getContentBySlug('footer-left-content');
                        $footer_left_content = !empty($footer_left_content) && is_object($footer_left_content) && !empty($footer_left_content->content) ? $footer_left_content->content : '';
                        $footer_left_content = strtr($footer_left_content, [
                        '{{LOGO_GREY}}' => '<span class="actual-logo sz-ft2"><img src="' . asset("public/assets/images/actual-logo-grey.png") .'"></span>'
                        ]);
                    @endphp
                    <p class="gen_txt">
                        {!! !empty($footer_left_content) && isset($footer_left_content) ? $footer_left_content : '' !!}
                    </p>
                   <div class="social-icons">
                    <ul>
                        <li><a href="{{ (!empty($site_setting) && is_object($site_setting) && !empty($site_setting->facebook_link) ? $site_setting->facebook_link : '#') }}" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>

                        <li><a href="{{ (!empty($site_setting) && is_object($site_setting) && !empty($site_setting->twitter_link) ? $site_setting->twitter_link : '#') }}" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>

                        <li><a href="{{ (!empty($site_setting) && is_object($site_setting) && !empty($site_setting->linked_in) ? $site_setting->linked_in : '#') }}" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>

                        <li><a href="{{ (!empty($site_setting) && is_object($site_setting) && !empty($site_setting->google_link) ? $site_setting->google_link : '#') }}" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>

                        <li><a href="{{ (!empty($site_setting) && is_object($site_setting) && !empty($site_setting->behance_link) ? $site_setting->behance_link : '#') }}" target="_blank"><i class="fa fa-behance" aria-hidden="true"></i></a></li>

                        <li><a href="{{ (!empty($site_setting) && is_object($site_setting) && !empty($site_setting->dribbble_link) ? $site_setting->dribbble_link : '#') }}" target="_blank"><i class="fa fa-dribbble" aria-hidden="true"></i></a></li>

                    </ul>

                </div>

            </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-6 mr-foot-mb">
            <div class="widget_ab_item">
                <div class="item_icon mb-mar"><i class="fa fa-location-arrow"></i></div>
                <div class="widget_ab_item_text">
                    <h2 class="text-white">Location</h2>

                    @php
                    $state_name = '';
                    if (isset($site_setting) && !empty($site_setting) && is_object($site_setting) && !empty($site_setting->state_id)) {
                    $state_name = App\Helpers\Helper::getStateNameById($site_setting->state_id);
                }
                if (isset($site_setting) && !empty($site_setting) && is_object($site_setting) && !empty($site_setting->city_id)) {
                $city_name = App\Helpers\Helper::getCityNameById($site_setting->city_id);
            }

            @endphp

            <p><span class="actual-logo sz-ft2"><img src="{{ asset('public/assets/images/actual-logo-grey.png') }}"></span> {{ (!empty($site_setting) && is_object($site_setting) && !empty($site_setting->street) ? $site_setting->street : '') }}, {{ (isset($state_name) && !empty($state_name) ? $state_name : '#') }}, {{ (isset($city_name) && !empty($city_name) ? $city_name : '') }}, {{ (!empty($site_setting) && is_object($site_setting) && !empty($site_setting->address_zip) ? $site_setting->address_zip : '') }}.</p>
        </div>
    </div>

</div>

<div class="col-md-3 col-sm-3 col-xs-6 pd-ph mr-foot-mb ">
                               <!--  <div class="widget_ab_item">
                                    <div class="item_icon"><i class="fa fa-phone"></i></div>
                                       <div class="widget_ab_item_text">
                                          <h6 class="text-white">Phone :</h6>
                                          <p>+1 530 497-0075</p>
                                    </div>
                                </div> -->
                                <!-- <div class="widget_ab_item m-top-30 ">
                                    <div class="item_icon"><i class="fa fa-envelope-o"></i></div>
                                        <div class="widget_ab_item_text">
                                           <h6 class="text-white">Email Address :</h6>
                                           <p>info@protectapp.com</p>
                                    </div>
                                </div> -->
                                <div class="widget_ab_item_text dmy">
                        @php
                            $footer_right_content = '';
                            $footer_right_content = App\Helpers\Helper::getContentBySlug('footer-right-content');
                            $footer_right_content = !empty($footer_right_content) && is_object($footer_right_content) && !empty($footer_right_content->content) ? $footer_right_content->content : '';
                            $footer_right_content = strtr($footer_right_content, [
                            '{{LOGO_GREY}}' => '<span class="actual-logo sz-ft2"><img src="' . asset("public/assets/images/actual-logo-grey.png") .'"></span>'
                            ]);
                        @endphp
                                    {!! !empty($footer_right_content) && isset($footer_right_content) ? $footer_right_content : '' !!}

                                </div>

                            </div>



                            <div class="col-md-3 col-sm-3 col-xs-6 mr-foot-mb ">
                                <div class="widget_item widget_newsletter">
                                    <h2 class="text-white">Newsletter</h2>
                                    <form class="form-inline m-top-30">
                                        <div class="form-group">
                                            <input type="email" class="form-control" placeholder="Enter you Email">
                                            <button type="submit" class="btn text-center"><i class="fa fa-arrow-right"></i></button>
                                        </div>

                                    </form>


                                </div><!-- End off widget item -->
                            </div><!-- End off col-md-3 -->
                            <ul class="footer-list">
                                <li><a href="{{url('/')}}/privacy-policy">Privacy Policy</a></li>
                                <li><a href="{{url('/')}}/terms-conditions">Terms & Conditions</a></li>
                            </ul>

                        </div>
                    </div>
                    <div class="main_footer fix bg-mega text-center  m-top-80">
                        <div class="col-md-12">
                            <p class="wow fadeInRight" data-wow-duration="1s">
                                &copy;Copyright {{date('Y')}} by <span class="actual-logo sz-ft2"><img src="{{ asset('public/assets/images/actual-logo-grey.png') }}"></span>.  All rights reserved.
                            </p>
                        </div>
                    </div>
                </footer>
