<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
//Password Broker Facade
use Illuminate\Support\Facades\Password;
use App\Admin;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class ForgotPasswordController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset emails and
      | includes a trait which assists in sending these notifications from
      | your application to your users. Feel free to explore this trait.
      |
     */

use SendsPasswordResetEmails;

    /**
     * @author Sandeep Kumar
     * @function: __construct
     * @desc: check user is valid or not and store the roles in allow roles property.
     */
    public function __construct() {
        try {
            $this->middleware('guest:admin');
            $this->helper = new Helper();
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: showLinkRequestForm
     * @desc: show reset password form.
     */
    public function showLinkRequestForm() {
        $data['site_setting'] = $this->helper->getAllSettings();
        return view('admin.passwords.email')->with($data);
    }

    /**
     * @author Sandeep Kumar
     * @function: sendResetLinkEmail
     * @param Request $request
     * @desc: send reset link email to users.
     */
    public function sendResetLinkEmail(Request $request) {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $user_check = Admin::where('email', $request->email)->first();
        if (isset($user_check) && !empty($user_check) && is_object($user_check)) {
            if (!$user_check->status) {
                $request->session()->flash('alert-danger', 'Your account is not activated. Please activate it first.');
                return redirect()->back()->withInput();
            }
        }


        $response = $this->broker()->sendResetLink(
                $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT ? $this->sendResetLinkResponse($response) : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * @author Sandeep Kumar
     * @function: broker
     * @desc: password broker.
     */
    public function broker() {
        return Password::broker('admins');
    }

}
