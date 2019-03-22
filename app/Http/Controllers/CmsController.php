<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Admin\Cms\StaticPage;
use App\Http\Requests\ContactUsRequest;
use App\Model\Admin\Contact;
use App\Helpers\Helper;
//use Event;
use Mail;

class CmsController extends Controller {

    protected $contact;
    protected $site_setting;

   
    public function __construct() {
        //$this->middleware('auth');
        $this->contact = new Contact();
        $this->site_setting = Helper::getAllSettings();
    }

    /**
     * @author Sandeep Kumar
     * @function: cmsPage
     * @param $slug
     * @desc: contact us page form.
     */
    public function contactUs() {
        return view('cms.contact-us')->with('site_setting', $this->site_setting);
    }

    /**
     * @author Sandeep Kumar
     * @function: cmsPage
     * @param $slug
     * @desc: display the page according to slug.
     */
    public function cmsPage($slug) {
        $arrPageData = $this->cms->getPageBySlug($slug);
        return view('cms.cms-page')->with('arrPageData', $arrPageData);
    }

    /**
     * @author Sandeep Kumar
     * @function: saveContactUs
     * @param ContactUsRequest $request
     * @desc: save contact us detail.
     */
    public function saveContactUs(ContactUsRequest $request) {

        try {

            $arrContact = [];
            $arrContact["name"] = $request->get("name");
            $arrContact["email"] = $request->get("email");
            $arrContact["phone"] = $request->get("phone");
            $arrContact['comment'] = $request->get("comment");

            $dataSave = Contact::storeData($arrContact);

            if ($dataSave) {

                /* Send mail to User */
                /* $eventData['to'] = $arrContact["email"];
                  $eventData['variable']['{name}'] = $arrContact["name"];
                  $eventData['template_code'] = 'SB002';
                  Event::fire('sendEmailByTemplate', collect($eventData)); */

                Mail::send('emails.contact', [
                    'name' => $arrContact["name"],
                        ], function ($message) use ($arrContact) {
                    $message->to($arrContact["email"])->subject('Contact Us');
                });

                /* send mail to admin */
                /* $eventData['to'] = $arrContact["email"];
                  $eventData['variable']['{name}'] = $arrContact["name"];
                  $eventData['variable']['{email}'] = $arrContact["email"];
                  $eventData['variable']['{phone}'] = $arrContact["phone"];
                  $eventData['variable']['{comment}'] = $arrContact["comment"];
                  $eventData['template_code'] = 'SB003';
                  Event::fire('sendEmailByTemplate', collect($eventData)); */
                Mail::send('emails.admin.contact-us', [
                    'name' => $arrContact["name"],
                    'email' => $arrContact["email"],
                    'phone' => $arrContact["phone"],
                    'comment' => $arrContact["comment"],
                        ], function ($message) use ($arrContact) {
                    $message->to($this->site_setting->admin_email)->subject('Contact Us ');
                });
                $request->session()->flash('alert-success', 'Your message successfully submitted. We will contact you soon...');
                return redirect()->back();
            }
        } catch (Exception $e) {
            // something went wrong
            $response = [];
            $response['error_msg'] = array($e->getMessage() . " In " . $e->getFile() . " At Line " . $e->getLine());
            return redirect()->back()->with(['err_contact_query_catch' => [$response], 'all_data' => [$data]]);
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: aboutUs
     * @desc: display aboutUs page.
     */
    public function aboutUs() {
        $arrData = StaticPage::getPageBySlug('about-us');
        return view('cms.about-us')
                        ->with('site_setting', $this->site_setting)
                        ->with('arrData', $arrData);
    }

    /**
     * @author Sandeep Kumar
     * @function: faq
     * @desc: display faq page.
     */
    public function faq() {
        $data['arrData'] = StaticPage::getPageBySlug('faq');
        $data['site_setting'] = $this->site_setting;
        $data['title'] = "Frequently Asked Questions";
        return view('cms.faq')->with($data);
    }

    /**
     * @author Sandeep Kumar
     * @function: termCondition
     * @desc: display terms and condition page.
     */
    public function termCondition() {
        $arrData = StaticPage::getPageBySlug('terms-conditions');
        return view('cms.terms-conditions')
                        ->with('site_setting', $this->site_setting)
                        ->with('arrData', $arrData);
    }

    /**
     * @author Sandeep Kumar
     * @function: privacyPolicy
     * @desc: display privacy policy page.
     */
    public function privacyPolicy() {
        $arrData = StaticPage::getPageBySlug('privacy-policy');
        return view('cms.privacy-policy')
                        ->with('site_setting', $this->site_setting)
                        ->with('arrData', $arrData);
    }

}
