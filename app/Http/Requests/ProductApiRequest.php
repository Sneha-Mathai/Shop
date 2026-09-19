<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Actual admin gate happens in route middleware; this just says
        // "if you got this far, you're allowed to attempt validation."
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
        ];
    }
}