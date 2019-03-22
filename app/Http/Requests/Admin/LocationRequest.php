<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class LocationRequest extends Request {

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

                        return [
                            'organization_id' => 'required',
                            'premise_id' => 'required',
                            'name' => 'required|unique:locations,name,NULL,id,premise_id,' . $this->get('premise_id'),
                            'status' => 'required',
                        ];
                    } else {

                        return [
                            'name' => 'required|unique:locations,name,NULL,id,premise_id,' . $this->get('premise_id'),
                            'premise_id' => 'required',
                            'status' => 'required',
                        ];
                    }
                }
            case 'PUT':
            case 'PATCH': {

                    if (!empty($this->get('location_id'))) {

                        if (\commonHelper::getRoleName(\Auth::user()->id)->name == \Config::get('constants.PLATFORM_ADMIN')) {

                            return [
                                'organization_id' => 'required',
                                'premise_id' => 'required',
                                'name' => 'required|unique:locations,name, ' . $this->get('location_id') . ',id,deleted_at,NULL,premise_id,' . $this->get('premise_id') . ',organization_id,' . $this->get('organization_id'),
                                'status' => 'required',
                            ];
                        } else {

                            return [
                                //'name' => 'required|unique:locations,name, '.$this->get('location_id').',id,deleted_at,NULL',
                                'name' => 'required',
                                'name' => 'required|unique:locations,name, ' . $this->get('location_id') . ',id,deleted_at,NULL,premise_id,' . $this->get('premise_id') . ',organization_id,' . $this->get('organization_id'),
                                'status' => 'required',
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
