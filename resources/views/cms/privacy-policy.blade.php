@extends('app')
@section('content')

<!--Home Sections-->
<section id="home" class="home inner-head bg-black fix p-policy">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="main_home inner-cl">
              <div class="col-md-12 ">
                <div class="hello_slid">
                    <div class="slid_item xs-text-center">
                        <div class="home_text xs-m-top-30">
                         <h1 class="text-white f_txt">{{ (isset($arrData) && !empty($arrData) && is_object($arrData) ? $arrData->page_title : '') }}</h1>
                     </div>
                 </div><!-- End off slid item -->
             </div>
         </div>
     </div>
 </div>
</div>

</section> <!--End off Home Sections-->
<div class="about-page-section">
    <div class="container">
        <div class="row">
          <div class="col-md-12 col-xs-12 privacy-bx">
            @php
            $content = (isset($arrData) && !empty($arrData) && is_object($arrData))?strtr($arrData->content, [
            '{{LOGO}}' => '<span class="actual-logo sz-grp" style="width:100px;margin:0 0 0 -2px;"><img src="' . asset("public/assets/images/actual-logo.png") .'"></span>',
            '{{LOGO_WHITE}}' => '<span class="actual-logo sz-grp" style="width:100px;margin:0 0 0 -2px;"><img src="' . asset("public/assets/images/actual-logo-white.png") .'"></span>'
            ]):'';
            @endphp
              <p>{!! $content !!}</p>
          </div>
        </div>
    </div>
</div>
@endsection

@section('pageTitle')
Privacy Policy
@endsection

@section('addtional_css')
@endsection

@section('jscript')
@endsection
