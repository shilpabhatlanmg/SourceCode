<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\UserDevices;
use App\Model\SiteSetting;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Traits\ApiResponseTrait;
use JWTAuth;
use Auth;
use JWTAuthException;
use Plivo;
use Event;

class MiscellaneousController extends BaseController {

    use ApiResponseTrait;

    /**
     * @author Karnika Sharma
     * @function: requestOTP
     * @param Request $request
     * @desc: request for otp.
     */
    public function requestOTP(Request $request) {
        $data = [];
        $contactNum = explode(' ', $request->contactNumber);
        $otp = $this->getOtp();
        $siteSetting = SiteSetting::where('option_name', 'otp_validiity')->first();
        $role = $request->isVisitor == "true" ? "Visitor" : "Security";

        $contactNumMob = $this->changeNumber($contactNum[1]);
        $user = User::where('contact_number', $contactNumMob)->where('country_code', $contactNum[0])->where('status', 'Active')->where('user_type',$role);
        if ($request->hasForgottenPw == true) {

            $contactNumMob = $this->changeNumber($contactNum[1]);
            //  $user = User::where('contact_number',$contactNumMob)->where('country_code',$contactNum[0])->where('user_type',$role)->first();
            if ($user->first()->user_type == "Visitor") {
                $message = ($role == "Security") ? "User does not exist" : 'Password can be changed only for security';
                return $this->returnDataApi(0, $message, (object) $data);
            }
            if ($user->count()) {
                $result = $user->first()->update(['otp' => $otp, 'otp_created_at' => \Carbon\Carbon::now()]);
                $params = array(
                    'src' => \Config::get('constants.PLIVO_SOURCE_NUMBER'),
                    'dst' => $this->removeBraces($user->first()->country_code . $user->first()->contact_number),
                    'text' => strtr(\Config::get('constants.FORGOT_PASSWORD_SMS'), [
                        '{{MOBILE}}' => $user->first()->country_code . ' ' . $this->removeBraces($user->first()->contact_number),
                        '{{OTP}}' => $otp
                    ])
                );
                $plivoSMS = Plivo::sendSMS($params);
                $data['OTP'] = $otp;
                $data['validity'] = $siteSetting->option_value;
                return $this->returnDataApi(1, 'Otp has been sent', $data);
            } else {
                return $this->returnDataApi(0, 'User not found', (object) $data);
            }
        } else {
            if ($user->count() && $user->first()->user_type == "Security") {
                return $this->returnDataApi(0, 'Already registered with security app', (object) $data);
            }

            if ($user->count()) {
                if ($user->first()->update(['otp' => $otp, 'otp_created_at' => \Carbon\Carbon::now()])) {
                    $params = array(
                        'src' => \Config::get('constants.PLIVO_SOURCE_NUMBER'),
                        'dst' => $this->removeBraces($user->first()->country_code . $user->first()->contact_number),
                        'text' => strtr(\Config::get('constants.FORGOT_PASSWORD_SMS-2'), [
                            '{{OTP}}' => $otp
                        ])
                    );
                    $plivoSMS = Plivo::sendSMS($params);
                    $data['OTP'] = $otp;
                    $data['validity'] = $siteSetting->option_value;
                    return $this->returnDataApi(1, 'Otp has been sent', $data);
                }
            } else {
                if ($role == "Visitor") {
                    $userVisitor = new User();
                    $userVisitor->contact_number = $contactNumMob;
                    $userVisitor->country_code = $contactNum[0];
                    $userVisitor->user_type = "Visitor";
                    $userVisitor->status = "Active";
                    $userVisitor->otp = $otp;
                    $userVisitor->otp_created_at = \Carbon\Carbon::now();
                    if ($userVisitor->save()) {
                        $params = array(
                            'src' => \Config::get('constants.PLIVO_SOURCE_NUMBER'),
                            'dst' => $this->removeBraces($request->contactNumber),
                            'text' => strtr(\Config::get('constants.FORGOT_PASSWORD_SMS-2'), [
                                '{{OTP}}' => $otp
                            ])
                        );
                        $plivoSMS = Plivo::sendSMS($params);
                        $data['OTP'] = $otp;
                        $data['validity'] = $siteSetting->option_value;
                        return $this->returnDataApi(1, 'Otp has been sent', $data);
                    }
                }
                return $this->returnDataApi(0, 'something went wrong', (object) $data);
            }
        }
        return $this->returnDataApi(0, 'parameter missing', (object) $data);
    }

    /**
     * @author Karnika Sharma
     * @function: verifyOTP
     * @param Request $request
     * @desc: otp verify by user.
     */
    public function verifyOTP(Request $request) {
        $data = [];
        if (!empty($request->contactNumber) && !empty($request->OTP)) {
            try {
                $role = $request->isVisitor == "true" ? "Visitor" : "Security";
                $data['isOTPValid'] = false;
                $data['accesstoken'] = NULL;

                $contactNum = explode(' ', $request->contactNumber);
                $user = User::where('contact_number', $this->changeNumber(trim($contactNum[1])))->where('country_code', $contactNum[0])->where('user_type', $role)->where('otp', $request->OTP);
                if (!$user->count()) {
                    return $this->returnDataApi(0, 'Please enter valid OTP', (object) $data);
                }
                $siteSetting = SiteSetting::where('option_name', '=', 'otp_validiity')->first();
                $previousOne = date(\Config::get('constants.GENERAL_DATE_TIME_FORMAT'), strtotime(' - 1 days'));
                if ($user->count() && ($user->first()->otp_created_at < $previousOne)) {
                    return $this->returnDataApi(0, 'OTP number expired', (object) $data);
                }
                if ($user->count()) {
                    if ($userToken = JWTAuth::fromUser($user->first())) {
                        $data['isOTPValid'] = true;
                        $data['accesstoken'] = $userToken;
                        return $this->returnDataApi(1, 'Verified', $data);
                    }
                }
            } catch (\Exception $e) {
                return $this->returnDataApi(0, 'Error in process', (object) $data);
            }
        }
        return $this->returnDataApi(0, 'Error in process', (object) $data);
    }


    /**
     * @author Karnika Sharma
     * @function: notificationTokenRegisteration
     * @param Request $request
     * @desc: nofifictaion token registration.
     */
    public function notificationTokenRegisteration(Request $request) {
        $data = [];
        try {
            $user = JWTAuth::toUser($request->token);
            //$user->device_token = $request->deviceToken;
            if(UserDevices::isExists($user->id, $request->deviceToken) == 0){
              $device = new UserDevices();
              $device->user_id = $user->id;
              $device->device_token = $request->deviceToken;
              $device->save();
            }
            if ($user->save()) {
                $data['contactNumber'] = $user->country_code . ' ' . $this->removeBraces($user->contact_number);
                return $this->returnDataApi(1, 'Updated', $data);
            }
        } catch (\Exception $e) {
            return $this->returnDataApi(0, 'Error in process', (object) $data);
        }
    }


    /**
     * @author Karnika Sharma
     * @function: getUuid
     * @param Request $request
     * @desc: get UUID.
     */
    public function getUuid(Request $request) {
        $data = [];
        $siteSetting = SiteSetting::where('option_name', 'uuid')->first();
        if (count($siteSetting) > 0) {
            $data = ['UUID' => $siteSetting->option_value];
            return $this->returnDataApi(1, 'success', $data);
        } else {
            return $this->returnDataApi(0, 'Data not found', (object) $data);
        }
    }

}
