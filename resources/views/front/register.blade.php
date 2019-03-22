@extends('app')

@section('content')

<!--Home Sections-->
<section id="home" class="home inner-head bg-black fix">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="main_home inner-cl">
        <div class="col-md-12">
          <div class="hello_slid">
            <div class="slid_item xs-text-center">
              <div class="home_text xs-m-top-30">
               <h1 class="text-white f_txt">Register Your Organization</h1>

             </div>

           </div><!-- End off slid item -->

         </div>
       </div>

     </div>

   </div>

 </div>
 
</section> <!--End off Home Sections-->

<section class="signup">
  <div class="container">
    <div class="row">
     <!--  <span class="heading">Sign Up As An Organization</span> -->
     <div class="information_tab">
      <div class="inner">
        <div class="first"></div>
        <div class="second-half"></div>
        <div class="One">
          <span class="cir">1</span>
          <span class="txt">Information</span>
        </div>
        <div class="second">
          <span class="cir">2</span>
          <span class="txt">Membership</span>
        </div>
        <div class="third">
          <span class="cir">3</span>
          <span class="txt">Payment</span>
        </div>
      </div>

    </div>

    {!!
      Form::open(
      array(
      'name' => 'joinus',
      'id' => 'joinus',
      'route' => 'saveinfo',
      'autocomplete' => 'on',
      'class' => '',
      'files' => false
      )
      )
      !!}

      <div class="sgn_up_form">
        <span class="hd">Organization Information:</span>


        <div class="inner">



          <div class="col-md-6 col-sm-6 col-xs-12 pd-left">
            <div class="form-group">
              {{ Form::text('name', (!empty($objData) && is_object($objData) && !empty($objData->name) ? $objData->name : ''), ['class' => 'form-control', 'placeholder' => 'Organization Name *']) }}
              <span class="help-block" style = "display:block;color:red;">
                <strong>{{ ( ($errors->has('name')) ? $errors->first('name') : '') }}</strong>
              </span>

            </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 pd-right">
           <div class="form-group">

            {{ Form::email('email', (!empty($objData) && is_object($objData) && !empty($objData->email) ? $objData->email : ''), ['class' => 'form-control', 'placeholder' => 'Organization Email Id *', 'readonly' => false]) }}

            <span class="help-block" style = "display:block;color:red; margin-top:20px;">
              <strong>{{ $errors->first('email') }}</strong>
            </span>

          </div>
        </div>
            
        <div class="col-md-6 col-sm-6 col-xs-12 pd-left">
            <div class="form-group">

             {{ Form::text('address', (!empty($objData) && is_object($objData) && !empty($objData->address) ? $objData->address : ''), ['class' => 'form-control', 'data' => 'address', 'placeholder' => 'Address *']) }}

             @if ($errors->has('address'))
             <span class="help-block" style = "display:block;color:red;">
               <strong>{{ $errors->first('address') }}</strong>
             </span>
             @endif

           </div>
        </div> 

        <div class="col-md-6 col-sm-6 col-xs-12 pd-right">
            <div class="form-group">

             {{ Form::text('zip_code', (!empty($objData) && is_object($objData) && !empty($objData->zip_code) && isset($objData->zip_code) ? $objData->zip_code : ''), ['class' => 'form-control user form-number', 'data' => 'zip_code', 'placeholder' => 'Enter zip code *']) }}

                        <span class="zip_code" style = "display:block;color:red;">

                        </span>

                        @if ($errors->has('zip_code'))
                        <span class="help-block" style = "display:block;color:red;">
                            <strong>{{ $errors->first('zip_code') }}</strong>
                        </span>
                        @endif

           </div>
        </div>   

        <div class="col-md-6 col-sm-6 col-xs-12 pd-left">
         <div class="form-group">

          {{ Form::select('country_id',[]+@$arrCountry->pluck('name','id')->toArray(), null,['id'=> 'country_id', 'class'=>'chosen-select form-control country_id']) }}

          @if ($errors->has('country_id'))
          <span class="help-block" style = "display:block;color:red;">
            <strong>{{ $errors->first('country_id') }}</strong>
          </span>
          @endif
        </div>
      </div>

      <div class="col-md-6 col-sm-6 col-xs-12 pd-right z11">
       <div class="form-group">
        {{Form::select('state_id',[''=>'Select State']+@$arrState->pluck('name','id')->toArray(), (!empty($objData) && is_object($objData) && !empty($objData->state_id) && isset($objData->state_id) ? $objData->state_id : ''),['id'=> 'state_id', 'class'=>'form-control state_id chosen-select required', 'required'=> true])}}

        @if ($errors->has('state_id'))
        <span class="help-block" style = "display:block;color:red;">
          <strong>{{ $errors->first('state_id') }}</strong>
        </span>
        @endif
        <span class="help-block state_id_error" style = "display:block;color:#a94442;">
                               
     </span>
      </div>
    </div>

    <div class="col-md-6 col-sm-6 col-xs-12 pd-left">
     <div class="form-group">
      @if(isset($arrCityData) && !empty($arrCityData) && count($arrCityData) > 0)

      {{ Form::select('city_id',[''=>'Select city *']+@$arrCityData->pluck('name','id')->toArray(), !empty($city_id) ? $city_id : '',['id'=> 'address_city_id', 'class'=>'form-control address_city_id chosen-select']) }}
      
      @else

      {{ Form::select('city_id',[''=>'Select city'], null,['id'=> 'address_city_id', 'class'=>'form-control address_city_id chosen-select']) }}
      @endif

      @if ($errors->has('city_id'))
      <span class="help-block" style = "display:block;color:red;">
        <strong>{{ $errors->first('city_id') }}</strong>
      </span>
      @endif
      <span class="help-block city_id_error" style = "display:block;color:#a94442;">
                               
     </span>
    </div>
  </div>


  <div class="col-md-6 col-sm-6 col-xs-12 pd-right">
   <div class="form-group">
    {{ Form::text('phone', (!empty($objData) && is_object($objData) && !empty($objData->phone) && isset($objData->phone) ? $objData->phone : ''), ['class' => 'form-control user form-number', 'title' => 'Enter phone number', 'data' => 'phone', 'placeholder' => 'Enter Contact no *']) }}

    @if ($errors->has('phone'))
    <span class="help-block" style = "display:block;color:red;">
      <strong>{{ $errors->first('phone') }}</strong>
    </span>
    @endif


  </div>
