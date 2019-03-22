<header>
    <div class="head-top">
        <div class="container">
            <div class="col-md-8 col-sm-9 col-xs-6">
                <p>
                    <?php
                    $state_name = '';
                    if (isset($site_setting) && !empty($site_setting) && is_object($site_setting) && !empty($site_setting->state_id)) {
                        $state_name = App\Helpers\Helper::getStateNameById($site_setting->state_id);
                    }
                    if (isset($site_setting) && !empty($site_setting) && is_object($site_setting) && !empty($site_setting->city_id)) {
                        $city_name = App\Helpers\Helper::getCityNameById($site_setting->city_id);
                    }

                    ?>
                    <span span class="location">
                        <img src="{{ asset('/public/assets/images/location.png') }}" alt="icon" class="img-responsive">{{ (isset($site_setting) && !empty($site_setting) && is_object($site_setting) && !empty($site_setting->street) ? $site_setting->street : '') }} {{ (isset($state_name) && !empty($state_name) ? $state_name : '#') }}, {{ (isset($city_name) && !empty($city_name) ? $city_name : '') }}, {{ (isset($site_setting) && !empty($site_setting) && is_object($site_setting) && !empty($site_setting->address_zip) ? $site_setting->address_zip : '') }}
                    </span> 
                    <span class="mail">
                        <img src="{{ asset('/public/assets/images/telephone.png') }}" alt="icon" class="img-responsive">{{ (isset($site_setting) && !empty($site_setting) && is_object($site_setting) && !empty($site_setting->address_phone) ? $site_setting->address_phone : '') }}
                    </span>
                </p>
            </div> 

            <div class="col-md-4 col-sm-3 col-xs-6">

                <div class="top-menu">
                    <ul>
                        @if(!Auth::check())
                        <li>
                            <a href="{{route('login')}}">
                                <i class="fa fa-user-circle custom-login" aria-hidden="true"></i>Sign in
                            </a>
                        </li>
                        @endif

                        @if(Auth::check())
                        <li class="dropdown dropdown2">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user-circle custom-login" aria-hidden="true"></i>My Account
                            </a>

                            <ul class="dropdown-menu dropdown-menu2">
                                <li><a href="{{ route('profile') }}">Profile</a></li>
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <i></i> Logout</a>
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header><!--end of top header-->