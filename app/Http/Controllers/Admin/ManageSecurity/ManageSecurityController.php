<?php

namespace App\Http\Controllers\Admin\ManageSecurity;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Admin;
use App\Model\OrganizationSubscription;
use App\Http\Requests\Admin\ManageSecurityRequest;
use App\Helpers\Helper;
use Plivo;
use Event;
use App\Traits\ApiResponseTrait;

class ManageSecurityController extends Controller {

    use ApiResponseTrait;

    protected $allow_roles = [];

    /**
     * @author Sandeep Kumar
     * @function: __construct
     * @desc: check user is valid or not and store the roles in allow roles property.
     */
    public function __construct() {
        $this->middleware('auth:admin');
        $this->allow_roles = array(\Config::get('constants.PLATFORM_ADMIN'), \Config::get('constants.ORGANIZATION_ADMIN'), \Config::get('constants.SUB_ADMIN'));
        $this->helper = new Helper();
    }

    /**
     * @author Sandeep Kumar
     * @function: index
     * @desc: Display a listing of security.
     */
    public function index() {
        try {
            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Manage Security List';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['title'] = 'Manage Security';
            $data['userList'] = User::getUserData();

            $data['adminorganisation'] = Admin::getOrganization();
            //$data['roles'] = \Auth::user()->roles->first();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
            $data['site_setting'] = $this->helper->getAllSettings();

            return view('admin.manage-security.security-list')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

    }

    /**
     * @author Sandeep Kumar
     * @function: store
     * @param ManageSecurityRequest $request
     * @desc: Save security.
     */
    public function store(ManageSecurityRequest $request) {
        try {

            $currentPlan = OrganizationSubscription::getCurrentPlan(\Auth::user()->id);

            $planPeople = !empty($currentPlan->getSubscriptionDetail->people_allow) ? $currentPlan->getSubscriptionDetail->people_allow : '';

            $totalPeople = \App\User::where(['organization_id' => \Auth::user()->id])->get()->count();


            if($totalPeople >= $planPeople && (\commonHelper::getRoleName(\Auth::user()->id)->name != \Config::get('constants.PLATFORM_ADMIN') && \commonHelper::getRoleName(\Auth::user()->id)->name != \Config::get('constants.SUB_ADMIN'))) {

                return response()->json(['errors' => [strtr(\Config::get('flash_msg.SubscriptionPeopleAllow'), [
                    '{{COUNT}}' => $planPeople
                ])]], 422);

            }

            request()->user()->authorizeRoles($this->allow_roles);

            //$otp_generate = Helper::codeOTP('4', '1234567890');
            $arrData = [];
            $arrData["organization_id"] = !empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id;
            $arrData["name"] = $request->get("name");
            $arrData["email"] = $request->get("email");
            $arrData["contact_number"] = $request->get("contact_number");
            $arrData["user_type"] = $request->get("user_type");
            $arrData['otp'] = $this->getOtp();
            $arrData['country_code'] = $request->get('country_code');
            $arrData['otp_created_at'] = \Carbon\Carbon::now();
            $arrData["created_by"] = \Auth::user()->id;

            $saveId = User::storeOrUpdateData($arrData);

            /* Upload profile Image */
            if (!empty($request->file('profile_image'))) {
                $arrData = [];
                $varFile = $request->file('profile_image');
                $varFileName = $varFile->getClientOriginalName();
                $varFileEncName = $saveId->id . "_" . str_replace(' ', '_', $varFileName);
                $arrData['profile_image'] = $varFileEncName;

                $varFile->storeAs('public/admin_assets/images/profile_image', $varFileEncName);
                User::storeOrUpdateData($arrData, $saveId->id);
            }

            if (!empty($saveId->id)) {

                $params = array(
                    'src' => \Config::get('constants.PLIVO_SOURCE_NUMBER'),
                    'dst' => $this->removeBraces($saveId->country_code . $saveId->contact_number),
                    'text' => strtr(\Config::get('constants.SECURITY_INVITE_SMS'), [
                        '{{MOBILE}}' => $saveId->country_code . ' ' . $this->removeBraces($saveId->contact_number),
                        '{{OTP}}' => $saveId->otp
                    ])
                );
                //print_r( $params);die;
                $eventData['to'] = $saveId->email;
                $eventData['variable']['{name}'] = $saveId->name;
                $eventData['variable']['{email}'] = $saveId->email;
                $eventData['variable']['{otp}'] = $saveId->otp;
                $eventData['variable']['{link}'] = link_to_route('login', 'Login Now!');
                $eventData['template_code'] = 'SB007';

                Event::fire('sendEmailByTemplate', collect($eventData));
                $plivo = Plivo::sendSMS($params);

                if ($plivo['status'] == '202') {
                    return response()->json(['success' => true, 'msg' => \Config::get('flash_msg.OtpSentMobileOrMail')], 200);
                } else {
                    return response()->json(['success' => true, 'msg' => \Config::get('flash_msg.OtpSentMail')], 200);
                }
            } else {

                return response()->json(['errors' => [\Config::get('flash_msg.SomethingWentWrong')]], 422);
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: show
     * @param $id
     * @desc: show to security information.
     */
    public function show($id) {
        try {

            $id = \Crypt::decryptString($id);

            request()->user()->authorizeRoles($this->allow_roles);

            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Manage Security List';
            $data['breadCrumData'][1]['url'] = url('admin/security');
            $data['breadCrumData'][1]['breadFaClass'] = '';

            $data['breadCrumData'][2]['text'] = 'View Security';
            $data['breadCrumData'][2]['breadFaClass'] = '';

            $data['title'] = 'View Security';

            $getRecord = User::getRecordByID($id);

            $data['objData'] = $getRecord;
            $data['site_setting'] = $this->helper->getAllSettings();

            return view('admin.manage-security.show')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: edit
     * @param $id
     * @desc: edit security.
     */
    public function edit($id) {
        try {

            $id = \Crypt::decryptString($id);

            request()->user()->authorizeRoles($this->allow_roles);

            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Security List';
            $data['breadCrumData'][1]['url'] = url('admin/security');
            $data['breadCrumData'][1]['breadFaClass'] = '';

            $data['breadCrumData'][2]['text'] = 'Edit Security';
            $data['breadCrumData'][2]['breadFaClass'] = '';

            $data['title'] = 'Edit Security';

            $getRecord = User::getRecordByID($id);
            $data['objData'] = $getRecord;
            $data['site_setting'] = $this->helper->getAllSettings();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
            $data['adminorganisation'] = Admin::getOrganization();

            return view('admin.manage-security.edit')
            ->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: update
     * @param ManageSecurityRequest $request
     * @param $id
     * @desc: update security.
     */
    public function update(ManageSecurityRequest $request, $id) {

        $id =  \Crypt::decryptString($id);

        try {



            request()->user()->authorizeRoles($this->allow_roles);
            $arrData = [];
            $arrData["organization_id"] = !empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id;
            $arrData["name"] = $request->get("name");
            $arrData["email"] = $request->get("email");
            $arrData["country_code"] = $request->get("country_code");
            $arrData["contact_number"] = $request->get('contact_number');
            
            User::storeOrUpdateData($arrData, $id);

            /* Upload image media for User */
            if (!empty($request->file('profile_image'))) {
                $arrData = [];
                $user_old = $request->get('profile_image_edit');
                $varFile = $request->file('profile_image');
                $varFileName = $varFile->getClientOriginalName();
                $varFileEncName = $id . "_" . str_replace(' ', '_', $varFileName);
                $path = 'public/storage/admin_assets/images/profile_image/' . $user_old;

                if (!empty($user_old)) {
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                $arrData['profile_image'] = $varFileEncName;

                $varFile->storeAs('public/admin_assets/images/profile_image', $varFileEncName);
                User::storeOrUpdateData($arrData, $id);
            }

            $request->session()->flash('alert-success', \Config::get('flash_msg.SecurityUpdated'));
            return redirect(route('security.index'));
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: destroy
     * @param Request $request
     * @param $id
     * @desc: delete security.
     */
    public function destroy(Request $request, $id) {
        try {

            $id = \Crypt::decryptString($id);
            $varData = User::deleteRecord($id);

            if ($varData) {
                $request->session()->flash('alert-success', \Config::get('flash_msg.SecurityDelete'));
                return redirect()->back();
            } else {
                $request->session()->flash('alert-danger', \Config::get('flash_msg.SecurityNotDeleted'));
                return redirect()->back();
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: reInvitation
     * @param Request $request
     * @desc: resend otp to security team.
     */
    public function reInvitation(Request $request) {
        try {

            $request = $request->all();

            $decryptId = \Crypt::decryptString($request['id']);

            //$otp_generate = Helper::codeOTP('4', '1234567890');
            $requestUser['otp'] = $this->getOtp();
            $requestUser['otp_created_at'] = \Carbon\Carbon::now();

            $checkRecord = User::find($decryptId);

            if (!empty($checkRecord) && is_object($checkRecord) && count($checkRecord) > 0) {

                $user = User::storeOrUpdateData($requestUser, $decryptId);


                if (isset($user) && !empty($user)) {

                    $params = array(
                        'src' => \Config::get('constants.PLIVO_SOURCE_NUMBER'),
                        'dst' => $this->removeBraces($user->country_code . $user->contact_number),
                        'text' => strtr(\Config::get('constants.SECURITY_INVITE_SMS'), [
                            '{{MOBILE}}' => $user->country_code . ' ' . $this->removeBraces($user->contact_number),
                            '{{OTP}}' => $user->otp
                        ])
                    );

                    $eventData['to'] = $user->email;
                    $eventData['variable']['{name}'] = $user->name;
                    $eventData['variable']['{email}'] = $user->email;
                    $eventData['variable']['{otp}'] = $user->otp;
                    $eventData['variable']['{link}'] = link_to_route('login', 'Login Now!');
                    $eventData['template_code'] = 'SB007';

                    Event::fire('sendEmailByTemplate', collect($eventData));

                    if (Plivo::sendSMS($params)['status'] == '202') {

                        return response()->json(['success' => true, 'msg' => \Config::get('flash_msg.OtpSentMobileOrMail')], 200);
                    } else {
                        return response()->json(['success' => true, 'msg' => \Config::get('flash_msg.OtpSentMail')], 200);
                    }
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
     * @function: changeStatus
     * @param Request $request
     * @desc: change status active or inactive.
     */
    public function changeStatus(Request $request) {
        try {
            $request = $request->all();
            $requestUser['status'] = $request['sts'];

            $decryptId = \Crypt::decryptString($request['id']);

            $checkRecord = User::find($decryptId);

            if (!empty($checkRecord) && is_object($checkRecord) && count($checkRecord) > 0) {

                if ($checkRecord->invitation_status != 'resend-invitation' && $request['sts'] == 'Active') {
                    $requestUser['invitation_status'] = 'complete';
                }

                $recordSts = User::storeOrUpdateData($requestUser, $decryptId);

                if (!empty($recordSts) && is_object($recordSts)) {

                    return response()->json(['success' => true, 'msg' => \Config::get('flash_msg.StatusChanged'), 'sts' => $recordSts->status, 'invit_sts' => $recordSts->invitation_status], 200);
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
