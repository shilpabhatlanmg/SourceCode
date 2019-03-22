@extends('adminLayout')
@section('content')
@section('pageTitle')
{{ (isset($title) && !empty($title) ? $title : '') }}
@endsection

<div id="page-wrapper">
	@include('common.admin.breadcrumb')
	<div class="main-page">
		<div class="forms">
			<h2 class="title1"> {{(!empty($objData->id))?'Edit Beacon':'Create Beacon' }}</h2>

			<div class="form-three widget-shadow">
				@if(!empty($saveId) && is_object($saveId))
				@include('common.admin.flash-message')
				@endif

				{!!
					Form::open(
					array(
					'name' => 'frmsave',
					'id' => 'admin_becon',
					'url' => 'admin/beacon/'.(!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : ''),
					'autocomplete' => 'off',
					'class' => 'form-horizontal',
					'files' => true
					)
					)
					!!}
					@section('editMethod')
					@show

					<div class="form-group">
						<label for="focusedinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">UUID</label>
						<div class="col-sm-8 col-xs-12">

							{{ Form::text('uuid', (isset($uuid) && !empty($uuid) ? $uuid : '') ,['class'=>'form-control','rows'=>'2', 'readonly' => true, 'placeholder' => 'Enter UUID'])}}
							@if ($errors->has('uuid'))
							<span class="help-block" style = "display:block;color:red;">
								<strong>{{ $errors->first('uuid') }}</strong>
							</span>
							@endif
						</div>
					</div>


					@if($roles->name == \Config::get('constants.PLATFORM_ADMIN'))
					<div class="form-group">
						<label for="focusedinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Organization *</label>
						<div class="col-sm-8 col-xs-12">
							@php

								$orgId = (!empty($organization_id) && isset($organization_id) ? $organization_id : '');
							
							@endphp

							{{ Form::select('organization_id',['' => '--select--']+@$adminorganisation->pluck('name','id')->toArray(), ( !empty($objData) && is_object($objData) && !empty($objData->organization_id) ? $objData->organization_id : $orgId),['id'=> 'organization_id', 'class'=>'form-control organization_id chosen-select'])}}

							<span class="major_id" style = "display:block;color:green;">{!! (!empty($becaonId) && isset($becaonId) && !empty($becaonId->becon_major_id) && isset($becaonId->becon_major_id) ? 'Major ID : '.$becaonId->becon_major_id : '') !!}</span>
							@if ($errors->has('organization_id'))
							<span class="help-block" style = "display:block;color:red;">
								<strong>{{ $errors->first('organization_id') }}</strong>
							</span>
							@endif
						</div>
					</div>
					@endif

					<div class="form-group">
						<label for="focusedinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Building Name *</label>
						<div class="col-sm-5 col-xs-10">



							{{ Form::select('premise_id',['' => '--select--']+@$premiselist->pluck('name','id')->toArray(), ( !empty($objData) && is_object($objData) && !empty($objData->premise_id) ? $objData->premise_id : ''),['id'=> 'premise_id', 'class'=>'form-control premise_id', 'style' => (($premiselist->count() == 0)?'display:none':'')])}}

							{{ Form::text('premise_text_id', '' ,['class'=>'form-control', 'placeholder' => 'Building Name', 'id'=> 'premise_text_id', 'style' => (($premiselist->count() > 0)?'display:none':'')])}}

							@if ($errors->has('premise_text_id'))
							<span class="help-block" style = "display:block;color:red;">
								<strong>{{ $errors->first('premise_text_id') }}</strong>
							</span>
							@endif


							@if ($errors->has('premise_id'))
							<span class="help-block" style = "display:block;color:red;">
								<strong>{{ $errors->first('premise_id') }}</strong>
							</span>
							@endif
						</div>
						<div class="col-sm-3 col-xs-2">
							<button id="add-premise" class="btn btn-info btn-xs premis " style="margin-top:5px;{{(!empty($objData->id))?'':'display:none'}}" title="Add more building" type="button"><i class="fa fa-plus">&nbsp;</i></button>
							<button id="list-premise" class="btn btn-info btn-xs premis2 " style="margin-top:5px;" title="Choose from the building list" type="button"><i class="fa fa-caret-square-o-down"></i></button>
						</div>
					</div>

					<div class="form-group">
						<label for="disabledinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Building Section Name *</label>
						<div class="col-sm-5 col-xs-10">
							@php
								$locationCount = '';
							@endphp

								{{ Form::select('location_id',['' => '--select--']+((!empty($locationlist))?@$locationlist->pluck('name','id')->toArray():[]), ( !empty($objData) && is_object($objData) && !empty($objData->location_id) ? $objData->location_id : ''),['id'=> 'location_id', 'class'=>'form-control location_id', 'style' => (($locationCount == 0)?'display:none':'')])}}


								{{ Form::text('location_text_id', '' ,['class'=>'form-control', 'placeholder' => 'Building Section Name','id'=> 'location_text_id', 'style' => (($locationCount > 0)?'display:none':'')])}}

								@if ($errors->has('location_text_id'))
							<span class="help-block" style = "display:block;color:red;">
								<strong>{{ $errors->first('location_text_id') }}</strong>
							</span>
							@endif

							@if ($errors->has('location_id'))
							<span class="help-block" style = "display:block;color:red;">
								<strong>{{ $errors->first('location_id') }}</strong>
							</span>
							@endif
						</div>
						<div class="col-sm-3 col-xs-2">
							<button id="add-location" class="btn btn-info btn-xs premis" style="margin-top:5px;{{($locationCount==0)?'display: none;':''}}" title="Add more Building Section" type="button"><i class="fa fa-plus">&nbsp;</i></button>
							<button id="list-location" class="btn btn-info btn-xs premis2" style="margin-top:5px;{{($locationCount==0)?'display: none;':''}}" title="Choose from the Building Section list" type="button"><i class="fa fa-caret-square-o-down"></i></button>
						</div>
					</div>

					@if($roles->name != \Config::get('constants.PLATFORM_ADMIN'))

					<div class="form-group">
						<label for="disabledinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Beacon Numeric ID</label>
						<div class="col-sm-8 col-xs-12">

							{{ Form::text('major_id', (!empty(Auth::user()->becon_major_id) ? Auth::user()->becon_major_id : '') ,['class'=>'form-control','rows'=>'2', 'readonly' => true, 'placeholder' => 'Major ID'])}}

							@if ($errors->has('major_id'))
							<span class="help-block" style = "display:block;color:red;">
								<strong>{{ $errors->first('major_id') }}</strong>
							</span>
							@endif
						</div>
					</div>

					@endif

					<div class="form-group">
						<label for="disabledinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Beacon Identifier</label>
						<div class="col-sm-8 col-xs-12">
							{{ Form::text('name', (!empty($objData) && is_object($objData) && !empty($objData->name) ? $objData->name : '') ,['class'=>'form-control','rows'=>'2', 'placeholder' => 'Beacon Identifier'])}}
							@if ($errors->has('name'))
							<span class="help-block" style = "display:block;color:red;">
								<strong>{{ $errors->first('name') }}</strong>
							</span>
							@endif
						</div>
					</div>

					<div class="form-group">
						<label for="disabledinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Minor Id *</label>
						<div class="col-sm-8 col-xs-12">
							{{ Form::text('minor_id', (!empty($objData) && is_object($objData) && !empty($objData->minor_id) ? $objData->minor_id : '') ,['class'=>'form-control','rows'=>'2', 'placeholder' => 'Enter Minor ID *'])}}
							@if ($errors->has('minor_id'))
							<span class="help-block" style = "display:block;color:red;">
								<strong>{{ $errors->first('minor_id') }}</strong>
							</span>
							@endif
						</div>
					</div>

					<div class="form-group">
						<label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Status *</label>
						<div class="col-sm-8 col-xs-12">
							{{Form::select('status',['Active' => 'Active', 'Inactive' => 'Inactive'], ( !empty($objData) && is_object($objData) && !empty($objData->status) ? $objData->status : ''),['class'=>'form-control'])}}
							@if ($errors->has('status'))
							<span class="help-block" style = "display:block;color:red;">
								<strong>{{ $errors->first('status') }}</strong>
							</span>
							@endif
						</div>
					</div>

					<div class="col-sm-offset-2">

						{{ Form::hidden('becon_id', (!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : '')) }}

						{{ Form::hidden('location_edit_id', ( !empty($objData) && is_object($objData) && !empty($objData->location_id) ? $objData->location_id : '')) }}

						{{ Form::hidden('premise_edit_id', ( !empty($objData) && is_object($objData) && !empty($objData->premise_id) ? $objData->premise_id : '')) }}

						@if(isset($objData) && !empty($objData) && is_object($objData) && !empty($objData->id))
						<button class="btn btn-primary">Update</button>
						@else
						<button class="btn btn-primary">Save & Continue</button>
						@endif

						<a href="{{route('beacon.index')}}">
							<button class="btn btn-danger" type="button">Cancel</button>
						</a>
					</div>

					{!! Form::close()!!}
				</div>
			</div>
		</div>
	</div>


	<!-----------Premise Model----------->
	<div class="modal fade" id="premiseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="exampleModalLabel">Add Building</h4>

					<div class="flash-message">

						<div class="alert-msg" style="display:none;">
							<span class="alermsg"></span><!--<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->
						</div>
					</div>


				</div>
				{{ Form::open(array('method' => 'post', 'url' => 'admin/premiseAdd', 'id' => 'becon_primise')) }}
				<div class="modal-body">


					<div class="form-group">
						<label for="recipient-name" class="control-label">Building Name:</label>
						{{ Form::text('name', app('request')->input('name'), ['placeholder'=>'Building Name...', 'class'=>'form-control']) }}

						{{ Form::hidden('organization_id', null, array('id' => 'org_premise')) }}
						{{ Form::hidden('status', 'Active', array('id' => 'premise_status')) }}
						{{ Form::hidden('_token', null, array('id' => 'premise_csrf_token')) }}

					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" name="premise_submit" class="btn btn-primary">Submit</button>
				</div>


			</div>
			{!! Form::close()!!}
		</div>
	</div>

	<!-----------Location Model----------->
	<div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="exampleModalLabel">Add Building Section</h4>

					<div class="flash-message">

						<div class="alert-msg" style="display:none;">
							<span class="alermsg"></span><!--<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->
						</div>
					</div>

				</div>

				{{ Form::open(array('method' => 'post', 'url' => 'admin/locationAdd', 'id' => 'becon_location')) }}
				<div class="modal-body">


					<div class="form-group">
						<label for="recipient-name" class="control-label">Building Section Name:</label>
						{{ Form::text('name', app('request')->input('name'), ['placeholder'=>'Building Section Name...', 'class'=>'form-control']) }}
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" name="location_submit" class="btn btn-primary">Submit</button>
				</div>
				{{ Form::hidden('organization_id', null, array('id' => 'org_location')) }}
				{{ Form::hidden('premise_id', null, array('id' => 'pre_id')) }}
				{{ Form::hidden('status', 'Active', array('id' => 'location_status')) }}
				{{ Form::hidden('_token', null, array('id' => 'location_csrf_token')) }}

			</div>
			{!! Form::close()!!}
		</div>
	</div>

	@endsection
	@section('addtional_css')
	@endsection

	@section('jscript')

	<script type="text/javascript" src="{{asset('public/admin_assets/js/app-js/beacon.js')}}"></script>
	
	@if((!empty(request()->all()['location_id']) && request()->all()['location_text_id'] == "" && request()->all()['location_id'] > 0) || (!empty($becaonId)))
	<script>
		/*$(function(){
			$('#location_text_id').hide();
            $('#location_id').show();
            $('#add-location').show();
            $('#list-location').hide();
            $('#list-premise').hide();
		});*/

		$(function(){

			$('#location_text_id').hide();
            $('#location_id').show();
            $('#add-location').show();
            $('#list-location').hide();
            $('#add-premise').show();
            $('#list-premise').hide();
		});
	</script>
	@endif;



	@if((!empty(request()->all()['location_text_id']) && request()->all()['location_id'] == ""))
	<script>

		$(function(){

			$('#location_text_id').show();
            $('#location_id').hide();
            $('#add-location').hide();
            $('#list-location').show();
            $('#add-premise').show();
            $('#list-premise').hide();
		});
	</script>
	@endif;


	@if(!empty(request()->all()['premise_text_id']) && empty(request()->all()['premise_id']))
	<script>
		$(function(){
			$('#premise_text_id').show();
            $('#premise_id').hide();
            $('#add-premise').hide();
            $('#list-premise').show();
		});
	</script>
	@endif;

	@endsection
