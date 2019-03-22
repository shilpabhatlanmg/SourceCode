@extends('adminLayout')

@section('content')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">
        <div class="forms">
            <h2 class="title1"> {{(!empty($objData->id))?'Edit Subscription':'Create Subscription' }}</h2>
            
            <div class="form-three widget-shadow">
                @include('common.admin.flash-message')

                {!!
                    Form::open(
                    array(
                    'name' => 'frmsave',
                    'id' => 'admin_user',
                    'url' => 'admin/subscription/'.(!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : ''),
                    'autocomplete' => 'off',
                    'class' => 'form-horizontal',
                    'files' => true
                    )
                    )
                    !!}
                    @section('editMethod')
                    @show


                    
                    
                    <div class="form-group">
                        <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">User Name *</label>
                        <div class="col-sm-8 col-xs-12">
                            {{ Form::text('username', '', ['class' => 'form-control form-number', 'data' =>'premises_allow', 'placeholder' => 'Enter User Name *']) }}

                            <span class="premises_allow" style = "display:block;color:red;">

                            </span>

                            @if ($errors->has('premises_allow'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Email Id *</label>
                         <div class="col-sm-8 col-xs-12">
                            {{ Form::text('email', (!empty($objData) && is_object($objData) && !empty($objData->email) ? $objData->email : ''), ['class' => 'form-control form-number', 'data' =>'duration', 'placeholder' => 'Enter Email Id *']) }}
                            <span class="duration" style = "display:block;color:red;">

                            </span>
                            @if ($errors->has('duration'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('duration') }}</strong>
                            </span>
                            @endif
                        </div>

                        
                </div>

                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Price *</label>
                    <div class="col-sm-8 col-xs-12">
                        {{ Form::text('price', (!empty($objData) && is_object($objData) && !empty($objData->price) ? $objData->price : ''), ['class' => 'form-control form-number', 'data' =>'price', 'placeholder' => 'Enter Price *']) }}

                        <span class="price" style = "display:block;color:red;">

                            @if ($errors->has('price'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('price') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Status *</label>
                        <div class="col-sm-8 col-xs-12">
                            {{Form::select('status',['Active' => 'Active', 'Inactive' => 'Inactive'], ( !empty($objData) && is_object($objData) && !empty($objData->status) ? $objData->status : ''),['id'=> 'status', 'class'=>'form-control address_city_id'])}}

                            @if ($errors->has('status'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('status') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-offset-2">
                     {{ Form::hidden('subscription_id', (!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : '')) }}

                     @if(isset($objData) && !empty($objData) && is_object($objData) && !empty($objData->id))
                     <button class="btn btn-primary">Update</button>
                     @else
                     <button class="btn btn-primary">Save</button>
                     @endif

                     <a href="{{route('subscription.index')}}">
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


@endsection
