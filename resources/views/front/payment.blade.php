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
    {!!
      Form::open(
      array(
      'name' => 'payment',
      'id' => 'paymentForm',
      'url' => 'payment/process',
      'autocomplete' => 'on',
      'class' => '',
      'files' => false
      )
      )
      !!}
      <div class="row">
        <!-- <span class="heading">Sign Up As An Organization</span> -->
        <div class="information_tab">
          <div class="inner m-ship pay">
            <div class="first"></div>
            <div class="second-half"></div>
            <div class="One">
              <span class="cir"><i class="fa fa-check" aria-hidden="true"></i></span>
              <span class="txt">Information</span>
            </div>
            <div class="second">
              <span class="cir"><i class="fa fa-check" aria-hidden="true"></i></span>
              <span class="txt">Membership</span>
            </div>
            <div class="third">
              <span class="cir">3</span>
              <span class="txt">Payment</span>
            </div>
          </div>

        </div>

        <div class="payment_bx">
         <div class="panel-left">
          <ul class="nav nav-tabs tabs-left">
            <li><a href="#credit" id="credit_card" data-toggle="tab">Credit Card</a></li>
            <!--<li><a href="#debit" data-toggle="tab">Debit Card</a></li>-->
            <li><a href="#credit" id="debit_card" data-toggle="tab">Debit Card</a></li>
          </ul>
        </div>
        <div class="panel-right">
          <div class="tab-content">
            <div class="tab-pane active" id="credit">
              <div class="mn_bx">
                <span class="hd">Payment Information:</span>
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

              <!-- <span class="vld_txt">Valid Till</span> -->
              <div class="col-md-6 col-sm-6 col-xs-6 pd-left tick">
               <div class="form-group">

                {!! Form::selectMonth('exp-month', null, ['class' => 'chosen-select form-control', 'placeholder' => 'Month', 'data-stripe' => 'exp-month']) !!}
              </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-6 pd-right tick">
             <div class="form-group">
              {!! Form::selectRange('exp-year', 2019, 2030, null, ['class' => 'chosen-select form-control', 'placeholder' => 'YYYY', 'data-stripe' => 'exp-year']) !!}

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

        {{ Form::hidden('organization_id', (!empty($organization_id) && isset($organization_id) ? $organization_id : '')) }}

        {{ Form::hidden('plan_id', '') }}

        {{ Form::hidden('pk', (!empty($stripe_pk) && isset($stripe_pk) ? $stripe_pk : '')) }}
        {{ Form::hidden('token', "") }}

        <div class="col-md-6 col-sm-6 col-xs-6 pd-right">
          <!-- <span class="whats_txt">What is this ?</span> -->
          <a  class="popoverData whats_txt" href="javascript:void(0)" data-content="Lorem Ipsum is simply dummy text " rel="popover" data-placement="top" data-trigger="hover" >What is this ?</a>
        </div>

      </div>

    </div>

  </div>
  








  <!--<div class="tab-pane" id="debit">
    <div class="mn_bx">
      <span class="hd">Payment Information:</span>
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

{{ Form::hidden('organization_id', (!empty($organization_id) && isset($organization_id) ? $organization_id : '')) }}

{{ Form::hidden('plan_id', '') }}

{{ Form::hidden('pk', (!empty($stripe_pk) && isset($stripe_pk) ? $stripe_pk : '')) }}
{{ Form::hidden('token1', "") }}

<div class="col-md-6 col-sm-6 col-xs-6 pd-right">

  <a  class="popoverData whats_txt" href="javascript:void(0)" data-content="Lorem Ipsum is simply dummy text " rel="popover" data-placement="top" data-trigger="hover" >What is this ?</a>
</div>

</div>

</div>


</div>-->
</div>
</div>

</div>

<a href="{{ route('plan', ['organization_id' => $organization_id]) }}" class="bk mr-bk">Back</a>
<input type="submit" class="nxt" name="submits" value="Pay">
<!--<button type="submit" class="nxt" onclick="window.location.href='thankyou.html'" type="submit">Submit</button>-->






</div>
{!! Form::close()!!}

</div>

<div class="modal fade bs-example-modal-sm in" id="ConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="ConfirmationModal" style="display: none;">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content"> 
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="ConfirmationModal">ProtectApp™</h4> 
                            </div> 
                            <div class="modal-body confirm_msg">
                            </div>
                        </div>
                    </div>
                </div>

</section>

@endsection

@section('pageTitle')
{{ !empty($title) ? $title : '' }}
@endsection

@section('addtional_css')

<link rel="stylesheet" href="{{asset('/public/admin_assets/css/app-css/formValidation.min.css')}}">

@endsection

@section('jscript')
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/stripe/stripe.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/stripe/formValidation.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/stripe/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/stripe/payment.js')}}"></script>

<script>
  
  if(localStorage.getItem('credit')){

    $("#credit_card").parent().addClass('active');

  }else if(localStorage.getItem('debit')){


    $("#debit_card").parent().addClass('active');
  }else {

    $("#credit_card").parent().addClass('active');    
  }

  $("#credit_card").on('click', function () {

    localStorage.setItem('credit', true);
    localStorage.removeItem('debit');

    $(this).parent().addClass('active');
    $("#debit_card").parent().removeClass('active');
    
    $("#paymentForm").formValidation('resetForm', true);
    

  });

  $("#debit_card").on('click', function () {

    localStorage.setItem('debit', true);
    localStorage.removeItem('credit');

    $(this).parent().addClass('active');
    $("#credit_card").parent().removeClass('active');
    $("#paymentForm").formValidation('resetForm', true);
    

  });

  $(document).ready(function(){
    $('.demo-4').selectMania({});

    $(window).on("load",function(){
      
      $(".select-mania-items").mCustomScrollbar({
        theme:"dark-3"
      });
      
    });
  });

  $('.popoverData').popover();
  $('.popoverOption').popover({ trigger: "hover" });




</script>
@endsection