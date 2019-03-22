<?php

namespace App\Http\Controllers\Admin\DynamicContent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Admin\Cms\DynamicContent;
use App\Http\Requests\Admin\Cms\DynamicContentRequest;
use App\Helpers\Helper;

class DynamicContentController extends Controller {

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
     * @desc: Display a listing of dynamic content.
     */
    public function index() {
        try {

            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Dynamic Content';
            $data['breadCrumData'][1]['breadFaClass'] = '';

            $data['title'] = 'Dynamic Content';

            $data['arrData'] = DynamicContent::getAllContent();
            $data['site_setting'] = $this->helper->getAllSettings();
            return view('admin/dynamic-content/index')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: editContent
     * @param $slug
     * @desc: show edit dynamic content.
     */
    public function editContent($slug) {
        try {

            request()->user()->authorizeRoles($this->allow_roles);

            switch ($slug) {

                case 'home-page-banner':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Dynamic Content Management';
                    $data['breadCrumData'][1]['url'] = url('admin/dynamic-content');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Home Banner';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Home Banner Content';
                    $data['slug'] = $slug;

                    $data['arrPageData'] = DynamicContent::getContentBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();

                    return view('admin/dynamic-content/edit')->with($data);
                    break;

                case 'app-screen':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Dynamic Content Management';
                    $data['breadCrumData'][1]['url'] = url('admin/dynamic-content');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'App Screen';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'App Screen';
                    $data['slug'] = $slug;
                    $data['arrPageData'] = DynamicContent::getContentBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();
                    return view('admin/dynamic-content/edit')->with($data);
                    break;

                case 'feature':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Dynamic Content Management';
                    $data['breadCrumData'][1]['url'] = url('admin/dynamic-content');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Feature';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Feature';
                    $data['slug'] = $slug;

                    $data['arrPageData'] = DynamicContent::getContentBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();
                    return view('admin/dynamic-content/edit')->with($data);
                    break;

                case 'design-group':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Dynamic Content Management';
                    $data['breadCrumData'][1]['url'] = url('admin/dynamic-content');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Design Group';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Design Group';
                    $data['slug'] = $slug;
                    $data['arrPageData'] = DynamicContent::getContentBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();
                    return view('admin/dynamic-content/edit')->with($data);
                    break;

                case 'business':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Dynamic Content Management';
                    $data['breadCrumData'][1]['url'] = url('admin/dynamic-content');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Business';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Business';
                    $data['slug'] = $slug;
                    $data['arrPageData'] = DynamicContent::getContentBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();
                    return view('admin/dynamic-content/edit')->with($data);
                    break;

                case 'congregations':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Dynamic Content Management';
                    $data['breadCrumData'][1]['url'] = url('admin/dynamic-content');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Congregations';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Congregations';
                    $data['slug'] = $slug;
                    $data['arrPageData'] = DynamicContent::getContentBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();
                    return view('admin/dynamic-content/edit')->with($data);
                    break;

                case 'schools':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Dynamic Content Management';
                    $data['breadCrumData'][1]['url'] = url('admin/dynamic-content');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Schools';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Schools';
                    $data['slug'] = $slug;
                    $data['arrPageData'] = DynamicContent::getContentBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();
                    return view('admin/dynamic-content/edit')->with($data);
                    break;

                case 'better-security-coverage':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Dynamic Content Management';
                    $data['breadCrumData'][1]['url'] = url('admin/dynamic-content');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Better Security Coverage';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Better Security Coverage';
                    $data['slug'] = $slug;
                    $data['arrPageData'] = DynamicContent::getContentBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();
                    return view('admin/dynamic-content/edit')->with($data);
                    break;

                case 'professional-responder':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Dynamic Content Management';
                    $data['breadCrumData'][1]['url'] = url('admin/dynamic-content');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Professional';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Professional';
                    $data['slug'] = $slug;
                    $data['arrPageData'] = DynamicContent::getContentBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();
                    return view('admin/dynamic-content/edit')->with($data);
                    break;

                case 'footer-left-content':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Dynamic Content Management';
                    $data['breadCrumData'][1]['url'] = url('admin/dynamic-content');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Footer Left Content';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Footer Left Content';
                    $data['slug'] = $slug;
                    $data['arrPageData'] = DynamicContent::getContentBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();
                    return view('admin/dynamic-content/edit')->with($data);
                    break;

                case 'footer-right-content':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Dynamic Content Management';
                    $data['breadCrumData'][1]['url'] = url('admin/dynamic-content');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Footer Right Content';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Footer Right Content';
                    $data['slug'] = $slug;
                    $data['arrPageData'] = DynamicContent::getContentBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();
                    return view('admin/dynamic-content/edit')->with($data);
                    break;

                case 'contact-us-content':

                    $data = Helper::getBreadCrumb();
                    $data['breadCrumData'][1]['text'] = 'Dynamic Content Management';
                    $data['breadCrumData'][1]['url'] = url('admin/dynamic-content');
                    $data['breadCrumData'][1]['breadFaClass'] = 'active';

                    $data['breadCrumData'][2]['text'] = 'Contact Us Content';
                    $data['breadCrumData'][2]['breadFaClass'] = '';

                    $data['title'] = 'Footer Right Content';
                    $data['slug'] = $slug;
                    $data['arrPageData'] = DynamicContent::getContentBySlug($slug);
                    $data['site_setting'] = $this->helper->getAllSettings();
                    return view('admin/dynamic-content/edit')->with($data);
                    break;
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: updateDynamicContent
     * @param DynamicContentRequest $request
     * @desc: update dynamic content.
     */
    public function updateDynamicContent(DynamicContentRequest $request) {

        try {
            request()->user()->authorizeRoles($this->allow_roles);
            $arrPageData = [];
            $varPageID = $request->get("slug");
            $arrPageData["title"] = $request->get("title");
            $arrPageData["content"] = $request->get("content");

            DynamicContent::updatePageBySlug($varPageID, $arrPageData);

            if (!\Cache::has($varPageID)) {
                \Cache::forever($varPageID, DynamicContent::where('slug', $varPageID)->first());
            } else {

                \Cache::forget($varPageID);
                \Cache::forever($varPageID, DynamicContent::where('slug', $varPageID)->first());
            }

            /* if (\Cache::has($varPageID)){
              $value = \Cache::get('website_description');
              dd(\Cache::get('website_description'));
              } else {

              \Cache::forever('website_description', StaticPage::where('slug_url', 'about-us')->first());
              } */

            $request->session()->flash('alert-success', \Config::get('flash_msg.PageUpdated'));
            return redirect(route('dynamic.content'));
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: getStaticPages
     * @desc: get dynamic content detail.
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
