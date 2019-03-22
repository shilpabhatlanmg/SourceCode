<section id="dialogue" class="dialogue bg-white roomy-80">
    <div class="container">
        <div class="row">
            <div class="main_dialogue text-center">
                <div class="col-md-12">

                    @php

                    $professional_responder = !empty($professional_responder) && is_object($professional_responder) && !empty($professional_responder->content) ? $professional_responder->content : '';

                    $professional_responder = strtr($professional_responder, [
                    '{{LOGO}}' => '<span class="actual-logo sz-quote"><img src="' . asset("public/assets/images/actual-logo.png") .'"></span>',
                    '{{LOGO_WHITE}}' => '<span class="actual-logo sz-quote"><img src="' . asset("public/assets/images/actual-logo-white.png") .'"></span>'
                    ]);

                    @endphp

                    {!! !empty($professional_responder) && isset($professional_responder) ? $professional_responder : '' !!}
                    
                    <!--<h2>The First Reponder is rarely a Professional Responder. The Professional Responder is the trained Police Officer, Firefighter, or Medic. Most &quot;First Responders&quot; are regular folks, trying to lend a hand.<br />
                        Empower your First Responders with<span class="actual-logo sz-quote"><img src="{{ asset('public/assets/images/actual-logo.png') }}"></span>.</h2>-->
                    </div>
                </div>
            </div>
        </div>
    </section>
