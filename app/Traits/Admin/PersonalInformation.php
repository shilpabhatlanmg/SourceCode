<?php

namespace App\Traits\Admin;

use App\Helpers\Helper;

trait PersonalInformation
{

    function createPersonalInformationRequest($request)
    {
        $input['name'] = $request['name'];
        $input['address'] = $request['address'];
        $input['country_id'] = $request['country_id'];
        $input['state_id'] = $request['state_id'];
        $input['city_id'] = $request['city_id'];
        $input['zip_code'] = $request['zip_code'];
        $input['phone'] = $request['phone'];
        return $input;
    }
}
