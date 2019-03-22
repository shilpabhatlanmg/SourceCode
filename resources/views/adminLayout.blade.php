<!DOCTYPE html>
<html>
<head>
    @include('common.admin.head')
</head>
<body class="cbp-spmenu-push">
    <div class="main-content">
        <div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
            @include('common.admin.sidebar')
        </div>
        @include('common.admin.header')
        @yield('content')
        @include('common.admin.footer')
    </div>

    <!--======================csrf toke generate for ajax request=========================-->
    <script> var csrf_token = "{{ csrf_token() }}"</script>
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/jquery-1.11.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/metisMenu.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/custom.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/admin_assets/js/jquery-ui.js') }}"></script>

    <!-----------drop down search plugin-------------->
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/chosen.jquery.js') }}"></script>


    <!-----------sidebar menu handling js-------------->
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/classie.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/SidebarNav.min.js') }}"></script>

    <!-----------scroll bar handling js-------------->
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/jquery.nicescroll.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/scripts.js') }}"></script>

    <!-----------loader handling js-------------->
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/jquery.loading.block.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/loader.function.js') }}"></script>

    <!-----------validation handling js-------------->
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/jquery.validate.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/jquery.mask.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/additional-methods.min.js') }}"></script>
    
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/client-validation.js') }}"></script>


    <!-----------confirmation popup handling js-------------->
    <script type="text/javascript" src="{{ asset('/public/admin_assets/js/app-js/jquery.confirm.js') }}"></script>

    <!-----------Language Translator plugin-------------->
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <script>
        $('.sidebar-menu').SidebarNav()

        var menuLeft = document.getElementById('cbp-spmenu-s1'),
        showLeftPush = document.getElementById('showLeftPush'),
        body = document.body,
        menuSpans = document.getElementsByClassName('menu-title'),
        navigationLogo = document.getElementById('navigation-logo');

        function doClicked(element, flag){
            if(flag == undefined){
                if(localStorage.getItem('menuOn')){
                    localStorage.removeItem('menuOn');
                }else{
                    localStorage.setItem('menuOn', true);
                }
            }
            classie.toggle(element, 'active');
            classie.toggle(body, 'cbp-spmenu-push-toright');
                //classie.toggle(menuLeft, 'cbp-spmenu-open');                
                classie.toggle(navigationLogo, 'logo-hide');
                disableOther('showLeftPush');
            }

            showLeftPush.onclick = function () {
                var element = this;
                doClicked(element);                
            };
            if(localStorage.getItem('menuOn')){
                doClicked(showLeftPush, true)
            }


            function disableOther(button) {
                if (button !== 'showLeftPush') {
                    classie.toggle(showLeftPush, 'disabled');
                }
            }

            $(".chosen-select").chosen()

            
        </script>

        @yield('jscript')
    </body>
    </html>