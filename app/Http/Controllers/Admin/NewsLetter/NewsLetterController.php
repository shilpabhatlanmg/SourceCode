<?php

namespace App\Http\Controllers\Admin\NewsLetter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Admin\NewsLetter\NewsLetter;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NewsLetterController extends Controller {

    protected $allow_roles = [];

    /**
     * @author Sandeep Kumar
     * @function: __construct
     * @desc: check user is valid or not and store the roles in allow roles property.
     */
    public function __construct() {
        $this->middleware('auth:admin');
        $this->allow_roles = array(\Config::get('constants.PLATFORM_ADMIN'));
    }

    /**
     * @author Sandeep Kumar
     * @function: index
     * @desc: display listing of newsletter.
     */
    public function index() {
        try {
            request()->user()->authorizeRoles($this->allow_roles);
            return view('admin/newsletter/newsletter-list');
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

}
