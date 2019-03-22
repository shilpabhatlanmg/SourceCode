@extends('adminLayout')
@section('content')
@section('pageTitle')
{{ (isset($title) && !empty($title) ? $title : '') }}
@endsection

<div id="page-wrapper">
	@include('common.admin.breadcrumb')
	<div class="main-page">
		<div class="forms">
			<h2 class="title1">View Admin User</h2>

			<div class="form-three widget-shadow" style="overflow:hidden">

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Organization Name</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->name) ? $objData->name : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Email </label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->email) ? $objData->email : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Address</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->address) ? $objData->address : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Country</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->countryName->name) && isset($objData->countryName->name) ? $objData->countryName->name : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">State</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->stateName->name) && isset($objData->stateName->name) ? $objData->stateName->name : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">City</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->cityName->name) && isset($objData->cityName->name) ? $objData->cityName->name : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Zip Code</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->zip_code) && isset($objData->zip_code) ? $objData->zip_code : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Contact Number</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->phone) && isset($objData->phone) ? $objData->phone : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Date of Registration</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						@php
						$timezone = timezone_name_from_abbr("", $objData->timezone*60, false);
						@endphp
						{{ (!empty($objData) && is_object($objData) ?(($objData->timezone != null)? \Carbon\Carbon::parse($objData->created_at)->setTimezone($timezone)->format('d-M-Y h:i:s'):\Carbon\Carbon::parse($objData->created_at)->format('d-M-Y h:i:s')) : '') }}
					</div>
				</div>

				

				<hr>
				

			</div>
		</div>
	</div>
</div>

@endsection
@section('addtional_css')
@endsection

@section('jscript')

@endsection
