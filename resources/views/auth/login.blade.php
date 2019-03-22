@extends('app')
@section('content')
<div class="log-banner">
  <div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default log-secti">
                <h4>Login</h4>

                <div class="panel-body">
                    @include('common.flash-message')
                    {!!
                    Form::open(
                    array(
                    'name' => 'frmLogin',
                    'id' => 'loginforms',
                    'route' => 'login',
                    'autocomplete' => 'on',
                    'class' => 'form-horizontal'
                    )
                    )
                    !!}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="control-label">E-Mail Address</label>
                        
                            {{ Form::email('email', '', ['class' => 'form-control', 'placeholder' => 'Enter email address *']) }}
                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                       
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="control-label">Password</label>
                        
                            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Enter password *']) }}
                            @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        
                    </div>
                    <div class="form-group">                       
                          <div class="checkbox">
                           <label>
                            {{ Form::checkbox('remember', null, old('remember') ? 'checked' : '', []) }} Remember Me
                           </label>
                          </div> 
                          <a class="for-pas" href="{{ route('password.request') }}">
                                Forgot Your Password?
                          </a>                        
                     </div>
                     <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                          <div class="signuplink">                                
                                
                           </div>
                        </div>
                     </div>                      
                    <div class="form-group">
                       
                            <p class="text-center"><button type="submit" class="submit-log">Login</button></p>
				            <p class="text-center">Don’t have an account? <a href="#">Sign up</a></p>
                        
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
Login
@endsection

@section('addtional_css')
@endsection

@section('jscript')
@endsection