</div>

<div class="col-md-6 col-sm-6 col-xs-12 pd-left">
   <div class="form-group">
    {{ Form::text('emergency_contact', (!empty($objData) && is_object($objData) && !empty($objData->emergency_contact) && isset($objData->emergency_contact) ? $objData->emergency_contact : ''), ['class' => 'form-control user form-number', 'title' => 'Enter Emergency Contact number', 'data' => 'emergency_contact', 'placeholder' => 'Enter Emergency Contact no *']) }}

    @if ($errors->has('emergency_contact'))
    <span class="help-block" style = "display:block;color:red;">
      <strong>{{ $errors->first('emergency_contact') }}</strong>
    </span>
    @endif


  </div>
</div>

<div class="col-md-6 col-sm-6 col-xs-12 pd-right">
 <div class="form-group">
  {!! Form::password('password', ['class' => 'form-control lock required', 'id' => 'passwords', 'placeholder' => 'Password']) !!}

  @if ($errors->has('password'))
  <span class="help-block" style = "display:block;color:red;">
    <strong>{{ $errors->first('password') }}</strong>
  </span>
  @endif


</div>
</div>

<div class="col-md-6 col-sm-6 col-xs-12 pd-left">
 <div class="form-group">
  {!! Form::password('password_confirmation', ['class' => 'form-control lock required', 'placeholder' => 'Confirm Password']) !!}

  @if ($errors->has('password_confirmation'))
  <span class="help-block" style = "display:block;color:red;">
    <strong>{{ $errors->first('password_confirmation') }}</strong>
  </span>
  @endif


</div>
</div>


<span class="hd mr-tp">Contact Person Details:</span>

<div class="col-md-6 col-sm-6 col-xs-12 pd-left">
 <div class="form-group">

  <input type="text" name="poc_name[]" class="form-control" value="{{ (!empty($objData) && is_object($objData) && !empty($objData->pocName->poc_name) ? $objData->pocName->poc_name : '') }}" placeholder="Enter name">

  @if ($errors->has('poc_name.0'))
  <span class="help-block" style = "display:block;color:red;">
    <strong>{{ $errors->first('poc_name.0') }}</strong>
  </span>
  @endif
