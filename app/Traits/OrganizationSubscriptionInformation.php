<?php

namespace App\Traits;

trait OrganizationSubscriptionInformation
{

    function createOrganizationSubscriptionInformationRequest($request)
    {
        $input['organization_id'] = $request->organization_id;
        $input['subscription_id'] = $request->subscription_id;
        $input['payment_id'] = $request->payment_id;
        $input['from_date'] = $request->from_date;
        $input['expiry_date'] = $request->expiry_date;
        return $input;
    }
}
