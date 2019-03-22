<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class PersonalInformationRequest extends Request {

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
    public function messages() {
        return [
            'user_image.dimensions' => 'Profile Image height Min 70px and Max 100px',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $rules['name'] = 'required';
        $rules['address'] = 'required';
        //$rules['phone'] = 'required|numeric|digits:10';
        $rules['state_id'] = 'required';
        $rules['city_id'] = 'required';
        $rules['zip_code'] = 'required';
        $rules['phone'] = 'required';
        //$rules['user_image'] = 'image|mimes:jpg,jpeg,png|dimensions:min_height=70,max_height=300';
        return $rules;
    }

}
