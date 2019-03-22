<?php

namespace App\Http\Controllers\Admin\Visitorlog;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Admin\VisitorLog;
use App\Admin;
use App\Http\Requests\Admin\VisitorlogRequest;
use App\Helpers\Helper;

class VisitorlogController extends Controller {

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
     * @desc: Display a listing of visitor users.
     */
    public function index() {

        try {
            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Visitor Log List';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['title'] = 'Visitor Log List';
            $data['arrData'] = VisitorLog::getVisiorLog();
            //$data['roles'] = \Auth::user()->roles->first();
            $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
            $data['adminorganisation'] = Admin::getOrganization();
            $data['site_setting'] = $this->helper->getAllSettings();
            return view('admin.visitorlogs.visitorlog-list')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

}