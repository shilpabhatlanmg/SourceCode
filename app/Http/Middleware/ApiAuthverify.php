<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ApiAuthverify {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {

        switch ($guard) {
            case 'api':
                if (!Auth::guard($guard)->check()) {
                    //return redirect('/login');
                    return response()
                                    ->json(['response' => 'Unauthorized access', 'status' => 401])
                                    ->withCallback($request->input('callback'));
                }
                break;
            default:
                return redirect('/');
        }
        return $next($request);
    }

}
