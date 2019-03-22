<?php

namespace App\Http\Controllers\Admin\AidRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Admin\AidRequest;
use App\Model\AidRequestResponse as AidRequestResponse;
use App\Helpers\Helper;
use App\Admin;
use App\User;
use App\Model\Admin\Incident\IncidentType;

class AidRequestController extends Controller {

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
     * @desc: Display a listing of aid request.
     */
    public function index() {
        try {

            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Aid Request List';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['title'] = 'Aid Request List';
            $data['aidRequestList'] = AidRequest::getAidRequest();
            //$data['roles'] = \Auth::user()->roles->first();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
            $data['site_setting'] = $this->helper->getAllSettings();
            $data['adminorganisation'] = Admin::getOrganization();
            $data['incidenttype'] = IncidentType::getIncidentType();
            return view('admin/aid-request/aid-request-list')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: respondedList
     * @param $id
     * @param $organization_id
     * @desc: Display a listing of All Request Response.
     */
    public function respondedList($id, $organization_id) {
        try {

            $id = \Crypt::decryptString($id);
            $orgId = \Crypt::decryptString($organization_id);


            request()->user()->authorizeRoles($this->allow_roles);

            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Aid Request List';
            $data['breadCrumData'][1]['url'] = url('admin/aid-request');
            $data['breadCrumData'][1]['breadFaClass'] = '';

            $data['breadCrumData'][2]['text'] = 'View Attendee List';
            $data['breadCrumData'][2]['breadFaClass'] = '';

            $data['site_setting'] = $this->helper->getAllSettings();

            $userId = User::getUserByOrgId($orgId);
            $orgDetail = Admin::getOrganization($orgId);

            $allUserId = [];
            if(!empty($userId) && is_object($userId) && count($userId) > 0){

                foreach($userId as $val){

                    $allUserId[] = $val->id;

            }

            }

            $data['timezone'] = !empty($orgDetail->timezone) ? $orgDetail->timezone : '';
            

            $data['title'] = 'View Attendee List';
             $data['objData'] = AidRequestResponse::getRecordByID($id, $allUserId);
            return view('admin/aid-request/respond-lists')
                            ->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

}
