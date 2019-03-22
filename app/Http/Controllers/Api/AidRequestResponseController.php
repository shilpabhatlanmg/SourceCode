<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Premise;
use App\User;
use Validator;
use DB;
use Response;
use JWTAuth;
use Auth;
use File;
use Mail;
use JWTAuthException;
use App\Traits\ApiResponseTrait;
use PushNotification;
use App\Components\PushNotifications;

class AidRequestResponseController extends Controller {

    use ApiResponseTrait;

    
    /**
     * @author Karnika Sharma
     * @function: reportResponseSubmit
     * @param Request $request
     * @desc: submit report response.
     */
    public function reportResponseSubmit(Request $request) {
        $data = [];
        try {
            $user = JWTAuth::toUser($request->token);
            $incident = new AidRequestResponse();
            $incident->aid_request_id = $request->incidentType;
            $incident->user_id = $user->id;
            $incident->status = 'Active';
            if ($incident->save()) {
                $data = $incident;
                return $this->returnDataApi(1, 'Success', $data);
            } else {
                return $this->returnDataApi(0, 'Failed', (object) $data);
            }
        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
            DB::rollBack();
        }
    }

}
