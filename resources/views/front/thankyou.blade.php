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
            <div class="thankyou_bx">
              <span class="image"><img src="{{ asset('public/assets/images/logo2.png') }}"></span>
              <h2 class="hd">Thank you</h2> 
              <span class="txt">Please check your email for a link to activate your account.</span> 
              <div class="price_bx">
                <strong>${{ !empty($sub_record) && is_object($sub_record) && !empty($sub_record->price) ? (int) $sub_record->price : '' }}</strong>/ {{ !empty($sub_record) && is_object($sub_record) && !empty($sub_record->type) ? ucfirst($sub_record->type) : '' }}
            </div>
            <span class="txt mr_txt"><strong>{{ !empty($sub_record) && is_object($sub_record) && !empty($sub_record->plan_name) ? ucwords($sub_record->plan_name) : '' }}</strong> selected</span>

            <span class="back_to_home">
                <a href="{{ route('home') }}"><i class="fa fa-angle-left" aria-hidden="true"></i> Back to Home</a>
            </span>

        </div>   
    </div>

</div>

</section>

@endsection