<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use App\Traits\ApiResponseTrait;
use Exception;

class authJWT {

    use ApiResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $data = [];
        try {
            $user = JWTAuth::toUser($request->header('token'));
            //$user = JWTAuth::toUser($request->input('token'));
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->returnDataApi(0, 'Token is Invalid.', $data);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                //return response()->json(['error'=>'Token is Expired']);
                return $this->returnDataApi(0, 'Token is Expired.', $data);
            } else {
                return $this->returnDataApi(0, 'Something is wrong.', $data);
            }
        }
        return $next($request);
    }

}
