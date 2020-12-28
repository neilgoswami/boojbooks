<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            'name' => 'required|max:255',
            'email' => 'regex:/^.+@.+\..+$/i|required|unique:users',
            'password' => 'required|max:255',
            'cpassword' => 'required|same:password'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'Email is already exists.',
            'email.regex' => 'Email must be valid email address.',
            'password.required' => 'Password is required.',
            'cpassword.required' => 'Confirm password is required.',
        ];
    }
}
