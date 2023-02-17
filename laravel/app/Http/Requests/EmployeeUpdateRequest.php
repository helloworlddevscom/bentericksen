<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeUpdateRequest extends FormRequest
{
    protected $rules = [
        'employment_status' => [
            'employment_status.hired' => 'required',
            'employment_status.employee_status' => 'required',
        ],
        'employment_termination' => [
            'employment_termination.date' => 'required',
            'employment_termination.type' => 'required',
            'employment_termination.rehire' => 'required',
        ],
        'salary' => [
            'salary.salary' => 'required',
            'salary.rate' => 'required',
            'salary.effective_at' => 'required',
        ],
        'employee_classification' => [
            'employee_classification.new_classification_id' => 'required',
            'employee_classification.new_classification_name' => 'sometimes|required',
        ],
        'contact' => [
            'contact.first_name' => 'required',
            'contact.last_name' => 'required',
            'contact.email' => 'required|email|unique:users,email',
        ],
        'personal' => [],
        'emergency' => [],
        'drivers_license' => [],
        'licensure' => [],
        'attendance' => [
            'attendance.start_date' => 'required',
            'attendance.end_date' => 'required',
            'attendance.status' => 'required',
        ],
        'leave_of_absence' => [],
        'leave_of_absence_update' => [],
        'job_description' => [],
        'paperwork' => [],
        'authorization' => [],
        'resend_email' => [],
    ];

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
        $action = $this->request->get('action');
        $rules = $this->rules[$action];

        // If primary user id is being provided (from an Update request),
        // add that to the rules to avoid checking for unique email
        // if it didn't get modified.
        if ($action == 'contact') {
            $rules['contact.email'] .= ','.$this->request->get('user_id');
        }

        return $rules;
    }
}
