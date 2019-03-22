<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\StaticPageController as StaticPageController;
use App\Model\StaticPage;
use Validator;
use App\Traits\ApiResponseTrait;

class StaticPageController extends BaseController {

    use ApiResponseTrait;


    /**
     * @author Karnika Sharma
     * @function: pageInfo
     * @param Request $request
     * @desc: get page information.
     */
    public function pageInfo(Request $request) {
        $data = [];
        if (!empty($request->contentType)) {
            $siteSetting = \App\Model\StaticPage::where('page_title', $request->contentType)->first();
        }
        if (is_null($siteSetting)) {
            return $this->returnDataApi(0, 'Page Info Not Found', (object) $data);
        } else {

            $data = ['PAGE' => $siteSetting->content];
            return $this->returnDataApi(1, 'Success', $data);
        }
    }

}
