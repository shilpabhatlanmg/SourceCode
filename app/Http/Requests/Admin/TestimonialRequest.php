<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class TestimonialRequest extends Request {

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
                        'content' => 'required',
                        //'author_rating' => 'required|unique:testimonials,author_rating',
                        'author_rating' => 'required',
                        'author_image' => 'nullable|image|mimes:jpeg,jpg,png|max:5000',
                        'occupation' => 'required',
                        'feedback_date' => 'required'
                    ];
                }
            case 'PUT':
            case 'PATCH': {

                    if (!empty($this->get('testimonial_id'))) {
                        return [
                            'content' => 'required',
                            //'author_rating' => 'required|unique:testimonials,author_rating, '.$this->get('testimonial_id').',id,deleted_at,NULL',
                            'author_rating' => 'required',
                            'author_image' => 'nullable|image|mimes:jpeg,jpg,png|max:5000',
                            'occupation' => 'required',
                            'feedback_date' => 'required'
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
