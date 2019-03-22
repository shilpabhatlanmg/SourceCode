<?php

namespace App\Http\Controllers\Admin\Premise;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Admin\Premise;
use App\Model\OrganizationSubscription;
use App\Admin;
use App\Http\Requests\Admin\PremiseRequest;
use App\Helpers\Helper;

class PremiseController extends Controller {

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
     * @desc: Display a listing of premise.
     */
    public function index() {
        try {

            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Building List';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['title'] = 'Building List';
            $data['arrData'] = Premise::getAllRecord();
            //$data['roles'] = \Auth::user()->roles->first();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
            $data['adminorganisation'] = Admin::getOrganization();
            $data['site_setting'] = $this->helper->getAllSettings();

            return view('admin.premise.premise-list')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: create
     * @desc: Create premise.
     */
    public function create() {
        try {


            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Building List';
            $data['breadCrumData'][1]['url'] = url('admin/premise');
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['breadCrumData'][2]['text'] = 'Create Building';
            $data['breadCrumData'][2]['breadFaClass'] = '';
            $data['title'] = 'Create Building';
            $data['adminorganisation'] = Admin::getOrganization();

            $data['organization_id'] = !empty(request()->hidden_org) ? \Crypt::decryptString(request()->hidden_org) : '';
            //$data['roles'] = \Auth::user()->roles->first();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
            $data['site_setting'] = $this->helper->getAllSettings();

            return view('admin.premise.create')->with($data);
            $data['arrCountry'] = $this->country->getAllCountry();
        } catch (Exception $ex) {

            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: store
     * @param PremiseRequest $request
     * @desc: Save premise.
     */
    public function store(PremiseRequest $request) {

        try {
            request()->user()->authorizeRoles($this->allow_roles);

            $currentPlan = OrganizationSubscription::getCurrentPlan(\Auth::user()->id);

            $planBuilding = !empty($currentPlan->getSubscriptionDetail->premises_allow) ? $currentPlan->getSubscriptionDetail->premises_allow : '';

            $totalBuilding = \App\Model\Admin\Premise::where(['organization_id' => \Auth::user()->id])->get()->count();


            if($totalBuilding >= $planBuilding && (\commonHelper::getRoleName(\Auth::user()->id)->name != \Config::get('constants.PLATFORM_ADMIN') && \commonHelper::getRoleName(\Auth::user()->id)->name != \Config::get('constants.SUB_ADMIN'))) {

                $request->session()->flash('alert-danger', strtr(\Config::get('flash_msg.SubscriptionBuildingAllow'), [
                    '{{COUNT}}' => $planBuilding
                ]));
                return redirect()->back();

            }


            $arrData = [];

            $arrData["name"] = $request->get("name");
            $arrData["organization_id"] = !empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id;
            $arrData["status"] = $request->get("status");
            $arrData["created_by"] = \Auth::user()->id;
            $saveId = Premise::storeOrUpdateData($arrData);



            if ($request->ajax()) {

                if (!empty($saveId)) {

                    $premiselist = Premise::getAllPremiseByOrgId($request->get("organization_id"));
                    return response()->json(['success' => true, 'datalist' => $premiselist, 'record' => $saveId]);
                } else {

                    return response()->json(['errors' => ['due to some error']], 422);
                }
            } else {



                if (!empty($saveId) && is_object($saveId)) {

                    $request->session()->flash('alert-success', \Config::get('flash_msg.PremiseAdded'));
                    return redirect(route('premise.index'));
                } else {

                    return \Redirect::back()->withInput()->with('alert-danger', \Config::get('flash_msg.PremiseNotAdded'));
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
     * @desc: edit premise.
     */
    public function edit($id) {
        try {

            $id = \Crypt::decryptString($id);

            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Building List';
            $data['breadCrumData'][1]['url'] = url('admin/premise');
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['breadCrumData'][2]['text'] = 'Edit Building';
            $data['breadCrumData'][2]['breadFaClass'] = '';
            $data['title'] = 'Edit Building';
            $data['adminorganisation'] = Admin::getOrganization();
            //$data['roles'] = \Auth::user()->roles->first();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
            $data['objData'] = Premise::getRecordByID($id);
            $data['site_setting'] = $this->helper->getAllSettings();
            return view('admin.premise.edit')
                            ->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: update
     * @param PremiseRequest $request
     * @param $id
     * @desc: update premise.
     */
    public function update(PremiseRequest $request, $id) {
        try {
            request()->user()->authorizeRoles($this->allow_roles);
            $arrData = [];
            $arrData["name"] = $request->get("name");
            $arrData["organization_id"] = !empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id;
            $arrData["status"] = $request->get("status");
            $arrData["updated_by"] = \Auth::user()->id;
            Premise::storeOrUpdateData($arrData, $id);
            /* Upload image media for Premise */

            $request->session()->flash('alert-success', \Config::get('flash_msg.PremiseUpdated'));
            return redirect(route('premise.index'));
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: destroy
     * @param Request $request
     * @param $id
     * @desc: Delete premise.
     */
    public function destroy(Request $request, $id) {

        try {

            $id = \Crypt::decryptString($id);

            request()->user()->authorizeRoles($this->allow_roles);


            $count_premise = Premise::withCount('locations')->where('id', $id)->first();

            if (!empty($count_premise->locations_count) && isset($count_premise->locations_count)) {

                $request->session()->flash('alert-danger', \Config::get('flash_msg.PremiseNotDeleted'));
                return redirect()->back();
            } else {

                $varData = Premise::deleteRecord($id);
                $request->session()->flash('alert-success', \Config::get('flash_msg.PremiseDeleted'));
                return redirect()->back();
            }


            /* $varData = Premise::deleteRecord($id);

              if ($varData) {
              $request->session()->flash('alert-success', \Config::get('flash_msg.PremiseDeleted'));
              return redirect()->back();
              } else {
              $request->session()->flash('alert-danger', \Config::get('flash_msg.PremiseNotDeleted'));
              return redirect()->back();
              } */
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

            $checkRecord = Premise::find($decryptId);

            if (!empty($checkRecord) && is_object($checkRecord) && count($checkRecord) > 0) {

                $recordSts = Premise::storeOrUpdateData($requestUser, $decryptId);

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
