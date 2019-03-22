<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminAuthverify {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        switch ($guard) {
            case 'admin':
                if (!Auth::guard($guard)->check()) {
                    return redirect('/admin');
                }
                break;
            default:
                return redirect('/admin');
        }
        return $next($request);
    }

}
