<?php

namespace App\Http\Requests;

use App\Enums\RoleType;
use App\Enums\PriorityType;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'min:5'],
            'description' => ['required', 'min:5'],
            'priority' => ['required', new Enum(PriorityType::class)],
            'due_date' => ['nullable', 'date', 'after_or_equal:today', 'date_format:Y-m-d'],
            'assign_to' => ['required'],
            'assign_to_id' => ['required', 'numeric'],
        ];
    }

    public function messages()
    {
        return [
            'assign_to_id.required' => 'You must assign this task to a registered user.',
            'assign_to_id.numeric' => 'Assign to name doest not exist.',
        ];
    }
}
