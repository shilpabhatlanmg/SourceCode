@extends('app')
@section('content')
<div class="log-banner reset-bann">
 <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="sign-up-form forget-pass">
                <h4>Reset Password</h4>

                <div class="forget-form-area reset-area">

                    {!! 
                    Form::open(
                    array(
                    'name' => 'reset_password',
                    'id' => 'reset_password',
                    'route' => 'password.request',
                    'autocomplete' => 'on',
                    'class' => 'form-horizontal'
                    )
                    )
                    !!}
                    <input type="hidden" name="token" value="{{ $token }}">


                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class=" control-label">E-Mail Address</label>

                       
                            {{ Form::email('email', '', ['class' => 'form-control', 'placeholder' => 'Enter email address *']) }}

                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="control-label">Password</label>

                       
                            {{ Form::password('password', ['class' => 'form-control', 'id' => 'passwords', 'placeholder' => 'Enter password *']) }}

                            @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                       
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password-confirm" class="control-label">Confirm Password</label>
                        
                            {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Enter Reconfirm password *']) }}
                            @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                            @endif
                        
                    </div>

                    <div class="form-group text-center">                        
                            <button type="submit" class="submit-log">Reset Password</button>
                    </div>
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
