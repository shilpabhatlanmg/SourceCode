<?php

namespace App\Helpers;

use App;
use Request;
use Carbon\Carbon;
use App\User;
use Event;
use DB;

class Helper {

    /**
     * Get the register path for the application
     * 
     * @return string
     */
    public static function getRegisterPath() {
        return App::make('App\Http\Controllers\Auth\AuthController')->registerPath();
    }

    /**
     * Get the login path for the application
     * 
     * @return string
     */
    public static function getLoginPath() {
        return App::make('App\Http\Controllers\Auth\AuthController')->loginPath();
    }

    /**
     * Get the logout path for the application
     * 
     * @return string
     */
    public static function getLogoutPath() {
        return App::make('App\Http\Controllers\Auth\AuthController')->logoutPath();
    }

    /**
     * Get the Current Location From Session
     * 
     * @return string
     */
    public static function getCurrentLocation() {
        return Request::session()->get('Location');
    }

    /**
     * Get the All Location
     * 
     * @return string
     */
    public static function AllCity() {
        return App\Model\City::getAllCity();
    }

    /**
     * getAllSettings
     * @param
     * @return
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAllSettings() {
        $arrSettingData = App\Model\Admin\SiteSetting\SiteSetting::getAllSettings();
        $arrData = [];
        foreach ($arrSettingData as $settingdata) {
            $arrData[$settingdata->option_name] = $settingdata->option_value;
        }
        return (object) $arrData;
    }

    /**
     * getAllSettings
     * @param
     * @return
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getActiveTestimonials() {
        $testimonial = App\Model\Admin\Testimonial::where('status', '=', 'Active')->get();
        return (object) $testimonial;
    }

    /**
     * getStateNameById
     * @param
     * @return
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getStateNameById($id = false) {
        $arrStateData = App\Model\State::getAllState($id);
        return (!empty($arrStateData) && !empty($arrStateData->name) ? $arrStateData->name : '');
    }

    /**
     * getStateNameById
     * @param
     * @return
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getCityByStateId($state_id) {
        $arrCityData = App\Model\City::getAllCityByStateID($state_id);
        return (isset($arrCityData) && !empty($arrCityData) ? $arrCityData : []);
    }

    /**
     * getStateNameById
     * @param
     * @return
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getCityNameById($id = false) {
        $arrCityData = App\Model\City::getAllCityByStateID('', $id);

        return (!empty($arrCityData) && !empty($arrCityData->name) ? $arrCityData->name : '');
    }

    /**
     * getFooterLeftContentBySlug
     * @param {slug}
     * @author Rahul Mehta
     */
    public static function getContentBySlug($slug) {
        $arrData = App\Model\DynamicContent::getContentBySlug($slug);

        return (!empty($arrData) ? $arrData : '');
    }

    /**
     * Get Date Format
     *
     * @param type   $date
     *
     * @param string $format
     *
     * @return string
     */
    public static function getDateByFormat($date = null, $fromFormat = 'Y-m-d H:i:s', $format = 'd M Y') {

        try {
            if (empty($date) || $date == "0000-00-00") {
                $date = '';
            } else {

                $date = Carbon::createFromFormat($fromFormat, $date)->format($format);
            }

            return $date;
        } catch (InvalidArgumentException $x) {
            echo $x->getMessage();
        }
    }

