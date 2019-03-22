<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller {

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message = '') {
        $response = [
            //'status' => 'success',
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($errorMessages = [], $code = 404) {
        $response = [
            //'status' => 'error',
            'message' => $errorMessages,
        ];

        return response()->json($response, $code);
    }

}
