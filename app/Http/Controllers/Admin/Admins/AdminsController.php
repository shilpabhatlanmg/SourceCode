<?php

namespace App\Http\Controllers\Admin\Admins;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Admin\Location;
use App\Model\Admin\Premise;
use App\Admin;
use App\Role;
use App\Http\Requests\Admin\LocationRequest;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Helpers\Helper;

class AdminsController extends Controller {

    protected $allow_roles = [];

    /**
     * @author Sandeep Kumar
     * @function: __construct
     * @desc: check user is valid or not and store the roles in allow roles property.
     */
    public function __construct() {
        $this->country = new Country();
        $this->state = new State();
        $this->city = new City();
        $this->middleware('auth:admin');
        $this->allow_roles = array(\Config::get('constants.PLATFORM_ADMIN'), \Config::get('constants.ORGANIZATION_ADMIN'));
        //$this->helper = new Helper();
    }

    /**
     * @author Sandeep Kumar
     * @function: create
     * @desc: display organization list.
     */
    public function index() {
        try {

            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Admin';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['title'] = 'Admin Users List';
            $role_sub_admin = Role::where('name', \Config::get('constants.SUB_ADMIN'))->first();
            $roleId = $role_sub_admin->id;
            //$data['userList'] = Admin::getAllRecord($roleId);

            $data['adminorganisation'] = Admin::getAllRecord($roleId);

            return view('admin.admins.admins')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: create
     * @desc: create organization.
     */
    public function create() {
        try {
            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Admin User List';
            $data['breadCrumData'][1]['url'] = url('admin/admin-users');
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['breadCrumData'][2]['text'] = 'Create Admin User';
            $data['breadCrumData'][2]['breadFaClass'] = '';
            $data['title'] = 'Create Admin User';

            $data['arrCountry'] = $this->country->getAllCountry();
            $data['arrState'] = $this->state->getAllState();
            return view('admin.admins.create')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: show
     * @param Request $request
     * @desc: store organization detail.
     */
    public function store(Request $request) {
        try {
            request()->user()->authorizeRoles($this->allow_roles);

            $id = "";
            $validation = \Validator::make($request->all(), [
                        'name' => 'required',
                        'email' => 'required|unique:organizations,email,' . ($id ? $id : '') . '|max:255',
                            // 'name' => 'required|unique:locations,name,NULL,id,deleted_at,NULL,premise_id,' . $request->get('premise_id'),
            ]);
            if ($validation->fails()) {

                request()->user()->authorizeRoles($this->allow_roles);

                $data = Helper::getBreadCrumb();
                $data['breadCrumData'][1]['text'] = 'Admin User List';
                $data['breadCrumData'][1]['url'] = url('admin/location');
                $data['breadCrumData'][1]['breadFaClass'] = '';
                $data['breadCrumData'][2]['text'] = 'Create Admin User';
                $data['breadCrumData'][2]['breadFaClass'] = '';
                $data['title'] = 'Create Building Section';
                $data['arrCountry'] = $this->country->getAllCountry();
                $data['arrState'] = $this->state->getAllState();

                //$data['roles'] = \Auth::user()->roles->first();
                $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);

                return view('admin.admins.create')->with($data)->withErrors($validation);
            }
            $role_sub_admin = Role::where('name', \Config::get('constants.SUB_ADMIN'))->first();
            $roleId = $role_sub_admin->id;
            $arrData = [];
            $arrData["username"] = \Auth::user()->username;
            $arrData["name"] = $request->get("name");
            $arrData["address"] = !empty($request->get("street")) ? $request->get("street") : "";
            $arrData["country_id"] = !empty($request->get("country_id")) ? $request->get("country_id") : "";
            $arrData["state_id"] = !empty($request->get("state_id")) ? $request->get("state_id") : "0";
            $arrData["city_id"] = !empty($request->get("city_id")) ? $request->get("city_id") : "0";
            $arrData["email"] = $request->get("email");
            $arrData["status"] = 'Active';
            $arrData["is_admin"] = \Auth::user()->is_admin;
            $arrData["is_root_admin"] = 0;
            $arrData['password'] = !empty($request->get("password")) ? bcrypt($request->get("password")) : "";
            $arrData['timezone'] = $request->get("timezone");
            $arrData['token'] = Helper::getToken();
            $arrData["phone"] = $request->get("address_phone");
            $arrData["zip_code"] = $request->get("address_zip");
            $arrData["created_by"] = \Auth::user()->id;
            $arrData["becon_major_id"] = 0;
            $arrData["role_id"] = $roleId;


            $user_id = Admin::storeOrUpdateData($arrData);


            $role_org_admin = Role::where('name', \Config::get('constants.SUB_ADMIN'))->first();

            if (!empty($user_id) && is_object($user_id)) {
                $user_id->roles()->attach($role_org_admin);

                $request->session()->flash('alert-success', \Config::get('flash_msg.AdminUserAdded'));
                return redirect(route('admins.index'));
            } else {

                return \Redirect::back()->withInput()->with('alert-danger', \Config::get('flash_msg.AdminUserNotAdded'));
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: show
     * @param $id
     * @desc: show organization detail.
     */
    public function show($id) {


        try {

            $id = \Crypt::decryptString($id);
            request()->user()->authorizeRoles($this->allow_roles);

            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Admin User List';
            $data['breadCrumData'][1]['url'] = url('admin/admin-users');
            $data['breadCrumData'][1]['breadFaClass'] = '';

            $data['breadCrumData'][2]['text'] = 'View User List';
            $data['breadCrumData'][2]['breadFaClass'] = '';

            $data['title'] = 'View Admin User ';

            $getRecord = Admin::getRecordByID($id);
            $data['objData'] = $getRecord;
            $data['arrCountry'] = $this->country->getAllCountry();
            $data['arrState'] = $this->state->getAllState();
            $data['arrCityData'] = $this->city->getAllCityByStateID($getRecord->state_id);
            return view('admin.admins.show')->with($data);
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
            $data['breadCrumData'][1]['text'] = 'Admin Users List';
            $data['breadCrumData'][1]['url'] = url('admin/admin-users');
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['breadCrumData'][2]['text'] = 'Edit Admin User';
            $data['breadCrumData'][2]['breadFaClass'] = '';
            $data['title'] = 'Edit Admin User';
            $data['adminorganisation'] = Admin::getOrganization();
            $adminUserDetail = Admin::getRecordByID($id);
            $data['objData'] = $adminUserDetail;
            //$data['roles'] = \Auth::user()->roles->first();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
            $data['arrCountry'] = $this->country->getAllCountry();
            $data['arrState'] = $this->state->getAllState();
            $data['arrCityData'] = $this->city->getAllCityByStateID($adminUserDetail->state_id);
            return view('admin.admins.edit')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: destroy
     * @param Request $request
     * @param $id
     * @desc: update organization.
     */
    public function update(Request $request, $id) {
        try {
            $id = \Crypt::decryptString($id);
            request()->user()->authorizeRoles($this->allow_roles);
            $validation = \Validator::make($request->all(), [
                        'name' => 'required',
                        'email' => 'required|unique:organizations,email,' . ($id ? $id : '') . '|max:255',
                            // 'name' => 'required|unique:locations,name,NULL,id,deleted_at,NULL,premise_id,' . $request->get('premise_id'),
            ]);


            if ($validation->fails()) {

                request()->user()->authorizeRoles($this->allow_roles);
                $data = Helper::getBreadCrumb();
                $data['breadCrumData'][1]['text'] = 'Location List';
                $data['breadCrumData'][1]['url'] = url('admin/location');
                $data['breadCrumData'][1]['breadFaClass'] = '';
                $data['breadCrumData'][2]['text'] = 'Edit Location';
                $data['breadCrumData'][2]['breadFaClass'] = '';
                $data['title'] = 'Edit Location';
                $data['adminorganisation'] = Admin::getOrganization();
                $adminUserDetail = Admin::getRecordByID($id);

                $data['objData'] = $adminUserDetail;
                //$data['roles'] = \Auth::user()->roles->first();
                $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
                $data['arrCountry'] = $this->country->getAllCountry();
                $data['arrState'] = $this->state->getAllState();
                $data['arrCityData'] = $this->city->getAllCityByStateID($adminUserDetail->state_id);

                return view('admin.admins.edit')->with($data)->withErrors($validation);
            }

            $arrData = [];
            $arrData["name"] = $request->get("name");
            $arrData["address"] = !empty($request->get("street")) ? $request->get("street") : "";
            $arrData["country_id"] = !empty($request->get("country_id")) ? $request->get("country_id") : "";
            $arrData["state_id"] = !empty($request->get("state_id")) ? $request->get("state_id") : "0";
            $arrData["city_id"] = !empty($request->get("city_id")) ? $request->get("city_id") : "0";
            $arrData["email"] = $request->get("email");
            $arrData["is_admin"] = \Auth::user()->is_admin;
            $arrData["phone"] = $request->get("address_phone");
            $arrData["zip_code"] = $request->get("address_zip");
            $arrData["updated_by"] = \Auth::user()->id;
            Admin::storeOrUpdateData($arrData, $id);
            $request->session()->flash('alert-success', \Config::get('flash_msg.AdminUserUpdated'));
            return redirect(route('admins.index'));
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
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
            request()->user()->authorizeRoles($this->allow_roles);
            $varData = Admin::deleteRecord($id);
            $request->session()->flash('alert-success', \Config::get('flash_msg.AdminUserDeleted'));
            return redirect()->back();
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

            $checkRecord = Admin::find($decryptId);

            if (!empty($checkRecord) && is_object($checkRecord) && count($checkRecord) > 0) {

                $recordSts = Admin::storeOrUpdateData($requestUser, $decryptId);

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

}
