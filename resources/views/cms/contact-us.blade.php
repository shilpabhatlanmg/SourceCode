@extends('app')
@section('content')
<div class="bingner-banner">
    <img src="{{ asset('/assets/images/contactus-banner.jpg') }}" alt="banner" class="img-responsive">
</div>
<div class="about-page-section con-page">
    <div class="container">
        <div class="row">
            @include('common.flash-message')
            <h1>Contact Us</h1>
            <div class="contactus-section">
                <div class="row">
                    <div class="col-md-6">
                        {!!
                        Form::open(
                        array(
                        'name' => 'contact_us',
                        'id' => 'contact_us',
                        'url' => route('contact.us'),
                        'autocomplete' => 'off',
                        'files' => false
                        )
                        )
                        !!}
                        <div class="form-group">
                            <label>Name <span class="star">*</span></label>
                            {{ Form::text('name', (isset($objData) && !empty($objData) && is_object($objData) ? $objData->name : ''), ['class' => 'form-control', 'placeholder' => 'Enter Name']) }}
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label>Phone Number <span class="star">*</span></label>
                            {{ Form::text('phone', (isset($objData) && !empty($objData) && is_object($objData) ? $objData->phone : ''), ['class' => 'form-control', 'placeholder' => 'Enter phone']) }}
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label>Email <span class="star">*</span></label>
                            {{ Form::email('email', (isset($objData) && !empty($objData) && is_object($objData) ? $objData->email : ''), ['class' => 'form-control', 'placeholder' => 'Enter email address', 'readonly' => false]) }}
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label>Comment <span class="star">*</span></label>
                            {{ Form::textarea('comment',(isset($objData) && !empty($objData) && is_object($objData) ? $objData->comment : ''),['class'=>'form-control','rows'=>'2'])}}
                            <span class="help-block" style = "display:block;color:red;">
                                <strong>{{ $errors->first('comment') }}</strong>
                            </span>
                        </div>

                        <div class="submit-box">
                            <input type="submit" value="Submit">
                        </div>

                        {!! Form::close()!!}
                    </div>
                    <div class="col-md-6">
                        <div class="contact-map">
                            <p  class="thumbnail">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m26!1m12!1m3!1d3499.867140005242!2d77.15162436508396!3d28.693620682393792!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m11!3e6!4m3!3m2!1d28.691660799999998!2d77.1547136!4m5!1s0x390d229ef54ef26f%3A0x62542882476ceb4!2snmg+address!3m2!1d28.693044399999998!2d77.152069!5e0!3m2!1sen!2sin!4v1543929394502" width="100%" height="365" frameborder="0" style="border:0" allowfullscreen></iframe></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageTitle')
Contact Us
@endsection

@section('addtional_css')
@endsection

@section('jscript')
@endsection
