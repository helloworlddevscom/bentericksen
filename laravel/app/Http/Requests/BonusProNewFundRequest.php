<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BonusProNewFundRequest extends FormRequest
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
            'fund_id' => 'required',
            'fund_name' => 'required',
            'fund_start_month' => 'required|numeric|between:1,12',
            'fund_start_year' => 'required|numeric|between:1900,2099',
            'fund_type' => 'required',
            'fund_amount' => 'required|numeric',
        ];

        return $rules;
    }
}
