<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Admin;
use App\Helpers\Helper;

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        try {
            $this->middleware('guest:admin')->except('logout');
            $this->helper = new Helper();
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request) {
        try {

            $request->session()->regenerate();

            $this->clearLoginAttempts($request);

            foreach ($this->guard()->user()->roles as $role) {
                if ($role->name == 'Organization Admin') {

                    $subscriptionCount = \DB::table('organization_subscriptions')->select('id', 'status', 'organization_id')->where('organization_id', '=', $this->guard()->user()->id)->where('status', '=', 'Active')->first();

                    if (count($subscriptionCount) == 0) {
                        return redirect('admin/subscriptions/change');
                    } else {
                        
                        return redirect('admin/dashboard');
                    }
                } else {
                    //echo "sdfsdfsdfsdfsd";die;
                    return redirect('admin/dashboard');
                }
            }
            /* dd($this->guard()->user()->roles);
              if($this->guard()->user()->roles == 'Organization Admin'){
              echo "sfdsfsd";die;
              }
              return redirect('admin/dashboard'); */
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm() {
        try {

            $data['site_setting'] = $this->helper->getAllSettings();
            return view('admin.login')->with($data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request) {
        $this->validateLogin($request);

        $user_check = Admin::where('email', $request->email)->first();
        if (isset($user_check) && !empty($user_check) && is_object($user_check)) {
            if ($user_check->status == 'Inactive') {
                $request->session()->flash('alert-danger', 'Your account is not activated. Please activate it first.');
                return redirect()->back()->withInput();
            }
        }


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard() {
        try {
            return Auth::guard('admin');
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }

    /**
     * @author Sandeep Kumar
     * @function: store
     * @param Request $request
     * @desc: Logout users.
     */
    public function logout(Request $request) {
        try {
            $this->guard('admin')->logout();
            $request->session()->invalidate();
            return redirect('/admin');
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage() . " In " . $ex->getFile() . " At Line " . $ex->getLine())->withInput();
        }
    }
}
