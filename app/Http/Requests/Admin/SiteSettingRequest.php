<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class SiteSettingRequest extends Request {

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
            'site_logo.dimensions' => 'Site logo height Min 1px and Max 45px',
            'fevicon.dimensions' => 'Fevicon Image height Min 5px and Max 5px',
            'footer_logo.dimensions' => 'Footer logo height Min 1px and Max 45px',
            'address_zip.digits' => 'Zip number must be 5 digits',
            'fevicon.image' => 'The favicon must be an image.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $rules['address_email'] = 'nullable|email';
        $rules['admin_email'] = 'nullable|email';
        $rules['address_zip'] = 'nullable|numeric|digits:5';
        $rules['from_email'] = 'nullable:email';
        //$rules['site_logo'] = 'image|mimes:jpg,jpeg,png|dimensions:min_height=1,max_height=45';

        $rules['site_logo'] = 'image|mimes:jpg,jpeg,png|max:5000';

        if (!empty($this->fevicon) && $this->fevicon->getClientOriginalExtension() != 'ico') {
            $rules['fevicon'] = 'image|mimes:jpg,jpeg,png|max:5000';
        }

        $rules['footer_logo'] = 'image|mimes:jpg,jpeg,png|max:5000';

        $rules['facebook_link'] = 'nullable|url';
        $rules['twitter_link'] = 'nullable|url';
        $rules['linked_in'] = 'nullable|url';
        $rules['google_link'] = 'nullable|url';
        $rules['behance_link'] = 'nullable|url';
        $rules['dribbble_link'] = 'nullable|url';
        return $rules;
    }

}
