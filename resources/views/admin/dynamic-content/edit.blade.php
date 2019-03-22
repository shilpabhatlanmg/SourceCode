@extends('adminLayout')

@section('content')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">
        <div class="forms">
            <h2 class="title1">{{ (isset($title) && !empty($title) ? $title : '') }}</h2>

            <div class="form-three widget-shadow">

                {!!
                    Form::
                    open(
                    array(
                    'id'=>'frm-save-static-page',
                    'class'=>'cmxform form-horizontal tasi-form',
                    'url' => route('update.content'),
                    'novalidate' => 'novalidate',
                    'files' => true
                    )
                    )
                    !!}

                    @if($slug != 'home-page-banner')

                    <div class="form-group">
                        <label for="focusedinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Page Title</label>
                        <div class="col-sm-8 col-xs-12">

                            {{ Form::text('title', (!empty($arrPageData) && is_object($arrPageData) && !empty($arrPageData->title) ? $arrPageData->title : ''), ['class' => 'form-control', 'placeholder' => 'Enter Title']) }}

                            @if ($errors->has('title'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Content</label>
                        <div class="col-sm-8 col-xs-12">
                            {{ Form::textarea('content',(!empty($arrPageData) && is_object($arrPageData) && !empty($arrPageData->content) ? $arrPageData->content : ''),['class'=>'form-control','rows'=>'2', 'id'=>'content', 'rows' => '8', 'cols' => '90'])}}

                            @if ($errors->has('content'))
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('content') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-offset-2">

                      {{ Form::hidden('slug', isset($slug) && !empty($slug) ? $slug : '') }}

                      <button class="btn btn-primary">Update</button>


                      <a href="{{route('dynamic.content')}}">
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
    CKEDITOR.replace( 'content' , {
      height: 250,
      extraPlugins: 'colorbutton',
      colorButton_colors: 'CF5D4E,454545,FFF,CCC,DDD,CCEAEE,66AB16',
      colorButton_enableAutomatic: false
  });

    CKEDITOR.addCss(".cke_editable{background-color: #6495ed}");
</script>
@endsection