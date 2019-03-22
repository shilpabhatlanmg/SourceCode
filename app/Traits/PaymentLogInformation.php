<?php

namespace App\Traits;

use Auth;

trait PaymentLogInformation
{

    function createPaymentLogInformationRequest($request, $arr = array())
    {

    	$input['txn_id'] = $request->id;
        $input['organization_id'] = $arr['organization_id'];
        $input['status'] = $request->status;
        $input['subscription_id'] = $arr['subscription_id'];
        $input['response'] = $request;
        
        if(!empty($arr['type'])){
    		$input['type'] = $arr['type'];
    	}
        return $input;
    }
}
