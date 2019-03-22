<?php

namespace App\Http\Requests\Admin\Cms;

use App\Http\Requests\Request;

class DynamicContentRequest extends Request {

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
                //'title' => 'required',
                //'slug' => 'required|unique:dynamic_contents,slug, '.$this->get('slug').',slug',
        ];
    }

}
