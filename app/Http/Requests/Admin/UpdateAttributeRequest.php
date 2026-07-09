<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('attributes', 'slug')->ignore($this->route('attribute'))],
            'unit' => ['nullable', 'string', 'max:50'],
            'group' => ['nullable', 'string', 'max:100'],
            'data_type' => ['required', 'in:text,number,select'],
            'options' => ['nullable', 'string'],
            'is_filterable' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
