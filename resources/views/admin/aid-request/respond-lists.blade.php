@extends('adminLayout')
@section('content')
@section('pageTitle')
{{ (isset($title) && !empty($title) ? $title : '') }}
@endsection

<div id="page-wrapper">
	@include('common.admin.breadcrumb')
	<div class="main-page">
		<div class="forms">
			<h2 class="title1">Attendee List</h2>

			@if(!empty($objData) && count($objData) > 0)

			<div class="form-three widget-shadow"  style="overflow:hidden">
				@foreach($objData as $responseData)

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">User Name</label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($responseData) && !empty($responseData->getUser->name) ? $responseData->getUser->name : '') }}
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Contact Number </label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						{{ (!empty($responseData) && !empty($responseData->getUser->contact_number) ?   $responseData->getUser->contact_number : '') }}
					</div>
				</div>

				
				<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
					<label for="disabledinput" class="col-md-3 col-sm-4 col-xs-12 control-label">Response Date/Time </label>
					<div class="col-sm-8 col-md-9 col-xs-12">
						<script>
							var localDate = getLocalDateTime("<?php echo $responseData->created_at; ?>");
						</script>

						@php

						$localTime = "<script>document.write(localDate)</script>";
						echo $localTime;

						@endphp
					</div>
				</div>
				
				<div class="br_btm" ></div>

				@endforeach  

			</div>
			@else
			<div>"No Record Found"</div>
			@endif

		</div>
	</div>
</div>

@endsection
@section('addtional_css')
@endsection

@section('jscript')

@endsection
