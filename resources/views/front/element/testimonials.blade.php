   <section id="test" class="test bg-black roomy-60 fix">
                <div class="overlay"></div>
                <div class="container">
                    <div class="row">                        
                        <div class="main_test fix text-center">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="head_title text-center fix">
                                    <h2 class=" text-white">Our Clients</h2>
                                    <p class="text-white">Read what our clients are saying about us.</p>
                                </div>
                            </div>
                            <div id="testslid" class="carousel slide carousel-fade" data-ride="carousel">

                                   <div class="carousel-inner" role="listbox">
									 @if(count($testimonials)>0 && !empty($testimonials))
									
									 @foreach($testimonials as $value)
									    @php
									      $path = 'public/storage/admin_assets/images/author_image/' . (!empty($value) && is_object($value) && !empty($value->author_image) ? $value->author_image : '');
										   $testimonials_content = !empty($value) && is_object($value) && !empty($value->content) ? $value->content : '';
											$testimonials_content = strtr($testimonials_content, [
											'{{LOGO}}' => '<span class="actual-logo sz-grp2"><img src="' . asset("public/assets/images/actual-logo.png") .'"></span>',
											'{{LOGO_WHITE}}' => '<span class="actual-logo sz-grp2"><img src="' . asset("public/assets/images/actual-logo-white.png") .'"></span>'
											]);
							
							
										
										@endphp
										
										<div @if ($loop->first) class="item active" @else class="item" @endif>
                                        <div class="col-md-8 col-md-offset-2">
                                            <div class="test_item fix">
                                                <div class="test_img fix">

                                                    @if(!empty($value) && is_object($value) && !empty($value->author_image) && file_exists($path))

                                                    {{ Html::image($path, 'image', array('title' => (!empty($value) && is_object($value) && !empty($value->author_image) ? $value->author_image : '' ), 'class' => 'img-circle')) }}

                                                    @else

                                                    {{ Html::image(asset('/public/admin_assets/images/user-profile.jpg'), 'no-image', array('title' => 'Image Preview', 'class' => 'img-circle')) }}

                                                    @endif

                                                    
                                                </div>

                                                <div class="test_text text-white">
												
                                                    <em>{!! !empty($testimonials_content) && isset($testimonials_content) ? $testimonials_content : '' !!}<span class="actual-logo sz-grp2"><img src="{{ asset('public/assets/images/actual-logo-white.png') }}"></span>. {{ $value->occupation }}</em>

                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        @endforeach
										@else
										    <p>No Testimonials</p>    
										@endif
                                     </div>
                                <!-- End off carosel inner -->

                                <!-- Controls -->
                                <a class="left carousel-control" href="#testslid" role="button" data-slide="prev">
                                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                                    <span class="sr-only">Previous</span>
                                </a>

                                <a class="right carousel-control" href="#testslid" role="button" data-slide="next">
                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                                    <span class="sr-only">Next</span>
                                </a>

                            </div>

                        </div>
                    </div><!-- End off row -->
                </div><!-- End off container -->
            </section><!-- End off test section -->
