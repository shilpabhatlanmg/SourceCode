@extends('adminLayout')

@section('content')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">
        <div class="forms">
            <h2 class="title1">Static Page Management</h2>

            <div class="form-three widget-shadow">

                {!!
                    Form::
                    open(
                    array(
                    'id'=>'frm-save-static-page',
                    'class'=>'cmxform form-horizontal tasi-form',
                    'url' => route('save_static_page'),
                    'novalidate' => 'novalidate',
                    'files' => true
                    )
                    )
                    !!}

                    <div class="form-group">
                        <label for="focusedinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Pate Title</label>
                        <div class="col-sm-8 col-xs-12">

                            {{ Form::text('page_title', (!empty($arrPageData) && is_object($arrPageData) && !empty($arrPageData->page_title) ? $arrPageData->page_title : ''), ['class' => 'form-control', 'placeholder' => 'Enter Title']) }}

                            @if ($errors->has('page_title'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('page_title') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="disabledinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Meta Tag</label>
                        <div class="col-sm-8 col-xs-12">
                            {{ Form::text('meta_tag', (!empty($arrPageData) && is_object($arrPageData) && !empty($arrPageData->meta_tag) ? $arrPageData->meta_tag : ''), ['class' => 'form-control', 'placeholder' => 'Enter meta tag']) }}

                            @if ($errors->has('meta_tag'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('meta_tag') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Meta Description</label>
                        <div class="col-sm-8 col-xs-12">
                            {{ Form::text('meta_desc', (!empty($arrPageData) && is_object($arrPageData) && !empty($arrPageData->meta_desc) ? $arrPageData->meta_desc : ''), ['class' => 'form-control', 'placeholder' => 'Enter meta description']) }}

                            @if ($errors->has('meta_tag'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('meta_tag') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Web Page Description</label>
                        <div class="col-sm-8 col-xs-12">
                            {{ Form::textarea('content',(!empty($arrPageData) && is_object($arrPageData) && !empty($arrPageData->content) ? $arrPageData->content : ''),['class'=>'form-control','rows'=>'2', 'id'=>'content', 'rows' => '8', 'cols' => '90'])}}

                            @if ($errors->has('content'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('content') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Status *</label>
                        <div class="col-sm-8 col-xs-12">
                            {{Form::select('status',['Active' => 'Active', 'Inactive' => 'Inactive'], $arrPageData->status, ['id'=> 'status', 'class'=>'form-control status'])}}

                            @if ($errors->has('status'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('status') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-offset-2">

                      {{ Form::hidden('page_slug', isset($slug) && !empty($slug) ? $slug : '') }}

                      <button class="btn btn-primary">Update</button>


                      <a href="{{route('admin_static_page')}}">
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
<script src="<?php echo env('APP_URL'); ?>vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'content' );
    CKEDITOR.addCss(".cke_editable{background-color: #6495ed}");
</script>
@endsection
