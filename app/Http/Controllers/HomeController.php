<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Admin\SiteSetting\SiteSetting;
use App\Model\Admin\Cms\DynamicContent;
use App\Model\Admin\Contact;
use App\Helpers\Helper;
use Event;

class HomeController extends Controller {

    use \App\Traits\ContactUsInformation;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //$this->middleware('auth');
        $this->sitesettings = new SiteSetting();
        $this->dynamicContent = new DynamicContent();
        $this->helper = new Helper();
    }

    /**
     * @author Sandeep Kumar
     * @function: index
     * @param Request $request
     * @desc: display home page content.
     */
    public function index(Request $request) {


        $data['title'] = 'Home';
        $data['site_setting'] = $this->helper->getAllSettings();
        $data['testimonials'] = $this->helper->getActiveTestimonials();

        $data['banner_content'] = $this->getContent('home-page-banner');
        $data['business'] = $this->getContent('business');

        $data['congregations'] = $this->getContent('congregations');
        $data['schools'] = $this->getContent('schools');

        $data['better_security_coverage'] = $this->getContent('better-security-coverage');

        $data['contact_us'] = $this->getContent('contact-us-content');
        $data['feature'] = $this->getContent('feature');

        $data['app_screen'] = $this->getContent('app-screen');

        $data['professional_responder'] = $this->getContent('professional-responder');
        $data['design_group'] = $this->getContent('design-group');

        $adminEmail = $this->helper->getAllSettingsByOption('admin_email');

        $requestContactTrait = $this->createContactInformationRequest($request);

        if ($request->ajax()) {


            $savedContact = Contact::storeOrUpdateData($requestContactTrait);

            if (!empty($savedContact) && is_object($savedContact)) {

                $eventData['to'] = $adminEmail->option_value;
                $eventData['variable']['{name}'] = $request->firstname . ' ' . $request->lastname;
                $eventData['variable']['{email}'] = $request->email;
                $eventData['variable']['{comment}'] = $request->comment;
                $eventData['template_code'] = 'SB002';

                Event::fire('sendEmailByTemplate', collect($eventData));

                return response()->json(['success' => true, 'msg' => 'Thank you we will contact you soon'], 200);
            } else {

                return response()->json(['errors' => ['due to some error']], 422);
            }
        }

        return view('front.index')->with($data);
    }

    /**
     * @author Sandeep Kumar
     * @function: getContent
     * @desc: get dynamic content.
     */
    public function getContent($slug) {

        if (\Cache::has($slug)) {

            return \Cache::get($slug);
        } else {

            return $this->dynamicContent->getContentBySlug($slug);
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: faq
     * @desc: display faq page.
     */
    public function faq() {
        try {
            $data['title'] = 'Faq';
            $data['site_setting'] = $this->helper->getAllSettings();
            return view('front.work')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: SiteOffline
     * @desc: site is offline if set from admin.
     */
    public function SiteOffline() {
        $arrSettingData = $this->sitesettings->getAllSettings();
        $arrData = [];
        $data['title'] = 'Site Offline';
        foreach ($arrSettingData as $settingdata) {
            $arrData[$settingdata->option_name] = $settingdata->option_value;
        }
        if ($arrData['site_offline'] == "0") {
            return view('site-offline')->with('offline_message', $arrData['site_offline_message']);
        } else {
            return redirect(route('welcome'));
        }
    }

}
