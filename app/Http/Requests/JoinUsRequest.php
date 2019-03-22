<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class JoinUsRequest extends Request {

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


                    return [
                        'name' => 'required|unique:organizations,name,NULL,id,deleted_at,NULL',
                        'address' => 'required',
                        'email' => 'required|unique:organizations,email,NULL,id,deleted_at,NULL',
                        'address' => 'required',
                        'country_id' => 'required',
                        'state_id' => 'required',
                        'city_id' => 'required',
                        'phone' => 'required'
                    ];
                }

            case 'PUT':
            case 'PATCH': {
                    return [
                        'name' => 'required|unique:organizations,name,' . $this->get('organization_id') . ',id,deleted_at,NULL',
                        'address' => 'required',
                        'email' => 'required|unique:organizations,email,' . $this->get('organization_id') . ',id,deleted_at,NULL',
                        'address' => 'required',
                        'country_id' => 'required',
                        'state_id' => 'required',
                        'city_id' => 'required',
                        'phone' => 'required'
                    ];
                }
            default:break;

                return [
                ];
        }
    }

}
