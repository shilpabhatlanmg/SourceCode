<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        foreach (Auth::user()->roles as $role) {

            switch ($role->name) {
                case \Config::get('constants.PLATFORM_ADMIN'):
                    return $next($request);
                    break;
                case \Config::get('constants.ORGANIZATION_ADMIN'):
                    return $next($request);
                    break;
                case \Config::get('constants.SUB_ADMIN'):
                    return $next($request);
                    break;
            }
        }

        return redirect('');
    }

}
