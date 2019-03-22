@extends('adminLayout')
@section('content')
@section('pageTitle')
{{ (isset($title) && !empty($title) ? $title : '') }}
@endsection

<div id="page-wrapper">
	@include('common.admin.breadcrumb')
	<div class="main-page">
		<div class="forms">
			<h2 class="title1">Organization Management</h2>

			<div class="form-three widget-shadow">

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

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Date of Expiry</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->subscriptionDetail->expiry_date) ?(($objData->subscriptionDetail->expiry_date != null)? \Carbon\Carbon::parse($objData->subscriptionDetail->expiry_date)->setTimezone($timezone)->format('d-M-Y h:i:s'):\Carbon\Carbon::parse($objData->subscriptionDetail->expiry_date)->format('d-M-Y h:i:s')) : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Last Date of Renewal</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						---
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Subscription Plan</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->subscriptionDetail->getSubscriptionDetail->plan_name) && isset($objData->subscriptionDetail->getSubscriptionDetail->plan_name) ? $objData->subscriptionDetail->getSubscriptionDetail->plan_name : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Subscription Status</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->subscriptionDetail->status) && isset($objData->subscriptionDetail->status) ? $objData->subscriptionDetail->status : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Status</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->status) && isset($objData->status) ? $objData->status : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Deactivation Reason</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{!! ( !empty($objData) && is_object($objData) && !empty($objData->reason) && $objData->status == 'Inactive' ? $objData->reason : '---') !!}
					</div>
				</div>

				<span class="detail_txt">Main POC details</span>

				<hr>
				@if(!empty($arr_poc_detail) && is_object($arr_poc_detail) && count($arr_poc_detail) > 0)
				@php
				$x = 1;
				@endphp

				@foreach($arr_poc_detail as $poc_data)
				<div class="row parentss">
					<div class="col-md-4 col-sm-4 col-xs-12 pd-none ">
						@if($x == 1)
						<label class="lb-head">POC name</label>
						@endif

						<div class="col-xs-12 pd-less p-name">
							<div class="row">

								{{ (!empty($poc_data) && is_object($poc_data) && !empty($poc_data->poc_name) ? $poc_data->poc_name : '') }}
							</div>
						</div>

					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 pd-none">
						@if($x == 1)
						<label class="lb-head ">POC contact number</label>
						@endif
						<div class="col-xs-12 pd-less p-number">

							{{ (!empty($poc_data) && is_object($poc_data) && !empty($poc_data->poc_contact_no) ? $poc_data->poc_contact_no : '') }}
						</div>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 pd-none">
						@if($x == 1)
						<label class="lb-head ">POC email id:</label>
						@endif
						<div class="col-xs-12 pd-less p-mail ">

							{{ (!empty($poc_data) && is_object($poc_data) && !empty($poc_data->poc_email) ? $poc_data->poc_email : '') }}
						</div>
					</div>
				</div>
				<hr>

				@php
				$x++;
				@endphp

				@endforeach

				@endif

			</div>
		</div>
	</div>
</div>

@endsection
@section('addtional_css')
@endsection

@section('jscript')

@endsection
