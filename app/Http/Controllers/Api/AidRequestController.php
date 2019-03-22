<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AidRequest;
use App\Model\Location;
use App\Model\Premise;
use App\Model\Becon;
use App\Model\ChatRecord;
use App\Admin;
use App\Model\AidRequestResponse;
use App\User;
use Validator;
use DB;
use Response;
use JWTAuth;
use Auth;
use JWTAuthException;
use File;
use Mail;
use App\Traits\ApiResponseTrait;
use PushNotification;
use App\Components\PushNotifications;

class AidRequestController extends Controller {

    use ApiResponseTrait;

    
    /**
     * @author Karnika Sharma
     * @function: reportIncident
     * @param Request $request
     * @desc: report incident by users.
     */
    public function reportIncident(Request $request) {
        $data = [];
        $user = JWTAuth::toUser($request->token);
        
        try {
            if (isset($user) && (!empty($user))) {
                
                $cn = $user->country_code . ' ' . $this->removeBraces($user->contact_number);

                switch ($request->incidentType) {
                    case 2: $incidentType = 'Fire';
                        break;
                    case 3: $incidentType = 'Police';
                        break;
                    case 4: $incidentType = 'Assists';
                        break;
                    default:
                        $incidentType = 'Medical';
                }

                $aidRequest = new AidRequest();
                $aidRequest->incident_type_id = $request->incidentType;
                $aidRequest->becon_major_id = $request->majorID;
                $aidRequest->minor_id = $request->minorID;
                $aidRequest->organization_id = \DB::raw('getOrganization('.$request->majorID.')');
                $aidRequest->becon_id = \DB::raw('getBeconId('.$request->majorID.','.$request->minorID.')');
                $aidRequest->user_id = $user->id;
                $aidRequest->status = 'Active';
                if ($aidRequest->save()) {
                    $organisation = Admin::where('becon_major_id', $request->majorID)->first();
                    if (isset($organisation) && (!empty($organisation))) {
                        $organisationName = $organisation->name;
                    } else {
                        $organisationName = '';
                    }
                    //\DB::enableQueryLog();
                    $securityGuards = User::where('organization_id', $organisation->id)
                                    ->with([
                                        'chatRecords',
                                        'devices',
                                        'incidentResponses'
                                    ])
                                    ->where('status', 'Active')->orWhereHas('incidentResponses', function ($q) {
                                return $q->where('created_at', '>=', \DB::raw('users.last_respond_incident'));
                            })->where('user_type', '=', 'Security')->get();
                    //print_r($securityGuards->toArray());die;
                    //echo '<pre>'; print_r(\DB::getQueryLog());die;
                    //echo '<pre>'; print_r($securityGuards->toArray());die;
                    $becons = Becon::with([
                                        'getLocationName' => function ($q) {
                                            $q->select('id', 'name');
                                        },
                                        'getPremiseName' => function ($q) {
                                            $q->select('id', 'name');
                                        }
                                    ])
                                    ->where('organization_id', $organisation->id)
                                    ->where('minor_id', $request->minorID)->first();

                    $premiseName = $locationName = '';
                    if (!empty($becons)) {
                        $premiseName = $becons->getPremiseName['name'];
                        $locationName = $becons->getLocationName['name'];
                    }
                    //$requestLists = AidRequest::where('user_id',$user->id)->where('id',$aidRequest->id)->first();
                    foreach ($securityGuards as $value) {
                        $response = new AidRequestResponse();
                        $response->aid_request_id = $aidRequest->id;
                        $response->user_id = $value->id;
                        if ($value->id != $user->id) {
                            $response->status = 'Inactive';
                            $list = [];
                            $list['reportID'] = $aidRequest->id;
                            if (config('app.timezone') != 'UTC') {
                                $list['timestamp'] = \Carbon\Carbon::parse($aidRequest->created_at, config('app.timezone'))->setTimezone('UTC')->format(\Config::get('constants.GENERAL_DATE_TIME_FORMAT')) . 'Z';
                            } else {
                                $list['timestamp'] = \Carbon\Carbon::parse($aidRequest->created_at, config('app.timezone'))->format(\Config::get('constants.GENERAL_DATE_TIME_FORMAT')) . 'Z';
                            }
                            $list['organization'] = $organisationName;
                            $list['location'] = $locationName;
                            $list['premise'] = $premiseName;
                            $list['type'] = $aidRequest->incident_type_id;
                            $list['hasResponded'] = false;
                            $attended = $attendedList = [];

                            $list['attendedBy'] = [];
                            $initiated['userID'] = $user->id;
                            $initiated['name'] = $user->name;
                            $initiated['contactNumber'] = $user->country_code . ' ' . $this->removeBraces($user->contact_number);
                            $initiated['profileImageURL'] = $this->getImagePath($user->profile_image);
                            $list['initiatedBy'] = $initiated;

                            //$collection = collect($value->incidentResponses);
                            // $filtered = $collection->where('created_at','>=',$value->last_respond_incident);
                            //$chatRecords = ChatRecord::where('user_id',$value->id)->first();
                            // if(count($chatRecords)>0){
                            //     $chatFlag = $chatRecords->count;
                            // } else {
                            //     $chatFlag= 0;
                            // }
                            $badge = count($value->incidentResponses);
                            $chatRecords = $value->chatRecords->pluck('count');
                            if (count($chatRecords) > 0) {
                                $badge = $chatRecords->sum();
                            }
                            $pushNotifications = new PushNotifications();
                            //$deviceToken = $value->device_token;
                            if(count($value->devices)){
                              foreach($value->devices as $deviceToken){
                                $res = $pushNotifications->firebaseNotification($deviceToken->device_token, 'Report Incident', 'report', json_encode($list), $cn, $incidentType, $badge);
                              }
                            }
                        } else {
                            $response->status = 'Active';
                        }
                        $response->save();
                    }
                    $data = ['organizationName' => $organisationName, 'premiseName' => $premiseName, 'locationName' => $locationName];
                    return $this->returnDataApi(1, 'Success.', $data);
                } else {
                    return $this->returnDataApi(0, 'Error in processing', (object) $data);
                }
            } else {
                return $this->returnDataApi(0, 'Token expired', (object) $data);
            }
        } catch (Exception $ex) {
            return $this->returnDataApi(0, 'Error in processing', (object) $data);
        }
    }


