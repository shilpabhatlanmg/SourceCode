<?php

namespace App\Traits;

trait PaymentInformation
{

    function createPaymentInformationRequest($request, $array = array())
    {
        $input['organization_id'] = $array['organization_id'];
        $input['pay_amount'] = ($request->items->data[0]->plan->amount)/100;
        $input['transaction_id'] = $request->id;
        $input['customer_id'] = $request->customer;
        $input['fingerprint'] = $array['fingerprints'];
        $input['transaction_response'] = $request;
        $input['status'] = $request->status;
        
        if($request->livemode){
         $input['mode'] = 'live';   
        }
        
        return $input;
    }
}
