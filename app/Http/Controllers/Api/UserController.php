<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\ChatRecord;
use Validator;
use DB;
use Hash;
use Response;
use JWTAuth;
use Auth;
use File;
use App\Model\UserDevices;
use Mail;
use JWTAuthException;
use App\Components\ImageProcessing;
use App\Traits\ApiResponseTrait;

class UserController extends Controller {

    use ApiResponseTrait;

    /**
     * @author Karnika Sharma
     * @function: updateuserpassword
     * @param Request $request
     * @desc: update user password.
     */
    public function updateuserpassword(Request $request) {
        $data = [];
        $user = JWTAuth::toUser($request->token);
        //~ $user = User::where('id',$request->id)->first();
        if (!empty($user)) {
            $user->password = Hash::make($request->password);
            if ($user->save()) {
                $data['userID'] = $user->id;
                $data['name'] = $user->name;
                $data['emailAddress'] = $user->email;
                $data['contactNumber'] = $user->country_code . ' ' . $this->removeBraces($user->contact_number);
                $data['token'] = $request->token;
                $data['profileImageURL'] = $this->getImagePath($user->profile_image);
                if (empty($user->password)) {
                    $data['shouldCreatePassword'] = true;
                } else {
                    $data['shouldCreatePassword'] = false;
                }
                return $this->returnDataApi(1, 'Success.', $data);
            } else {
                return $this->returnDataApi(0, 'Error in process', (object) $data);
            }
        }
    }

    /**
     * @author Karnika Sharma
     * @function: updateUserProfile
     * @param Request $request
     * @desc: change user password from the security app.
     */
    public function changePassword(Request $request) {
        $data = [];
        $user = JWTAuth::toUser($request->token);
        if (Hash::check($request->oldPassword, $user->password) == false) {
            return $this->returnDataApi(0, 'Current Password does not matched', $data);
        }
        if (Hash::check($request->oldPassword, $user->password)) {
            try {
                $user->password = Hash::make($request->newPassword);
                $user->save();
                $data['userID'] = $user->id;
                $data['name'] = $user->name;
                $data['emailAddress'] = $user->email;
                $data['contactNumber'] = $user->country_code . ' ' . $this->removeBraces($user->contact_number);
                $data['token'] = $request->token;
                $data['profileImageURL'] = $this->getImagePath($user->profile_image);
                if (empty($user->password)) {
                    $data['shouldCreatePassword'] = true;
                } else {
                    $data['shouldCreatePassword'] = false;
                }
                return $this->returnDataApi(1, 'Password Updated', $data);
            } catch (\Exception $e) {
                return $this->returnDataApi(0, [$e->getMessage()], (object) $data);
            }
        } else {
            return $this->returnDataApi(0, 'Current Password does not matched.', (object) $data);
        }
    }

    /**
     * @author Karnika Sharma
     * @function: updateUserProfile
     * @param Request $request
     * @desc: update user profile from the security app.
     */
    public function updateUserProfile(Request $request) {
        $data = [];
        $emptyObj = new \stdClass();
        DB::beginTransaction();
        $user = JWTAuth::toUser($request->token);
        try {
            $user->name = (isset($request->name)) ? $request->name : $user->name;
            $user->email = (isset($request->email)) ? $request->email : $user->email;
            $user->contact_number = (isset($request->contactNumber)) ? $this->changeNumber(trim(str_replace($user->country_code, '', $request->contactNumber))) : $user->contact_number;
            if (isset($request->profileImage)) {
                $TIMESTAMP = mt_rand(1, 1000) . '_' . time() . '_';
                $imageData = base64_decode($request->profileImage);
                $imgProcess = new ImageProcessing();
                $mime_type = $imgProcess->getImageMimeType($imageData);
                $path = public_path("/storage/admin_assets/images/profile_image/");

                $filename = $TIMESTAMP . '.' . $mime_type;
                $file = $path . $filename;
                if (!File::exists($path)) {
                    File::makeDirectory($path, 0777, true, true);
                }
                $success = file_put_contents($file, $imageData);
            }
            $user->profile_image = (isset($request->profileImage)) ? $filename : $user->profile_image;
            if ($user->save()) {
                DB::commit();
                $data['userID'] = $user->id;
                $data['name'] = $user->name;
                $data['emailAddress'] = $user->email;
                $data['contactNumber'] = $user->country_code . ' ' . $user->contact_number;
                $data['token'] = $user->device_token;
                $data['profileImageURL'] = $this->getImagePath($user->profile_image);
                if (empty($user->password)) {
                    $data['shouldCreatePassword'] = true;
                } else {
                    $data['shouldCreatePassword'] = false;
                }
                return $this->returnDataApi(1, 'Data has been updated', $data);
            } else {
                return $this->returnDataApi(0, 'Failed', $emptyObj);
            }
        } catch (\Exception $e) {         //$e->getMessage()
            return $this->returnDataApi(0, 'Failed', $emptyObj);
            DB::rollBack();
        }
    }

