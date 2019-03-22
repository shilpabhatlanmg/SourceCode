@extends('adminLayout')

@section('content')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

<div id="page-wrapper">
  @include('common.admin.breadcrumb')

  <div class="main-page">
    <div class="tables">

      <h4>{{ (isset($title) && !empty($title) ? $title : '') }}</h4>

      <div class="payment_bx">

        <div class="panel-right">
          <div class="mn_bx">
            <span class="hd">{{ (isset($heading) && !empty($heading) ? $heading : '') }}:</span>
            {!!
              Form::open(
              array(
              'name' => 'payment',
              'id' => 'paymentForm',
              'url' => '',
              'autocomplete' => 'on',
              'class' => '',
              'files' => false
              )
              )
              !!} 
              <div class="payment_frm">
                <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
                 <div class="form-group">
                  {{ Form::text('number', null, ['class' => 'form-control', 'placeholder' => 'Enter card no. *', 'data-stripe' => 'number']) }}
                  <span class="help-block" style = "display:block;color:red;">
                    <strong>{{ $errors->first('number') }}</strong>
                  </span>
                </div>
              </div>

              <div class="col-md-12 col-sm-12 col-xs-12 pd-less">
               <div class="form-group">
                 {{ Form::text('card_holder_name', null, ['class' => 'form-control', 'placeholder' => 'Enter card holder name. *']) }}
                 <span class="help-block" style = "display:block;color:red;">
                  <strong>{{ $errors->first('card_holder_name') }}</strong>
                </span>
              </div>
            </div>


            <div class="col-md-6 col-sm-6 col-xs-6 pd-left">
             <div class="form-group">

              {!! Form::selectMonth('exp-month', null, ['class' => 'form-control', 'placeholder' => 'Month', 'data-stripe' => 'exp-month']) !!}

            </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-6 pd-right">
           <div class="form-group">
            {!! Form::selectRange('exp-year', 2019, 2030, null, ['class' => 'form-control', 'placeholder' => 'YYYY', 'data-stripe' => 'exp-year']) !!}
            
            <span class="help-block" style = "display:block;color:red;">
              <strong>{{ $errors->first('selectYear') }}</strong>
            </span>
          </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-6 pd-left">
          <div class="form-group">
           {{ Form::number('cvvNumber', null, ['class' => 'form-control', 'placeholder' => 'CVV *', 'data-stripe' => 'cvc']) }}
           <span class="help-block" style = "display:block;color:red;">
            <strong>{{ $errors->first('cvvNumber') }}</strong>
          </span>
        </div>
      </div>
      {{ Form::hidden('organization_subscription_id', (!empty($organization_subscription_id) && isset($organization_subscription_id) ? $organization_subscription_id : '')) }}
      
      {{ Form::hidden('plan_id', '') }}

      {{ Form::hidden('organization_id', null) }}

      {{ Form::hidden('pk', (!empty($stripe_pk) && isset($stripe_pk) ? $stripe_pk : '')) }}
      {{ Form::hidden('token', "") }}
    </div>
    <input type="submit" class="btn btn-primary" name="submits" value="{{ (!empty($type) && $type == 'card' ? 'Update' : 'Pay') }}">
    {!! Form::close()!!}

  </div>

</div>

</div>

</div>
</div>
</div>

@endsection

@section('addtional_css')

<link rel="stylesheet" href="{{asset('/public/admin_assets/css/app-css/formValidation.min.css')}}">

@endsection

@section('jscript')
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/stripe/stripe.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/stripe/formValidation.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/stripe/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/stripe/payment.js')}}"></script>
@endsection