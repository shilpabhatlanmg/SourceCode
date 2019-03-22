@extends('app')

@section('content')


<!--Home Sections-->
<section id="home" class="home tk-head bg-black fix">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="main_home inner-cl">
              <div class="col-md-12">
                <div class="hello_slid">
                    <div class="slid_item xs-text-center">
                        <div class="home_text xs-m-top-30">
                           <h1 class="text-white f_txt"></h1>
                           
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
        <h1 class="thankyou_txt">Error !!</h1>
        <h2  class="populat-head">Access Denied<span class="actual-logo tk-size"><img src="{{ asset('public/assets/images/actual-logo.png') }}"> </span></h2>

        <center>Go to <a href="{{ route('welcome')  }}">Home Page</a></center>
        
    </div>

</section>





@endsection

@section('pageTitle')
403 Error
@endsection

@section('addtional_css')
@endsection

@section('jscript')

@endsection