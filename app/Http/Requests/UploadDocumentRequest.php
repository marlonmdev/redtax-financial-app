<?php

namespace App\Http\Requests;

use App\Enums\ContactType;
use App\Enums\ServiceType;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class UploadDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:5'],
            'customer_type' => ['required', Rule::in(['Individual', 'Business'])],
            'company' => ['required_if:customer_type,Business'],
            'preferred_contact' => ['required', new Enum(ContactType::class)],
            'email' => ['required', 'email', 'email:rfc,dns', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'address' => ['required'],
            'city' => ['required'],
            'state' => ['required'],
            'zip_code' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'referred_by' => ['nullable', 'string'],
            'services' => ['required', new Enum(ServiceType::class)],
            'details' => ['nullable', 'string'],
            'document' => ['required', 'array', 'max:12'],
            'document.*' => [
                function ($attribute, $value, $fail) {
                    $allowedExtensions = ['jpg', 'jpeg', 'pdf', 'docx', 'doc', 'xlsx', 'xls', 'csv'];
                    $extension = $value->getClientOriginalExtension();

                    if (!in_array($extension, $allowedExtensions)) {
                        $fail('Files must be of type: jpg, pdf, docx, doc ,xlsx, xls, and csv.');
                    }
                },
                'max:3072'
            ],
        ];
    }

    public function messages()
    {
        return [
            'document.required' => 'You must upload a file.',
            'document.max' => 'You can only upload a maximum of :max files.',
            'document.*.mimes' => 'Files must be of type: jpg, pdf, docx, doc, xlsx, xls, and csv.',
            'document.*.max' => 'A file must not be greater than 3MB.',
        ];
    }
}
