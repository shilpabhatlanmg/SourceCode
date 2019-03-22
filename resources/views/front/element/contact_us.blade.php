<section id="contact" class="app_screen cnt-screen">
	<div class="container">
		<div class="row">
			<span class="heading">Contact Us</span>
			<span class="txt"> @php

                                    $contact_us_content = !empty($contact_us) && is_object($contact_us) && !empty($contact_us->content) ? $contact_us->content : '';

                                    
                                    @endphp

                                    {!! !empty($contact_us_content) && isset($contact_us_content) ? $contact_us_content : '' !!}
                        </span>
			<div class="frm_outer">
				<div class="frm-inner">
					{{ Form::open(array('id'=>'contactForm')) }}
					<div class="col-md-6 col-sm-6 col-xs-12 pd-1">
						<div class="form-group">
							{{ Form::text('first_name', NULL, ['class'=>'form-control customName required first_name','id'=>'first_name', 'minlength'=>'2', 'maxlength'=>'20','value'=>'' ,"placeholder"=>"First Name"]) }}						
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12 pd-2">
						<div class="form-group">
							{{ Form::text('last_name',NULL ,['class'=>'form-control required last_name', 'minlength'=>'2', 'maxlength'=>'20',"placeholder"=>"Last Name"]) }}						
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							{{ Form::text('email',NULL ,['class'=>'form-control email required email', 'minlength'=>'2', 'maxlength'=>'40',"placeholder"=>"Email Address"]) }}
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							{{ Form::textarea('comment',NULL ,['class'=>'required form-control comment', 'minlength'=>'2', 'rows'=>"3","placeholder"=>"Comment"]) }}						
						</div>
					</div>				
					{{ Form::submit('Send Message', array('class'=>'send-message contactFormSubmit')) }}				
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
</section>

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
