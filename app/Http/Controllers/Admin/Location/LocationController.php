<?php

namespace App\Http\Controllers\Admin\Location;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Admin\Location;
use App\Model\Admin\Premise;
use App\Admin;
use App\Http\Requests\Admin\LocationRequest;
use App\Helpers\Helper;

class LocationController extends Controller {

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
     * @desc: Display a listing of Beacon.
     */
    public function index() {
        try {

            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Building Section List';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['title'] = 'Building Section List';
            $data['arrData'] = Location::getAllRecord();
            //$data['roles'] = \Auth::user()->roles->first();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
            $data['adminorganisation'] = Admin::getOrganization();
            $data['site_setting'] = $this->helper->getAllSettings();

            return view('admin.location.location-list')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: create
     * @desc: Create location.
     */
    public function create() {
        try {
            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Building Section List';
            $data['breadCrumData'][1]['url'] = url('admin/location');
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['breadCrumData'][2]['text'] = 'Create Building Section';
            $data['breadCrumData'][2]['breadFaClass'] = '';
            $data['title'] = 'Create Building Section';

            $data['adminorganisation'] = Admin::getOrganization();
            $data['organization_id'] = !empty(request()->hidden_org) ? \Crypt::decryptString(request()->hidden_org) : '';
            //$data['roles'] = \Auth::user()->roles->first();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
            $data['premiselist'] = Premise::getPremise(!empty(request()->hidden_org) ? \Crypt::decryptString(request()->hidden_org) : \Auth::user()->id);
            $data['site_setting'] = $this->helper->getAllSettings();

            return view('admin.location.create')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: store
     * @param Request $request
     * @desc: Save location.
     */
    public function store(Request $request) {

        try {
            request()->user()->authorizeRoles($this->allow_roles);


            if (\Auth::user()->roles->first()->name == \Config::get('constants.PLATFORM_ADMIN')) {


                $validation = \Validator::make($request->all(), [
                            'organization_id' => 'required',
                            'premise_id' => 'required',
                            'name' => 'required|unique:locations,name,NULL,id,deleted_at,NULL,premise_id,' . $request->get('premise_id'),
                            'status' => 'required',
                ]);
            } else {

                $validation = \Validator::make($request->all(), [
                            'name' => 'required|unique:locations,name,NULL,id,deleted_at,NULL,premise_id,' . $request->get('premise_id'),
                            'premise_id' => 'required',
                            'status' => 'required',
                ]);
            }

            if ($validation->fails()) {

                request()->user()->authorizeRoles($this->allow_roles);
                $data = Helper::getBreadCrumb();
                $data['breadCrumData'][1]['text'] = 'Building Section List';
                $data['breadCrumData'][1]['url'] = url('admin/location');
                $data['breadCrumData'][1]['breadFaClass'] = '';
                $data['breadCrumData'][2]['text'] = 'Create Building Section';
                $data['breadCrumData'][2]['breadFaClass'] = '';
                $data['title'] = 'Create Building Section';

                $data['adminorganisation'] = Admin::getOrganization();
                $data['organization_id'] = !empty($request->get('organization_id')) ? $request->get('organization_id') : '';
                //$data['roles'] = \Auth::user()->roles->first();
                $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
                $data['premiselist'] = Premise::getPremise($request->get('organization_id'));

                if ($request->ajax()) {

                    return response()->json(['success' => false, 'errors' => $validation->getMessageBag()->toArray()], 422);
                }


                return view('admin.location.create')->with($data)->withErrors($validation);
            }

            $arrData = [];

            $arrData["name"] = $request->get("name");

            $arrData["organization_id"] = !empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id;
            $arrData["premise_id"] = $request->get("premise_id");

            $arrData["status"] = $request->get("status");

            $arrData["created_by"] = \Auth::user()->id;

            $saveId = Location::storeOrUpdateData($arrData);

            if ($request->ajax()) {

                if (!empty($saveId)) {

                    $locationlist = Location::getAllLocationByPremiseId($request->get("premise_id"));
                    return response()->json(['success' => true, 'datalist' => $locationlist, 'record' => $saveId]);
                } else {

                    return response()->json(['success' => false, 'errors' => ['due to some error']], 422);
                }
            } else {

                if (!empty($saveId) && is_object($saveId)) {

                    $request->session()->flash('alert-success', \Config::get('flash_msg.LocationAdded'));
                    return redirect(route('location.index'));
                } else {

                    return \Redirect::back()->withInput()->with('alert-danger', \Config::get('flash_msg.LocationNotAdded'));
                }
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
     * @desc: edit location.
     */
    public function edit($id) {
        try {

            $id = \Crypt::decryptString($id);

            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Building Section List';
            $data['breadCrumData'][1]['url'] = url('admin/location');
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['breadCrumData'][2]['text'] = 'Edit Building Section';
            $data['breadCrumData'][2]['breadFaClass'] = '';
            $data['title'] = 'Edit Building Section';
            $data['adminorganisation'] = Admin::getOrganization();

            //$data['roles'] = \Auth::user()->roles->first();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);

            $locationDetail = Location::getRecordByID($id);
            $data['objData'] = $locationDetail;

            $data['premiselist'] = Premise::getPremise($locationDetail->organization_id);
            $data['site_setting'] = $this->helper->getAllSettings();

            return view('admin.location.edit')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: update
     * @param Request $request
     * @param $id
     * @desc: update location.
     */
    public function update(Request $request, $id) {
        try {

            request()->user()->authorizeRoles($this->allow_roles);

            if (\Auth::user()->roles->first()->name == \Config::get('constants.PLATFORM_ADMIN')) {


                $validation = \Validator::make($request->all(), [
                            'organization_id' => 'required',
                            'premise_id' => 'required',
                            'name' => 'required|unique:locations,name, ' . $request->get('location_id') . ',id,deleted_at,NULL,premise_id,' . $request->get('premise_id') . ',organization_id,' . $request->get('organization_id'),
                            'status' => 'required',
                ]);
            } else {

                $validation = \Validator::make($request->all(), [
                            'name' => 'required',
                            'name' => 'required|unique:locations,name, ' . $request->get('location_id') . ',id,deleted_at,NULL,premise_id,' . $request->get('premise_id') . ',organization_id,' . \Auth::user()->id,
                            'status' => 'required',
                ]);
            }

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
                //$data['roles'] = \Auth::user()->roles->first();
                $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
                $locationDetail = Location::getRecordByID($id);
                $data['objData'] = $locationDetail;
                $data['premiselist'] = Premise::getPremise($request->get('organization_id'));

                return view('admin.location.edit')->with($data)->withErrors($validation);
            }

            $arrData = [];
            $arrData["name"] = $request->get("name");
            $arrData["organization_id"] = !empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id;
            $arrData["premise_id"] = $request->get("premise_id");
            $arrData["status"] = $request->get("status");
            $arrData["updated_by"] = \Auth::user()->id;
            Location::storeOrUpdateData($arrData, $id);
            /* Upload image media for Location */

            $request->session()->flash('alert-success', \Config::get('flash_msg.LocationUpdated'));
            return redirect(route('location.index'));
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: destroy
     * @param Request $request
     * @param $id
     * @desc: Delete location.
     */
    public function destroy(Request $request, $id) {

        try {

            $id = \Crypt::decryptString($id);

            request()->user()->authorizeRoles($this->allow_roles);

            $count_becons = Location::withCount('becons')->where('id', $id)->first();

            if (!empty($count_becons->becons_count) && isset($count_becons->becons_count)) {

                $request->session()->flash('alert-danger', \Config::get('flash_msg.LocationNotDeleted'));
                return redirect()->back();
            } else {

                $varData = Location::deleteRecord($id);
                $request->session()->flash('alert-success', \Config::get('flash_msg.LocationDeleted'));
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

            $checkRecord = Location::find($decryptId);

            if (!empty($checkRecord) && is_object($checkRecord) && count($checkRecord) > 0) {

                $recordSts = Location::storeOrUpdateData($requestUser, $decryptId);

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