</div>
</div>

<div class="col-md-6 col-sm-6 col-xs-12 pd-right">
 <div class="form-group">

   <input type="text" name="poc_contact_no[]" value="{{ (!empty($objData) && is_object($objData) && !empty($objData->pocName->poc_contact_no) ? $objData->pocName->poc_contact_no : '') }}" title = "Enter phone number" class="form-control" placeholder="Enter contact number">

   @if ($errors->has('poc_contact_no.0'))
   <span class="help-block" style = "display:block;color:red;">
    <strong>{{ $errors->first('poc_contact_no.0') }}</strong>
  </span>
  @endif
</div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 pd-less">
 <div class="form-group">

  <input type="email" name="poc_email[]" value="{{ (!empty($objData) && is_object($objData) && !empty($objData->pocName->poc_email) ? $objData->pocName->poc_email : '') }}" class="form-control" placeholder="Enter email">
  @if ($errors->has('poc_email.0'))
  <span class="help-block" style = "display:block;color:red;">
    <strong>{{ $errors->first('poc_email.0') }}</strong>
  </span>
  @endif
</div>
</div>

<div class="col-sm-12">
   <div class="form-group">
  <label style="display:block;" class="checkbx">
    {!! Form::checkbox('terms_conditions', 'null', (!empty($objData) && is_object($objData) && !empty($objData->phone) && isset($objData->phone) ? '1' : '')) !!}&nbsp;<a href="{{ route('terms.conditions') }}" target="_blank">by clicking this i agree terms and condition</a>
  </label>

  @if ($errors->has('terms_conditions'))
  <span class="help-block" style = "display:block;color:red;">
    <strong>{{ $errors->first('terms_conditions') }}</strong>
  </span>
  @endif
</div>
</div>


</div>

</div>

<button class="nxt" type="submit">Next</button>

{{ Form::hidden('timezone', null) }}

{{ Form::input('hidden', 'organization_id', !empty($orga_id) && isset($orga_id) ? $orga_id : '', ['readonly' => 'readonly']) }}

{{ Form::input('hidden', 'role_id', (!empty($objData) && is_object($objData) && !empty($objData->role_id) && isset($objData->role_id) ? $objData->role_id : ''), ['readonly' => 'readonly']) }}

{{ Form::input('hidden', 'becon_major_id', (!empty($objData) && is_object($objData) && !empty($objData->becon_major_id) && isset($objData->becon_major_id) ? $objData->becon_major_id : ''), ['readonly' => 'readonly']) }}

<input type="hidden" name="poc_id[]" value="{{ (!empty($objData) && is_object($objData) && !empty($objData->pocName->id) ? $objData->pocName->id : '') }}">

{!! Form::close()!!}

</div>

</div>

</section>

@endsection

@section('pageTitle')
{{ !empty($title) ? $title : '' }}
@endsection

@section('addtional_css')

<!-----------drop down search plugin css-------------->
<link href="{{ asset('/public/admin_assets/css/chosen.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('jscript')
<script type="text/javascript" src="{{asset('public/admin_assets/js/app-js/city.js')}}"></script>
<script type="text/javascript" src="{{asset('public/assets/js/app-js/client-validation.js')}}"></script>

<!-----------drop down search plugin-------------->
<script type="text/javascript" src="{{ asset('/public/admin_assets/js/chosen.jquery.js') }}"></script>
<script>

  $(document).ready(function(){

    var timezone_offset_minutes = new Date().getTimezoneOffset();
    timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;

    $('.demo-4').selectMania({});

    $(window).on("load",function(){

      $(".select-mania-items").mCustomScrollbar({
        theme:"dark-3"
      });

    });

    $('input[name="zip_code"]').mask('00000', {placeholder: "Enter zip code *"});
    $('input[name="phone"]').mask('(000) 000-0000', {placeholder: "(___) ___-____"});
    $('input[name="emergency_contact"]').mask('(000) 000-0000', {placeholder: "(___) ___-____"});
    $('input[name="poc_contact_no[]"]').mask('(000) 000-0000', {placeholder: "(___) ___-____"});
    $('input[name="timezone"]').val(timezone_offset_minutes);
  });

  $(".chosen-select").chosen();
</script>
@endsection