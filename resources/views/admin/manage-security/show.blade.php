@extends('adminLayout')
@section('content')
@section('pageTitle')
{{ (isset($title) && !empty($title) ? $title : '') }}
@endsection

<div id="page-wrapper">
	@include('common.admin.breadcrumb')
	<div class="main-page">
		<div class="forms">
			<h2 class="title1">Security Management</h2>

			<div class="form-three widget-shadow" style="width:100%; display:block; float:left; margin: 0 0 30px 0;">

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Organization Name</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->getOrganizationName->name) ? $objData->getOrganizationName->name : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Name </label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->name) ? $objData->name : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Email</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->email) ? $objData->email : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Contact Number</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($objData) && is_object($objData) && !empty($objData->contact_number) ? $objData->country_code.' '.$objData->contact_number : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Account Status</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						@php

						$status = '';
						if ($objData->status == 'Inactive') {
						$status = "Inactive";
						$label = "danger";
						$sts = 'Active';
					} else {
					$status = "Active";
					$label = "success";
					$sts = 'Inactive';
				}

				@endphp

				{{ $status }}
			</div>
		</div>



		<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
			<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Invitation Status</label>
			<div class="col-sm-8 col-md-9 col-xs-12">
				@php

				if ($objData->invitation_status == 'complete') {
				$invit_status = "Complete";
				$invit_label = "success";

			} else if ($objData->invitation_status == 'resend-invitation') {
			$invit_status = "Resend Invitation";
			$invit_label = "primary";

		} else {
		$invit_status = "Pending";
		$invit_label = "danger";
	}

	@endphp

	{{ $invit_status }}
</div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
	<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Profile</label>
	<div class="col-sm-8 col-md-9 col-xs-12">

		@php 

		$path = 'public/storage/admin_assets/images/profile_image/' . (!empty($objData) && is_object($objData) && !empty($objData->profile_image) ? $objData->profile_image : '');

		@endphp

		@if(!empty($objData) && is_object($objData) && !empty($objData->profile_image) && file_exists($path))

		{{ Html::image($path, 'image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview', 'title' => (!empty($objData) && is_object($objData) && !empty($objData->profile_image) ? $objData->profile_image : '' ), 'class' => 'img_prvew')) }}

		@else

		{{ Html::image(asset('public/admin_assets/images/user-profile.jpg'), 'no-image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview', 'title' => 'Image Preview', 'class' => 'img_prvew')) }}

		@endif
	</div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
	<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Last Login</label>
	<div class="col-sm-8 col-md-9 col-xs-12">
		{{ (!empty($objData) && is_object($objData) ? \Carbon\Carbon::parse($objData->last_login)->format('d-M-Y h:i:s') : '') }}
	</div>
</div>

</div>
</div>
</div>
</div>


@endsection
@section('addtional_css')
@endsection

@section('jscript')

@endsection