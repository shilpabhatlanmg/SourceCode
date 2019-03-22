<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Admin;
use App\Model\Becon;
use App\Model\Location;
use App\Model\Premise;
use App\Model\AidRequest;
use App\Model\Admin\VisitorLog;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Traits\ApiResponseTrait;
use JWTAuth;
use Auth;
use JWTAuthException;

class BeaconController extends Controller {

    use ApiResponseTrait;

    
    /**
     * @author Karnika Sharma
     * @function: getBeaconUUID
     * @desc: get UUID by beacon.
     */
    public function getBeaconUUID() {
        $beaconUUID = \App\Model\Admin\SiteSetting\SiteSetting::where('option_name', '=', 'uuid')->first();
        if (is_null($beaconUUID)) {
            return $this->sendError(__('messages.beacon.uuid_not_found'));
        }
        return $this->sendResponse(['UUID' => $beaconUUID->option_value]);
    }


    /**
     * @author Karnika Sharma
     * @function: getorgpreLocInfo
     * @param Request $request
     * @desc: get location request by user.
     */
    public function getorgpreLocInfo(Request $request) {
        $data = [];
        $organizationData = [];
        $premiseName = null;
        $locationName = null;
        $emergencyContact = null;
        $user = JWTAuth::toUser($request->token);
        try {
            if ($user) {
                if (isset($request->majorID)) {
                    $organisation = Admin::where('becon_major_id', $request->majorID)->where('status', 'Active')->first();
                    if (!empty($organisation) && $request->minorID) {
                        $becons = Becon::with([
                                            'getLocationName' => function ($q) {
                                                $q->select('id', 'name');
                                            },
                                            'getPremiseName' => function ($q) {
                                                $q->select('id', 'name');
                                            },
                                            'getOrganizationName' => function ($q) {
                                                $q->select('id', 'emergency_contact');
                                            }
                                        ])
                                        ->where('organization_id', $organisation->id)
                                        ->where('minor_id', $request->minorID)->first();

                        $premiseName = $locationName = '';
                        if (!empty($becons)) {
                            $locationName = $becons->getLocationName['name'];
                            $premiseName = $becons->getPremiseName['name'];
                            $emergencyContact = $this->removeBraces($becons->getOrganizationName['emergency_contact']);
                            if ($user->user_type == 'Security') {
                                $aidRequest = AidRequest::where('becon_major_id', $request->majorID)
                                                ->where('minor_id', $request->minorID)
                                                ->with(['responses' => function ($q) use ($user) {
                                                        return $q->where('user_id', $user->id);
                                                    }])
                                                ->where('status', 'Active')->orderBy('id', 'DESC')->get();

                                    if (!empty($aidRequest) && is_object($aidRequest) && count($aidRequest[0]->responses) > 0) {

                                        foreach($aidRequest as $aidResepon){

                                            $aidResepon->responses()->where('user_id', $user->id)->update(['status' => 'Active']);
                                        }

                                    }
                                /*if (!empty($aidRequest) && count($aidRequest->responses)) {
                                    $aidRequest->responses[0]->update(['status' => 'Active']);
                                }*/
                            } elseif ($user->user_type == 'Visitor') {
                                $visitorLog = new VisitorLog();
                                $visitorLog->location_id = $becons->getLocationName['id'];
                                $visitorLog->user_id = $user->id;
                                $visitorLog->status = 'Active';
                                $visitorLog->save();
                            }
                            $organisationID = $organisation->id;
                            $organisationName = $organisation->name;
                            $data = [
                                'organization' => [
                                    'id' => $organisationID,
                                    'name' => $organisationName,
                                    'subscriptionStatus' => 'active'
                                ],
                                'premise' => $premiseName,
                                'locationName' => $locationName,
                                'emergencyContact' => $emergencyContact
                                
                            ];
                            return $this->returnDataApi(1, 'Success.', $data);
                        } else {
                            return $this->returnDataApi(0, 'Insufficient information, cannot determine your location ', (object) $data);
                        }
                    } else {
                        return $this->returnDataApi(0, 'Insufficient information, cannot determine your location ', (object) $data);
                    }
                } else {
                    return $this->returnDataApi(0, 'Insufficient information, cannot determine your location', (object) $data);
                }
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
                //return $this->returnDataApi(0, 'Invalid user credentials', (object)$data);
            }
        } catch (Exception $ex) {
            return $this->returnDataApi(0, 'Insufficient information, cannot determine your location', (object) $data);
        }
    }

}
