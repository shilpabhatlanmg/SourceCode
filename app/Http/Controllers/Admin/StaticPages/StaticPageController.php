<?php

namespace App\Http\Controllers\Admin\StaticPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Admin\Cms\StaticPage;
use App\Http\Requests\Admin\Cms\StaticPageRequest;
use App\Helpers\Helper;

class StaticPageController extends Controller {

    protected $allow_roles = [];

    /**
     * @author Sandeep Kumar
     * @function: __construct
     * @desc: check user is valid or not and store the roles in allow roles property.
     */
    public function __construct(Request $request) {

        $this->middleware('auth:admin');
        $this->allow_roles = array(\Config::get('constants.PLATFORM_ADMIN'), \Config::get('constants.SUB_ADMIN'));
        $this->helper = new Helper();
    }

    /**
     * @author Sandeep Kumar
     * @function: index
     * @desc: Display a listing of static pages.
     */
    public function index() {
        try {
            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Page Management';
            $data['breadCrumData'][1]['breadFaClass'] = '';

            $data['title'] = 'Static Page List';

            $data['arrData'] = StaticPage::getAllStaticPages();
            $data['site_setting'] = $this->helper->getAllSettings();
            return view('admin/static-pages/index')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: editStaticPage
     * @param $slug
     * @desc: edit static pages.
     */
    public function editStaticPage($slug) {
        try {

            request()->user()->authorizeRoles($this->allow_roles);

            switch ($slug) {

                case 'faq':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Page Management';
                    $data['breadCrumData'][1]['url'] = url('admin/static-page');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Faq';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Faq';
                    $data['slug'] = $slug;

                    $data['arrPageData'] = StaticPage::getPageBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();

                    return view('admin/static-pages/faq')->with($data);
                    break;

                case 'guide-user':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Page Management';
                    $data['breadCrumData'][1]['url'] = url('admin/static-page');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Guide User';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Guide User';
                    $data['slug'] = $slug;

                    $data['arrPageData'] = StaticPage::getPageBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();

                    return view('admin/static-pages/guide-user')->with($data);
                    break;

                case 'privacy-policy':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Page Management';
                    $data['breadCrumData'][1]['url'] = url('admin/static-page');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Privacy Policy';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Privacy Policy';
                    $data['slug'] = $slug;
                    $data['arrPageData'] = StaticPage::getPageBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();
                    return view('admin/static-pages/privacy-policy')->with($data);
                    break;

                case 'terms-conditions':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Page Management';
                    $data['breadCrumData'][1]['url'] = url('admin/static-page');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Terms & Conditions';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Terms & Conditions';
                    $data['slug'] = $slug;

                    $data['arrPageData'] = StaticPage::getPageBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();
                    return view('admin/static-pages/terms-conditions')->with($data);
                    break;

                    case 'about-us':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Page Management';
                    $data['breadCrumData'][1]['url'] = url('admin/static-page');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'About Us';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'About Us';
                    $data['slug'] = $slug;

                    $data['arrPageData'] = StaticPage::getPageBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();
                    return view('admin/static-pages/about-us')->with($data);
                    break;
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: edit
     * @param StaticPageRequest $request
     * @desc: update static page.
     */
    public function saveStaticPage(StaticPageRequest $request) {

        try {
            request()->user()->authorizeRoles($this->allow_roles);
            $arrPageData = [];
            $varPageID = $request->get("page_slug");
            $arrPageData["page_title"] = $request->get("page_title");
            $arrPageData["meta_tag"] = $request->get("meta_tag");
            $arrPageData["meta_desc"] = $request->get("meta_desc");
            $arrPageData["content"] = $request->get("content");
            $arrPageData["status"] = $request->get("status");

            StaticPage::updatePageBySlug($varPageID, $arrPageData);

            $request->session()->flash('alert-success', \Config::get('flash_msg.PageUpdated'));
            return redirect(route('admin_static_page'));
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: getStaticPages
     * @desc: get all static pages.
     */
    public function getStaticPages() {
        try {
            request()->user()->authorizeRoles($this->allow_roles);
            $data = StaticPage::getAllStaticPages();
            return $this->dataProvider->getStaticPages($this->request, $data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

}
