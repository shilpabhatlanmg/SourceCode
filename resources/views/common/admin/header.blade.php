@php
$role = \Auth::user()->roles->first()->name;
@endphp

<div class="sticky-header header-section ">
    <div class="header-left"> 
        <!--toggle button start-->
        <button id="showLeftPush"><i class="fa fa-bars"></i></button>
        <!--toggle button end-->
        <div class="profile_details_left"><!--notifications of menu start -->
            <!--<ul class="nofitications-dropdown">
                <li class="dropdown head-dpdn"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-envelope"></i><span class="badge">4</span></a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="notification_header">
                                <h3>You have 3 new messages</h3>
                            </div>
                        </li>
                        <li><a href="#">
                                <div class="user_img"><img src="{{ asset('/assets/images/1.jpg') }}" alt=""></div>
                                <div class="notification_desc">
                                    <p>Lorem ipsum dolor amet</p>
                                    <p><span>1 hour ago</span></p>
                                </div>
                                <div class="clearfix"></div>
                            </a></li>
                        <li class="odd"><a href="#">
                                <div class="user_img"><img src="{{ asset('/assets/images/4.jpg') }}" alt=""></div>
                                <div class="notification_desc">
                                    <p>Lorem ipsum dolor amet </p>
                                    <p><span>1 hour ago</span></p>
                                </div>
                                <div class="clearfix"></div>
                            </a></li>
                        <li><a href="#">
                                <div class="user_img"><img src="{{ asset('/assets/images/3.jpg') }}" alt=""></div>
                                <div class="notification_desc">
                                    <p>Lorem ipsum dolor amet </p>
                                    <p><span>1 hour ago</span></p>
                                </div>
                                <div class="clearfix"></div>
                            </a></li>
                        <li><a href="#">
                                <div class="user_img"><img src="{{ asset('/assets/images/2.jpg') }}" alt=""></div>
                                <div class="notification_desc">
                                    <p>Lorem ipsum dolor amet </p>
                                    <p><span>1 hour ago</span></p>
                                </div>
                                <div class="clearfix"></div>
                            </a></li>
                        <li>
                            <div class="notification_bottom"> <a href="#">See all messages</a> </div>
                        </li>
                    </ul>
                </li>
            </ul>-->
            <div class="clearfix"> </div>
        </div>
        <!--notification menu end -->
        <div class="clearfix"> </div>
    </div>
    <div class="header-right">
        <!--<div id="google_translate_element"></div>-->
        <div class="profile_details">
            <ul>
                <li class="dropdown profile_details_drop"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <div class="profile_img"> 
                            <!--<span class="prfil-img">
                                <?php $path = 'storage/admin/profile_image/' . (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) ? Auth::guard('admin')->user()->user_image : ''); ?>

                                @if(!empty(Auth::guard('admin')->user()->user_image) && file_exists($path))
                                <img src="{{ asset($path) }}" class="img-responsive" height="50" width="50" alt="{{ (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) ? Auth::guard('admin')->user()->user_image : '') }}">
                                @else
                                <img src="{{ asset('admin_assets/images/user-img.png') }}" height="50" width="50" class="img-responsive" alt="no-image">
                                @endif
                            </span>-->
                            <div class="user-name">
                                <p>{{ config('constants.SITE_NAME') }}</p>
                                <span>{{ (!empty(Auth::guard('admin')->user()) && is_object(Auth::guard('admin')->user()) ? Auth::guard('admin')->user()->name : '') }}</span> </div>
                            <i class="fa fa-cog lnr"></i> 
                            <div class="clearfix"></div>
                        </div>
                    </a>
                    <ul class="dropdown-menu drp-mnu">
                        @if($role == \Config::get('constants.PLATFORM_ADMIN') &&  \Auth::user()->is_root_admin==1)
                        <li> <a href="{{ route('site.setting') }}"><i class="fa fa-cog"></i> Settings</a> </li>
                        @endif
                        <li> <a href="{{ route('admin.change.password') }}"><i class="fa fa-user"></i>Change Password</a> </li>
                        <li> <a href="{{ route('admin.profile') }}"><i class="fa fa-suitcase"></i>My Profile</a> </li>
                        <li>
                            <a href="{{ route('admin.logout') }}"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out"></i> Logout</a>
                            </a>

                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="clearfix"> </div>
    </div>
    <div class="clearfix"> </div>
</div>