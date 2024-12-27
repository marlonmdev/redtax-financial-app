<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRoleRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_id' => 'required',
            'role_id.*' => 'exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'role_id.required' => 'Role is required'
        ];
    }
}
