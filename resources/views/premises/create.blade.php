@extends('adminLayout')
@section('content')
@section('pageTitle')
{{ (isset($title) && !empty($title) ? $title : '') }}
@endsection

<!-- main content start-->
<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">
        <h2 class="title1">Premises Management</h2>
        <div class="blank-page widget-shadow scroll" id="style-2 div1">
            @include('common.admin.flash-message')
            <div class="row">
                <div class="col-md-12">
                    <h3 class="divider-sec text-right">@yield('pageTitle')</h3>
                    {!!
                        Form::open(
                        array(
                        'name' => 'frmsave',
                        'id' => 'admin_premises',
                        'url' => 'admin/premises/'.(!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : ''),
                        'autocomplete' => 'off',
                        'class' => 'form-horizontal',
                        'files' => true
                        )
                        )
                        !!}
                        @section('editMethod')
                        @show
				<div class="panel panel-primary">
				<div class="panel-body">
				<div class="tab-content">
				<div class="tab-pane active" id="horizontal-form">

				<div class="form-group">
				<label class="col-sm-2 control-label">Name *</label>
				<div class="col-sm-9">
				{{ Form::text('name', (!empty($objData) && is_object($objData) && !empty($objData->name) ? $objData->name : '') ,['class'=>'form-control','rows'=>'2', 'placeholder' => 'Enter name *'])}}

				@if ($errors->has('name'))
				<span class="help-block" style = "display:block;color:red;">
				<strong>{{ $errors->first('name') }}</strong>
				</span>
				@endif
				</div>
				</div>
				 <div class="form-group">
                   <label class="col-sm-2 control-label">Organisation *</label>
                        <div class="col-md-9">
							{{ Form::select('organisation_admin_id',[]+@$adminorganisation->pluck('name','id')->toArray(), ( !empty($objData) && is_object($objData) && !empty($objData->organisation_admin_id) ? $objData->organisation_admin_id : ''),['id'=> 'organisation_admin_id', 'class'=>'form-control organisation_admin_id'])}}
                            
                            @if ($errors->has('organisation_admin_id'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('organisation_admin_id') }}</strong>
                            </span>
                            @endif
                        </div>
                  </div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Status</label>
					<div class="col-sm-9">
						{{Form::select('status',['1' => 'Active', '0' => 'Inactive'], ( !empty($objData) && is_object($objData) && !empty($objData->status) ? $objData->status : ''),['id'=> 'address_city_id', 'class'=>'form-control address_city_id'])}}
						@if ($errors->has('status'))
						<span class="help-block" style = "display:block;color:red;">
							<strong>{{ $errors->first('status') }}</strong>
						</span>
						@endif
					</div>
				</div>
				</div>

				</div>
				</div>
				<div class="panel-footer">
				<div class="row">
				<div class="col-sm-8 col-sm-offset-2" style="text-align: center;margin-top: 15px;">
				{{ Form::hidden('premises_id', (!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : '')) }}
                @if(isset($objData) && !empty($objData) && is_object($objData) && !empty($objData->id))
				   <button class="btn btn-warning">Update</button>
				@else
				   <button class="btn btn-warning">Save</button>
				@endif

				<a href="{{route('premises.index')}}">
				<button class="btn btn-default btn-raised btn-danger" type="button">Cancel</button>
				</a>
				</div>
				</div>
				</div>
				</div>
				{!! Form::close()!!}
				</div>
				</div>
            </div>
        </div>
    </div>
    @endsection
    @section('addtional_css')
    @endsection
