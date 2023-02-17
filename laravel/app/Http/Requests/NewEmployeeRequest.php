<?php

namespace App\Http\Requests;

class NewEmployeeRequest extends Request
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
            'contact.first_name' => 'required',
            'contact.last_name' => 'required',
            'contact.address1' => 'required',
            'contact.city' => 'required',
            'contact.state' => 'required',
            'contact.postal_code' => 'required',
            'contact.phone1' => 'required',
            'contact.user_role' => 'required',
            'contact.email' => 'required|unique:users,email',
            'contact.hired' => 'required',
            'contact.dob' => 'required',
        ];
    }
}
