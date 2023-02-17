<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BonusProPlanStaffBonusRequest extends FormRequest
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
            'staff_bonus_percentage' => 'required|numeric|between:0,100',
            'hygiene_bonus_percentage' => 'required_if:hygiene_plan,1|numeric|between:0,100|nullable',
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
        ];
    }
}
