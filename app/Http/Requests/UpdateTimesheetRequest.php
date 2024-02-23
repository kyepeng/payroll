<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTimesheetRequest extends FormRequest
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
            'date' => 'required|date',
            'hours_worked' => 'required|integer',
            'description' => 'required',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'date.required' => 'The date field is required.',
            'date.date' => 'The date field must be a valid date.',
            'hours_worked.required' => 'The hours worked field is required.',
            'hours_worked.integer' => 'The hours worked field must be an integer.',
            'description.required' => 'The description field is required.',
        ];
    }
}
