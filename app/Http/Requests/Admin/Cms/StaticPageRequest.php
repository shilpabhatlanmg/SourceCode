<?php

namespace App\Http\Requests\Admin\Cms;

use App\Http\Requests\Request;

class StaticPageRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {

        return [
            'page_title' => 'required',
            'meta_tag' => 'required',
            'meta_desc' => 'required',
            'content' => 'required',
                //'page_image' => 'image|mimes:jpg,jpeg,png'
        ];
    }

}
