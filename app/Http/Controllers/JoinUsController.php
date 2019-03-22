<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\State;
use App\Model\Country;
use App\Model\City;
use App\Http\Requests\JoinUsRequest;
use App\Helpers\Helper;
use App\User;
use App\Admin;
use App\Model\Admin\PocDetail;
use App\Model\OrganizationSubscription;
use App\Model\Admin\Subscription;
use App\Role;
use App\Model\PaymentLog;
use App\Model\Payment;
use Event;

class JoinUsController extends Controller {

    use \App\Traits\OrganizationInformation;
    use \App\Traits\PaymentInformation;
    use \App\Traits\PaymentLogInformation;
    use \App\Traits\OrganizationSubscriptionInformation;

    protected $state;
    protected $sitesettings;

    
    /**
     * @author Sandeep Kumar
     * @function: __construct
     * @desc: check user is valid or not and store the roles in allow roles property.
     */
    public function __construct() {
        //$this->middleware('auth');
        $this->state = new State();
        $this->country = new Country();
        $this->city = new City();
        $this->helper = new Helper();
    }

    /**
     * @author Sandeep Kumar
     * @function: index
     * @param $org_id
     * @desc: Display a registration form.
     */
    public function index($org_id = false) {
        $data['title'] = 'Register';
        $data['site_setting'] = $this->helper->getAllSettings();
        $data['arrCountry'] = $this->country->getAllCountry();
        $data['arrState'] = $this->state->getAllState();
        $data['orga_id'] = $org_id;

        if (!empty($org_id)) {

            $org_id = !empty($org_id) ? \Crypt::decryptString($org_id) : '';
            $orgDetail = Admin::getRecordByID($org_id);
            $data['city_id'] = $orgDetail->city_id;
            $data['arrCityData'] = $this->city->getAllCityByStateID($orgDetail->state_id);
            $data['objData'] = $orgDetail;
        }

        return view('front.register')->with($data);
    }

