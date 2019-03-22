@extends('app')
@section('content')
<div class="Sign-up-sec">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="sign-up-form forget-pass">
                    <h4>Reset Password</h4>

                    <div class="forget-form-area ">
                        @include('common.flash-message')
                        @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @endif

                        {!!
                        Form::open(
                        array(
                        'name' => 'frmLogin',
                        'id' => 'forgotpassword',
                        'route' => 'password.email',
                        'autocomplete' => 'on',
                        'class' => 'form-horizontal'
                        )
                        )
                        !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">E-Mail Address</label>

                            {{ Form::email('email', '', ['class' => 'form-control', 'placeholder' => 'Enter email address *']) }}

                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif                       
                        </div>

                        <div class="sine-sub">                        
                            <input type="submit" class="send-submit" name="submits" value="Send Password Reset Link">                    </div>
                        {!! Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageTitle')
Reset Password
@endsection

@section('addtional_css')
@endsection

@section('jscript')
@endsection