    public static function getToken() {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    /**
     * Id_encode
     *
     * This function to encode ID by a custom number
     * @param string
     *
     */
    public static function ID_encode($id) {
        $encode_id = '';
        if ($id) {
            $encode_id = rand(1, 9) . (($id + 19)) . rand(1, 9);
        } else {
            $encode_id = '';
        }
        return $encode_id;
    }

    /* End of function */

    /**
     * Id_decode
     *
     * This function to decode ID by a custom number
     * @param string
     *
     */
    public static function ID_decode($encoded_id) {
        $id = '';
        if ($encoded_id) {
            $id = substr($encoded_id, 1, strlen($encoded_id) - 2);
            $id = $id - 19;
        } else {
            $id = '';
        }
        return $id;
    }

    /**
     * @Function codeOTP
     * @purpose generate otp code
     * @created  Dec 2018
     * @author Sandeep Kumar
     */
    public static function codeOTP($length = 5, $chars = 'abcdefghijklmnopqrstuvwxyz1234567890') {
        $chars_length = (strlen($chars) - 1);
        $string = $chars{rand(0, $chars_length)};
        for ($i = 1; $i < $length; $i = strlen($string)) {
            $r = $chars{rand(0, $chars_length)};
            if ($r != $string{$i - 1})
                $string .= $r;
        }
        return $string;
    }

   

      public static function curlHitUrl($Url) {
        // is cURL installed yet?
        // is cURL installed yet?
        if (!function_exists('curl_init')) {
            die('Sorry cURL is not installed!');
        }

        // OK cool - then let's create a new cURL resource handle
        $ch = curl_init();

        // OK cool - then let's create a new cURL resource handle
        // Now set some options (most are optional)
        // Set URL to download
        curl_setopt($ch, CURLOPT_URL, $Url);
        // Set a referer
        //curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm");
        // User agent
        // Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        // Download the given URL, and return output
        ///////////Checking error for sending message/////////
        /* if (curl_exec($ch) === FALSE) {
          die("Curl Failed: " . curl_error($ch));
          } else {
          return curl_exec($ch);
      } */
      $output = curl_exec($ch);

        // Close the cURL resource, and free system resources
      curl_close($ch);

      return $output;
  }

    /**
     * @Function getBreadCrumb
     * @purpose get breadcrumb initial
     * @created  Dec 2018
     * @author Sandeep Kumar
     */
    public static function getBreadCrumb() {
        $viewData['faClass'] = 'fa-table';
        $breadCrumData[0]['text'] = 'Dashboard';
        $breadCrumData[0]['url'] = url('admin/dashboard');
        $breadCrumData[0]['breadFaClass'] = 'fa-dashboard';
        $viewData['breadCrumData'] = $breadCrumData;
        return $viewData;
    }

    /**
     * @Function premiseValidationData
     * @purpose get validation initial
     * @created  Jan 2019
     * @author Sandeep Kumar
     */
    public static function premiseValidationData($request, $premiseData) {
        try {

            if (\Auth::user()->roles->first()->name == \Config::get('constants.PLATFORM_ADMIN')) {



                if ($request->method() == 'POST') {

                    $premiseValidation = \Validator::make($premiseData, [
                        'organization_id' => 'required',
                        'premise_text_id' => 'required|unique:premises,name,NULL,id,deleted_at,NULL,organization_id,' . $request->post()['organization_id'],
                        'minor_id' => 'required|unique:becons,minor_id, NULL,id,deleted_at,NULL,organization_id,' . $request->post()['organization_id'],
                    ], ['minor_id' => \Config::get('flash_msg.BeaconMinorId')]);
                } else if ($request->method() == 'PUT') {

                    $premiseValidation = \Validator::make($premiseData, [
                        'organization_id' => 'required',
                        'premise_text_id' => 'required|unique:premises,name,NULL,id,deleted_at,NULL,organization_id,' . $request->post()['organization_id'],
                        'minor_id' => 'required|unique:becons,minor_id,' . $request->post()['becon_id'] . ',id,deleted_at,NULL,organization_id,' . $request->post()['organization_id'],
                    ], ['minor_id.required' => \Config::get('flash_msg.BeaconMinorId')]);
                }
            } else {

                unset($premiseData['organization_id']);

                if ($request->method() == 'POST') {

                    $premiseValidation = \Validator::make($premiseData, [
                        'premise_text_id' => 'required|unique:premises,name,NULL,id,deleted_at,NULL,organization_id,' . \Auth::user()->id,
                        'minor_id' => 'required|unique:becons,minor_id, NULL,id,deleted_at,NULL,organization_id,' . \Auth::user()->id,
                    ], ['minor_id' => \Config::get('flash_msg.BeaconMinorId')]);
                } else if ($request->method() == 'PUT') {

                    $premiseValidation = \Validator::make($premiseData, [
                        'premise_text_id' => 'required|unique:premises,name,NULL,id,deleted_at,NULL,organization_id,' . \Auth::user()->id,
                        'minor_id' => 'required|unique:becons,minor_id,' . $request->post()['becon_id'] . ',id,deleted_at,NULL,organization_id,' . \Auth::user()->id,
                    ], ['minor_id.required' => \Config::get('flash_msg.BeaconMinorId')]);
                }
            }

            return $premiseValidation;
        } catch (InvalidArgumentException $x) {
            echo $x->getMessage();
        }
    }

    /**
     * @Function locationValidationData
     * @purpose get validation initial
     * @created  Jan 2019
     * @author Sandeep Kumar
     */
    public static function locationValidationData($request, $locationData) {
        try {

            if (\Auth::user()->roles->first()->name == \Config::get('constants.PLATFORM_ADMIN')) {

                if ($request->method() == 'POST') {

                    $locationValidation = \Validator::make($locationData, [
                        'organization_id' => 'required',
                        'premise_id' => 'required',
                        'location_text_id' => 'required|unique:locations,name,NULL,id,premise_id,' . $locationData['premise_id'],
                        'minor_id' => 'required|unique:becons,minor_id, NULL,id,deleted_at,NULL,organization_id,' . $request->post()['organization_id'],
                    ], ['minor_id.required' => \Config::get('flash_msg.BeaconMinorId')]);
                } else if ($request->method() == 'PUT') {

                    $locationValidation = \Validator::make($locationData, [
                        'organization_id' => 'required',
                        'premise_id' => 'required',
                        'location_text_id' => 'required|unique:locations,name,NULL,id,premise_id,' . $locationData['premise_id'],
                        /* 'location_text_id' => 'required|unique:locations,name, '.$request->get('location_edit_id').',id,deleted_at,NULL,premise_id,' . $request->get('premise_edit_id') . ',organization_id,' . $request->get('organization_id'), */
                        'minor_id' => 'required|unique:becons,minor_id, ' . $request->post()['becon_id'] . ',id,deleted_at,NULL,organization_id,' . $request->post()['organization_id'],
                    ], ['minor_id' => \Config::get('flash_msg.BeaconMinorId')]);
                }
            } else {

                unset($locationData['organization_id']);

                if ($request->method() == 'POST') {

                    $locationValidation = \Validator::make($locationData, [
                        'premise_id' => 'required',
                        'location_text_id' => 'required|unique:locations,name,NULL,id,premise_id,' . $locationData['premise_id'],
                        'minor_id' => 'required|unique:becons,minor_id, NULL,id,deleted_at,NULL,organization_id,' . \Auth::user()->id,
                    ], ['minor_id.required' => \Config::get('flash_msg.BeaconMinorId')]);
                } else if ($request->method() == 'PUT') {

                    $locationValidation = \Validator::make($locationData, [
                        'premise_id' => 'required',
                        'location_text_id' => 'required|unique:locations,name,NULL,id,premise_id,' . $locationData['premise_id'],
                        /* 'location_text_id' => 'required|unique:locations,name, '.$request->get('location_edit_id').',id,deleted_at,NULL,premise_id,' . $request->get('premise_edit_id') . ',organization_id,' . $request->get('organization_id'), */
                        'minor_id' => 'required|unique:becons,minor_id, ' . $request->post()['becon_id'] . ',id,deleted_at,NULL,organization_id,' . \Auth::user()->id,
                    ], ['minor_id' => \Config::get('flash_msg.BeaconMinorId')]);
                }
            }

            return $locationValidation;
        } catch (InvalidArgumentException $x) {
            echo $x->getMessage();
        }
    }

    /**
     * @Function BeaconValidationData
     * @purpose get validation initial
     * @created  Jan 2019
     * @author Sandeep Kumar
     */
    public static function BeaconValidationData($request) {
        try {

            if (\Auth::user()->roles->first()->name == \Config::get('constants.PLATFORM_ADMIN')) {

                if ($request->method() == 'POST') {

                    $beaconValidation = \Validator::make($request->all(), [
                        'organization_id' => 'required',
                        'premise_id' => 'required',
                        'location_id' => 'required',
                        'minor_id' => 'required|numeric|unique:becons,minor_id,NULL,id,deleted_at,NULL,organization_id,' . $request->get('organization_id'),
                        'status' => 'required',
                    ]);
                } else if ($request->method() == 'PUT') {

                    $beaconValidation = \Validator::make($request->all(), [
                        'organization_id' => 'required',
                        'premise_id' => 'required',
                        'location_id' => 'required',
                        'minor_id' => 'required|numeric|unique:becons,minor_id, ' . $request->get('becon_id') . ',id,deleted_at,NULL,organization_id,' . $request->get('organization_id'),
                        'status' => 'required',
                    ]);
                }
            } else {

                $org_id = !empty($request->get('organization_id')) ? $request->get('organization_id') : \Auth::user()->id;


                if ($request->method() == 'POST') {

                    $beaconValidation = \Validator::make($request->all(), [
                        'premise_id' => 'required',
                        'location_id' => 'required',
                        'minor_id' => 'required|numeric|unique:becons,minor_id,NULL,id,deleted_at,NULL,organization_id,' . $org_id,
                        'status' => 'required',
                    ]);
                } else if ($request->method() == 'PUT') {

                    $beaconValidation = \Validator::make($request->all(), [
                        'premise_id' => 'required',
                        'location_id' => 'required',
                        'minor_id' => 'required|numeric|unique:becons,minor_id, ' . $request->get('becon_id') . ',id,deleted_at,NULL,organization_id,' . $org_id,
                        'status' => 'required',
                    ]);
                }
            }

            return $beaconValidation;
        } catch (InvalidArgumentException $x) {
            echo $x->getMessage();
        }
    }

    public static function getAllSettingsByOption($option_name) {
        $settingData = App\Model\Admin\SiteSetting\SiteSetting::where('option_name', $option_name)->first();
        return (object) $settingData;
    }

    /**
     * getDefaultCardId
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getDefaultCardId($id, $custId) {
        try {


            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            try {


                $customer = \Stripe\Customer::retrieve($custId);

                if (!empty($customer) && is_object($customer)) {

                    $defaultCard = $customer->default_source;
                    return $defaultCard;
                }
            } catch (\Stripe\Error\Base $e) {

                return response()->json(['errors' => [$e->getMessage()]], 422);
                //dd('Stripe Plan not found! error: '.$e->getMessage());
            }
        } catch (Exception $ex) {

            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    public static function getRoleName($userId = false) {
        $role = \DB::table('organizations')
        ->select('organizations.id', 'organizations.role_id', 'roles.id', 'roles.name')
        ->join('roles', 'roles.id', '=', 'organizations.role_id')
        ->where(['organizations.id' => $userId])
        ->first();

        return  (!empty($role) && is_object($role) ? $role : false);
    }

    public static function createPassPattern($n) {

      $s= '';
      for ($i = 1; $i <= $n; $i++) {

        if($i<=3){
            $s .= '*';
        }
        if($i==4 || $i==5 || $i==6){
            $s .= $i;
        }

    }
    return $s;

}

}
