<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class FrontUserAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        switch ($guard) {
            case 'web':
                if (!Auth::guard($guard)->check()) {
                    return redirect('/login');
                }
                break;
            default:
                return redirect('/');
        }
        return $next($request);
    }

}
