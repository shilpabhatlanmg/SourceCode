<?php

namespace App\Traits;

use App\Helpers\Helper;

trait OrganizationInformation
{

    function createOrganizationInformationRequest($request)
    {
        $input['name'] = $request['name'];
        $input['email'] = $request['email'];
        $input['address'] = $request['address'];
        $input['zip_code'] = $request['zip_code'];
        $input['country_id'] = $request['country_id'];
        $input['state_id'] = $request['state_id'];
        $input['city_id'] = $request['city_id'];
        $input['phone'] = $request['phone'];
        $input['emergency_contact'] = $request['emergency_contact'];
        $input['becon_major_id'] = $request['becon_major_id'];
        $input['password'] = bcrypt($request['password']);
        $input['temp_password'] = \Crypt::encryptString($request['password']);
        $input['timezone'] = $request['timezone'];
        $input['role_id'] = $request['role_id'];
        $input['token'] = Helper::getToken();
        return $input;
    }
}
