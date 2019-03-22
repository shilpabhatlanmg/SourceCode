<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ChangePasswordRequest extends Request {

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
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {

        $rules['current_password'] = 'required';
        $rules['password'] = 'required|string|min:6|confirmed';
        $rules['password_confirmation'] = 'required|string|min:6';
        return $rules;
    }

}
