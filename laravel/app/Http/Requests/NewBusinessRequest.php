<?php

namespace App\Http\Requests;

class NewBusinessRequest extends Request
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
        $email_rules = 'required|unique:users,email';
        $password_rules = 'required';

        // If primary user id is being provided (from an Update request),
        // add that to the rules to avoid checking for unique email
        // if it didn't get modified.
        if ($this->request->get('primary_user_id')) {
            $email_rules .= ','.$this->request->get('primary_user_id');
            $password_rules = null;
        }

        $rules = [
            'name' => 'required',
            'type' => 'required',
            'address1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'primary_first_name' => 'required',
            'primary_last_name' => 'required',
            'primary_role' => 'required',
            'phone1' => 'required',
            'asa.type' => 'required_if:hrdirector_enabled,1',
            'asa.expiration' => 'required_if:hrdirector_enabled,1',
        ];

        // BonusPro expiration date is required if BP is enabled and if the "Lifetime Access" box is UNchecked
        if ($this->request->get('bonuspro_enabled') == 1) {
            // Note: as of May 2019, the expiration date is a MySQL TIMESTAMP and is limited to the years 1970-2038.
            // If this becomes a problem, change the column type to DATE (we're not using the time here.)
            if ($this->request->get('bonuspro_lifetime_access') == 0) {
                // the format in the rule appears in the error message, hence using m/d/Y format
                $rules['bonuspro_expiration_date'] = 'required|date|after:01/01/1970|before:01/01/2038';
            } else {
                $rules['bonuspro_expiration_date'] = 'sometimes|nullable|date|after:01/01/1970|before:01/01/2038';
            }
        }

        $rules['primary_email'] = $email_rules;

        if ($password_rules) {
            $rules['owner_login_password'] = $password_rules;
        }

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
            'asa.type.required_if'  => 'Business ASA type field is required if HR Director is enabled.',
            'asa.expiration.required_if' => 'Business ASA expiration date field is required if HR Director is enabled.',
            'bonuspro_expiration_date.required' => 'BonusPro expiration date is required if BonusPro is enabled and lifetime access is not checked.',
        ];
    }
}
