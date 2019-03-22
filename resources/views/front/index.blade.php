@extends('app')

@section('content')
<!--Home Sections-->
	@include('front.element.banner')
	
	<!--Featured Section-->
	@include('front.element.feature')

	<!-- app screen -->
	@include('front.element.appscreen')

	<!--Dialogue section-->
	@include('front.element.dialogue')

	<!--Business Section-->
	@include('front.element.business')   

	<!--Testimonials Section-->
	@include('front.element.testimonials')
				
	<!--contact Section-->            
	@include('front.element.contact_us')
           
@endsection

@section('pageTitle')
{{ !empty($title) ? $title : '' }}
@endsection

@section('addtional_css')
@endsection

@section('jscript')

@endsection
