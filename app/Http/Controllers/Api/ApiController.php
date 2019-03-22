<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use JWTAuth;
use Auth;
use JWTAuthException;
use App\User;
use App\Model\UserDevices;
use App\Traits\ApiResponseTrait;
use \Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use PushNotification;
use App\Components\PushNotifications;

class ApiController extends BaseController
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        // load user object
        // $this->user = new User;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAuthUser(Request $request)
    {
        $data = [];
        $sendData = [];
        $user = JWTAuth::toUser($request->token);
        try {
            if (isset($user)) {
                $sendData['userID'] = $user->id;
                $sendData['name'] = $user->name;
                $sendData['emailAddress'] = $user->email;
                $sendData['contactNumber'] = $user->country_code . ' ' . $this->removeBraces($user->contact_number);
                $sendData['profileImageURL'] = $this->getImagePath($user->profile_image);
                $sendData['token'] = $request->token;//$user->device_token;
                if (!empty($user->password)) {
                    $sendData['shouldCreatePassword'] = false;
                } else {
                    $sendData['shouldCreatePassword'] = true;
                }
                $data = $sendData;
                return $this->returnDataApi(1, 'Success', $data);
            } else {
                return $this->returnDataApi(0, 'Error in process', (object) $data);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnDataApi(0, 'Transaction Error', (object) $data);
        }
    }

    /**
     * @author Karnika Sharma
     * @function: securitylogin
     * @param Request $request
     * @desc: security login.
     */
    public function securitylogin(Request $request)
    {
        $displayuser = [];
        $data = [];
        $contactNum = explode(' ', $request->contactNumber);
        $countryCode = trim($contactNum[0]);
        $contactNumMob = $this->changeNumber(trim($contactNum[1]));
        $checkExists = User::where('contact_number', $contactNumMob)->where('country_code', $countryCode);
        try {
            if ($checkExists->count()) {
                $shouldCreatePassword = true;
                if (!empty($checkExists->first()->password)) {
                    $shouldCreatePassword = false;
                    if ($token = JWTAuth::attempt(['contact_number' => $contactNumMob, 'country_code' => $countryCode, 'password' => $request->password])) {
                        //$user = JWTAuth::toUser($token);
                        $user = $checkExists->first();
                    } else {
                        return $this->returnDataApi(0, 'Invalid login credentials', (object) $data);
                    }
                } else {
                    $user = User::where('contact_number', $contactNumMob)->where('country_code', $countryCode)->where('otp', $request->password);
                    $previousOne = date(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'), strtotime(' - 1 days'));
                    if ($user->count()) {
                        if ($user->first()->otp_created_at < $previousOne) {
                            return $this->returnDataApi(0, 'OTP number expired', (object) $data);
                        } else {
                            $user = $user->first();
                        }
                    } else {
                        return $this->returnDataApi(0, 'Wrong OTP entered', (object) $data);
                    }
                }

                if (!empty($user)) {
                    if (!$userToken = JWTAuth::fromUser($user)) {
                        return $this->returnDataApi(0, 'Invalid login credentials', (object) $data);
                    }
                    if ($request->deviceToken) {
                        //$user->device_token = $request->deviceToken;
                        if (UserDevices::isExists($user->id, $request->deviceToken) == 0) {
                            $device = new UserDevices();
                            $device->user_id = $user->id;
                            $device->device_token = $request->deviceToken;
                            $device->save();
                        }
                    }
                    $user->status = 'Active';
                    $user->invitation_status = 'complete';
                    $user->save();
                    $user->token = $userToken;
                    $displayuser['userID'] = $user->id;
                    $displayuser['name'] = $user->name;
                    $displayuser['profileImageURL'] = $this->getImagePath($user->profile_image);
                    $displayuser['emailAddress'] = $user->email;
                    $displayuser['contactNumber'] = $user->country_code . ' ' . $this->removeBraces($user->contact_number);
                    $displayuser['shouldCreatePassword'] = $shouldCreatePassword;
                    $displayuser['token'] = $userToken;
                    $data = $displayuser;
                    return $this->returnDataApi(1, 'Success', $data);
                } else {
                    return $this->returnDataApi(0, 'Invalid login credentials', (object) $data);
                }
            } else {
                return $this->returnDataApi(0, 'Invalid login credentails', (object) $data);
            }
        } catch (\Exception $e) {
            return $this->returnDataApi(0, 'Invalid login credentials', $data);
        }
    }
}
