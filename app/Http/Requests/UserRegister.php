<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegister extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required |email',
            'password' => 'required |confirmed | alpha_num |between:6,8',
        ];
    }

    public function messages()
    {
        return [
            'name' => 'The user name is required',
            'email' => 'the user email is required',
            'password' => 'the user password is required',
        ];
    }
}
