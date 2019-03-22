<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User;
use App\Model\ChatRecord;
use App\Model\AidRequestResponse;
use App\Traits\ApiResponseTrait;
use JWTAuth;
use Auth;
use JWTAuthException;
use PushNotification;
use App\Components\PushNotifications;
use App\Http\Controllers\Controller as Controller;

class ChatRecordController extends Controller
{
    use ApiResponseTrait;

    /**
     * @author Karnika Sharma
     * @function: updateLastMessage
     * @param Request $request
     * @desc: update last message.
     */
    public function updateLastMessage(Request $request)
    {
        $data = $contacts = $reponseContacts = [];
        $user = JWTAuth::toUser($request->token);
        $receiverID = User::with(['devices', 'incidentResponses'])->where('id', $request->receiverID)->first();

        $checkMssageSender = ChatRecord::where('chat_user_id', $request->receiverID)->where('user_id', $user->id)->orderBy('chat_date_time', 'desc')->first();
        $checkMssageRecevier = ChatRecord::where('chat_user_id', $user->id)->where('user_id', $request->receiverID)->orderBy('chat_date_time', 'desc')->first();



        if (count($checkMssageSender) > 0) {
            $checkMssageSender->latest_msg = $request->lastMessage;
            $checkMssageSender->chat_date_time = \Carbon\Carbon::now()->format(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'));
            $checkMssageSender->chat_user_image = $this->getImagePath($user->profile_image);

            $checkMssageRecevier->count = ($checkMssageRecevier->count) + 1;
            $checkMssageRecevier->latest_msg = $request->lastMessage;
            $checkMssageRecevier->chat_date_time = \Carbon\Carbon::now()->format(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'));
            $checkMssageRecevier->chat_user_image = $this->getImagePath($receiverID->profile_image);
            $checkMssageRecevier->save();
            if ($checkMssageSender->save()) {

                /*                 * **************notification payload content***************************** */
                $contacts['userID'] = $user->id;
                $contacts['name'] = $user->name;
                $contacts['emailAddress'] = $user->email;
                $contacts['contactNumber'] = $user->country_code . ' ' . $user->contact_number;
                $contacts['profileImageURL'] = $this->getImagePath($user->profile_image);

                $contacts['timestampOfLastMessage'] = \Carbon\Carbon::now()->format(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'));
                if (($user->id) < ($request->receiverID)) {
                    $userID1 = $user->id;
                    $userID2 = $request->receiverID;
                } else {
                    $userID1 = $request->receiverID;
                    $userID2 = $user->id;
                }

                $doc = ((pow(2, 15)) * $userID1) + $userID2;
                $contacts['unreadMessages'] = ($checkMssageRecevier->count);
                $contacts['nodeIdentifier'] = (string) $doc;
                $contacts['lastMessage'] = $request->lastMessage;

                /*                 * **************notification payload content end***************************** */
                /*                 * **************api response content***************************** */

                $reponseContacts['userID'] = $receiverID->id;
                $reponseContacts['name'] = $receiverID->name;
                $reponseContacts['emailAddress'] = $receiverID->email;
                $reponseContacts['contactNumber'] = $receiverID->country_code . ' ' . $receiverID->contact_number;
                $reponseContacts['profileImageURL'] = $this->getImagePath($receiverID->profile_image);
                $reponseContacts['timestampOfLastMessage'] = \Carbon\Carbon::now()->format(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'));
                if (($user->id) < ($request->receiverID)) {
                    $userID1 = $user->id;
                    $userID2 = $request->receiverID;
                } else {
                    $userID1 = $request->receiverID;
                    $userID2 = $user->id;
                }

                $doc = ((pow(2, 15)) * $userID1) + $userID2;
                $reponseContacts['unreadMessages'] = ($checkMssageRecevier->count);
                $reponseContacts['nodeIdentifier'] = (string) $doc;
                $reponseContacts['lastMessage'] = $request->lastMessage;

                $data = $reponseContacts;
                $collection = collect($receiverID->incidentResponses);
                $filtered = $collection->where('created_at', '>=', $receiverID->last_respond_incident);

                /*                 * ***************badge count************************** */
                $badge = $checkMssageRecevier->count + count($filtered);
                /*                 * ******************badge count end***************************** */

                $pushNotifications = new PushNotifications();

                //$deviceToken = $receiverID->device_token;
                foreach ($receiverID->devices as $deviceToken) {
                    $res = $pushNotifications->firebaseNotification($deviceToken->device_token, 'send messages', 'chat', json_encode($contacts), $user->name, 'chat', $badge);
                }
                return $this->returnDataApi(1, 'Updated', $data);
            } else {
                return $this->returnDataApi(0, 'Error in process', (object) $data);
            }
        } else {
            $chatRecordsSender = new ChatRecord();
            $chatRecordsSender->chat_user_id = $request->receiverID;
            $chatRecordsSender->user_id = $user->id;
            $chatRecordsSender->latest_msg = $request->lastMessage;
            $chatRecordsSender->chat_date_time = \Carbon\Carbon::now()->format(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'));
            $chatRecordsSender->count = 0;
            $chatRecordsSender->chat_user_image = $this->getImagePath($user->profile_image);

            $chatRecordsReceiver = new ChatRecord();
            $chatRecordsReceiver->chat_user_id = $user->id;
            $chatRecordsReceiver->user_id = $request->receiverID;
            $chatRecordsReceiver->latest_msg = $request->lastMessage;
            $chatRecordsReceiver->chat_date_time = \Carbon\Carbon::now()->format(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'));
            $chatRecordsReceiver->count = 1;
            $chatRecordsReceiver->chat_user_image = $this->getImagePath($user->profile_image);

            if ($chatRecordsReceiver->save()) {
                $chatRecordsSender->save();

                $contacts['userID'] = $user->id;
                $contacts['name'] = $user->name;
                $contacts['emailAddress'] = $user->email;
                $contacts['contactNumber'] = $user->country_code . ' ' . $user->contact_number;

                $contacts['profileImageURL'] = $this->getImagePath($user->profile_image);
                $contacts['timestampOfLastMessage'] = \Carbon\Carbon::now()->format(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'));
                if (($user->id) < ($request->receiverID)) {
                    $userID1 = $user->id;
                    $userID2 = $request->receiverID;
                } else {
                    $userID1 = $request->receiverID;
                    $userID2 = $user->id;
                }

                $doc = ((pow(2, 15)) * $userID1) + $userID2;
                $contacts['unreadMessages'] = 1;
                $contacts['nodeIdentifier'] = (string) $doc;
                $contacts['lastMessage'] = $request->lastMessage;


                /*                 * *************reponse object *********************************** */

                $reponseContacts['userID'] = $receiverID->id;
                $reponseContacts['name'] = $receiverID->name;
                $reponseContacts['emailAddress'] = $receiverID->email;
                $reponseContacts['contactNumber'] = $receiverID->country_code . ' ' . $receiverID->contact_number;

                $reponseContacts['profileImageURL'] = $this->getImagePath($receiverID->profile_image);

                $reponseContacts['timestampOfLastMessage'] = \Carbon\Carbon::now()->format(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'));
                if (($user->id) < ($request->receiverID)) {
                    $userID1 = $user->id;
                    $userID2 = $request->receiverID;
                } else {
                    $userID1 = $request->receiverID;
                    $userID2 = $user->id;
                }

                $doc = ((pow(2, 15)) * $userID1) + $userID2;
                $reponseContacts['unreadMessages'] = 1;
                $reponseContacts['nodeIdentifier'] = (string) $doc;
                $reponseContacts['lastMessage'] = $request->lastMessage;

                $data = $reponseContacts;
                /*                 * *************reponse object end*********************************** */

                $reponses = AidRequestResponse::where('user_id', $user->id);

                /*                 * ***************badge count************************** */
                $chatRecords = ChatRecord::where('user_id', $request->receiverID)->where('chat_user_id', $user->id)->first();
                $chatFlag = $chatRecords->count;

                $collection = collect($receiverID->incidentResponses);
                $filtered = $collection->where('created_at', '>=', $receiverID->last_respond_incident);

                /*                 * ******************badge count end ***************************** */
                $badge = $chatFlag + count($filtered);
                $pushNotifications = new PushNotifications();
                //$deviceToken = $receiverID->device_token;
                foreach($receiverID->devices as $deviceToken){
                  $res = $pushNotifications->firebaseNotification($deviceToken->device_token, 'send messages', 'chat', json_encode($contacts), $user->name, 'chat', $badge);
                }
                return $this->returnDataApi(1, 'saved', $data);
            } else {
                return $this->returnDataApi(0, 'Error in process', (object) $data);
            }
        }
    }

    /**
     * @author Karnika Sharma
     * @function: resetUnreadMessages
     * @param Request $request
     * @desc: reset unread messages.
     */
    public function resetUnreadMessages(Request $request)
    {
        $data = [];
        $contacts = [];
        $user = JWTAuth::toUser($request->token);
        $checkMssage = ChatRecord::where('chat_user_id', $request->receiverID)->where('user_id', $user->id)->first();
        if (count($checkMssage) > 0) {
            $checkMssage->count = 0;
            if ($checkMssage->save()) {
                $contacts['timestampOfLastMessage'] = \Carbon\Carbon::now()->format('Y-m-d');
                if (($user->id) < ($request->receiverID)) {
                    $userID1 = $user->id;
                    $userID2 = $request->receiverID;
                } else {
                    $userID1 = $request->receiverID;
                    $userID2 = $user->id;
                }
                $doc = ((pow(2, 15)) * $userID1) + $userID1;
                $contacts['unreadMessages'] = 0;
                $contacts['nodeIdentifier'] = (string) $doc;
                $contacts['lastMessage'] = $request->lastMessage;
                $data = $contacts;
                return $this->returnDataApi(1, 'Updated', $data);
            } else {
                return $this->returnDataApi(0, 'Error in process', (object) $data);
            }
        } else {
            return $this->returnDataApi(0, 'Data not found', (object) $data);
        }
    }

    /**
     * @author Karnika Sharma
     * @function: total_badge
     * @param Request $request
     * @desc: count total badge.
     */
    public function total_badge(Request $request)
    {
        $data = [];
        $contacts = [];
        $user = JWTAuth::toUser($request->token);
        $chatRecords = ChatRecord::where('user_id', $user->id)->pluck('count');
        if (count($chatRecords) > 0) {
            $chatFlag = $chatRecords->sum();
        } else {
            $chatFlag = 0;
        }
        $securityGuard = User::with('incidentResponses')->where('id', $user->id)->where('user_type', '=', 'Security')->first();
        if ($securityGuard && count($securityGuard->incidentResponses)) {
            $collection = collect($securityGuard->incidentResponses);
            $filtered = $collection->where('created_at', '>=', $user->last_respond_incident);
            $incidentCount = count($filtered);
        } else {
            $incidentCount = 0;
        }
        $totalBadgeCount = $chatFlag + $incidentCount;
        $data = ['chat_badge_count' => $chatFlag, 'incident_badge_count' => $incidentCount, 'total_badge' => $totalBadgeCount];
        return $this->returnDataApi(1, 'Updated', $data);
    }

    /**
     * @author Karnika Sharma
     * @function: sendError
     * @param $errorMessages
     * @param $code
     * @desc: Chat Notification.
     */
    public function sendError($errorMessages = [], $code = 404)
    {
        $response = [
            'message' => $errorMessages,
        ];
        return response()->json($response, $code);
    }

    /**
     * @author Karnika Sharma
     * @function: chatNotification
     * @param $errorMessages
     * @param $code
     * @desc: Chat Notification.
     */
    public function chatNotification($errorMessages = [], $code = 404)
    {
        $response = [
            'message' => $errorMessages,
        ];
        return response()->json($response, $code);
    }

    /**
     * @author Karnika Sharma
     * @function: croneUnresponses
     * @param Request $request
     * @desc: send notification every 10 min to security guard.
     */
    public function croneUnresponses_old(Request $request)
    {
        $responsesReq = AidRequestResponse::with([
                    'getAidRequest' => function ($q) {
                        return $q->select('id', 'incident_type_id', 'minor_id', 'status', 'created_at');
                    },
                    'getAidRequest.responses' => function ($q) {
                        return $q->where('status', 'Active')->select('id', 'aid_request_id', 'user_id', 'status');
                    },
                    'getAidRequest.responses.getUser' => function ($q) {
                        return $q->select('id', 'name', 'country_code', 'contact_number', 'profile_image');
                    },
                    'getAidRequest.getBeconsName' => function ($q) {
                        return $q->select('id', 'organization_id', 'premise_id', 'location_id', 'minor_id');
                    },
                    'getAidRequest.getBeconsName.getPremiseName' => function ($q) {
                        return $q->select('id', 'name');
                    },
                    'getAidRequest.getBeconsName.getLocationName' => function ($q) {
                        return $q->select('id', 'name');
                    },
                    'getAidRequest.getBeconsName.getOrganizationName' => function ($q) {
                        return $q->select('id', 'name');
                    },
                    'getUser' => function ($q) {
                        return $q->select('id', 'country_code', 'contact_number');
                    },
                    'getUser.devices',
                    'getUser.incidentResponses' => function ($q) {
                        return $q->select('id');
                    },
                    'getUser.chatRecords'
                ])->where('status', 'Inactive')->get();
                
        $currentHour = date('H');
        $pushNotification = new PushNotifications();
        foreach ($responsesReq as $value) {
            $fromFormat = $currentHour . ':' . date('i', strtotime($value->getAidRequest->created_at));
            $toFormat = date('H:i');
            echo 'CRON wil fire a message at ' . $fromFormat . ' == ' . $toFormat . '<br />';
            if ($fromFormat == $toFormat) {
                echo '<br />' . 'HHHH' . '<br />';
                $list = [];
                $list['reportID'] = $value->aid_request_id;
                $list['timestamp'] = date(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'), strtotime($value->getAidRequest->created_at));
                //echo '<pre>';print_r($value->getAidRequest->getBeconsName);die;
                $list['organization'] = $value->getAidRequest->getBeconsName->getOrganizationName->name;
                $list['location'] = $value->getAidRequest->getBeconsName->getLocationName->name;
                $list['premise'] = $value->getAidRequest->getBeconsName->getPremiseName->name;
                $list['type'] = $value->getAidRequest->incident_type_id;
                $list['hasResponded'] = false;
                $list['attendedBy'] = [];
                foreach ($value->getAidRequest->responses as $response) {
                    $attended = [];
                    $attended['userID'] = $response->getUser->id;
                    $attended['name'] = $response->getUser->name;
                    $attended['profileImageURL'] = $this->getImagePath($response->profile_image);
                    $attended['contactNumber'] = $response->getUser->country_code . ' ' . $this->removeBraces($response->getUser->contact_number);
                    $list['attendedBy'][] = $attended;
                    unset($attended);
                }
                $initiated['userID'] = $value->getUser->id;
                $initiated['name'] = $value->getUser->name;
                $initiated['contactNumber'] = $value->getUser->country_code . ' ' . $this->removeBraces($value->getUser->contact_number);
                $initiated['profileImageURL'] = $this->getImagePath($value->getUser->profile_image);
                $list['initiatedBy'] = $initiated;
                $badge = count($value->getUser->incidentResponses);
                $chatRecords = $value->getUser->chatRecords->pluck('count');
                if (count($chatRecords) > 0) {
                    $badge = $chatRecords->sum();
                }
                switch ($value->getAidRequest->incident_type_id) {
                    case 2: $incidentType = 'Fire';
                        break;
                    case 3: $incidentType = 'Police';
                        break;
                    case 4: $incidentType = 'Assists';
                        break;
                    default:
                        $incidentType = 'Medical';
                }

                foreach($value->getUser->devices as $devieToken){
                  $res = $pushNotification->firebaseNotification($deviceToken->device_token, $value->aid_request_id, 'cron-report', json_encode($list), $initiated['contactNumber'], $incidentType, $badge);
                }
            }
        }
    }
    

    /**
     * @author Karnika Sharma
     * @function: croneUnresponses
     * @param Request $request
     * @desc: send notification every 10 min to security guard.
     */
    public function croneUnresponses(Request $request)
    {
        //$pushNotification = new PushNotifications();
        //$res = $pushNotification->sendNotificationFCM();
        //die();
        $responsesReq = AidRequestResponse::with([
                    'getAidRequest' => function ($q) {
                        return $q->select('id', 'incident_type_id','user_id', 'minor_id', 'status', 'created_at');
                    },
                    'getAidRequest.responses' => function ($q) {
                        return $q->where('status', 'Active')->select('id', 'aid_request_id', 'user_id', 'status');
                    },
                    'getAidRequest.responses.getUser' => function ($q) {
                        return $q->select('id', 'name', 'country_code', 'contact_number', 'profile_image');
                    },
                    'getAidRequest.getBeconsName' => function ($q) {
                        return $q->select('id', 'organization_id', 'premise_id', 'location_id', 'minor_id');
                    },
                    'getAidRequest.getBeconsName.getPremiseName' => function ($q) {
                        return $q->select('id', 'name');
                    },
                    'getAidRequest.getBeconsName.getLocationName' => function ($q) {
                        return $q->select('id', 'name');
                    },
                    'getAidRequest.getBeconsName.getOrganizationName' => function ($q) {
                        return $q->select('id', 'name');
                    },
                    'getUser' => function ($q) {
                        return $q->select('id', 'country_code', 'contact_number','name');
                    },
                    'getAidRequest.getRequestUser' => function ($q) {
                        return $q->select('id', 'country_code', 'contact_number','name');
                    },        
                            
                    'getUser.devices',
                    'getUser.incidentResponses' => function ($q) {
                        return $q->select('id');
                    },
                    'getUser.chatRecords'
                ])->where('status', 'Inactive')->get();
                    

        $currentHour = date('H');
        $pushNotification = new PushNotifications();
        
       
        
        if(!empty($responsesReq) && is_object($responsesReq) && count($responsesReq) > 0){
        foreach ($responsesReq as $value) {
            
            $toFormat = strtotime($value->updated_at);
            $fromFormat = strtotime(date('Y-m-d H:i:s'));
            echo $value->updated_at;
            
            if(date('Y-m-d H:i:s')>$value->updated_at){
            //echo "dsfdsf";die;
            //$to_time = strtotime($value->updated_at);
            //$from_time = strtotime(date('Y-m-d h:i:s'));
            $roundTime= round(abs($toFormat-$fromFormat) / 60,2);
            
            
            //dd($value);
            /*$fromMinutesMultiplier = (int)(date('i')/10);
            //$fromMinutesMultiplier = ($fromMinutesMultiplier+1) > 5?1:($fromMinutesMultiplier +1);
            $fromMinutes = $fromMinutesMultiplier*10+(int)date('i', strtotime($value->created_at))%10;
            $fromFormat = $currentHour . ':' . $fromMinutes;
            //$fromFormat = $currentHour . ':' . date('i', strtotime($value->getAidRequest->created_at));
            $toFormat = date('H:i');*/
            echo 'CRON wil fire a message at ' . $fromFormat . ' == ' . $toFormat . '<br />';
            echo $roundTime;
           // echo "dsfdsfdsfds".date('Y-m-d H:i:s');
            
            // $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at);
    //$from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2017-5-6 3:35:35');
            if ($roundTime>=10 && $roundTime < 12) {
                \DB::table('aid_request_responses')
            ->where('id', $value->id)
            ->update(['updated_at' => date('Y-m-d H:i:s')]);
                
                echo '<br />' . 'HHHH' . '<br />';
                $list = [];
                $list['reportID'] = !empty($value->aid_request_id) ? $value->aid_request_id :"";
                $list['timestamp'] = date(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'), strtotime($value->getAidRequest->created_at));
                //echo '<pre>';print_r($value->getAidRequest->getBeconsName);die;
                $list['organization'] = !empty($value->getAidRequest->getBeconsName->getOrganizationName->name) ? $value->getAidRequest->getBeconsName->getOrganizationName->name : '';
                $list['location'] = !empty($value->getAidRequest->getBeconsName->getLocationName->name) ? $value->getAidRequest->getBeconsName->getLocationName->name : '';
                $list['premise'] = !empty($value->getAidRequest->getBeconsName->getPremiseName->name) ? $value->getAidRequest->getBeconsName->getPremiseName->name : '';
                $list['type'] = !empty($value->getAidRequest->incident_type_id) ? $value->getAidRequest->incident_type_id : '';
                $list['hasResponded'] = false;
                $list['attendedBy'] = [];

                if(!empty($value->getAidRequest->responses)){

                foreach ($value->getAidRequest->responses as $response) {
                    $attended = [];
                    $attended['userID'] = !empty($response->getUser->id) ? $response->getUser->id : '';
                    $attended['name'] = !empty($response->getUser->name) ? $response->getUser->name : '';
                    $attended['profileImageURL'] = $this->getImagePath($response->profile_image);
                    $attended['contactNumber'] = !empty($response->getUser->country_code) ? $response->getUser->country_code . ' ' . $this->removeBraces($response->getUser->contact_number) : '';
                    $list['attendedBy'][] = $attended;
                    unset($attended);
                }
            }
                $initiated['userID'] = !empty($value->getUser->id) ? $value->getUser->id : '';
                $initiated['name'] = !empty($value->getUser->name) ? $value->getUser->name : '';
                $initiated['contactNumber'] = !empty($value->getAidRequest->getRequestUser->country_code) ? $value->getAidRequest->getRequestUser->country_code . ' ' . $this->removeBraces($value->getAidRequest->getRequestUser->contact_number) : '';
                $initiated['profileImageURL'] = !empty($value->getUser->profile_image) ? $this->getImagePath($value->getUser->profile_image) : '';
                $list['initiatedBy'] = $initiated;
                $badge = !empty($value->getUser->incidentResponses) ? count($value->getUser->incidentResponses) : '';
                $chatRecords = !empty( $value->getUser->chatRecords) ? $value->getUser->chatRecords->pluck('count') : '';
                if (count($chatRecords) > 0) {
                    $badge = !empty($chatRecords) ? $chatRecords->sum() : '';
                }
                switch ($value->getAidRequest->incident_type_id) {
                    case 2: $incidentType = 'Fire';
                        break;
                    case 3: $incidentType = 'Police';
                        break;
                    case 4: $incidentType = 'Assists';
                        break;
                    default:
                        $incidentType = 'Medical';
                }
                  
                if(!empty($value->getUser->devices) && count($value->getUser->devices) > 0){
                    foreach($value->getUser->devices as $deviceToken){
                        if(!empty($deviceToken->device_token)){
                            //echo $deviceToken->device_token;
                            //echo $value->aid_request_id;
                            //echo '<pre>';print_r($list);
                            //echo $incidentType;die;
                  $res = $pushNotification->firebaseNotification($deviceToken->device_token, $value->aid_request_id, 'cron-report', json_encode($list), $initiated['contactNumber'], $incidentType, $badge);
                    //dd($res);
                  }
                }

                }
                
            }
        }   
        }
    }
    
    
    }
    
    
}
