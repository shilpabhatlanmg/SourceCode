<?php

namespace App\Http\Controllers\Admin\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PersonalInformationRequest;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Admin;
use Auth;
use Hash;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\StaticPage;
use App\Model\Admin\PocDetail;
use App\Helpers\Helper;

class ProfileController extends Controller {

    use \App\Traits\Admin\PersonalInformation;

    protected $country;
    protected $state;
    protected $city;


    /**
     * @author Sandeep Kumar
     * @function: __construct
     * @desc: check user is valid or not and store the roles in allow roles property.
     */
    public function __construct() {
        $this->middleware('auth:admin');
        $this->allow_roles = array(\Config::get('constants.PLATFORM_ADMIN'), \Config::get('constants.ORGANIZATION_ADMIN'));
        $this->country = new Country();
        $this->state = new State();
        $this->city = new City();
        $this->helper = new Helper();
    }

    /**
     * @author Sandeep Kumar
     * @function: index
     * @desc: Display a profile information.
     */
    public function index() {

        request()->user()->authorizeRoles($this->allow_roles);

        $data = Helper::getBreadCrumb();
        $data['breadCrumData'][1]['text'] = 'View Profile';
        $data['breadCrumData'][1]['breadFaClass'] = '';

        //$data['roles'] = \Auth::user()->roles->first();
        $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);

        $data['title'] = 'User Profile';
        $data['arr_poc_detail'] = PocDetail::getPocById(Auth::user()->id);
        $data['site_setting'] = $this->helper->getAllSettings();
        return view('admin.user-profile')->with($data);
        ;
    }

    /**
     * @author Sandeep Kumar
     * @function: updateProfile
     * @desc: edit profile.
     */
    public function updateProfile() {
        request()->user()->authorizeRoles($this->allow_roles);

        $arrCountry = [];
        $arrState = [];
        $arrCityData = [];
        $data = Helper::getBreadCrumb();
        $data['breadCrumData'][1]['text'] = 'View Profile';
        $data['breadCrumData'][1]['url'] = url('admin/view-profile');
        $data['breadCrumData'][1]['breadFaClass'] = '';
        $data['breadCrumData'][2]['text'] = 'Edit Profile';
        $data['breadCrumData'][2]['breadFaClass'] = '';

        $data['title'] = 'Edit Profile';
        $data['arrCountry'] = $this->country->getAllCountry();
        $data['arrState'] = $this->state->getAllState();
        $data['arr_poc_detail'] = PocDetail::getPocById(Auth::user()->id);
        $data['arrCityData'] = $this->city->getAllCityByStateID(Auth::guard('admin')->user()->state_id);

        //$data['roles'] = \Auth::user()->roles->first();
        $data['roles'] = \commonHelper::getRoleName(\Auth::user()->id);
        $data['site_setting'] = $this->helper->getAllSettings();

        return view('admin.edit-profile')->with($data);
    }

    /**
     * @author Sandeep Kumar
     * @function: updatePersonalInformation
     * @param PersonalInformationRequest $request
     * @desc: update profile information.
     */
    public function updatePersonalInformation(PersonalInformationRequest $request) {
        try {

            request()->user()->authorizeRoles($this->allow_roles);

            $requestVar = $request->all();
            $requestUserTrait = $this->createPersonalInformationRequest($requestVar);

            /* $inputFile = $request->file('user_image');

              if (!empty($inputFile)) {
              $filename = Auth::user()->id . "_" . $inputFile->getClientOriginalName();
              $path = 'storage/Admin/profile_image/' . Auth::user()->user_image;
              if (!empty(Auth::user()->user_image)) {
              if (file_exists($path)) {
              unlink($path);
              }
              }

              $inputFile->storeAs('public/admin/profile_image', $filename);
              $requestUserTrait['user_image'] = $filename;
              } */

            $user_id = Admin::storeOrUpdateData($requestUserTrait, Auth::user()->id);
            if (isset($user_id) && !empty($user_id)) {


                /////////////Update Record for POC Details /////////////////

                if (is_array($request->poc_name) && !empty($request->poc_name)) {

                    foreach ($request->poc_name as $key => $val) {


                        if (!empty($request->poc_id[$key]) && isset($request->poc_id[$key])) {


                            $poc_detail = PocDetail::find($request->poc_id[$key]);
                            $poc_detail->organization_id = Auth::user()->id;
                            $poc_detail->poc_name = $request->poc_name[$key];
                            $poc_detail->poc_contact_no = $request->poc_contact_no[$key];
                            $poc_detail->poc_email = $request->poc_email[$key];


                            if ($poc_detail->save()) {
                                $flg = '1';
                            }
                        } else {

                            $poc_detail = new PocDetail;
                            $poc_detail->organization_id = Auth::user()->id;
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

                    $request->session()->flash('alert-success', \Config::get('flash_msg.DetailUpdated'));
                }
            } else {
                $request->session()->flash('alert-danger', \Config::get('flash_msg.DetailNotUpdated'));
            }

            return redirect(route('admin.profile'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage() . " In " . $e->getFile() . " At Line " . $e->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: userPassword
     * @desc: edit user password.
     */
    public function userPassword() {
        request()->user()->authorizeRoles($this->allow_roles);
        $data = Helper::getBreadCrumb();
        $data['breadCrumData'][1]['text'] = 'Change Password';
        $data['breadCrumData'][1]['breadFaClass'] = '';
        $data['title'] = 'Change Password';
        $data['site_setting'] = $this->helper->getAllSettings();
        return view('admin.change-password')->with($data);
    }

    /**
     * @author Sandeep Kumar
     * @function: changePassword
     * @param ChangePasswordRequest $request
     * @desc: update user password.
     */
    public function changePassword(ChangePasswordRequest $request) {
        try {

            request()->user()->authorizeRoles($this->allow_roles);

            if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
                // The passwords matches
                //return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");

                $request->session()->flash('alert-danger', 'Your current password does not matches with the password you provided. Please try again.');
                return redirect(route('admin.change.password'));
            }
            if (strcmp($request->get('current_password'), $request->get('password')) == 0) {
                //Current password and new password are same
                //return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");

                $request->session()->flash('alert-danger', 'New Password cannot be same as your current password. Please choose a different password.');
                return redirect(route('admin.change.password'));
            }
            //Change Password
            $user = Auth::user();
            $user->password = bcrypt($request->get('password'));
            $user->save();
            $request->session()->flash('alert-success', \Config::get('flash_msg.PasswordChanged'));
            return redirect(route('admin.change.password'));
        } catch (Exception $ex) {
            
        }
    }

}
