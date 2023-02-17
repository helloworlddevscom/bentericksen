<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BonusProNewEmployeeRequest extends FormRequest
{

    /**
     * @var
     */
    private $userId;

    /**
     * BonusProNewEmployeeRequest constructor.
     */
    public function __construct() {

        parent::__construct();
        $this->userId = Request()->input('id');

    }

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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,'.$this->userId,
            'address1' => 'sometimes|required',
            'city' => 'sometimes|required',
            'state' => 'sometimes|required',
            'postal_code' => 'sometimes|required',
            'dob' => 'sometimes|required',
            'hired' => 'sometimes|required',
            'phone1' => 'sometimes|required',
            'bp_eligibility_date' => 'required_if:bp_eligible,true',
            'bp_bonus_percentage' => 'required_if:distribution_type,percentage',
            'bp_employee_type' => 'required',
        ];

        return $rules;
    }
}