    /**
     * @author Sandeep Kumar
     * @function: saveInfo
     * @param Request $request
     * @desc: process the registartion information.
     */
    public function saveInfo(Request $request) {

        try {

            if (!empty($request->organization_id)) {

                $organization_id = \Crypt::decryptString($request->organization_id);

                $validation = \Validator::make($request->all(), [
                            'name' => 'required|unique:organizations,name,' . $organization_id . ',id,deleted_at,NULL',
                            'address' => 'required',
                            'email' => 'required|unique:organizations,email,' . $organization_id . ',id,deleted_at,NULL',
                            'address' => 'required',
                            'zip_code' => 'required',
                            'country_id' => 'required',
                            'state_id' => 'required',
                            'city_id' => 'required',
                            'phone' => 'required',
                            'emergency_contact' => 'required',
                            'password' => 'required|min:6',
                            'password_confirmation' => 'required|same:password',
                            'terms_conditions' => 'required',
                ]);
            } else {

                $validation = \Validator::make($request->all(), [
                            'name' => 'required|unique:organizations,name,NULL,id,deleted_at,NULL',
                            'address' => 'required',
                            'email' => 'required|unique:organizations,email,NULL,id,deleted_at,NULL',
                            'address' => 'required',
                            'zip_code' => 'required',
                            'country_id' => 'required',
                            'state_id' => 'required',
                            'city_id' => 'required',
                            'phone' => 'required',
                            'emergency_contact' => 'required',
                            'password' => 'required|min:6',
                            'password_confirmation' => 'required|same:password',
                            'terms_conditions' => 'required',
                ]);
            }

            if ($validation->fails()) {

                $site_setting = $this->helper->getAllSettings();
                $data['arrCountry'] = $this->country->getAllCountry();
                $data['arrState'] = $this->state->getAllState();
                $data['arrCityData'] = $this->city->getAllCityByStateID($request->get('state_id'));
                $data['city_id'] = $request->get('city_id');
                return view('front.register')->with($data)->withErrors($validation);
            }

            if (!empty($request->organization_id)) {

                $organization_id = \Crypt::decryptString($request->organization_id);

                $requestOrgTrait = $this->createOrganizationInformationRequest($request);
                $user_id = Admin::storeOrUpdateData($requestOrgTrait, $organization_id);

                if (is_array($request->poc_name) && !empty($request->poc_name)) {

                    foreach ($request->poc_name as $key => $val) {


                        if (!empty($request->poc_id[$key]) && isset($request->poc_id[$key])) {


                            $poc_detail = PocDetail::find($request->poc_id[$key]);
                            $poc_detail->organization_id = $user_id->id;
                            $poc_detail->poc_name = $request->poc_name[$key];
                            $poc_detail->poc_contact_no = $request->poc_contact_no[$key];
                            $poc_detail->poc_email = $request->poc_email[$key];


                            if ($poc_detail->save()) {
                                $flg = '1';
                            }
                        } else {

                            $poc_detail = new PocDetail;
                            $poc_detail->organization_id = $user_id->id;
                            $poc_detail->poc_name = $request->poc_name[$key];
                            $poc_detail->poc_contact_no = $request->poc_contact_no[$key];
                            $poc_detail->poc_email = $request->poc_email[$key];

                            if ($poc_detail->save()) {

                                $flg = '1';
                            }
                        }
                    }
                }


                return redirect(route('plan', ['organization_id' => \Crypt::encryptString($organization_id)]));
            }



            $id = \DB::table('becon_majors')->insertGetId(
                    ['id' => NULL]
            );

            if (isset($id) && !empty($id)) {

                $role_org_admin = Role::where('name', \Config::get('constants.ORGANIZATION_ADMIN'))->first();

                $requestVar = array_merge($request->all(), ['becon_major_id' => $id, 'role_id'=>$role_org_admin->id]);
            }

            $role_org_admin = Role::where('name', \Config::get('constants.ORGANIZATION_ADMIN'))->first();


            $requestOrgTrait = $this->createOrganizationInformationRequest($requestVar);

            $user_id = Admin::storeOrUpdateData($requestOrgTrait);



            $user_id->roles()->attach($role_org_admin);

            if (isset($user_id) && !empty($user_id)) {

                /////////////Update Record for POC Details /////////////////

                if (is_array($request->poc_name) && !empty($request->poc_name)) {

                    foreach ($request->poc_name as $key => $val) {


                        if (!empty($request->poc_id[$key]) && isset($request->poc_id[$key])) {


                            $poc_detail = PocDetail::find($request->poc_id[$key]);
                            $poc_detail->organization_id = $user_id->id;
                            $poc_detail->poc_name = $request->poc_name[$key];
                            $poc_detail->poc_contact_no = $request->poc_contact_no[$key];
                            $poc_detail->poc_email = $request->poc_email[$key];


                            if ($poc_detail->save()) {
                                $flg = '1';
                            }
                        } else {

                            $poc_detail = new PocDetail;
                            $poc_detail->organization_id = $user_id->id;
                            $poc_detail->poc_name = $request->poc_name[$key];
                            $poc_detail->poc_contact_no = $request->poc_contact_no[$key];
                            $poc_detail->poc_email = $request->poc_email[$key];

                            if ($poc_detail->save()) {

                                $flg = '1';
                            }
                        }
                    }
                }

                if (isset($flg) && !empty($flg) && $flg == '1') {

                    $request->session()->flash('alert-success', \Config::get('flash_msg.OrganizationAdded'));
                }
            } else {
                $request->session()->flash('alert-danger', \Config::get('flash_msg.OrganizationNotAdded'));
            }

            return redirect(route('plan', ['organization_id' => \Crypt::encryptString($user_id->id)]));
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: selectPlan
     * @param $organization_id
     * @desc: choose plan.
     */
    public function selectPlan($organization_id) {

        $data['title'] = 'Select Plan';
        $data['organization_id'] = $organization_id;
        $site_setting = $this->helper->getAllSettings();
        $data['allPlanArrData'] = Subscription::getAllRecord(['type' => 'Active']);
        return view('front.plans')->with($data);
    }

    /**
     * @author Sandeep Kumar
     * @function: selectPlan
     * @param Request $request
     * @desc: provide payment detail.
     */
    public function payment(Request $request) {
        try {


            $data['title'] = 'Payment Details';
            $data['organization_id'] = $request->organization_id;
            $data['plan_id'] = $request->plan_id;
            $data['site_setting'] = $this->helper->getAllSettings();
            //$data['stripe_pk'] = config('services.stripe.key');

            $data['stripe_pk'] = !empty($this->helper->getAllSettings()->stripe_pk) ? $this->helper->getAllSettings()->stripe_pk : config('services.stripe.key');

            return view('front.payment')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: selectPlan
     * @param Request $request
     * @desc: provide payment detail.
     */
    public function paymentProcess(Request $request) {
        try {

            $data['title'] = 'Payment Details';


            $plan_id = \Crypt::decryptString($request->plan_id);
            $organization_id = \Crypt::decryptString($request->organization_id);

            $orgDetail = Admin::Where(['id' => $organization_id])->first();

            //\Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            \Stripe\Stripe::setApiKey(!empty($this->helper->getAllSettings()->stripe_sk) ? $this->helper->getAllSettings()->stripe_sk : config('services.stripe.secret'));

            try {

                $customer = \Stripe\Customer::create([
                            'email' => !empty($orgDetail) && is_object($orgDetail) && !empty($orgDetail->email) ? $orgDetail->email : '',
                            'source' => $request->token,
                ]);

                $subscription = \Stripe\Subscription::create([
                            'customer' => $customer->id,
                            'items' => [['plan' => $plan_id]]
                ]);

                //////////response insert in payment Log table///////
                $arr['organization_id'] = $organization_id;
                $arr['subscription_id'] = $plan_id;
                $requestLogPaymentTrait = $this->createPaymentLogInformationRequest($subscription, $arr);
                PaymentLog::storeOrUpdateData($requestLogPaymentTrait);

                if ($subscription->status == 'active') {

                    $sub_record = Subscription::getRecordByID($plan_id);

                    //////////record insert in payment table///////

                    $finger_prints = \Stripe\Token::retrieve($request->token);

                    $arrData['fingerprints'] = $finger_prints->card->fingerprint;
                    $arrData['organization_id'] = $organization_id;

                    $requestPaymentTrait = $this->createPaymentInformationRequest($subscription, $arrData);
                    $payment = Payment::storeOrUpdateData($requestPaymentTrait);

                    if ($sub_record->type == 'monthly') {

                        $expiry_date = date('Y-m-d H:i:s', strtotime('+' . $sub_record->duration . ' months'));
                    } else if ($sub_record->type == 'yearly') {

                        $expiry_date = date('Y-m-d H:i:s', strtotime('+' . $sub_record->duration . ' years'));
                    }

                    $request->request->add(['subscription_id' => $plan_id]);
                    $request->request->add(['payment_id' => $payment->id]);
                    $request->request->add(['expiry_date' => $expiry_date]);
                    $request->request->add(['from_date' => date('Y-m-d h:i:s')]);
                    $request->request->add(['organization_id' => $organization_id]);

                    //////////record insert in ogranization subscription table///////
                    $requestOrgSubsTrait = $this->createOrganizationSubscriptionInformationRequest($request);
                    $orgSubs = OrganizationSubscription::storeOrUpdateData($requestOrgSubsTrait);

                    $eventData['to'] = $orgDetail->email;
                    $eventData['variable']['{name}'] = !empty($orgDetail->pocName->poc_name) ? $orgDetail->pocName->poc_name : $orgDetail->email;
                    $eventData['variable']['{link}'] = link_to_route('activate_user', 'Activate your account!', ['token' => $orgDetail->token, 'email' => $orgDetail->email]);

                    $pw = \Crypt::decryptString($orgDetail->temp_password);
                    
                    $eventData['variable']['{username}'] = $orgDetail->email;
                    $eventData['variable']['{password}'] = Helper::createPassPattern(strlen($pw));

                    $eventData['template_code'] = 'SB006';

                    Event::fire('sendEmailByTemplate', collect($eventData));

                    if (!empty($orgSubs) && is_object($orgSubs)) {

                        Admin::storeOrUpdateData(['temp_password' => ''], $organization_id);

                        return response()->json(['success' => true, 'msg' => 'Successfully subscribed', 'plan_id' => \Crypt::encryptString($plan_id)], 200);
                    }
                }
            } catch (\Stripe\Error\Base $e) {

                return response()->json(['errors' => [$e->getMessage()]], 422);
                //dd('Stripe Plan not found! error: '.$e->getMessage());
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }


    /**
     * @author Sandeep Kumar
     * @function: thankYou
     * @param $planId
     * @desc: after done payment redirect to thankyou page.
     */
    public function thankYou($planId) {

        try {

            $data['title'] = 'Thank You';
            $data['site_setting'] = $this->helper->getAllSettings();
            $plan_id = \Crypt::decryptString($planId);
            $data['sub_record'] = Subscription::getRecordByID($plan_id);

            return view('front.thankyou')->with($data);
        } catch (\Stripe\Error\Base $e) {

            return response()->json(['errors' => [$e->getMessage()]], 422);
            //dd('Stripe Plan not found! error: '.$e->getMessage());
        }
    }


    /**
     * @author Sandeep Kumar
     * @function: activateUser
     * @param Request $request
     * @desc: account activation verification.
     */
    public function activateUser(Request $request) {

        $whr['token'] = $request->get('token');
        $whr['email'] = $email = $request->get('email');
        $whr['status'] = '0';
        $data['site_setting'] = $this->helper->getAllSettings();

        $res = User::getUserData($email, 'email');
        if (@$res->status) {
            return view('activation.already_registered')->with(compact('site_setting'));
        }

        $data = User::activateUser($whr);

        if (!empty($data)) {
            return view('activation.thankyou')->with($data);
        } else {
            return view('errors.404')->with($data);
        }
    }

}
