<?php

namespace App\Http\Controllers\Admin\Organization;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Admin;
use App\Role;
use App\Model\Admin\PocDetail;
use App\Http\Requests\Admin\OrganizationRequest;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\OrganizationSubscription;
use Event;

class OrganizationController extends Controller {

    protected $allow_roles = [];

    use \App\Traits\Admin\OrganizationInformation;

    /**
     * @author Sandeep Kumar
     * @function: __construct
     * @desc: check user is valid or not and store the roles in allow roles property.
     */
    public function __construct() {
        $this->middleware('auth:admin')->except(['activateUser']);
        $this->allow_roles = array(\Config::get('constants.PLATFORM_ADMIN'), \Config::get('constants.SUB_ADMIN'));
        $this->country = new Country();
        $this->state = new State();
        $this->city = new City();
        $this->helper = new Helper();
    }

    /**
     * @author Sandeep Kumar
     * @function: index
     * @desc: Display a listing of organization.
     */
    public function index() {

        if (request()->session()->get('state_id'))
            request()->session()->forget('state_id');
        try {
            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Manage Organizations List';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['title'] = 'Manage Organizations';
            $role_org_admin = Role::where('name', \Config::get('constants.ORGANIZATION_ADMIN'))->first();
            $roleId = $role_org_admin->id;
            $data['userList'] = Admin::getAllRecord($roleId);
            //$data['roles'] = \Auth::user()->roles->first();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
            //$data['adminorganisation'] = Admin::getOrganization();
           
            $data['site_setting'] = $this->helper->getAllSettings();
            return view('admin.organization.organization-list')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: create
     * @desc: Create organization.
     */
    public function create() {
        request()->user()->authorizeRoles($this->allow_roles);
        $data = Helper::getBreadCrumb();
        $data['breadCrumData'][1]['text'] = 'Organization List';
        $data['breadCrumData'][1]['url'] = url('admin/organization');
        $data['breadCrumData'][1]['breadFaClass'] = '';
        $data['breadCrumData'][2]['text'] = 'Create Organization';
        $data['breadCrumData'][2]['breadFaClass'] = '';
        $data['title'] = 'Create Organization';
        $data['arrCountry'] = $this->country->getAllCountry();
        $data['arrState'] = $this->state->getAllState();
        if (request()->session()->get('state_id') > 0) {
            $data['arrCityData'] = $this->city->getAllCityByStateID(request()->session()->get('state_id'));
        }
        $data['site_setting'] = $this->helper->getAllSettings();
        return view('admin.organization.create')->with($data);
    }

    /**
     * @author Sandeep Kumar
     * @function: store
     * @param OrganizationRequest $request
     * @desc: Save organization.
     */
    public function store(OrganizationRequest $request) {
        try {

            if (request()->session()->get('state_id'))
                request()->session()->forget('state_id');

            $id = \DB::table('becon_majors')->insertGetId(
                    ['id' => NULL]
            );
            
            $role_org_admin = Role::where('name', \Config::get('constants.ORGANIZATION_ADMIN'))->first();
             
            if (isset($id) && !empty($id)) {

                $requestVar = array_merge($request->all(), ['becon_major_id' => $id,'role_id'=>$role_org_admin->id]);
            }

            
                //dd($requestVar);

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

            return redirect(route('organization.index'));
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: show
     * @param $id
     * @desc: show organization information.
     */
    public function show($id) {
        try {

            $id = \Crypt::decryptString($id);

            request()->user()->authorizeRoles($this->allow_roles);

            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Organization List';
            $data['breadCrumData'][1]['url'] = url('admin/organization');
            $data['breadCrumData'][1]['breadFaClass'] = '';

            $data['breadCrumData'][2]['text'] = 'View Organization';
            $data['breadCrumData'][2]['breadFaClass'] = '';

            $data['title'] = 'View Organization';

            $getRecord = Admin::getRecordByID($id);
            //dd($getRecord);

            $data['objData'] = $getRecord;
            $data['arrCountry'] = $this->country->getAllCountry();
            $data['arrState'] = $this->state->getAllState();
            $data['arr_poc_detail'] = PocDetail::getPocById($id);
            $data['arrCityData'] = $this->city->getAllCityByStateID($getRecord->state_id);
            $data['site_setting'] = $this->helper->getAllSettings();

            return view('admin.organization.show')
                            ->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: edit
     * @param $id
     * @desc: edit organization.
     */
    public function edit($id) {
        try {

            $id = \Crypt::decryptString($id);

            request()->user()->authorizeRoles($this->allow_roles);

            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Organization List';
            $data['breadCrumData'][1]['url'] = url('admin/organization');
            $data['breadCrumData'][1]['breadFaClass'] = '';

            $data['breadCrumData'][2]['text'] = 'Edit Organization';
            $data['breadCrumData'][2]['breadFaClass'] = '';

            $data['title'] = 'Edit Organization';

            $getRecord = Admin::getRecordByID($id);

            $data['objData'] = $getRecord;
            $data['arrCountry'] = $this->country->getAllCountry();
            $data['arrState'] = $this->state->getAllState();
            $data['arr_poc_detail'] = PocDetail::getPocById($id);
            $data['arrCityData'] = $this->city->getAllCityByStateID($getRecord->state_id);
            $data['site_setting'] = $this->helper->getAllSettings();

            return view('admin.organization.edit')
                            ->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: update
     * @param OrganizationRequest $request
     * @param $id
     * @desc: update organization.
     */
    public function update(OrganizationRequest $request, $id) {


        $requestVar = array_merge($request->all(), ['id' => $id]);
        $requestOrgTrait = $this->createOrganizationInformationRequest($requestVar);

        $user_id = Admin::storeOrUpdateData($requestOrgTrait, $id);

        if (isset($user_id) && !empty($user_id)) {

            /////////////Update Record for POC Details /////////////////

            if (is_array($request->poc_name) && !empty($request->poc_name)) {

                foreach ($request->poc_name as $key => $val) {


                    if (!empty($request->poc_id[$key]) && isset($request->poc_id[$key])) {


                        $poc_detail = PocDetail::find($request->poc_id[$key]);
                        $poc_detail->organization_id = $id;
                        $poc_detail->poc_name = $request->poc_name[$key];
                        $poc_detail->poc_contact_no = $request->poc_contact_no[$key];
                        $poc_detail->poc_email = $request->poc_email[$key];


                        if ($poc_detail->save()) {
                            $flg = '1';
                        }
                    } else {

                        $poc_detail = new PocDetail;
                        $poc_detail->organization_id = $id;
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

                $request->session()->flash('alert-success', \Config::get('flash_msg.OrganizationUpdated'));
            }
        } else {
            $request->session()->flash('alert-danger', \Config::get('flash_msg.OrganizationNotUpdated'));
        }

        if (!empty($request->search)) {

            return redirect()->route('organization.index', ['search' => !empty($request->search) ? $request->search : '']);
        } else {
            return redirect(route('organization.index'));
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: destroy
     * @param Request $request
     * @param $id
     * @desc: Delete organization.
     */
    public function destroy(Request $request, $id) {
        try {

            $id = \Crypt::decryptString($id);
            $orgDetail = Admin::find($id);
            $role_org_admin = Role::where('name', \Config::get('constants.ORGANIZATION_ADMIN'))->first();

            $subId = OrganizationSubscription::getOrgSubscriptionDetail($id);


            if(!empty($subId) && is_object($subId)){

                $subcrption_id = $subId->getPaymentDetail->transaction_id;

                \Stripe\Stripe::setApiKey(!empty($this->helper->getAllSettings()->stripe_sk) ? $this->helper->getAllSettings()->stripe_sk : config('services.stripe.secret'));

                try{

                    $custId = $subId->getPaymentDetail->customer_id;

                    $cu = \Stripe\Customer::retrieve($custId);
                    $response = $cu->delete();

                    if($response->deleted){

                        if(!empty($role_org_admin) && is_object($role_org_admin) && count($role_org_admin) > 0){

                            $orgDetail->roles()->detach($role_org_admin->id);    
                        }

                        $varData = Admin::deleteRecord($id);

                        $request->session()->flash('alert-success', \Config::get('flash_msg.OrganizationDeleted'));
                        return redirect()->back();

                    }

                    //$subscription = \Stripe\Subscription::retrieve($subcrption_id);
                    //$cancel = $subscription->cancel();

                    
                

                } catch (\Stripe\Error\Base $e) {

                    $request->session()->flash('alert-danger', \Config::get('flash_msg.OrganizationNotDeleted'));
                    return redirect()->back();

                }
            }

            if(!empty($role_org_admin) && is_object($role_org_admin) && count($role_org_admin) > 0){

                $orgDetail->roles()->detach($role_org_admin->id);    
            }
            

            $varData = Admin::deleteRecord($id);

            if ($varData) {
                $request->session()->flash('alert-success', \Config::get('flash_msg.OrganizationDeleted'));
                return redirect()->back();
            } else {
                $request->session()->flash('alert-danger', \Config::get('flash_msg.OrganizationNotDeleted'));
                return redirect()->back();
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: resendActivationMail
     * @param Request $request
     * @desc: resend activation mail to organization.
     */
    public function resendActivationMail(Request $request) {
        try {

            $request = $request->all();
            $id = \Crypt::decryptString($request['id']);
            $user = Admin::getOrganization($id);

            if (!empty($user) && is_object($user)) {

                $eventData['to'] = $user->email;
                $eventData['variable']['{name}'] = !empty($user->pocName->poc_name) ? $user->pocName->poc_name : $user->email;
                $eventData['variable']['{link}'] = link_to_route('activate_user', 'Activate your account!', ['token' => $user->token, 'email' => $user->email]);

                $getPass = Helper::codeOTP(6);

                $eventData['variable']['{username}'] = $user->email;
                $eventData['variable']['{password}'] = $getPass;

                $eventData['template_code'] = 'SB006';

                Event::fire('sendEmailByTemplate', collect($eventData));

                Admin::storeOrUpdateData(['password' => bcrypt($getPass)], $user->id);

                return response()->json(['success' => true, 'msg' => 'Reactivation Mail Sent successfully'], 200);
            } else {
                return response()->json(['errors' => ['Due to some error']], 422);
            }
        } catch (Exception $ex) {
            return $e->getMessage();
        }
    }


    /**
     * @author Sandeep Kumar
     * @function: activateUser
     * @param Request $request
     * @desc: mail activation verification.
     */
    public function activateUser(Request $request) {

        $whr['token'] = $request->get('token');
        $whr['email'] = $email = $request->get('email');
        $whr['status'] = 'Inactive';
        $res = Admin::getOrganizationByEmail($email);

        $data['site_setting'] = $this->helper->getAllSettings();

        if ($res->status == 'Active') {
            return view('activation.already_registered')->with($data);
        }

        $updateRecord = Admin::activateUser($whr);

        if (!empty($updateRecord)) {
            return view('activation.thankyou')->with($data);
        } else {
            return view('errors.404')->with($data);
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
            $requestUser['reason'] = 'Deactivated By Admin';

            $checkRecord = Admin::find($decryptId);

            if (!empty($checkRecord) && is_object($checkRecord) && count($checkRecord) > 0) {

                $recordSts = Admin::storeOrUpdateData($requestUser, $decryptId);

                if (!empty($recordSts) && is_object($recordSts)) {

                    return response()->json(['success' => true, 'msg' => \Config::get('flash_msg.StatusChanged'), 'reason' => $recordSts->reason, 'sts' => $recordSts->status], 200);
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

}
