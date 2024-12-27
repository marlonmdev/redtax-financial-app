<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        return  [
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required',
            'role_id.*' => 'exists:roles,id',
            'password' => 'required|confirmed|min:8',
        ];
    }

    public function messages()
    {
        return [
            'role_id.required' => 'Role is required'
        ];
    }
}
