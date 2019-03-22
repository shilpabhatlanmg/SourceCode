@extends('app')

@section('content')

<!--Home Sections-->
<section id="work" class="work bg-black  fix" name="work">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="main_home">
        <div class="col-md-12">
          <div class="hello_slid">
            <div class="slid_item xs-text-center">
              <div class="home_text xs-m-top-30">
               <h1 class="text-white f_txt">Frequently Asked Questions</h1>

             </div>

           </div><!-- End off slid item -->

         </div>
       </div>
     </div>
   </div><!--End off row-->
 </div><!--End off container -->
</section> <!--End off Home Sections-->

<!--Featured Section-->
<section id="features" class="features bg-grey fq">
  <div class="container">
    <div class="row">
      <div class="main_features fix roomy-70">
        <div class="col-md-6 col-sm-12 col-xs-12 ">
          <div class="features_item fq-mb-txt m-top-70">

            <div class="f_item_text">
              <h2>How does it work?</h2>
              <p>Low Energy Bluetooth beacons are similar to radio stations.  They are transmitting, but not listening.  The beacons transmit a signal to alert your phone.  Your phone needs an app to respond to the signal put out by the beacons.  In this case, <span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span>.  Without <span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span> to respond, nothing happens on your phone</p>

              <p><span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span> uses the strength of the beacon's signal to determine how far away from the beacon you are and that information is used to determine where you are inside the building (or area).  </p>

              <p>If you use <span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span> to summon aid, your location and the basic nature of the situation is recorded and sent to your security team (volunteers, teachers, security guards, ushers, etc.). The security team is dispatched via text message.  If you press Fire, Medical, or Assist me, only the security team is dispatched to your location.  If you press the Police icon, the security team is dispatched, and the local emergency phone number is dialed.</p>

              <p>Please note:  <span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span> ONLY works where beacons have been installed and a subscription to <span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span> is current for the location.  <span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span> does not work everywhere and does not work without properly configured and installed Bluetooth beacons.                                        </p>

            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
          <div class="features_item fq-mb-txt m-top-70">

            <div class="f_item_text">
              <h2>Will the app take my personal data and disclose it?
              </h2>
              <p>No.  The application will only report your location to the security team (first responders) when you activate one of the four buttons.  
              </p>

            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
          <div class="features_item fq-mb-txt m-top-70">

            <div class="f_item_text">
              <h2>I value my privacy.  I don't really want people knowing where I am all the time.</h2>
              <p>We value your privacy as well.  <span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span> will report your location and your phone number (just like any other text message) to the security team (first responders) only if you activate one of the four buttons.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
          <div class="features_item fq-mb-txt m-top-70">

            <div class="f_item_text">
              <h2>We have a Security Team in place.  Why do we need <span class="actual-logo fq-size"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span>?
              </h2>
              <p>Your security team can't be everywhere at once.  Putting <span class="actual-logo"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span> in the hands of your group makes every member of the group the eyes and ears of your security team.  
              </p>
            </div>
          </div>
        </div>

      </div>
    </div><!-- End off row -->
  </div><!-- End off container -->
</section><!-- End off Featured Section-->
@endsection

@section('pageTitle')
{{ !empty($title) ? $title : '' }}
@endsection

@section('addtional_css')

@endsection

@section('jscript')

@endsection