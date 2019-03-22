@extends('adminLayout')

@section('content')

@section('pageTitle')
News Letter
@endsection

<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <h2 class="title1">News Letter Management</h2>
        <div class="blank-page widget-shadow scroll" id="style-2 div1">
            @include('common.admin.flash-message')
            <div class="row">

                <div class="col-md-12">

                    <h3 class="divider-sec text-right">@yield('pageTitle')</h3>
                    <div id="tableDiv"></div>
                    <!--<button id="sendTriger" class="btn btn-primary" data-href="{{URL::to('newsletters')}}">Send Bulk</button>-->
                    <button id="deleteTriger" class="btn btn-danger">Delete Selected</button>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('addtional_css')
@endsection

@section('jscript')
<!-----------datatable handling js-------------->
<script type="text/javascript" src="{{asset('/admin_assets/js/datatable/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/admin_assets/js/datatable/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/admin_assets/js/datatable/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/admin_assets/js/datatable/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/admin_assets/js/datatable/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{asset('/admin_assets/js/datatable/buttons.html5.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js"></script>

<!-----------newsletter listing  js-------------->
<script type="text/javascript" src="{{asset('/admin_assets/js/bringit-js/newsletter.js')}}"></script>
@endsection