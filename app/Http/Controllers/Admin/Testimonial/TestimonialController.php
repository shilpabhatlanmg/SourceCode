<?php

namespace App\Http\Controllers\Admin\Testimonial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Admin\Testimonial;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Helpers\Helper;

class TestimonialController extends Controller {

    protected $allow_roles = [];

    /**
     * @author Sandeep Kumar
     * @function: __construct
     * @desc: check user is valid or not and store the roles in allow roles property.
     */
    public function __construct() {
        $this->middleware('auth:admin');
        $this->allow_roles = array(\Config::get('constants.PLATFORM_ADMIN'), \Config::get('constants.SUB_ADMIN'));
        $this->helper = new Helper();
    }

    /**
     * @author Sandeep Kumar
     * @function: index
     * @desc: Display a listing of testimonial.
     */
    public function index() {
        try {
            request()->user()->authorizeRoles($this->allow_roles);

            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Testimonial List';
            $data['breadCrumData'][1]['breadFaClass'] = '';

            $data['title'] = 'Testimonial List';

            $data['arrData'] = Testimonial::getAllRecord();
            $data['site_setting'] = $this->helper->getAllSettings();

            return view('admin.testimonial.testimonial-list')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: create
     * @desc: Create testimonial.
     */
    public function create() {
        try {
            request()->user()->authorizeRoles($this->allow_roles);

            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Testimonial List';
            $data['breadCrumData'][1]['url'] = url('admin/testimonial');
            $data['breadCrumData'][1]['breadFaClass'] = '';

            $data['breadCrumData'][2]['text'] = 'Create Testimonial';
            $data['breadCrumData'][2]['breadFaClass'] = '';

            $data['title'] = 'Create Testimonial';
            $data['site_setting'] = $this->helper->getAllSettings();

            return view('admin.testimonial.create')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: store
     * @param TestimonialRequest $request
     * @desc: Save testimonial.
     */
    public function store(TestimonialRequest $request) {
        try {
            request()->user()->authorizeRoles($this->allow_roles);
            $arrData = [];
            $arrData["content"] = $request->get("content");
            $arrData["author_rating"] = $request->get("author_rating");
            $arrData["occupation"] = $request->get("occupation");
            $arrData["author_email"] = $request->get("author_email");
            $arrData['feedback_date'] = $request->get('feedback_date');
            $arrData["status"] = $request->get("status");

            $saveId = Testimonial::storeOrUpdateData($arrData);


            /* Upload image media for Testimonial */
            if (!empty($request->file('author_image'))) {
                $arrData = [];
                $varFile = $request->file('author_image');
                $varFileName = $varFile->getClientOriginalName();
                $varFileEncName = $saveId->id . "_" . str_replace(' ', '_', $varFileName);
                $arrData['author_image'] = $varFileEncName;

                $varFile->storeAs('public/admin_assets/images/author_image', $varFileEncName);
                Testimonial::storeOrUpdateData($arrData, $saveId->id);
            }

            if (!empty($saveId) && is_object($saveId)) {

                $request->session()->flash('alert-success', \Config::get('flash_msg.TestimonialAdded'));
                return redirect(route('testimonial.index'));
            } else {

                return \Redirect::back()->withInput()->with('alert-danger', \Config::get('flash_msg.TestimonialNotAdded'));
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
     * @desc: edit testimonial.
     */
    public function edit($id) {
        try {

            $id = \Crypt::decryptString($id);

            request()->user()->authorizeRoles($this->allow_roles);

            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Testimonial List';
            $data['breadCrumData'][1]['url'] = url('admin/testimonial');
            $data['breadCrumData'][1]['breadFaClass'] = '';

            $data['breadCrumData'][2]['text'] = 'Edit Testimonial';
            $data['breadCrumData'][2]['breadFaClass'] = '';

            $data['title'] = 'Edit Testimonial';

            $data['objData'] = Testimonial::getRecordByID($id);
            $data['site_setting'] = $this->helper->getAllSettings();
            return view('admin.testimonial.edit')
                            ->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: update
     * @param TestimonialRequest $request
     * @param $id
     * @desc: update testimonial.
     */
    public function update(TestimonialRequest $request, $id) {
        try {

            request()->user()->authorizeRoles($this->allow_roles);
            $arrData = [];
            $arrData["content"] = $request->get("content");
            $arrData["author_rating"] = $request->get("author_rating");
            $arrData["occupation"] = $request->get("occupation");
            $arrData["author_email"] = $request->get("author_email");
            $arrData["feedback_date"] = $request->get('feedback_date');
            $arrData["status"] = $request->get("status");
            Testimonial::storeOrUpdateData($arrData, $id);

            /* Upload image media for Testimonial */
            if (!empty($request->file('author_image'))) {
                $arrData = [];
                $testimonial_old = $request->get('author_image_edit');
                $varFile = $request->file('author_image');
                $varFileName = $varFile->getClientOriginalName();
                $varFileEncName = $id . "_" . str_replace(' ', '_', $varFileName);
                $path = 'public/storage/admin_assets/images/author_image/' . $testimonial_old;

                if (!empty($testimonial_old)) {
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                $arrData['author_image'] = $varFileEncName;

                $varFile->storeAs('public/admin_assets/images/author_image', $varFileEncName);
                Testimonial::storeOrUpdateData($arrData, $id);
            }

            $request->session()->flash('alert-success', \Config::get('flash_msg.TestimonialUpdated'));
            return redirect(route('testimonial.index'));
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: destroy
     * @param Request $request
     * @param $id
     * @desc: Delete testimonial.
     */
    public function destroy(Request $request, $id) {
        try {

            $id = \Crypt::decryptString($id);

            request()->user()->authorizeRoles($this->allow_roles);
            $testimonial_old = $request->get('author_image_edit');
            $path = 'public/storage/admin_assets/images/author_image/' . $testimonial_old;
            if (!empty($testimonial_old)) {
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $varData = Testimonial::deleteRecord($id);


            if ($varData) {
                $request->session()->flash('alert-success', \Config::get('flash_msg.TestimonialDeleted'));
                return redirect()->back();
            } else {
                $request->session()->flash('alert-danger', \Config::get('flash_msg.TestimonialNotDeleted'));
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

            $checkRecord = Testimonial::find($decryptId);

            if (!empty($checkRecord) && is_object($checkRecord) && count($checkRecord) > 0) {

                $recordSts = Testimonial::storeOrUpdateData($requestUser, $decryptId);

                if (!empty($recordSts) && is_object($recordSts)) {

                    return response()->json(['success' => true, 'msg' => \Config::get('flash_msg.StatusChanged'), 'id' => $recordSts->id, 'sts' => $recordSts->status], 200);
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
