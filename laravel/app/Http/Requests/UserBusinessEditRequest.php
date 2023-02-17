<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserBusinessEditRequest extends Request
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
        return [
            'address1' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'phone1' => 'required|min:7',
        ];
    }
}
