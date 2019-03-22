<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class OrganizationRequest extends Request {

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
            'author_image.dimensions' => 'Author Image height must 120px',
            'author_image.image.*.image' => 'The uploaded file must be a image',
            'author_image.image.*.mimes' => 'The uploaded file type must be a file of type: jpeg, jpg, png.',
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
                        $this->session()->put('state_id', $this->state_id);
                        return [
                            'name' => 'required|unique:organizations,name,NULL,id,deleted_at,NULL',
                            'address' => 'required',
                            'email' => 'required|unique:organizations,email,NULL,id,deleted_at,NULL',
                            'address' => 'required',
                            'country_id' => 'required',
                            'state_id' => 'required',
                            'city_id' => 'required',
                            'zip_code' => 'required',
                            'password' => 'required|min:6',
                            'password_confirmation' => 'required|same:password',
                            'phone' => 'required',
                            'emergency_contact' => 'required',
                        ];
                    } else {

                        return [
                            'name' => 'required|unique:organizations,name,NULL,id,deleted_at,NULL',
                            'address' => 'required',
                            'email' => 'required|unique:organizations,email,NULL,id,deleted_at,NULL',
                            'address' => 'required',
                            'country_id' => 'required',
                            'state_id' => 'required',
                            'city_id' => 'required',
                            'zip_code' => 'required',
                            'password' => 'required|min:6',
                            'password_confirmation' => 'required|same:password',
                            'phone' => 'required',
                            'emergency_contact' => 'required',
                        ];
                    }
                }
            case 'PUT':
            case 'PATCH': {

                    if (!empty($this->get('organization_id'))) {

                        if (\commonHelper::getRoleName(\Auth::user()->id)->name == \Config::get('constants.PLATFORM_ADMIN')) {

                            return [
                                'name' => 'required|unique:organizations,name,' . $this->get('organization_id') . ',id,deleted_at,NULL',
                                'address' => 'required',
                                'email' => 'required|unique:organizations,email,' . $this->get('organization_id') . ',id,deleted_at,NULL',
                                'address' => 'required',
                                'country_id' => 'required',
                                'state_id' => 'required',
                                'city_id' => 'required',
                                'zip_code' => 'required',
                                'phone' => 'required',
                                'emergency_contact' => 'required',
                            ];
                        } else {

                            return [
                                'name' => 'required|unique:organizations,name,' . $this->get('organization_id') . ',id,deleted_at,NULL',
                                'address' => 'required',
                                'email' => 'required|unique:organizations,email,' . $this->get('organization_id') . ',id,deleted_at,NULL',
                                'address' => 'required',
                                'country_id' => 'required',
                                'state_id' => 'required',
                                'city_id' => 'required',
                                'zip_code' => 'required',
                                'phone' => 'required',
                                'emergency_contact' => 'required',
                            ];
                        }
                    }
                    return [];
                }
            default:break;

                return [
                ];
        }
    }

}
