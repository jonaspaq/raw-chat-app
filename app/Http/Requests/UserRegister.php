<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegister extends FormRequest
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
            'email' => 'required|unique:users,email',
            'name' => 'required',
            'password' => 'required|between:6,32',
            'password_confirmation' => 'same:password'
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Username has already been taken.'
        ];
    }
}
