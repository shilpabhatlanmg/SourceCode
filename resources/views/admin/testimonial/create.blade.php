@extends('adminLayout')

@section('content')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">
        <div class="forms">
            <h2 class="title1"> {{(!empty($objData->id))?'Edit Testimonial':'Create Testimonial' }}</h2>
            <div class="form-three widget-shadow">

                {!!
                    Form::open(
                    array(
                    'name' => 'frmsave',
                    'id' => 'admin_tetimonial',
                    'url' => 'admin/testimonial/'.(!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : ''),
                    'autocomplete' => 'off',
                    'class' => 'form-horizontal',
                    'files' => true
                    )
                    )
                    !!}
                    @section('editMethod')
                    @show

                    <div class="form-group">
                        <label for="focusedinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Upload Image</label>
                        <div class="choose_btn">
                           <div class="upload_choose">
                               <label class="custom-file-upload">
                                   {{ Form::file('author_image', ['id' => 'image', 'class'=>'']) }}
                                   Choose File
                               </label>

                           </div>

                           @if ($errors->has('author_image'))
                           <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('author_image') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="profile_image_btn ">
                        @php

                        $path = 'public/storage/admin_assets/images/author_image/' . (!empty($objData) && is_object($objData) && !empty($objData->author_image) ? $objData->author_image : '');

                        @endphp

                        @if(!empty($objData) && is_object($objData) && !empty($objData->author_image) && file_exists($path))

                        {{ Html::image($path, 'image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview', 'title' => (!empty($objData) && is_object($objData) && !empty($objData->author_image) ? $objData->author_image : '' ), 'class' => 'img_prvew')) }}

                        @else

                        {{ Html::image(asset('/public/admin_assets/images/user-profile.jpg'), 'no-image', array('style' => 'max-width:100px;', 'id' => 'headshot_preview', 'title' => 'Image Preview', 'class' => 'img_prvew')) }}


                        @endif

                        {{ Form::hidden('author_image_edit', (!empty($objData) && is_object($objData) && !empty($objData->author_image) ? $objData->author_image : '')) }}
                    </div>
                </div>


                <div class="form-group">
                    <label for="focusedinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Content *</label>
                    <div class="col-sm-8 col-xs-12">

                        {{ Form::textarea('content', (!empty($objData) && is_object($objData) && !empty($objData->content) ? $objData->content : '') ,['class'=>'form-control','rows'=>'2', 'style' => "resize: none;", 'placeholder' => 'Enter Content *'])}}
                    </div>
                </div>
                <div class="form-group">
                    <label for="disabledinput" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Rating *</label>
                    <div class="col-sm-8 col-xs-12">
                        {{ Form::text('author_rating', (!empty($objData) && is_object($objData) && !empty($objData->author_rating) ? $objData->author_rating : ''), ['class' => 'form-control', 'placeholder' => 'Enter Rating *']) }}

                        @if ($errors->has('author_rating'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('author_rating') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Occupation *</label>
                    <div class="col-sm-8 col-xs-12">
                        {{ Form::text('occupation', (!empty($objData) && is_object($objData) && !empty($objData->occupation) ? $objData->occupation : ''), ['class' => 'form-control', 'placeholder' => 'Enter Occupation/Business *']) }}

                        @if ($errors->has('occupation'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('occupation') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Email *</label>
                    <div class="col-sm-8 col-xs-12">
                        {{ Form::email('author_email', (!empty($objData) && is_object($objData) && !empty($objData->author_email) ? $objData->author_email : ''), ['class' => 'form-control', 'placeholder' => 'Enter email']) }}

                        @if ($errors->has('occupation'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('author_email') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Feedback Date *</label>
                    <div class="col-sm-8 col-xs-12">
                        {{ Form::text('feedback_date', (!empty($objData) && is_object($objData) && !empty($objData->feedback_date) ? $objData->feedback_date : ''), ['class' => 'form-control datepicker', 'placeholder' => 'Enter feedback date *']) }}

                        @if ($errors->has('feedback_date'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('feedback_date') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Status *</label>
                    <div class="col-sm-8 col-xs-12">
                        {{Form::select('status',['Active' => 'Active', 'Inactive' => 'Inactive'], (!empty($objData) && is_object($objData) && !empty($objData->status) ? $objData->status : ''), ['id'=> 'address_city_id', 'class'=>'form-control address_city_id'])}}

                        @if ($errors->has('status'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('status') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="col-sm-offset-2">
                   {{ Form::hidden('testimonial_id', (!empty($objData) && is_object($objData) && !empty($objData->id) ? $objData->id : '')) }}

                   @if(isset($objData) && !empty($objData) && is_object($objData) && !empty($objData->id))
                   <button class="btn btn-primary">Update</button>
                   @else
                   <button class="btn btn-primary">Save</button>
                   @endif

                   <a href="{{route('testimonial.index')}}">
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

<script>
    $(function () {
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
        });
    });

    $(document).ready(function(){

        $("#image").change(function(){
            headshotPreview(this);
        });


    });

    function headshotPreview(input)
    {
        if(input.files && input.files[0])
        {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#headshot_preview').attr('src', e.target.result);
                $('#headshot_preview').addClass('profile_pic_upload');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
