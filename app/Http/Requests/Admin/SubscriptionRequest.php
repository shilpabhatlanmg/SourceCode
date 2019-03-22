<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class SubscriptionRequest extends Request {

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

        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                        //'plan_name' => 'required|unique:subscriptions,plan_name,NULL,id,deleted_at,NULL',
                    'plan_name' => 'required',
                    'people_allow' => 'required|numeric|min:1|max:1000',
                    'premises_allow' => 'required|numeric|min:1|max:1000',
                    'duration' => 'required|numeric|min:1|max:31',
                    'type' => 'required',
                        //'price' => "required|regex:/^\d*(\.\d{1,2})?$/",
                        //'price' => 'required|regex:/^\d*(\.\d{1,2})?$/|unique:subscriptions,price,NULL,id,deleted_at,NULL',

                    'price' => 'required|unique:subscriptions,price,NULL,id,deleted_at,NULL,people_allow,' . $this->get('people_allow'),

                    'status' => 'required'
                ];
            }
            case 'PUT':
            case 'PATCH': {

                if (!empty($this->get('subscription_id'))) {

                    return [
                            //'plan_name' => 'required|unique:subscriptions,plan_name, '.$this->get('subscription_id').',id,deleted_at,NULL',
                        'plan_name' => 'required',
                        'people_allow' => 'required|numeric|min:1|max:1000',
                        'premises_allow' => 'required|numeric|min:1|max:1000',
                        'duration' => 'required|numeric|min:1|max:31',
                        'type' => 'required',
                            //'price' => "required|regex:/^\d*(\.\d{1,2})?$/",
                            //'price' => 'required|unique:subscriptions,price, ' . $this->get('subscription_id') . ',id,deleted_at,NULL',
                        'price' => 'required|unique:subscriptions,price, ' . $this->get('subscription_id') . ',id,deleted_at,NULL,people_allow,' . $this->get('people_allow'),
                        'status' => 'required'
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
