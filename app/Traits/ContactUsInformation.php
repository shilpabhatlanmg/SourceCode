<?php

namespace App\Traits;

use App\Helpers\Helper;
use Hash;
use Auth;

trait ContactUsInformation
{

    function createContactInformationRequest($request)
    {
            $input['first_name'] = $request['first_name'];
            $input['last_name'] = $request['last_name'];
            $input['email'] = $request['email'];
            $input['comment'] = $request['comment'];
            return $input;
    }
}
