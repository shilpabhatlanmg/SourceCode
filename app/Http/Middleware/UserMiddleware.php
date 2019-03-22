<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class UserMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (request()->user() && ((request()->user()->user_type != '1') && (request()->user()->user_type != '2'))) {
            return new Response(view('errors.403'));
        } else if (empty(request()->user())) {
            return redirect(route('welcome'));
        }

        return $next($request);
    }

}
