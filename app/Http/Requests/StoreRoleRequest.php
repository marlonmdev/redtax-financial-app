<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_name' => 'required|unique:roles,role_name',
            'description' => 'required|min:5',
            'permission_id' => 'required',
            'permission_id.*' => 'exists:permissions,id',
        ];
    }

    public function messages()
    {
        return [
            'permission_id.required' => 'Permission is required'
        ];
    }
}