    /**
     * @author Karnika Sharma
     * @function: getcontactsLastChat
     * @param Request $request
     * @desc: logout from the security app with delete device token 24 december 18.
     */
    public function userLogout(Request $request) {
        $data = [];

        $user = JWTAuth::toUser($request->token);
        //$user->device_token = null;

        if(!empty($request->deviceToken)){
          $deviceId = UserDevices::isExists($user->id, $request->deviceToken);
          if($deviceId) UserDevices::where('id', $deviceId)->first()->forceDelete();
        }
        try {
            if ($user->save()) {
                JWTAuth::invalidate();
                $data = ['success_Message' => 'Logged out successfully'];
                return $this->returnDataApi(1, 'Success', $data);
            } else {
                return $this->returnDataApi(0, 'Failed', (object) $data);
            }
        } catch (\Exception $e) {
            return $this->returnDataApi(0, 'Failed', $e->getMessage());
            DB::rollBack();
        }
    }

    /**
     * @author Karnika Sharma
     * @function: getcontactsLastChat
     * @param Request $request
     * @desc: get logged user contacts.
     */
    public function getcontacts(Request $request) {
        $data = [];
        $list = [];
        $page = $request->page;
        $user = JWTAuth::toUser($request->token);
        $records = env('RECORD_PER_PAGE');
        $totalRecords = 0;
        $totalPages = 1;
        if ($user) {
            $totalRecords = User::where('organization_id', $user->organization_id)->get();
            $totalRecords = $totalRecords->count();
            $totalPages = ($totalRecords > 0) ? $totalRecords / env('RECORD_PER_PAGE') : 1;
            $securityGuard = User::where('organization_id', $user->organization_id)
                            ->with(['chatRecords' => function($q) use ($user) {
                                    return $q->where('chat_user_id', '=', $user->id);
                                }])
                            ->where('user_type', '=', 'Security')
                            ->skip($page * env('RECORD_PER_PAGE'))->take(env('RECORD_PER_PAGE'))->get();
            foreach ($securityGuard as $value) {
                $contacts = [];
                if ($value->id != $user->id) {
                    $contacts['userID'] = $value->id;
                    $contacts['name'] = $value->name;
                    $contacts['emailAddress'] = $value->email;
                    $contacts['contactNumber'] = $value->country_code . ' ' . $this->removeBraces($value->contact_number);
                    $contacts['profileImageURL'] = $this->getImagePath($value->profile_image);
                    if (($user->id) < ($value->id)) {
                        $userID1 = $user->id;
                        $userID2 = $value->id;
                    } else {
                        $userID1 = $value->id;
                        $userID2 = $user->id;
                    }

                    $doc = ((pow(2, 15)) * $userID1) + $userID2;
                    //$messages = ChatRecord::where('user_id', $user->id)->where('chat_user_id', $value->id)->first();
                    $messages = $value->chatRecords->first();
                    if (count($messages) > 0) {
                        $messCount = $messages->count;
                    } else {
                        $messCount = 0;
                    }

                    $contacts['unreadMessages'] = $messCount;
                    $contacts['nodeIdentifier'] = (string) $doc;

                    if (count($messages) > 0) {
                        $lastmessag = $messages->latest_msg;
                        $chatTime = date(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'), strtotime($messages->chat_date_time));
                    } else {
                        $lastmessag = null;
                        $chatTime = "0000:00:00";
                    }
                    $contacts['timestampOfLastMessage'] = $chatTime;
                    $contacts['lastMessage'] = $lastmessag;
                    $list[] = $contacts;
                }
            }
        }
        $data = ['contacts' => $list, 'totalPages' => round($totalPages), 'currentPage' => $page, 'recordsPerPage' => $records];
        return $this->returnDataApi(1, 'Listing', $data);
    }


    /**
     * @author Karnika Sharma
     * @function: getcontactsLastChat
     * @param Request $request
     * @desc: get Contact Last chat record.
     */
    public function getcontactsLastChat(Request $request) {
        $data = [];
        $contacts = [];
        $list = [];
        $page = $request->page;
        $user = JWTAuth::toUser($request->token);
        $records = env('RECORD_PER_PAGE');
        $messages = ChatRecord::where('user_id', $user->id)->orderBy('chat_date_time', 'desc')->get();

        $securityGuard = User::where('organization_id', $user->organization_id)->get();
        foreach ($messages as $value):
            $users = User::where('id', $value->chat_user_id)->first();
            if (count($users) > 0) {
                $contacts['userID'] = $users->id;
                $contacts['name'] = $users->name;
                $contacts['emailAddress'] = $users->email;
                $contacts['contactNumber'] = $users->country_code . ' ' . $this->removeBraces($users->contact_number);
                $contacts['profileImageURL'] = $this->getImagePath($users->profile_image);
                $contacts['timestampOfLastMessage'] = date(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'), strtotime($value->chat_date_time));
                if (($user->id) < ($value->chat_user_id)) {
                    $userID1 = $user->id;
                    $userID2 = $value->chat_user_id;
                } else {
                    $userID1 = $value->chat_user_id;
                    $userID2 = $user->id;
                }

                $doc = ((pow(2, 15)) * $userID1) + $userID2;
                $contacts['unreadMessages'] = $value->count;
                $contacts['nodeIdentifier'] = (string) $doc;
                $contacts['lastMessage'] = $value->latest_msg;
                $list[] = $contacts;
            }
        endforeach;
        $data = ['contacts' => $list];
        return $this->returnDataApi(1, 'Listing', $data);
    }

}
