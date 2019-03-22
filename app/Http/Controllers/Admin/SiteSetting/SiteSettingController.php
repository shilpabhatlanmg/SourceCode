<?php

namespace App\Http\Controllers\Admin\SiteSetting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Admin\SiteSetting\SiteSetting;
use App\Http\Requests\Admin\SiteSettingRequest;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use Auth;
use App\Helpers\Helper;

class SiteSettingController extends Controller {

    protected $sitesettings;
    protected $country;
    protected $state;
    protected $city;
    protected $allow_roles = [];

    
    /**
     * @author Sandeep Kumar
     * @function: __construct
     * @desc: check user is valid or not and store the roles in allow roles property.
     */
    public function __construct() {
        $this->middleware('auth:admin');
        $this->sitesettings = new SiteSetting();
        $this->country = new Country();
        $this->state = new State();
        $this->city = new City();
        $this->helper = new Helper();
        $this->allow_roles = array(\Config::get('constants.PLATFORM_ADMIN'));
    }

    /**
     * @author Sandeep Kumar
     * @function: index
     * @desc: Display a listing of site setting.
     */
    public function index() {
        try {
            request()->user()->authorizeRoles($this->allow_roles);
            $arrSettingData = $this->sitesettings->getAllSettings();
            $arrData = [];
            $arrCountry = [];
            $arrState = [];
            $arrCityData = [];
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Site Setting';
            $data['breadCrumData'][1]['breadFaClass'] = '';

            $data['title'] = 'Site Setting';
            $data['arrCountry'] = $this->country->getAllCountry();
            $data['arrState'] = $this->state->getAllState();
            if (isset($arrSettingData) && !empty($arrSettingData) && is_object($arrSettingData) && count($arrSettingData) > 0) {

                foreach ($arrSettingData as $settingdata) {
                    $arrData[$settingdata->option_name] = $settingdata->option_value;
                }

                $objData = [];
                if (isset($arrData) && !empty($arrData) && is_array($arrData)) {
                    $data['objData'] = (object) $arrData;
                }

                $data['arrCityData'] = $this->city->getAllCityByStateID($data['objData']->state_id);
            }

            $data['site_setting'] = $this->helper->getAllSettings();
            return view('admin.sitesetting.sitesetting')
                            ->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: saveSettings
     * @param SiteSettingRequest $request
     * @desc: Save site setting.
     */
    public function saveSettings(SiteSettingRequest $request) {
        try {
            request()->user()->authorizeRoles($this->allow_roles);
            if ($request->get("site_offline")) {
                $site_offline = '0';
            } else {
                $site_offline = '';
            }
            $arrSettingData = [];
            /* General Settings */
            $arrSettingData["site_offline"] = $site_offline;
            $arrSettingData["site_offline_message"] = $request->get("site_offline_message");
            $arrSettingData["uuid"] = $request->get("uuid");

            /* Stripe API Settings */
            $arrSettingData["stripe_pk"] = $request->get("stripe_pk");
            $arrSettingData["stripe_sk"] = $request->get("stripe_sk");

            /* Currency Settings */
            //$arrSettingData["currency"] = $request->get("currency");

            /* Address Line1 */
            $arrSettingData["street"] = $request->get("street");
            $arrSettingData["country_id"] = $request->get("country_id");
            $arrSettingData["state_id"] = $request->get("state_id");
            $arrSettingData["city_id"] = $request->get("city_id");
            $arrSettingData["address_zip"] = $request->get("address_zip");
            $arrSettingData["address_phone"] = $request->get("address_phone");
            $arrSettingData["address_email"] = $request->get("address_email");

            /* Site Settings */
            $arrSettingData["admin_email"] = $request->get("admin_email");
            $arrSettingData["from_email"] = $request->get("from_email");
            $arrSettingData["from_name"] = $request->get("from_name");
            $arrSettingData["copyright_content"] = $request->get("copyright_content");

            /* Social Settings */
            $arrSettingData["facebook_link"] = $request->get("facebook_link");
            $arrSettingData["twitter_link"] = $request->get("twitter_link");
            $arrSettingData["linked_in"] = $request->get("linked_in");
            $arrSettingData["google_link"] = $request->get("google_link");
            $arrSettingData["behance_link"] = $request->get("behance_link");
            $arrSettingData["dribbble_link"] = $request->get("dribbble_link");

            if (!empty($request->file('site_logo'))) {

                $site_logo = $request->get('site_logo_image');
                $varFile = $request->file('site_logo');
                $varFileName = $varFile->getClientOriginalName();
                $varFileEncName = str_replace(' ', '_', $varFileName);
                $path = 'storage/site_logo/' . $site_logo;

                if (!empty($site_logo)) {
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                $varFile->storeAs('public/site_logo', $varFileEncName);
                $this->sitesettings->updateSettings('site_logo', $varFileEncName);
            }

            /* Upload site favicon */

            if (!empty($request->file('fevicon'))) {
                $site_fav = $request->get('favicon_image');
                $varFile = $request->file('fevicon');
                $varFileName = $varFile->getClientOriginalName();
                $varFileEncName = str_replace(' ', '_', $varFileName);
                $path = 'storage/fevicon/' . $site_fav;
                if (!empty($site_fav)) {
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                $varFile->storeAs('public/fevicon', $varFileEncName);
                $this->sitesettings->updateSettings('fevicon', $varFileEncName);
            }

            /* Upload footer logo */
            if (!empty($request->file('footer_logo'))) {
                $site_footer_img = $request->get('site_footer_image');
                $varFile = $request->file('footer_logo');
                $varFileName = $varFile->getClientOriginalName();
                $varFileEncName = str_replace(' ', '_', $varFileName);
                $path = 'storage/footer_logo/' . $site_footer_img;
                if (!empty($site_footer_img)) {
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                $varFile->storeAs('public/footer_logo', $varFileEncName);
                $this->sitesettings->updateSettings('footer_logo', $varFileEncName);
            }

            foreach ($arrSettingData as $k => $setting) {
                $this->sitesettings->updateSettings($k, $setting);
            }

            $request->session()->flash('alert-success', \Config::get('flash_msg.SiteSettingUpdated'));

            return redirect(route('site.setting'));
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

}
