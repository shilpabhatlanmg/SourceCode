<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\State\State;
use App\Model\Admin\NewsLetter;
use Response;
use App\Helpers\Helper;
use App\Model\City;
use App\User;
use Auth;
use Event;
use Session;
use DateTime;
use App\Model\Admin\PocDetail;

class AjaxController extends Controller {

    protected $city;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        try {
            $this->city = new City();
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * Get City By State ID
     * 
     * @param void
     * @return mixed Json | 0 (in case of failure)
     * @since 0.1
     */
    public function getCity(Request $request) {
        if (request()->session()->get('state_id'))
            request()->session()->forget('state_id');
        $varStateID = (int) $request->query('varStateID');
        $result = $this->city->getAllCityByStateID($varStateID);
        return ($result ? Response::json($result) : (int) $result);
    }

    /**
     * saveEmailSubscription
     * @param
     * @return json
     * @author Sandeep Kumar
     */
    public function saveEmailSubscription(Request $request) {
        $requestVar = $request->all();
        $input['email'] = $email = $requestVar['subscribe_email'];
        $input['token'] = Helper::getToken();
        $data = [];
        try {

            $data = NewsLetter::storeEmailSubscription($input);

            $rt['success'] = 'true';
            $rt['result'] = $data;

            $eventData['to'] = $data->email;
            $eventData['variable']['{email}'] = $data->email;
            $eventData['variable']['{link}'] = link_to_route('activate.email.subscription', 'Activate My Subscription Now!', ['token' => $data->token, 'email' => $data->email]);
            $eventData['template_code'] = 'SB001';

            // Send Email to User
            Event::fire('sendEmailByTemplate', collect($eventData));

            $rt['html'] = view('newsletter.success_popup')->render();
        } catch (\Exception $e) {
            $rt['success'] = 'false';
            $rt['errors'] = $e->getMessage();
            $rt['code'] = $e->getCode();
        }
        return json_encode($rt);
    }

    /**
     * @author Sandeep Kumar
     * @function: checkEmailSubscription
     * @param Request $request
     * @desc: check email subscription.
     */
    public function checkEmailSubscription(Request $request) {
        $requestVar = $request->all();
        $email = $requestVar['email'];
        try {
            $data = NewsLetter::findEmailSubscription($email);
            $rt['success'] = 'true';
            $rt['result'] = $data;
        } catch (\Exception $e) {
            $rt['success'] = 'false';
            $rt['errors'] = $e->getMessage();
            $rt['code'] = $e->getCode();
        }
        return json_encode($rt);
    }

    /**
     * @author Sandeep Kumar
     * @function: removePocDetails
     * @param Request $request
     * @desc: remove poc detail.
     */
    public function removePocDetails(Request $request) {
        $requestVar = $request->all();
        $id = $requestVar['varID'];

        try {
            if ($data = PocDetail::deleteRecord($id)) {
                $rt['success'] = 'true';
                $rt['result'] = $data;
            } else {
                $rt['success'] = 'false';
                $rt['result'] = '';
            }
        } catch (\Exception $e) {
            $rt['success'] = 'false';
            $rt['errors'] = $e->getMessage();
            $rt['code'] = $e->getCode();
        }
        return json_encode($rt);
    }

    /**
     * @author Sandeep Kumar
     * @function: otpGenerate
     * @param Request $request
     * @desc: generate otp.
     */
    public function otpGenerate(Request $request) {

        try {

            $id = $request->get('order_id');
            $otp_generate = Helper::codeOTP();

            $arr_order['otp'] = $otp_generate;
            $otp_update = Order::storeData($arr_order, $id);

            if (!empty($otp_update) && count($otp_update) > 0) {

                Helper::sendOtpMobile($otp_generate, $otp_update->userDetail->phone);
            }
            if (!empty($otp_update) && count($otp_update) > 0) {
                $rt['success'] = 'true';
                $rt['result'] = $otp_update;
            } else {
                $rt['success'] = 'false';
                $rt['result'] = 'no bringer available';
            }

            //dd(\DB::getQueryLog());
        } catch (\Exception $e) {
            $rt['success'] = 'false';
            $rt['errors'] = $e->getMessage();
            $rt['code'] = $e->getCode();
        }
        return json_encode($rt);
    }

    /**
     * @author Sandeep Kumar
     * @function: otpConfirm
     * @param Request $request
     * @desc: otp confirmation
     */
    public function otpConfirm(Request $request) {

        try {

            $id = $request->get('order_id');
            $input_otp = $request->get('otp_code');

            $arr_where['id'] = $id;
            $arr_where['type'] = 'id';

            $order_detail = Order::getOrder($arr_where);
            $arr_delivery['order_status'] = '3';
            $arr_delivery['drop_date'] = new DateTime;

            if (isset($input_otp) && !empty($input_otp)) {

                if ($order_detail->otp == $input_otp) {

                    $arr_pending = Order::storeData($arr_delivery, $id);
                    $arr_user['is_assign'] = '0';
                    $arr_user['last_login'] = new DateTime;
                    User::storeData($arr_user, $arr_pending->bringer_id);

                    if ($arr_pending->order_status == '3') {

                        $sts = 'Order Deliverd Successfully';

                        //////////mail trigger for both pickup and drop user//////////
                        $eventData['to'] = $arr_pending->pickup_email;
                        $eventData['variable']['{name}'] = $arr_pending->pickup_name;
                        $eventData['variable']['{email}'] = $arr_pending->pickup_email;
                        $eventData['variable']['{status}'] = $sts;
                        $eventData['template_code'] = 'SB005';
                        //$eventData['variable']['{link}'] = link_to_route('login', 'Check Status!');
                        // Send Email to User
                        Event::fire('sendEmailByTemplate', collect($eventData));

                        $eventData['to'] = $arr_pending->drop_email;
                        $eventData['variable']['{name}'] = $arr_pending->drop_name;
                        $eventData['variable']['{email}'] = $arr_pending->drop_email;
                        $eventData['variable']['{status}'] = $sts;
                        $eventData['template_code'] = 'SB005';
                        //$eventData['variable']['{link}'] = link_to_route('login', 'Check Status!');
                        // Send Email to User
                        Event::fire('sendEmailByTemplate', collect($eventData));
                    }

                    $rt['success'] = 'true';
                    $rt['result'] = $order_detail;
                    $rt['msg'] = 'order deliverd successfully..';
                } else {
                    $rt['success'] = 'false';
                    $rt['errors'] = 'Incorrect OTP please try again';
                }
            } else {
                $rt['success'] = 'false';
                $rt['errors'] = 'Please enter otp code';
            }


            //dd(\DB::getQueryLog());
        } catch (\Exception $e) {
            $rt['success'] = 'false';
            $rt['errors'] = $e->getMessage();
            $rt['code'] = $e->getCode();
        }
        return json_encode($rt);
    }

}
