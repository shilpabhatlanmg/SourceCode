<?php

namespace App\Http\Controllers\Admin\Beacon;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Admin\Becon;
use App\Model\Admin\Premise;
use App\Model\Admin\Location;
use App\Admin;
use App\Http\Requests\Admin\BeconRequest;
use App\Helpers\Helper;
use App\Model\Admin\SiteSetting\SiteSetting;

class BeconController extends Controller {

    protected $allow_roles = [];

    /**
     * @author Sandeep Kumar
     * @function: __construct
     * @desc: check user is valid or not and store the roles in allow roles property.
     */
    public function __construct() {
        $this->middleware('auth:admin');
        $this->allow_roles = array(\Config::get('constants.PLATFORM_ADMIN'), \Config::get('constants.ORGANIZATION_ADMIN'), \Config::get('constants.SUB_ADMIN'));
        $this->sitesettings = new SiteSetting();
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
            $data['breadCrumData'][1]['text'] = 'Beacon List';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['title'] = 'Beacon List';
            //$data['roles'] = \Auth::user()->roles->first();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
            $data['arrData'] = Becon::getAllRecord();
            //dd($data['arrData']);
            $data['adminorganisation'] = Admin::getOrganization();
            $data['site_setting'] = $this->helper->getAllSettings();
            return view('admin.becon.becon-list')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: create
     * @desc: Create beacon.
     */
    public function create() {
        try {
            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Beacon List';
            $data['breadCrumData'][1]['url'] = url('admin/beacon');
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['breadCrumData'][2]['text'] = 'Create Beacon';
            $data['breadCrumData'][2]['breadFaClass'] = '';
            $data['title'] = 'Create Beacon';

            $arrSettingData = $this->sitesettings->getAllSettings();
            $arrData = [];

            foreach ($arrSettingData as $settingdata) {
                $arrData[$settingdata->option_name] = $settingdata->option_value;
            }

            if (!empty($arrData) && is_array($arrData) && !empty($arrData['uuid']) && isset($arrData['uuid'])) {
                $data['uuid'] = $arrData['uuid'];
            }

            $data['adminorganisation'] = Admin::getOrganization();
            $data['organization_id'] = !empty(request()->hidden_org) ? \Crypt::decryptString(request()->hidden_org) : '';
            $data['premiselist'] = Premise::getPremise(!empty(request()->hidden_org) ? \Crypt::decryptString(request()->hidden_org) : \Auth::user()->id);

            $data['locationCount'] = 0;
            //$data['roles'] = \Auth::user()->roles->first();

            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);


            $data['site_setting'] = $this->helper->getAllSettings();


            return view('admin.becon.create')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: store
     * @param Request $request
     * @desc: Save Beacon.
     */
    public function store(Request $request) {
        try {

            request()->user()->authorizeRoles($this->allow_roles);

            $locationFlag = $premiseFlag = false;
            $locationMsg = $premiseMsg = null;

            if ($request->post()['premise_id'] == null) {

                if ($request->post()['premise_text_id'] != null) {

                    $premiseData = [
                        'organization_id' => !empty($request->post()['organization_id']) ? $request->post()['organization_id'] : \Auth::user()->id,
                        'premise_text_id' => $request->post()['premise_text_id'],
                        'minor_id' => $request->post()['minor_id']
                    ];



                    $premiseValidation = \App\Helpers\Helper::premiseValidationData($request, $premiseData);

                    //dd($premiseValidation->errors());

                    if (!$premiseValidation->fails()) {

                        $premiseData['name'] = $premiseData['premise_text_id'];

                        unset($premiseData['premise_text_id']);
                        unset($premiseData['minor_id']);

                        $premise = Premise::storeOrUpdateData($premiseData);

                        if ($premise) {

                            $request->merge(['premise_id' => $premise->id]);

                            if ($request->post()['location_id'] == null) {

                                if ($request->post()['location_text_id'] != null) {

                                    $locationData = [
                                        'organization_id' => !empty($request->post()['organization_id']) ? $request->post()['organization_id'] : \Auth::user()->id,
                                        'premise_id' => $premise->id,
                                        'location_text_id' => $request->post()['location_text_id'],
                                        'minor_id' => $request->post()['minor_id']
                                    ];

                                    $locationValidation = \App\Helpers\Helper::locationValidationData($request, $locationData);


                                    if (!$locationValidation->fails()) {

                                        $locationData['name'] = $locationData['location_text_id'];

                                        unset($locationData['minor_id']);
                                        unset($locationData['location_text_id']);

                                        $location = Location::storeOrUpdateData($locationData);

                                        if ($location)
                                            $request->merge(['location_id' => $location->id]);
                                    }else {
                                        //$locationMsg = $locationValidation->errors()->toArray()['name'][0];
                                        $locationFlag = true;
                                        $data = Helper::getBreadCrumb();
                                        $data['breadCrumData'][1]['text'] = 'Beacon List';
                                        $data['breadCrumData'][1]['url'] = url('admin/beacon');
                                        $data['breadCrumData'][1]['breadFaClass'] = '';
                                        $data['breadCrumData'][2]['text'] = 'Create Beacon';
                                        $data['breadCrumData'][2]['breadFaClass'] = '';
                                        $data['title'] = 'Create Beacon';

                                        $arrSettingData = $this->sitesettings->getAllSettings();
                                        $arrData = [];

                                        foreach ($arrSettingData as $settingdata) {
                                            $arrData[$settingdata->option_name] = $settingdata->option_value;
                                        }

                                        if (!empty($arrData) && is_array($arrData) && !empty($arrData['uuid']) && isset($arrData['uuid'])) {
                                            $data['uuid'] = $arrData['uuid'];
                                        }

                                        $data['adminorganisation'] = Admin::getOrganization();
                                        $data['premiselist'] = Premise::getPremise(!empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id);
                                        $data['locationlist'] = Location::getAllLocationByPremiseId($request->post()['premise_id']);
                                        //$data['roles'] = \Auth::user()->roles->first();
                                        $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);

                                        return view('admin.becon.create')->with($data)->withInput($request->all)->withErrors($locationValidation);
                                    }
                                }
                            }
                        }
                    } else {

                        $data = Helper::getBreadCrumb();
                        $data['breadCrumData'][1]['text'] = 'Beacon List';
                        $data['breadCrumData'][1]['url'] = url('admin/beacon');
                        $data['breadCrumData'][1]['breadFaClass'] = '';
                        $data['breadCrumData'][2]['text'] = 'Create Beacon';
                        $data['breadCrumData'][2]['breadFaClass'] = '';
                        $data['title'] = 'Create Beacon';

                        $arrSettingData = $this->sitesettings->getAllSettings();
                        $arrData = [];

                        foreach ($arrSettingData as $settingdata) {
                            $arrData[$settingdata->option_name] = $settingdata->option_value;
                        }

                        if (!empty($arrData) && is_array($arrData) && !empty($arrData['uuid']) && isset($arrData['uuid'])) {
                            $data['uuid'] = $arrData['uuid'];
                        }

                        $data['adminorganisation'] = Admin::getOrganization();



                        $data['premiselist'] = Premise::getPremise(!empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id);
                        $data['locationlist'] = Location::getAllLocationByPremiseId($request->get('premise_id'));

                        //$data['roles'] = \Auth::user()->roles->first();
                        $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
                        

                        return view('admin.becon.create')->with($data)->withInput($request->all)->withErrors($premiseValidation);
                        //$premiseMsg = $premiseValidation->errors()->toArray()['name'][0];
                        $premiseFlag = true;
                    }
                }
            } else {

                if ($request->post()['location_id'] == null) {

                    if ($request->post()['location_text_id'] != null) {

                        $locationData = [
                            'organization_id' => !empty($request->post()['organization_id']) ? $request->post()['organization_id'] : \Auth::user()->id,
                            'premise_id' => $request->post()['premise_id'],
                            'location_text_id' => $request->post()['location_text_id'],
                            'minor_id' => $request->post()['minor_id']
                        ];

                        $locationValidation = \App\Helpers\Helper::locationValidationData($request, $locationData);
                        //dd($locationValidation->errors());

                        if (!$locationValidation->fails()) {

                            $locationData['name'] = $locationData['location_text_id'];

                            unset($locationData['minor_id']);
                            unset($locationData['location_text_id']);
                            $location = Location::storeOrUpdateData($locationData);

                            if ($location)
                                $request->merge(['location_id' => $location->id]);
                        } else {

                            //$locationMsg = $locationValidation->errors()->toArray()['name'][0];
                            $locationFlag = true;

                            $data = Helper::getBreadCrumb();
                            $data['breadCrumData'][1]['text'] = 'Beacon List';
                            $data['breadCrumData'][1]['url'] = url('admin/beacon');
                            $data['breadCrumData'][1]['breadFaClass'] = '';
                            $data['breadCrumData'][2]['text'] = 'Create Beacon';
                            $data['breadCrumData'][2]['breadFaClass'] = '';
                            $data['title'] = 'Create Beacon';

                            $arrSettingData = $this->sitesettings->getAllSettings();
                            $arrData = [];

                            foreach ($arrSettingData as $settingdata) {
                                $arrData[$settingdata->option_name] = $settingdata->option_value;
                            }

                            if (!empty($arrData) && is_array($arrData) && !empty($arrData['uuid']) && isset($arrData['uuid'])) {
                                $data['uuid'] = $arrData['uuid'];
                            }

                            $data['adminorganisation'] = Admin::getOrganization();
                            $data['premiselist'] = Premise::getPremise(!empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id);
                            $data['locationlist'] = Location::getAllLocationByPremiseId($request->post()['premise_id']);
                            //$data['roles'] = \Auth::user()->roles->first();
                            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);


                            return view('admin.becon.create')->with($data)->withInput($request->all)->withErrors($locationValidation);
                        }
                    }
                }
            }


            $beaconValidation = \App\Helpers\Helper::BeaconValidationData($request);

            if ($beaconValidation->fails() || $locationFlag || $premiseFlag) {

                request()->user()->authorizeRoles($this->allow_roles);
                $data = Helper::getBreadCrumb();
                $data['breadCrumData'][1]['text'] = 'Beacon List';
                $data['breadCrumData'][1]['url'] = url('admin/beacon');
                $data['breadCrumData'][1]['breadFaClass'] = '';
                $data['breadCrumData'][2]['text'] = 'Create Beacon';
                $data['breadCrumData'][2]['breadFaClass'] = '';
                $data['title'] = 'Create Beacon';

                $arrSettingData = $this->sitesettings->getAllSettings();
                $arrData = [];

                foreach ($arrSettingData as $settingdata) {
                    $arrData[$settingdata->option_name] = $settingdata->option_value;
                }

                if (!empty($arrData) && is_array($arrData) && !empty($arrData['uuid']) && isset($arrData['uuid'])) {
                    $data['uuid'] = $arrData['uuid'];
                }

                $data['adminorganisation'] = Admin::getOrganization();
                $data['premiselist'] = Premise::getPremise(!empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id);
                $data['locationlist'] = Location::getAllLocationByPremiseId($request->get('premise_id'));
                //$data['roles'] = \Auth::user()->roles->first();
                $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);

                return view('admin.becon.create')->with($data)->withErrors($beaconValidation);
            }

            $arrData = [];
            $arrData["organization_id"] = !empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id;
            $arrData["premise_id"] = $request->get("premise_id");
            $arrData["location_id"] = $request->get("location_id");
            $arrData["name"] = $request->get("name");
            $arrData["minor_id"] = $request->get("minor_id");
            $arrData["status"] = $request->get("status");
            $arrData["created_by"] = \Auth::user()->id;
            $saveId = Becon::storeOrUpdateData($arrData);

            if (isset($saveId) && !empty($saveId)) {

                $request->session()->flash('alert-success', \Config::get('flash_msg.BeaconAdded'));
                //return redirect(route('beacon.index'));
                $locationFlag = true;

                $data = Helper::getBreadCrumb();
                $data['breadCrumData'][1]['text'] = 'Beacon List';
                $data['breadCrumData'][1]['url'] = url('admin/beacon');
                $data['breadCrumData'][1]['breadFaClass'] = '';
                $data['breadCrumData'][2]['text'] = 'Create Beacon';
                $data['breadCrumData'][2]['breadFaClass'] = '';
                $data['title'] = 'Create Beacon';
                $data['saveId'] = $saveId;

                $arrSettingData = $this->sitesettings->getAllSettings();
                $arrData = [];

                foreach ($arrSettingData as $settingdata) {
                    $arrData[$settingdata->option_name] = $settingdata->option_value;
                }

                if (!empty($arrData) && is_array($arrData) && !empty($arrData['uuid']) && isset($arrData['uuid'])) {
                    $data['uuid'] = $arrData['uuid'];
                }

                $data['adminorganisation'] = Admin::getOrganization();
                $data['premiselist'] = Premise::getPremise(!empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id);
                $data['locationlist'] = Location::getAllLocationByPremiseId($request->post()['premise_id']);
                //$data['roles'] = \Auth::user()->roles->first();
                $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);

                return view('admin.becon.create')->with($data)->withInput($request->all);
            } else {

                //return \Redirect::back()->withInput()->with('alert-danger', \Config::get('flash_msg.BeaconNotAdded'));
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
     * @desc: show edit beacon detail.
     */
    public function edit($id) {
        try {

            $id = \Crypt::decryptString($id);

            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Beacon List';
            $data['breadCrumData'][1]['url'] = url('admin/beacon');
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['breadCrumData'][2]['text'] = 'Edit Beacon';
            $data['breadCrumData'][2]['breadFaClass'] = '';
            $data['title'] = 'Edit Beacon';

            $arrSettingData = $this->sitesettings->getAllSettings();

            $arrData = [];

            foreach ($arrSettingData as $settingdata) {
                $arrData[$settingdata->option_name] = $settingdata->option_value;
            }

            if (!empty($arrData) && is_array($arrData) && !empty($arrData['uuid']) && isset($arrData['uuid'])) {
                $data['uuid'] = $arrData['uuid'];
            }

            $beconDetail = Becon::getRecordByID($id);

            $data['adminorganisation'] = Admin::getOrganization();
            $data['premiselist'] = Premise::getPremise($beconDetail->organization_id);
            $data['locationlist'] = Location::getAllLocationByPremiseId($beconDetail->premise_id);

            $data['becaonId'] = Admin::getOrganization($beconDetail->organization_id);
            $data['objData'] = $beconDetail;
            //$data['roles'] = \Auth::user()->roles->first();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
            $data['site_setting'] = $this->helper->getAllSettings();


            return view('admin.becon.edit')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: update
     * @param BannerRequest $request
     * @param $id
     * @desc: update beacon detail.
     */
    public function update(Request $request, $id) {

        try {

            request()->user()->authorizeRoles($this->allow_roles);

            $locationFlag = $premiseFlag = false;
            $locationMsg = $premiseMsg = null;

            if ($request->post()['premise_id'] == null) {

                if ($request->post()['premise_text_id'] != null) {

                    $premiseData = [
                        'organization_id' => !empty($request->post()['organization_id']) ? $request->post()['organization_id'] : \Auth::user()->id,
                        'premise_text_id' => $request->post()['premise_text_id'],
                        'minor_id' => $request->post()['minor_id']
                    ];

                    $premiseValidation = \App\Helpers\Helper::premiseValidationData($request, $premiseData);
                    //dd($premiseValidation->errors());

                    if (!$premiseValidation->fails()) {

                        $premiseData['name'] = $premiseData['premise_text_id'];
                        unset($premiseData['premise_text_id']);
                        unset($premiseData['minor_id']);

                        //$premise = Premise::storeOrUpdateData($premiseData, $request->get('premise_edit_id'));
                        $premise = Premise::storeOrUpdateData($premiseData);

                        if ($premise) {
                            $request->merge(['premise_id' => $premise->id]);
                            if ($request->post()['location_id'] == null) {

                                if ($request->post()['location_text_id'] != null) {

                                    $locationData = [
                                        'organization_id' => !empty($request->post()['organization_id']) ? $request->post()['organization_id'] : \Auth::user()->id,
                                        'premise_id' => $premise->id,
                                        'location_text_id' => $request->post()['location_text_id'],
                                        'minor_id' => $request->post()['minor_id']
                                    ];

                                    $locationValidation = \App\Helpers\Helper::locationValidationData($request, $locationData);

                                    if (!$locationValidation->fails()) {

                                        $locationData['name'] = $locationData['location_text_id'];
                                        unset($locationData['minor_id']);
                                        unset($locationData['location_text_id']);

                                        $location = Location::storeOrUpdateData($locationData, $request->get('location_edit_id'));

                                        if ($location)
                                            $request->merge(['location_id' => $location->id]);
                                    }else {

                                        //$locationMsg = $locationValidation->errors()->toArray()['name'][0];
                                        $locationFlag = true;
                                        request()->user()->authorizeRoles($this->allow_roles);
                                        $data = Helper::getBreadCrumb();
                                        $data['breadCrumData'][1]['text'] = 'Beacon List';
                                        $data['breadCrumData'][1]['url'] = url('admin/beacon');
                                        $data['breadCrumData'][1]['breadFaClass'] = '';
                                        $data['breadCrumData'][2]['text'] = 'Edit Beacon';
                                        $data['breadCrumData'][2]['breadFaClass'] = '';
                                        $data['title'] = 'Edit Beacon';

                                        $arrSettingData = $this->sitesettings->getAllSettings();

                                        $arrData = [];

                                        foreach ($arrSettingData as $settingdata) {
                                            $arrData[$settingdata->option_name] = $settingdata->option_value;
                                        }

                                        if (!empty($arrData) && is_array($arrData) && !empty($arrData['uuid']) && isset($arrData['uuid'])) {
                                            $data['uuid'] = $arrData['uuid'];
                                        }

                                        $beconDetail = Becon::getRecordByID($id);

                                        $data['adminorganisation'] = Admin::getOrganization();
                                        $data['premiselist'] = Premise::getPremise($beconDetail->organization_id);
                                        $data['locationlist'] = Location::getAllLocationByPremiseId($beconDetail->premise_id);

                                        $data['becaonId'] = Admin::getOrganization($beconDetail->organization_id);
                                        $data['objData'] = $beconDetail;
                                        //$data['roles'] = \Auth::user()->roles->first();
                                        $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);

                                        return view('admin.becon.edit')->with($data)->withInput($request->all)->withErrors($locationValidation);
                                    }
                                }
                            }
                        }
                    } else {

                        request()->user()->authorizeRoles($this->allow_roles);
                        $data = Helper::getBreadCrumb();
                        $data['breadCrumData'][1]['text'] = 'Beacon List';
                        $data['breadCrumData'][1]['url'] = url('admin/beacon');
                        $data['breadCrumData'][1]['breadFaClass'] = '';
                        $data['breadCrumData'][2]['text'] = 'Edit Beacon';
                        $data['breadCrumData'][2]['breadFaClass'] = '';
                        $data['title'] = 'Edit Beacon';

                        $arrSettingData = $this->sitesettings->getAllSettings();

                        $arrData = [];

                        foreach ($arrSettingData as $settingdata) {
                            $arrData[$settingdata->option_name] = $settingdata->option_value;
                        }

                        if (!empty($arrData) && is_array($arrData) && !empty($arrData['uuid']) && isset($arrData['uuid'])) {
                            $data['uuid'] = $arrData['uuid'];
                        }

                        $beconDetail = Becon::getRecordByID($id);

                        $data['adminorganisation'] = Admin::getOrganization();
                        $data['premiselist'] = Premise::getPremise($beconDetail->organization_id);
                        $data['locationlist'] = Location::getAllLocationByPremiseId($beconDetail->premise_id);

                        $data['becaonId'] = Admin::getOrganization($beconDetail->organization_id);
                        $data['objData'] = $beconDetail;
                        //$data['roles'] = \Auth::user()->roles->first();
                        $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);

                        return view('admin.becon.edit')->with($data)->withInput($request->all)->withErrors($premiseValidation);
                    }
                }
            } else {


                if ($request->post()['location_id'] == null) {

                    if ($request->post()['location_text_id'] != null) {

                        $locationData = [
                            'organization_id' => !empty($request->post()['organization_id']) ? $request->post()['organization_id'] : \Auth::user()->id,
                            'premise_id' => $request->post()['premise_id'],
                            'location_text_id' => $request->post()['location_text_id'],
                            'minor_id' => $request->post()['minor_id']
                        ];

                        $locationValidation = \App\Helpers\Helper::locationValidationData($request, $locationData);

                        //dd($locationValidation->errors());

                        if (!$locationValidation->fails()) {

                            $locationData['name'] = $locationData['location_text_id'];

                            unset($locationData['minor_id']);
                            unset($locationData['location_text_id']);

                            //$location = Location::storeOrUpdateData($locationData, $request->get('location_edit_id'));
                            $location = Location::storeOrUpdateData($locationData);
                            if ($location)
                                $request->merge(['location_id' => $location->id]);
                        } else {

                            request()->user()->authorizeRoles($this->allow_roles);
                            $data = Helper::getBreadCrumb();
                            $data['breadCrumData'][1]['text'] = 'Beacon List';
                            $data['breadCrumData'][1]['url'] = url('admin/beacon');
                            $data['breadCrumData'][1]['breadFaClass'] = '';
                            $data['breadCrumData'][2]['text'] = 'Edit Beacon';
                            $data['breadCrumData'][2]['breadFaClass'] = '';
                            $data['title'] = 'Edit Beacon';

                            $arrSettingData = $this->sitesettings->getAllSettings();

                            $arrData = [];

                            foreach ($arrSettingData as $settingdata) {
                                $arrData[$settingdata->option_name] = $settingdata->option_value;
                            }

                            if (!empty($arrData) && is_array($arrData) && !empty($arrData['uuid']) && isset($arrData['uuid'])) {
                                $data['uuid'] = $arrData['uuid'];
                            }

                            $beconDetail = Becon::getRecordByID($id);

                            $data['adminorganisation'] = Admin::getOrganization();
                            $data['premiselist'] = Premise::getPremise($beconDetail->organization_id);
                            $data['locationlist'] = Location::getAllLocationByPremiseId($beconDetail->premise_id);

                            $data['becaonId'] = Admin::getOrganization($beconDetail->organization_id);
                            $data['objData'] = $beconDetail;
                            //$data['roles'] = \Auth::user()->roles->first();
                            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);

                            return view('admin.becon.edit')->with($data)->withInput($request->all)->withErrors($locationValidation);
                        }
                    }
                }
            }

            $beaconValidation = \App\Helpers\Helper::BeaconValidationData($request);

            //dd($beaconValidation->errors());

            if ($beaconValidation->fails() || $locationFlag || $premiseFlag) {

                request()->user()->authorizeRoles($this->allow_roles);
                $data = Helper::getBreadCrumb();
                $data['breadCrumData'][1]['text'] = 'Beacon List';
                $data['breadCrumData'][1]['url'] = url('admin/beacon');
                $data['breadCrumData'][1]['breadFaClass'] = '';
                $data['breadCrumData'][2]['text'] = 'Edit Beacon';
                $data['breadCrumData'][2]['breadFaClass'] = '';
                $data['title'] = 'Edit Beacon';

                $arrSettingData = $this->sitesettings->getAllSettings();

                $arrData = [];

                foreach ($arrSettingData as $settingdata) {
                    $arrData[$settingdata->option_name] = $settingdata->option_value;
                }

                if (!empty($arrData) && is_array($arrData) && !empty($arrData['uuid']) && isset($arrData['uuid'])) {
                    $data['uuid'] = $arrData['uuid'];
                }

                $beconDetail = Becon::getRecordByID($id);

                $data['adminorganisation'] = Admin::getOrganization();
                $data['premiselist'] = Premise::getPremise(!empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id);
                $data['locationlist'] = Location::getAllLocationByPremiseId($request->get('premise_id'));
                $data['becaonId'] = Admin::getOrganization(!empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id);
                $data['objData'] = $beconDetail;
                //$data['roles'] = \Auth::user()->roles->first();
                $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);

                return view('admin.becon.edit')->with($data)->withInput($request->all)->withErrors($beaconValidation);
            }

            $arrData = [];
            $arrData["organization_id"] = !empty($request->get("organization_id")) ? $request->get("organization_id") : \Auth::user()->id;
            $arrData["premise_id"] = $request->get("premise_id");
            $arrData["location_id"] = $request->get("location_id");
            $arrData["name"] = $request->get("name");
            $arrData["minor_id"] = $request->get("minor_id");
            $arrData["status"] = $request->get("status");
            $arrData["updated_by"] = \Auth::user()->id;
            Becon::storeOrUpdateData($arrData, $id);

            $request->session()->flash('alert-success', \Config::get('flash_msg.BeaconUpdated'));
            //return redirect(route('beacon.index'));
            //return redirect(route('beacon.create'));

            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Beacon List';
            $data['breadCrumData'][1]['url'] = url('admin/beacon');
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['breadCrumData'][2]['text'] = 'Edit Beacon';
            $data['breadCrumData'][2]['breadFaClass'] = '';
            $data['title'] = 'Edit Beacon';

            $arrSettingData = $this->sitesettings->getAllSettings();

            $arrData = [];

            foreach ($arrSettingData as $settingdata) {
                $arrData[$settingdata->option_name] = $settingdata->option_value;
            }

            if (!empty($arrData) && is_array($arrData) && !empty($arrData['uuid']) && isset($arrData['uuid'])) {
                $data['uuid'] = $arrData['uuid'];
            }

            $beconDetail = Becon::getRecordByID($id);
            $data['adminorganisation'] = Admin::getOrganization();
            $data['premiselist'] = Premise::getPremise($beconDetail->organization_id);
            $data['locationlist'] = Location::getAllLocationByPremiseId($beconDetail->premise_id);

            $data['becaonId'] = Admin::getOrganization($beconDetail->organization_id);
            $data['objData'] = $beconDetail;
            //$data['roles'] = \Auth::user()->roles->first();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
            $data['saveId'] = $beconDetail;

            return view('admin.becon.edit')->with($data)->withInput($request->all);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: destroy
     * @param Request $request
     * @param $id
     * @desc: Delete beacon.
     */
    public function destroy(Request $request, $id) {
        try {

            $id = \Crypt::decryptString($id);

            request()->user()->authorizeRoles($this->allow_roles);
            $varData = Becon::deleteRecord($id);

            if ($varData) {
                $request->session()->flash('alert-success', \Config::get('flash_msg.BeaconDeleted'));
                return redirect()->back();
            } else {
                $request->session()->flash('alert-danger', \Config::get('flash_msg.BeaconNotDeleted'));
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

            $checkRecord = Becon::find($decryptId);

            if (!empty($checkRecord) && is_object($checkRecord) && count($checkRecord) > 0) {

                $recordSts = Becon::storeOrUpdateData($requestUser, $decryptId);

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
