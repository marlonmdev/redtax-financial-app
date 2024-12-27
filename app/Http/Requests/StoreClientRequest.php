<?php

namespace App\Http\Requests;

use App\Enums\ContactType;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
            'name' => ['required', 'min:5'],
            'customer_type' => ['required', Rule::in(['Individual', 'Business'])],
            'company' => ['required_if:customer_type,Business'],
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', Rule::unique('clients', 'email')],
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', Rule::unique('clients', 'phone')],
            'preferred_contact' => ['required', new Enum(ContactType::class)],
            'address' => ['required'],
            'city' => ['required'],
            'state' => ['required'],
            'zip_code' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'tax_identification_number' => ['nullable', Rule::unique('clients', 'tax_identification_number')],
            'referred_by' => ['nullable'],
        ];
    }
}
