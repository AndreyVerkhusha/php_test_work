<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'phone' => 'string|nullable',
            'last_name' => 'string|nullable',
            'first_name' => 'string|nullable',
            'middle_name' => 'string|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
            'format' => 'in:json,xml|nullable',
            'created_at' => 'string|nullable',
            'sort_field' => 'string|nullable'
        ];
    }
}
