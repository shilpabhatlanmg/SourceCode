<?php

namespace App\Http\Controllers\Admin\Subscription;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Admin\Subscription;
use App\Http\Requests\Admin\SubscriptionRequest;
use App\Model\OrganizationSubscription;
use App\Model\PaymentLog;
use App\Model\StripeCustomer;
use App\Model\Payment;
use App\Helpers\Helper;
use App\Admin;

class SubscriptionController extends Controller {

    protected $allow_roles = [];

    use \App\Traits\PaymentInformation;
    use \App\Traits\PaymentLogInformation;
    use \App\Traits\OrganizationSubscriptionInformation;

    /**
     * @author Sandeep Kumar
     * @function: __construct
     * @desc: check user is valid or not and store the roles in allow roles property.
     */
    public function __construct() {
        $this->middleware('auth:admin');
        $this->allow_roles = array(\Config::get('constants.PLATFORM_ADMIN'), \Config::get('constants.SUB_ADMIN'));
        $this->helper = new Helper();
    }

    /**
     * @author Sandeep Kumar
     * @function: index
     * @desc: Display a listing of subscription plan.
     */
    public function index() {
        try {

            request()->user()->authorizeRoles($this->allow_roles);

            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Subscription List';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['title'] = 'Subscription List';

            $data['arrData'] = Subscription::getAllRecord();
            $data['site_setting'] = $this->helper->getAllSettings();

            return view('admin.subscription.subscription-list')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: create
     * @desc: Create subscription.
     */
    public function create() {
        try {

            request()->user()->authorizeRoles($this->allow_roles);

            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Subscription List';
            $data['breadCrumData'][1]['url'] = url('admin/subscription');
            $data['breadCrumData'][1]['breadFaClass'] = '';

            $data['breadCrumData'][2]['text'] = 'Create Subscription';
            $data['breadCrumData'][2]['breadFaClass'] = '';
            $data['title'] = 'Create Subscription';
            $data['site_setting'] = $this->helper->getAllSettings();

            return view('admin.subscription.create')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: store
     * @param SubscriptionRequest $request
     * @desc: Save subscription plan.
     */
    public function store(SubscriptionRequest $request) {
        try {

            if($request->get("type") == 'yearly' && $request->get("duration") > 1){

                return redirect()->back()->withErrors($request->session()->flash('alert-danger', \Config::get('flash_msg.SubscriptionYear')))->withInput();

            }else if($request->get("type") == 'monthly' && $request->get("duration") > 12){

                return redirect()->back()->withErrors($request->session()->flash('alert-danger', \Config::get('flash_msg.SubscriptionMonth')))->withInput();

            }

            request()->user()->authorizeRoles($this->allow_roles);
            $arrData = [];
            $arrData["plan_name"] = $request->get("plan_name");
            $arrData["people_allow"] = $request->get("people_allow");
            $arrData["premises_allow"] = $request->get("premises_allow");
            $arrData["duration"] = $request->get("duration");
            $arrData["type"] = $request->get("type");
            $arrData["price"] = $request->get("price");
            $arrData["status"] = $request->get("status");

            $saveId = Subscription::storeOrUpdateData($arrData);

            if (!empty($saveId) && is_object($saveId)) {

                if ($saveId->type == 'monthly') {
                    $interval = 'month';
                    $interCount = $saveId->duration;
                } else if ($saveId->type == 'yearly') {
                    $interval = 'year';
                    $interCount = '1';
                }

                //\Stripe\Stripe::setApiKey(config('services.stripe.secret'));

                \Stripe\Stripe::setApiKey(!empty($this->helper->getAllSettings()->stripe_sk) ? $this->helper->getAllSettings()->stripe_sk : config('services.stripe.secret'));

                try {

                    $plan = \Stripe\Plan::create(array(
                        "amount" => ($saveId->price) * 100,
                        "interval" => $interval,
                        "interval_count" => $interCount,
                        "product" => array(
                            "name" => $saveId->plan_name
                        ),
                        "currency" => "usd",
                        "id" => $saveId->id
                    ));
                } catch (\Stripe\Error\Base $e) {

                    $request->session()->flash('alert-danger', $e->getMessage());
                    return redirect(route('subscription.index'));
                }

                $request->session()->flash('alert-success', \Config::get('flash_msg.SubscriptionAdded'));
                return redirect(route('subscription.index'));
            } else {

                return \Redirect::back()->withInput()->with('alert-danger', \Config::get('flash_msg.SubscriptionNotAdded'));
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * @author Sandeep Kumar
     * @function: edit
     * @param $id
     * @desc: edit subscription plan.
     */
    public function edit($id) {
        try {

            $id = \Crypt::decryptString($id);

            request()->user()->authorizeRoles($this->allow_roles);

            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Subscription List';
            $data['breadCrumData'][1]['url'] = url('admin/subscription');
            $data['breadCrumData'][1]['breadFaClass'] = '';

            $data['breadCrumData'][2]['text'] = 'Edit Subscription';
            $data['breadCrumData'][2]['breadFaClass'] = '';

            $data['title'] = 'Edit Subscription';

            $data['objData'] = Subscription::getRecordByID($id);
            $data['site_setting'] = $this->helper->getAllSettings();
            return view('admin.subscription.edit')
            ->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: update
     * @param SubscriptionRequest $request
     * @param $id
     * @desc: edit subscription plan.
     */
    public function update(SubscriptionRequest $request, $id) {

        try {

            if($request->get("type") == 'yearly' && $request->get("duration") > 1){

                return redirect()->back()->withErrors($request->session()->flash('alert-danger', \Config::get('flash_msg.SubscriptionYear')))->withInput();

            }else if($request->get("type") == 'monthly' && $request->get("duration") > 12){

                return redirect()->back()->withErrors($request->session()->flash('alert-danger', \Config::get('flash_msg.SubscriptionMonth')))->withInput();

            }

            request()->user()->authorizeRoles($this->allow_roles);
            $arrData = [];
            $arrData["plan_name"] = $request->get("plan_name");
            $arrData["people_allow"] = $request->get("people_allow");
            $arrData["premises_allow"] = $request->get("premises_allow");
            $arrData["duration"] = $request->get("duration");
            $arrData["type"] = $request->get("type");
            $arrData["price"] = $request->get("price");
            $arrData["status"] = $request->get("status");


            $count_subscription_active = Subscription::withCount('organizationSubscriptions')->where('id', $id)->first();

            if (!empty($count_subscription_active->organization_subscriptions_count) && isset($count_subscription_active->organization_subscriptions_count)) {

                $request->session()->flash('alert-danger', strtr(\Config::get('flash_msg.SubscriptionNotUpdated'), [
                    '{{COUNT}}' => $count_subscription_active->organization_subscriptions_count
                ]));
                return redirect()->back();
            }


            //\Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            \Stripe\Stripe::setApiKey(!empty($this->helper->getAllSettings()->stripe_sk) ? $this->helper->getAllSettings()->stripe_sk : config('services.stripe.secret'));


            try {

                if ($request->get("type") == 'monthly') {
                    $interval = 'month';
                    $interCount = $request->get("duration");
                } else if ($request->get("type") == 'yearly') {
                    $interval = 'year';
                    $interCount = '1';
                }

                $plan = \Stripe\Plan::retrieve($id);
                $prod = \Stripe\Product::retrieve($plan->product);
                $plan->delete();
                $prod->delete();

                $plan = \Stripe\Plan::create(array(
                    "amount" => ($request->get("price")) * 100,
                    "interval" => $interval,
                    "interval_count" => $interCount,
                    "product" => array(
                        "name" => $request->get("plan_name")
                    ),
                    "currency" => "usd",
                    "id" => $id
                ));


                $recordUpdate = Subscription::storeOrUpdateData($arrData, $id);
            } catch (\Stripe\Error\Base $e) {

                $recordUpdate = Subscription::storeOrUpdateData($arrData, $id);
            }

            if (!empty($recordUpdate) && is_object($recordUpdate)) {
                $request->session()->flash('alert-success', \Config::get('flash_msg.SubscriptionUpdated'));
                return redirect(route('subscription.index'));
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: destroy
     * @param Request $request
     * @param $id
     * @desc: delete subscription plan.
     */
    public function destroy(Request $request, $id) {
        try {

            $id = \Crypt::decryptString($id);


            request()->user()->authorizeRoles($this->allow_roles);

            $count_subscription_active = Subscription::withCount('organizationSubscriptions')->where('id', $id)->first();

            if (!empty($count_subscription_active->organization_subscriptions_count) && isset($count_subscription_active->organization_subscriptions_count)) {

                $request->session()->flash('alert-danger', strtr(\Config::get('flash_msg.SubscriptionNotDeleted'), [
                    '{{COUNT}}' => $count_subscription_active->organization_subscriptions_count
                ]));
                return redirect()->back();
            }

            //\Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            \Stripe\Stripe::setApiKey(!empty($this->helper->getAllSettings()->stripe_sk) ? $this->helper->getAllSettings()->stripe_sk : config('services.stripe.secret'));

            try {

                $plan = \Stripe\Plan::retrieve($id);
                $prod = \Stripe\Product::retrieve($plan->product);
                $plan->delete();
                $prod->delete();
                $varData = Subscription::deleteRecord($id);
            } catch (\Stripe\Error\Base $e) {

                $varData = Subscription::deleteRecord($id);
                //dd('Stripe Plan not found! error: '.$e->getMessage());
            }

            if ($varData) {

                $request->session()->flash('alert-success', \Config::get('flash_msg.SubscriptionDeleted'));
                return redirect()->back();
            } else {
                $request->session()->flash('alert-danger', \Config::get('flash_msg.SubscriptionNotDeleted'));
                return redirect()->back();
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: changeStatus
     * @param Request $request
     * @desc: change status active or inactive.
     */
    public function changeStatus(Request $request) {
        try {

            $request = $request->all();
            $requestUser['status'] = $request['sts'];

            $decryptId = \Crypt::decryptString($request['id']);

            $checkRecord = Subscription::find($decryptId);

            if (!empty($checkRecord) && is_object($checkRecord) && count($checkRecord) > 0) {

                $recordSts = Subscription::storeOrUpdateData($requestUser, $decryptId);

                if (!empty($recordSts) && is_object($recordSts)) {

                    return response()->json(['success' => true, 'msg' => \Config::get('flash_msg.StatusChanged'), 'sts' => $recordSts->status], 200);
                } else {
                    return response()->json(['errors' => [\Config::get('flash_msg.SomethingWentWrong')]], 422);
                }
            } else {
                return response()->json(['errors' => [\Config::get('flash_msg.SomethingWentWrong')]], 422);
            }
        } catch (Exception $ex) {
            return $e->getMessage();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: subscriptionPlan
     * @desc: current subscription plan display for organization.
     */
    public function subscriptionPlan() {
        try {

            $allow_roles = array(\Config::get('constants.ORGANIZATION_ADMIN'));
            request()->user()->authorizeRoles($allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Subscription Plan';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['title'] = 'Subscription Plan';
            $data['site_setting'] = $this->helper->getAllSettings();
            $data['currentPlanArrData'] = OrganizationSubscription::getPlanRecord('current');
            $data['futurePlanArrData'] = OrganizationSubscription::getPlanRecord('future');
            $data['oldPlanArrData'] = OrganizationSubscription::getPlanRecord('old');

            return view('admin.subscription.subscription-plan')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: changeSubscription
     * @desc: change subscription here display all plan.
     */
    public function changeSubscription() {
        try {

            //$id = \Crypt::decryptString($id);

            $allow_roles = array(\Config::get('constants.ORGANIZATION_ADMIN'));
            request()->user()->authorizeRoles($allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Subscription Plan';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['breadCrumData'][1]['url'] = url('admin/subscriptions/plans');

            $data['breadCrumData'][2]['text'] = 'Subscription Select';
            $data['breadCrumData'][2]['breadFaClass'] = '';
            $data['title'] = 'Subscription Select';
            //$data['organization_subscription_id'] = $id;

            $currentPlan = OrganizationSubscription::getCurrentPlan(\Auth::user()->id);

            $plnId = !empty($currentPlan) && is_object($currentPlan) && !empty($currentPlan->subscription_id) ? $currentPlan->subscription_id : '';

            $data['site_setting'] = $this->helper->getAllSettings();
            $data['allPlanArrData'] = Subscription::getAllRecord(['type' => 'Active', 'plan_id' => $plnId]);

            return view('admin.subscription.subscription-select')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: payment
     * @desc: payment detail for plan.
     */
    public function payment(Request $request) {
        try {

            //$plan_id = \Crypt::decryptString($request->plan_id);
            //$organization_subscription_id = \Crypt::decryptString($request->organization_subscription_id);
            $allow_roles = array(\Config::get('constants.ORGANIZATION_ADMIN'));
            request()->user()->authorizeRoles($allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Subscription Plan';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['breadCrumData'][1]['url'] = url('admin/subscriptions/plans');

            $data['breadCrumData'][2]['text'] = 'Payment Details';
            $data['breadCrumData'][2]['breadFaClass'] = '';
            $data['title'] = 'Payment Details';
            $data['heading'] = 'Payment Information';
            $data['plan_id'] = $request->plan_id;

            $data['site_setting'] = $this->helper->getAllSettings();
            $data['stripe_pk'] = config('services.stripe.key');


            return view('admin.subscription.payment')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: paymentProcess
     * @param Request $request
     * @desc: payment processing for plan.
     */
    public function paymentProcess(Request $request) {
        try {

            $allow_roles = array(\Config::get('constants.ORGANIZATION_ADMIN'));
            request()->user()->authorizeRoles($allow_roles);


            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Subscription Plan';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['breadCrumData'][1]['url'] = url('admin/subscriptions/plans');

            $data['breadCrumData'][2]['text'] = 'Payment Details';
            $data['breadCrumData'][2]['breadFaClass'] = '';
            $data['title'] = 'Payment Details';

            $plan_id = \Crypt::decryptString($request->plan_id);

            $currentPlan = OrganizationSubscription::getCurrentPlan(\Auth::user()->id);


            //\Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            \Stripe\Stripe::setApiKey(!empty($this->helper->getAllSettings()->stripe_sk) ? $this->helper->getAllSettings()->stripe_sk : config('services.stripe.secret'));

            try {

                ///////////////////////////payment deduction part//////////////////

                $customer = \Stripe\Customer::create([
                    'email' => \Auth::user()->email,
                    'source' => $request->token,
                ]);

                $subscription = \Stripe\Subscription::create([
                    'customer' => $customer->id,
                    'items' => [['plan' => $plan_id]]
                ]);

                //////////response insert in payment Log table///////
                $arr['organization_id'] = \Auth::user()->id;
                $arr['subscription_id'] = $plan_id;
                
                $requestLogPaymentTrait = $this->createPaymentLogInformationRequest($subscription, $arr);

                PaymentLog::storeOrUpdateData($requestLogPaymentTrait);

                if ($subscription->status == 'active') {

                    $sub_record = Subscription::getRecordByID($plan_id);

                    //////////record insert in payment table///////

                    $finger_prints = \Stripe\Token::retrieve($request->token);

                    $arrData['fingerprints'] = $finger_prints->card->fingerprint;
                    $arrData['organization_id'] = \Auth::user()->id;

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
                    $request->request->add(['organization_id' => \Auth::user()->id]);

                    //////////record insert in ogranization subscription table///////
                    $requestOrgSubsTrait = $this->createOrganizationSubscriptionInformationRequest($request);
                    $orgSubs = OrganizationSubscription::storeOrUpdateData($requestOrgSubsTrait);

                    if (!empty($orgSubs) && is_object($orgSubs)) {

                        return response()->json(['success' => true, 'msg' => 'Subscription changed successfully!', 'plan_id' => ''], 200);
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
     * @function: subscriptionCancel
     * @param Request $request
     * @desc: subscription cancel by organization.
     */
    public function subscriptionCancel(Request $request) {
        try {
            $organization_subscription_id = \Crypt::decryptString($request->id);
            $orgSubs = OrganizationSubscription::getRecordByID($organization_subscription_id);
            $subcrption_id = $orgSubs['getPaymentDetail']->transaction_id;

            $count_log = PaymentLog::where(['txn_id' => $subcrption_id, 'type' => '2', 'organization_id' => \Auth::user()->id])->count();

            if (!empty($count_log) && isset($count_log)) {

                return response()->json(['errors' => ['Subscription already canceled']], 422);
            }

            //\Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            \Stripe\Stripe::setApiKey(!empty($this->helper->getAllSettings()->stripe_sk) ? $this->helper->getAllSettings()->stripe_sk : config('services.stripe.secret'));

            try {

                $subscription = \Stripe\Subscription::retrieve($subcrption_id);
                //$cancel = $subscription->cancel();
                $subscription->cancel_at_period_end = true;
                $reponse_object = $subscription->save();


                if ($reponse_object->status == 'active') {

                    //////////response insert in payment Log table///////
                    $arr['organization_id'] = \Auth::user()->id;
                    $arr['type'] = '2';
                    $requestLogPaymentTrait = $this->createPaymentLogInformationRequest($reponse_object, $arr);
                    $storeLog = PaymentLog::storeOrUpdateData($requestLogPaymentTrait);

                    if (!empty($storeLog) && is_object($storeLog)) {

                        return response()->json(['success' => true, 'msg' => 'Subscription canceled successfully'], 200);
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
     * @function: createCardPayment
     * @param $id
     * @desc: add card detail.
     */
    public function createCardPayment($id) {
        try {

            $allow_roles = array(\Config::get('constants.ORGANIZATION_ADMIN'));
            request()->user()->authorizeRoles($allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Subscription Plan';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['breadCrumData'][1]['url'] = url('admin/subscriptions/plans');

            $data['breadCrumData'][2]['text'] = 'Payment Method';
            $data['breadCrumData'][2]['breadFaClass'] = '';
            $data['title'] = 'Payment Method';
            $data['heading'] = 'Card Information';
            $data['type'] = 'card';
            $data['organization_subscription_id'] = $id;

            $data['site_setting'] = $this->helper->getAllSettings();
            $data['stripe_pk'] = config('services.stripe.key');


            return view('admin.subscription.payment')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: changeCardPayment
     * @param Request $request
     * @desc: change card payment detail
     */
    public function changeCardPayment(Request $request) {
        try {

            $organization_subscription_id = \Crypt::decryptString($request->organization_subscription_id);
            $orgSubs = OrganizationSubscription::getRecordByID($organization_subscription_id);

            $customer_id = $orgSubs['getPaymentDetail']->customer_id;

            //\Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            \Stripe\Stripe::setApiKey(!empty($this->helper->getAllSettings()->stripe_sk) ? $this->helper->getAllSettings()->stripe_sk : config('services.stripe.secret'));

            try {

                /* $finger_prints = \Stripe\Token::retrieve($request->token);

                  $count_card = Payment::where(['fingerprint' => $finger_prints->card->fingerprint])->count();


                  if(!empty($count_card) && isset($count_card)){

                  return response()->json(['errors' => [ 'Card already exists' ]], 422);

              } */

              $customer = \Stripe\Customer::retrieve($customer_id);
              $object_repsonse = $customer->sources->create(["source" => $request->token]);

              if (!empty($object_repsonse) && is_object($object_repsonse)) {

                return response()->json(['success' => true, 'plan_id' => '', 'msg' => 'Card added successfully!'], 200);
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
     * @function: viewCardPayment
     * @param $id
     * @desc: view list of card payment detail
     */
    public function viewCardPayment($id) {
        try {


            $organization_subscription_id = \Crypt::decryptString($id);

            $orgSubs = OrganizationSubscription::getRecordByID($organization_subscription_id);

            $customer_id = $orgSubs['getPaymentDetail']->customer_id;

            //\Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            \Stripe\Stripe::setApiKey(!empty($this->helper->getAllSettings()->stripe_sk) ? $this->helper->getAllSettings()->stripe_sk : config('services.stripe.secret'));

            try {

                $data = Helper::getBreadCrumb();
                $data['breadCrumData'][1]['text'] = 'Subscription Plan';
                $data['breadCrumData'][1]['breadFaClass'] = '';
                $data['breadCrumData'][1]['url'] = url('admin/subscriptions/plans');

                $data['breadCrumData'][2]['text'] = 'Card Detail';
                $data['breadCrumData'][2]['breadFaClass'] = '';
                $data['title'] = 'Card Detail';
                $data['site_setting'] = $this->helper->getAllSettings();

                $object_repsonse = \Stripe\Customer::retrieve($customer_id)->sources->all(['limit' => 3, 'object' => 'card']);

                if (!empty($object_repsonse) && is_object($object_repsonse)) {

                    $data['object_repsonse'] = $object_repsonse;

                    return view('admin.subscription.payment-list')->with($data);
                }
            } catch (\Stripe\Error\Base $e) {

                $request->session()->flash('alert-danger', $e->getMessage());
                return redirect()->back();
            }
        } catch (Exception $ex) {

            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: defaultCardPayment
     * @param Request $request
     * @desc: make default card payment for payment next cycle
     */
    public function defaultCardPayment(Request $request) {
        try {

            $id = \Crypt::decryptString($request->id);
            $CustId = \Crypt::decryptString($request->cust_id);

            //\Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            \Stripe\Stripe::setApiKey(!empty($this->helper->getAllSettings()->stripe_sk) ? $this->helper->getAllSettings()->stripe_sk : config('services.stripe.secret'));

            try {


                $customer = \Stripe\Customer::retrieve($CustId);
                $customer->default_source = $id;
                $objectResponse = $customer->save();

                //dd($objectResponse);

                if (!empty($objectResponse) && is_object($objectResponse)) {

                    return response()->json(['success' => true, 'msg' => 'Default Source Set Successfully', 'responseType' => 'defaultCard'], 200);
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
     * @function: deleteCard
     * @param Request $request
     * @desc: delete card payment detail
     */
    public function deleteCard(Request $request) {
        try {

            $id = \Crypt::decryptString($request->id);
            $CustId = \Crypt::decryptString($request->cust_id);

            //\Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            \Stripe\Stripe::setApiKey(!empty($this->helper->getAllSettings()->stripe_sk) ? $this->helper->getAllSettings()->stripe_sk : config('services.stripe.secret'));

            try {

                $customer = \Stripe\Customer::retrieve($CustId);
                $objectResponse = $customer->sources->retrieve($id)->delete();

                if (!empty($objectResponse) && is_object($objectResponse)) {

                    return response()->json(['success' => true, 'msg' => 'Card Deleted Successfully', 'responseType' => 'delete'], 200);
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
     * @function: paymentHistory
     * @desc: display Payment history for subscription
     */
    public function paymentHistory() {

        try {

            //\Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            \Stripe\Stripe::setApiKey(!empty($this->helper->getAllSettings()->stripe_sk) ? $this->helper->getAllSettings()->stripe_sk : config('services.stripe.secret'));

            try {

                $data = Helper::getBreadCrumb();
                $data['breadCrumData'][1]['text'] = 'Payment History';
                $data['breadCrumData'][1]['breadFaClass'] = '';
                $data['breadCrumData'][1]['url'] = url('admin/subscriptions/payment/history');
                $data['title'] = 'Payment History';
                $data['site_setting'] = $this->helper->getAllSettings();

                $data['arrData'] = OrganizationSubscription::getRecordByID();

                return view('admin.subscription.payment-history')->with($data);
                
            } catch (\Stripe\Error\Base $e) {

                $request->session()->flash('alert-danger', $e->getMessage());
                return redirect()->back();
            }
        } catch (Exception $ex) {

            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: subscriptionDetail
     * @param Request $request
     * @param $subscriptionId
     * @desc: display subscription detail invoice
     */
    public function subscriptionDetail(Request $request, $subscriptionId) {

        try {

            $subscriptionId = \Crypt::decrypt($subscriptionId);

            \Stripe\Stripe::setApiKey(!empty($this->helper->getAllSettings()->stripe_sk) ? $this->helper->getAllSettings()->stripe_sk : config('services.stripe.secret'));

            try {

                $data = Helper::getBreadCrumb();
                $data['breadCrumData'][1]['text'] = 'Payment History';
                $data['breadCrumData'][1]['breadFaClass'] = '';
                $data['breadCrumData'][1]['url'] = url('admin/subscriptions/payment/history');

                $data['breadCrumData'][2]['text'] = 'Invoice Detail';
                $data['breadCrumData'][2]['breadFaClass'] = '';
                $data['title'] = 'Invoice Detail';
                $data['site_setting'] = $this->helper->getAllSettings();

                $subDetail = \Stripe\Invoice::all();

                $invoiceData = [];
                foreach($subDetail->data as $sub){
                    if($sub->subscription == $subscriptionId){

                        $invoiceData[] = \Stripe\Invoice::retrieve($sub->id);
                        
                    }
                }

                $data['invoiceData'] = $invoiceData;

                return view('admin.subscription.subscription-detail')->with($data);
                
            } catch (\Stripe\Error\Base $e) {

                $request->session()->flash('alert-danger', $e->getMessage());
                return redirect()->back();
            }
        } catch (Exception $ex) {

            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

}
