<?php

namespace App\Http\Controllers\Admin\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Admin;
use App\Role;
use App\Model\PaymentLog;



class TransactionController extends Controller {

    protected $allow_roles = [];

    /**
     * @author Sandeep Kumar
     * @function: __construct
     * @desc: check user is valid or not and store the roles in allow roles property.
     */
    public function __construct() {
        $this->middleware('auth:admin')->except(['activateUser']);
        $this->allow_roles = array(\Config::get('constants.PLATFORM_ADMIN'), \Config::get('constants.SUB_ADMIN'));
        
    }

    /**
     * @author Sandeep Kumar
     * @function: index
     * @desc: display the list of transaction information.
     */
    public function index() {

        
        try {
            request()->user()->authorizeRoles($this->allow_roles);
            $data = Helper::getBreadCrumb();
            $data['breadCrumData'][1]['text'] = 'Transactions List';
            $data['breadCrumData'][1]['breadFaClass'] = '';
            $data['title'] = 'Manage Transactions';
            $data['transactionList'] = PaymentLog::getAllRecord();
            //dd($data['transactionList']);
            return view('admin.transaction.transaction-list')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

}
