<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ManageSecurityRequest extends Request {

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
            'profile_image.dimensions' => 'Author Image height must 120px',
            'profile_image.image.*.image' => 'The uploaded file must be a image',
            'profile_image.image.*.mimes' => 'The uploaded file type must be a file of type: jpeg, jpg, png.',
            "contact_number.unique" => "This contact number is already used. Please ask to deactivate it if you want to use it for another organization"
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {

        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    if (\commonHelper::getRoleName(\Auth::user()->id)->name == \Config::get('constants.PLATFORM_ADMIN')) {
                        return [
                            'organization_id' => 'required',
                            'name' => 'required',
                            'email' => 'required|unique:users,email',
                            'contact_number' => 'required|unique:users,contact_number,NULL,id,deleted_at,NULL,user_type,"Security",status,"Active",country_code,' . $this->get('country_code') . ',contact_number,' . $this->get('contact_number'),
                            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png|max:5000',
                        ];
                    } else {

                        return [
                            'name' => 'required',
                            'email' => 'required|unique:users,email',
                            'contact_number' => 'required|unique:users,contact_number,NULL,id,deleted_at,NULL,user_type,"Security",status,"Active",country_code,' . $this->get('country_code') . ',contact_number,' . $this->get('contact_number'),
                            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png|max:5000',
                        ];
                    }
                }
            case 'PUT':
            case 'PATCH': {

                    if (\commonHelper::getRoleName(\Auth::user()->id)->name == \Config::get('constants.PLATFORM_ADMIN')) {
                        return [
                            'organization_id' => 'required',
                            'name' => 'required',
                            'email' => 'required|unique:users,email,' . $this->get('user_id') . ',id,deleted_at,NULL',
                            'contact_number' => 'required|unique:users,contact_number,' . $this->get('user_id') . ',id,deleted_at,NULL,user_type,"Security",status,"Active",country_code,' . $this->get('country_code') . ',contact_number,' . $this->get('contact_number'),
                            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png|max:5000',
                        ];
                    } else {

                        return [
                            'name' => 'required',
                            'email' => 'required|unique:users,email,' . $this->get('user_id') . ',id,deleted_at,NULL',
                            'contact_number' => 'required|unique:users,contact_number,NULL,id,deleted_at,NULL,user_type,"Security",status,"Active",country_code,' . $this->get('country_code') . ',contact_number,' . $this->get('contact_number'),
                            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png|max:5000',
                        ];
                    }
                }
            default:break;

                return [
                ];
        }
    }

}
