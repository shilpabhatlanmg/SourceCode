<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class VisitorlogRequest extends Request {

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
                        'name' => 'required',
                        'organisation_admin_id' => 'required',
                            //'author_rating' => 'required|unique:testimonials,author_rating',
                    ];
                }
            case 'PUT':
            case 'PATCH': {

                    if (!empty($this->get('premises_id'))) {
                        return [
                            'name' => 'required',
                            'organisation_admin_id' => 'required',
                                //'author_rating' => 'required|unique:testimonials,author_rating, '.$this->get('testimonial_id').',id,deleted_at,NULL',
                        ];
                    }
                    return [];
                }
            default:break;

                return [
                ];
        }
    }

}
