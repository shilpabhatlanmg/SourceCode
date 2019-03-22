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
            <div class="inner m-ship">
                <div class="first"></div>
                <div class="second-half"></div>
                <div class="One">
                    <span class="cir"><i class="fa fa-check" aria-hidden="true"></i></span>
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

        <div class="membership_bx">
         <div class="slider-nav2">

            @if(count($allPlanArrData)>0 && !empty($allPlanArrData))
            @foreach($allPlanArrData as $key => $var)

            {!!
                Form::open(
                array(
                'name' => 'frmsave',
                'id' => 'select-plan',
                'url' => 'payment',
                'autocomplete' => 'off',
                'class' => 'form-horizontal',
                'files' => false,
                'method' => 'get'
                )
                )
                !!}

                <div class="item">
                    <div class="col-md-12 col-sm-12 col-xs-12 bx">
                        <div class="full-bx">
                            <span class="image">
                                <img src="{{ asset('public/assets/images/b1.png') }}">
                            </span>
                            <span class="head">{{ ((!empty($var) && is_object($var)) && !empty($var->plan_name) && isset($var->plan_name) ? $var->plan_name : '') }}</span>
                            <ul>
                                <li>Allows {{ ((!empty($var) && is_object($var)) && !empty($var->people_allow) && isset($var->people_allow) ? $var->people_allow : '') }} Security Team Members.</li>

                                <li>Allows {{ ((!empty($var) && is_object($var)) && !empty($var->premises_allow) && isset($var->premises_allow) ? $var->premises_allow : '') }} Buildings. </li>

                                <li>{{ ((!empty($var) && is_object($var)) && !empty($var->duration) && isset($var->duration) ? $var->duration. ' '.$var->type : '') }} Payments.</li>

                            </ul>

                            <div class="price_txt">${{ ((!empty($var) && is_object($var)) && !empty($var->price) && isset($var->price) ? $var->price : '') }}</div>

                            {{ Form::hidden('plan_id', (!empty($var) && is_object($var) && !empty($var->id) ? \Crypt::encryptString($var->id) : '')) }}

                            {{ Form::hidden('organization_id', (!empty($organization_id) && is($organization_id) ? $organization_id : '')) }}

                            <button class="selt_txt">Select</button>
                        </div>
                    </div>

                </div>


                {!! Form::close()!!}

                @endforeach
                @endif

            </div>
            <!--  -->
        </div>

        <a href="{{ route('joinus', ['organization_id' => $organization_id]) }}" class="bk">Back</a>
        <a href="#" class="next2">Next</a>

    </div>

</div>

</section>

@endsection

@section('pageTitle')
{{ !empty($title) ? $title : '' }}
@endsection

@section('addtional_css')
<style>
.selt_txt {
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
    width: 88%;
    display: inline-block;
    text-align: center;
    color: #0f75bc;
    padding: 13px 43px;
    border-radius: 25px;
    border: 1px solid #0f75bc;
    background: #fff;
}
</style>

@endsection

@section('jscript')
<script>
    $(function () {                     


        $('.slider-nav2').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            dots: false,
            autoplay: true,
            focusOnSelect: true,
            responsive: [
            {
              breakpoint: 992,
              settings: {
                centerPadding: '30px',
                slidesToShow: 3,
                slidesToScroll:1
            }
        },

        {
          breakpoint:768,
          settings: {
            centerPadding: '0px',
            slidesToShow: 2,
            slidesToScroll:1
        }
    },

    {
      breakpoint: 600,
      settings: {
        centerPadding: '20px',
        slidesToShow: 1
    }
}
]
});     
    });
</script>

@endsection