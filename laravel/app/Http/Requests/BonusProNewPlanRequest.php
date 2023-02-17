<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BonusProNewPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'plan_id' => 'required',
            'plan_name' => 'required',
            'distribution_type' => 'required',
            'rolling_average' => 'required',
            'type_of_practice' => 'required',
            'hygiene_plan' => 'required|boolean',
            'separate_fund' => 'required|boolean',
            'use_business_address' => 'required|boolean',
            'start_month' => 'required|numeric|between:1,12',
            'start_year' => 'required|numeric|between:1900,2099',
            'password' => 'required_if:password_set,0|confirmed',
        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'start_month.between'  => 'Invalid month. (MM)',
            'start_year.between'  => 'Invalid year. (YYYY)',
            'password.required_if' => 'Password is required.',
        ];
    }
}
