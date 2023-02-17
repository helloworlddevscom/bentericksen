<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class TimeOffRequestsRequest extends Request
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
            'type' => 'required',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'A type of request is required',
            'start_at.required' => 'A start date is required',
            'end_at.required' => 'An end date is required and after start date',
            'end_at.after_or_equal' => 'End date must be a date equal to or after the start date',
        ];
    }
}
