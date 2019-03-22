<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('pageTitle')</title>
@yield('addtional_css')
@php($path = 'public/storage/fevicon/' . (isset($site_setting) && !empty($site_setting) && is_object($site_setting) && !empty($site_setting->fevicon) ? $site_setting->fevicon : ''))
<link rel="icon" href="{{ asset($path) }}" type="image/x-icon">
<link href="{{ asset('/public/admin_assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/public/admin_assets/css/style.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('/public/admin_assets/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/public/admin_assets/css/SidebarNav.min.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('/public/admin_assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">

<!--<link href="{{ asset('/public/admin_assets/css/layout.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/public/assets/css/jquery-ui.css') }}" rel="stylesheet" type="text/css" media="all"/>-->
	<link href="{{ asset('/public/admin_assets/css/jquery-ui.css') }}" rel="stylesheet" type="text/css" media="all"/>

	<!--------jquery confirmation popup----------->
	<link href="{{ asset('/public/admin_assets/css/app-css/jquery-confirm.css') }}" rel="stylesheet" type="text/css" />


	<!--------Data response css----------->
	<link href="{{ asset('/public/admin_assets/css/app-css/table.css') }}" rel="stylesheet" type="text/css" />

	<link href="{{ asset('/public/admin_assets/css/app-css/table-responsive.css') }}" rel="stylesheet" type="text/css" />


	<!-----------drop down search plugin css-------------->
	<link href="{{ asset('/public/admin_assets/css/chosen.css') }}" rel="stylesheet" type="text/css" />

	<!--------Data table css----------->
<!--<link href="{{ asset('/public/admin_assets/css/datatable/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/public/admin_assets/css/datatable/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />-->

	<script>
		var siteURL = "<?php echo env('APP_URL'); ?>";

		function getLocalDateTime(utc_date){

			var utc_date = utc_date+'Z';

			var months = ["Jan","Feb","Mar","Apr","May","June","July","Aug","Sept","Oct","Nov","Dec"];

			var localDate = new Date(utc_date);
			

        	//return formatAMPM(localDate)

        	return localDate.getDate()+'-'+ months[localDate.getMonth()]+'-'+localDate.getFullYear()+' '+localDate.getHours()+':'+localDate.getMinutes()+':'+localDate.getSeconds();
        	
        }

        function formatAMPM(date) {
        	var hours = date.getHours();
        	var minutes = date.getMinutes();
        	var ampm = hours >= 12 ? 'pm' : 'am';
        	hours = hours % 12;
		hours = hours ? hours : 12; // the hour '0' should be '12'
		minutes = minutes < 10 ? '0'+minutes : minutes;
		var strTime = hours + ':' + minutes + ' ' + ampm;
		return strTime;
	}
</script>