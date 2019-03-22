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
               <h1 class="text-white f_txt">{{ (isset($arrData) && !empty($arrData) && is_object($arrData) ? $arrData->page_title : '') }}</h1>

             </div>

           </div><!-- End off slid item -->

         </div>
       </div>
     </div>
   </div><!--End off row-->
 </div><!--End off container -->
</section> <!--End off Home Sections-->

<div class="features features bg-grey fq">
    <div class="container">
        <div class="row">
            <div class="main_features fix roomy-70">
              @php
              $content = (isset($arrData) && !empty($arrData) && is_object($arrData))?strtr($arrData->content, [
              '{{LOGO}}' => '<span class="actual-logo"><img src="' . asset("public/assets/images/actual-logo.png") .'"></span>'
              ]):'';
              @endphp
                {!! $content !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageTitle')
Work
@endsection

@section('addtional_css')
@endsection

@section('jscript')
@endsection
