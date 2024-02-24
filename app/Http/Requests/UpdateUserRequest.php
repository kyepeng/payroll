<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$this->route('user'),
            'password' => 'required|same:confirm-password',
            'current_password' => 'required_with:password|current_password',
            'roles' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'A name is required.',
            'email.required' => 'An email address is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'This email is already in use.',
            'password.required' => 'Password is required',
            'password.min' => 'The password must be at least 8 characters.',
            'password.same' => 'The password confirmation does not match.',
            'current_password.required_with' => 'The current password field is required when updating password.',
            'roles.required' => 'At least one role is required.',
        ];

    }
}