    /**
     * @author Karnika Sharma
     * @function: getIncidents
     * @param Request $request
     * @desc: get incident report.
     */
    public function getIncidents(Request $request) {
        $data = $list = $initiated = [];
        $reports = [];
        $aidRequest = [];
        $user = JWTAuth::toUser($request->token);
        $user->last_respond_incident = \Carbon\Carbon::now()->format(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'));
        $user->save();

        $page = isset($request->page) ? $request->page : 0;
        $records = (int) env('RECORD_PER_PAGE');
        $type = $request->type;

        $yesterday = date('Y-m-d', strtotime("-1 days")); //.' 23:59:00';
        $dayPrevious = date('Y-m-d', strtotime("-2 days")); //.' 00:00:00';
        $time = date('Y-m-d') . ' 00:00:00';
        $organisation = admin::where('id', $user->organization_id)->first();
        if ($type == 'today') {
            $aidRequest = AidRequest::where('becon_major_id', $organisation->becon_major_id)->with(['getBeconsName.getPremiseName'])->with(['getBeconsName.getLocationName', 'responses' => function ($q) {
                            return $q->with([
                                        'getUser' => function ($q) {
                                            return $q->select('id', 'name', 'email', 'contact_number', 'profile_image');
                                        }
                                    ])->where('status', 'Active');
                        }])->where('created_at', '>=', $time)->orderBy('created_at', 'desc')->skip($page * $records)->take($records)->get();

            $totalData = AidRequest::where('becon_major_id', $organisation->becon_major_id)->with(['getBeconsName.getPremiseName'])->with(['getBeconsName.getLocationName', 'responses' => function ($q) {
                            return $q->with([
                                        'getUser' => function ($q) {
                                            return $q->select('id', 'name', 'email', 'contact_number', 'profile_image');
                                        }
                                    ])->where('status', 'Active');
                        }])->where('created_at', '>=', $time)->get();

            $total = $totalData->count();
        }

        if ($type == 'yesterday') {
            $aidRequest = AidRequest::where('becon_major_id', $organisation->becon_major_id)->
                            //~ with(['getBeconsName.getPremiseName'])->

                            with(['responses' => function ($q) {
                                    return $q->with([
                                                'getUser' => function ($q) {
                                                    return $q->select('id', 'name', 'email', 'contact_number', 'profile_image');
                                                }
                                            ])->where('status', 'Active');
                                }])->whereBetween('created_at', [$yesterday . ' 00:00:00', $yesterday . ' 23:59:59'])->orderBy('created_at', 'desc')->skip($page * $records)->take($records)->get();


            $totalData = AidRequest::where('becon_major_id', $organisation->becon_major_id)->with(['getBeconsName.getPremiseName'])->with(['getBeconsName.getLocationName', 'responses' => function ($q) {
                            return $q->with([
                                        'getUser' => function ($q) {
                                            return $q->select('id', 'name', 'email', 'contact_number', 'profile_image');
                                        }
                                    ])->where('status', 'Active');
                        }])->whereBetween('created_at', [$yesterday . ' 00:00:00', $yesterday . ' 23:59:59'])->orderBy('created_at', 'desc')->get();

            $total = $totalData->count();
        }

        if ($type == 'others') {
            $time = date('Y-m-d') . ' 00:00:00';
            $aidRequest = AidRequest::where('becon_major_id', $organisation->becon_major_id)->with(['getBeconsName.getPremiseName'])->with(['getBeconsName.getLocationName'])->with(['responses' => function ($q) {
                            return $q->with([
                                        'getUser' => function ($q) {
                                            return $q->select('id', 'name', 'email', 'contact_number', 'profile_image');
                                        }
                                    ])->where('status', 'Active');
                        }])->where('created_at', '<=', $dayPrevious)->orderBy('created_at', 'desc')->skip($page * $records)->take($records)->get();

            $totalData = AidRequest::where('becon_major_id', $organisation->becon_major_id)->with(['getBeconsName.getPremiseName'])->with(['getBeconsName.getLocationName', 'responses' => function ($q) {
                            return $q->with([
                                        'getUser' => function ($q) {
                                            return $q->select('id', 'name', 'email', 'contact_number', 'profile_image');
                                        }
                                    ])->where('status', 'Active');
                        }])->where('created_at', '<=', $dayPrevious)->get();
            $total = $totalData->count();
        }
        if (isset($aidRequest) && $aidRequest->count()) {
            foreach ($aidRequest as $aidreq) {
                $list['reportID'] = $aidreq->id;
                if (config('app.timezone') != 'UTC') {
                    $list['timestamp'] = \Carbon\Carbon::parse($aidreq->created_at, config('app.timezone'))->setTimezone('UTC')->format(\Config::get('constants.GENERAL_DATE_TIME_FORMAT')) . 'Z';
                } else {
                    $list['timestamp'] = \Carbon\Carbon::parse($aidreq->created_at, config('app.timezone'))->format(\Config::get('constants.GENERAL_DATE_TIME_FORMAT')) . 'Z';
                }
                $list['organization'] = $organisation->name;
                $becons = Becon::with([
                                    'getLocationName' => function ($q) {
                                        $q->select('id', 'name');
                                    },
                                    'getPremiseName' => function ($q) {
                                        $q->select('id', 'name');
                                    }
                                ])
                                ->where('organization_id', $organisation->id)
                                ->where('minor_id', $aidreq->minor_id)->first();

                $premiseName = $locationName = '';
                if (!empty($becons)) {
                    $premiseName = $becons->getPremiseName['name'];
                    $locationName = $becons->getLocationName['name'];
                }
                $list['location'] = $locationName;
                $list['premise'] = $premiseName;
                $list['type'] = $aidreq->incident_type_id;
                $list['hasResponded'] = false;
                $attended = $attendedList = [];
                foreach ($aidreq->responses as $responses) {
                    $attended['userID'] = $responses['getUser']->id;
                    $attended['name'] = $responses['getUser']->name;
                    $attended['profileImageURL'] = $this->getImagePath($responses->profile_image);
                    $attended['contactNumber'] = $responses['getUser']->country_code . ' ' . $this->removeBraces($responses['getUser']->contact_number);
                    $attendedList [] = $attended;
                }
                $list['attendedBy'] = $attendedList;
                $initiated['userID'] = $user->id;
                $initiated['name'] = $user->name;
                $initiated['contactNumber'] = $user->country_code . ' ' . $this->removeBraces($user->contact_number);
                $initiated['profileImageURL'] = $this->getImagePath($user->profile_image);
                $list['initiatedBy'] = $initiated;
                $reports[] = $list;
            }
            $totalPages = $total / $records;
            $data = ['records_count' => $total, 'reports' => $reports, 'totalPages' => round($totalPages), 'currentPage' => (int) $page, 'recordsPerPage' => (int) $records];
            return $this->returnDataApi(1, 'Success.', $data);
        } else {
            $data = ['reports' => $reports];
            return $this->returnDataApi(1, 'No result found', $data);
        }
    }


    /**
     * @author Karnika Sharma
     * @function: reportsresponse
     * @param Request $request
     * @desc: report response by user.
     */
    public function reportsresponse(Request $request) {
        $data = [];
        try {
            $user = JWTAuth::toUser($request->token);
            $getResponse = AidRequestResponse::where('aid_request_id', $request->incidentID)->where('user_id', $user->id);
            if ($getResponse->count()) {
                $data = $getResponse->first();
                if ($data->update(['status' => 'Active'])) {
                    return $this->returnDataApi(1, 'Success', $data);
                } else {
                    return $this->returnDataApi(0, 'Failed', (object) $data);
                }
            } else {
                return $this->returnDataApi(0, 'Records not found', (object) $data);
            }
        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
            DB::rollBack();
        }
    }

}
