@extends('adminLayout')
@section('content')
@section('pageTitle')
{{ (isset($title) && !empty($title) ? $title : '') }}
@endsection

<div id="page-wrapper">
	@include('common.admin.breadcrumb')
	<div class="main-page">
		<div class="forms">
			<h2 class="title1"> {{(!empty($objData->id))?'Edit Building Section':'Create Building Section' }}</h2>

			<div class="form-three widget-shadow">

				{!!
					Form::open(
					array(
					'name' => 'frmsave',
					'id' => 'admin_location',
					'url' => 'admin/location/'.(!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : ''),
					'autocomplete' => 'off',
					'class' => 'form-horizontal',
					'files' => true
					)
					)
					!!}
					@section('editMethod')
					@show


					@if($roles->name == \Config::get('constants.PLATFORM_ADMIN'))
					<div class="form-group">
						<label for="focusedinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Organization *</label>
						<div class="col-sm-8 col-xs-12">

							{{ Form::select('organization_id',['' => '--select--']+@$adminorganisation->pluck('name','id')->toArray(), ( !empty($objData) && is_object($objData) && !empty($objData->organization_id) ? $objData->organization_id : $organization_id),['id'=> 'organization_id', 'class'=>'form-control organization_id chosen-select', 'data-type' => 'loc'])}}
							@if ($errors->has('organization_id'))
							<span class="help-block" style = "display:block;color:red;">
								<strong>{{ $errors->first('organization_id') }}</strong>
							</span>
							@endif
						</div>
					</div>
					@endif

					<div class="form-group">
						<label for="focusedinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Building *</label>
						<div class="col-sm-8 col-xs-12">

							{{ Form::select('premise_id',['' => '--select--']+@$premiselist->pluck('name','id')->toArray(), ( !empty($objData) && is_object($objData) && !empty($objData->premise_id) ? $objData->premise_id : ''),['id'=> 'premise_id', 'class'=>'form-control premise_id', 'data' => 'location'])}}
							@if ($errors->has('premise_id'))
							<span class="help-block" style = "display:block;color:red;">
								<strong>{{ $errors->first('premise_id') }}</strong>
							</span>
							@endif
						</div>
					</div>

					<div class="form-group">
						<label for="disabledinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Building Section Name *</label>
						<div class="col-sm-8 col-xs-12">
							{{ Form::text('name', (!empty($objData) && is_object($objData) && !empty($objData->name) ? $objData->name : '') ,['class'=>'form-control','rows'=>'2', 'placeholder' => 'Enter Building Section Name *'])}}

							@if ($errors->has('name'))
							<span class="help-block" style = "display:block;color:red;">
								<strong>{{ $errors->first('name') }}</strong>
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

						{{ Form::hidden('location_id', (!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : '')) }}

						@if(isset($objData) && !empty($objData) && is_object($objData) && !empty($objData->id))
						<button class="btn btn-primary">Update</button>
						@else
						<button class="btn btn-primary">Save</button>
						@endif

						<a href="{{route('location.index')}}">
							<button class="btn btn-danger" type="button">Cancel</button>
						</a>
					</div>

					{!! Form::close()!!}
				</div>
			</div>
		</div>
	</div>
	@endsection

	@section('addtional_css')
	@endsection

	@section('jscript')
        <script type="text/javascript" src="{{asset('public/admin_assets/js/app-js/beacon.js')}}"></script>
        @endsection
