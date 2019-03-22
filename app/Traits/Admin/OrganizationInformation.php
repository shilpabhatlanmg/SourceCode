<?php

namespace App\Traits\Admin;

use App\Helpers\Helper;

trait OrganizationInformation
{

    function createOrganizationInformationRequest($request)
    {
        if(!empty($request['id']) && isset($request['id'])){

            $input['name'] = $request['name'];
            $input['email'] = $request['email'];
            $input['address'] = $request['address'];
            $input['country_id'] = $request['country_id'];
            $input['state_id'] = $request['state_id'];
            $input['city_id'] = $request['city_id'];
            $input['zip_code'] = $request['zip_code'];
            $input['phone'] = $request['phone'];
            $input['emergency_contact'] = $request['emergency_contact'];
            $input['password'] = bcrypt($request['password']);
            $input['timezone'] = $request['timezone'];
            $input['token'] = Helper::getToken();
        //$input['zip_code'] = $request['zip_code'];
        //$input['phone'] = $request['phone'];
            return $input;



        }else {

            $input['name'] = $request['name'];
            $input['email'] = $request['email'];
            $input['address'] = $request['address'];
            $input['country_id'] = $request['country_id'];
            $input['state_id'] = $request['state_id'];
            $input['city_id'] = $request['city_id'];
            $input['zip_code'] = $request['zip_code'];
            $input['phone'] = $request['phone'];
            $input['emergency_contact'] = $request['emergency_contact'];
            $input['becon_major_id'] = $request['becon_major_id'];
            $input['role_id'] = $request['role_id'];
            $input['password'] = bcrypt($request['password']);
            $input['token'] = Helper::getToken();
        //$input['zip_code'] = $request['zip_code'];
        //$input['phone'] = $request['phone'];
            return $input;

        }

    }
}
