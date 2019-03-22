@php
$role = commonHelper::getRoleName(\Auth::user()->id);
$role = !empty($role) && is_object($role) && !empty($role->name) ? $role->name : false;
$disabled = true;

if(!empty($role) && $role =='Organization Admin'){
    $subscriptionCount = \DB::table('organization_subscriptions')->select('id','status','organization_id')->where('organization_id', '=', \Auth::user()->id)->where('status', '=', 'Active')->first();
    if(count($subscriptionCount)==0)
       $disabled = false;
    }

@endphp

<aside class="sidebar-left">
    <nav class="navbar navbar-inverse">
        
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <!--<h1><a class="navbar-brand" href="dashboard"> {{ config('constants.SITE_NAME') }}</a></h1>-->
            <center>
                <a href="{{ route('dashboard') }}" id="navigation-logo">

                    {{ Html::image(asset(env('APP_URL').'/public/assets/images/admin_nav.png'), 'no-image', array('title' => 'Image Preview')) }}
                </a>
            </center>
        </div>
        
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="sidebar-menu">
                <li class="header">&nbsp;</li>
                <li class="treeview {{ Request::is('dashboard', 'admin/dashboard') ? 'active' : '' }}"> <a href="{{ route('dashboard') }}"> 
                        
                        <i class="fa fa-dashboard" title="Dashboard"></i> <span class="menu-title">Dashboard</span> </a> </li>

                @if($role == \Config::get('constants.PLATFORM_ADMIN') || $role == \Config::get('constants.SUB_ADMIN'))

                <li>
                    <a href="{{ route('subscription.index') }}" style="{{ Request::is('admin/subscription', 'admin/subscription/*') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-cc" title="Subscription Management"></i> <span class="menu-title">Subscription Management</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('admin/organization') }}" style="{{ Request::is('admin/organization', 'admin/organization/*') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-users" title="Organization Management"></i> <span class="menu-title">Organization Management</span>
                    </a>
                </li>

                @endif

                @if($role == \Config::get('constants.ORGANIZATION_ADMIN'))
                <li>
                    <a href="{{ !empty($disabled) ? url('admin/subscriptions/plans') : url('admin/subscriptions/change') }}" style="{{ Request::is('admin/subscriptions/plans', 'admin/subscriptions/*') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-cc" disabled="{{$disabled}}" title="Subscription Plan"></i> <span class="menu-title">Subscription Plan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ !empty($disabled) ? url('admin/payment/history') : url('admin/subscriptions/change') }}" style="{{ Request::is('admin/payment/history', 'admin/invoice/detail/*') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-cc" disabled="{{$disabled}}" title="Subscription Plan"></i> <span class="menu-title">Payment History</span>
                    </a>
                </li>
                @endif

                <li>
                    <a href="{{ !empty($disabled) ? url('admin/security')  : "javascript:void(0)" }}" style="{{ Request::is('admin/security', 'admin/security/*') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-key" title="Security Team Management"></i> <span class="menu-title">Security Team Management</span>
                    </a>
                </li>

                <li>
                    <a href="{{ !empty($disabled) ?  url('admin/premise') : "javascript:void(0)" }}" style="{{ Request::is('admin/premise', 'admin/premise/*') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-building" title="Building Management"></i> <span class="menu-title">Building Management</span>
                    </a>
                </li>

                <li>
                    <a href="{{ !empty($disabled) ? url('admin/location') : "javascript:void(0)" }}" style="{{ Request::is('admin/location', 'admin/location/*') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-location-arrow" title="Building Section Management"></i> <span class="menu-title">Building Section Management</span>
                    </a>
                </li>

                <li>
                    <a href="{{ !empty($disabled) ? url('admin/beacon'): "javascript:void(0)" }}" style="{{ Request::is('admin/beacon', 'admin/beacon/*') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-bullseye" title="Beacon Management"></i> <span class="menu-title">Beacon Management</span>
                    </a>
                </li> 

                <li>
                    <a href="{{ !empty($disabled) ? url('admin/aid-request'): "javascript:void(0)" }}" style="{{ Request::is('admin/aid-request', 'admin/aid-request/*', 'admin/aid-request-responded/*') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-user-plus" title="Aid Request Management"></i> <span class="menu-title">Aid Request List</span>
                    </a>
                </li>

                <li>
                    <a href="{{ !empty($disabled) ? url('admin/visitorlogs'): "javascript:void(0)" }}" style="{{ Request::is('admin/visitorlogs', 'admin/visitorlogs/*') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-line-chart" title="Visitor Log Management"></i> <span class="menu-title">Visitor Log List</span>
                    </a>
                </li>

                @if($role == \Config::get('constants.PLATFORM_ADMIN') || $role == \Config::get('constants.SUB_ADMIN'))
                <li>
                    <a href="{{ route('testimonial.index') }}" style="{{ Request::is('admin/testimonial', 'admin/testimonial/*') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-fax" title="Testimonial Management"></i> <span class="menu-title">Testimonial Management</span>
                    </a>
                </li>


                <li><a href="{{ url('admin/static-page') }}" style="{{ Request::is('admin/static-page', 'admin/edit-page/terms-conditions', 'admin/edit-page/privacy-policy', 'admin/edit-page/faq', 'admin/edit-page/guide-user') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-file-text" title="Manage Static Pages"></i> <span class="menu-title">Manage Static Pages</span>
                </a>
            </li>

            <li><a href="{{ url('admin/dynamic-content') }}" style="{{ Request::is('admin/dynamic-content', 'admin/edit-content/*') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-file-text" title="Manage Dynamic Content"></i> <span class="menu-title">Manage Dynamic Content</span>
                </a>
            </li>

             
             @if($role == \Config::get('constants.PLATFORM_ADMIN'))
           
             <li><a href="{{ url('admin/admin-users') }}" style="{{ Request::is('admin/admin-users', 'admin/edit-content/*', 'admin/admin-user/*') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-file-text" title="Manage Admin User"></i> <span class="menu-title">Manage Admin User</span>
                </a>
            </li>
            <li><a href="{{ url('admin/transaction-details') }}" style="{{ Request::is('admin/transaction-details', 'admin/transaction-detail/*', 'admin/invoice/detail/*') ? 'background-color:rgba(0,0,0,.1); color:#fff' : '' }}"><i class="fa fa-file-text" title="Transaction Details"></i> <span class="menu-title">Manage Transaction Details</span>
                </a>
            </li>
            @endif
            @endif

        </ul>
    </div>
    <!-- /.navbar-collapse --> 
</nav>
</aside>
