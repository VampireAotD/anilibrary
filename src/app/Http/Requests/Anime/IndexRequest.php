<?php

declare(strict_types=1);

namespace App\Http\Requests\Anime;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'page'    => 'nullable|integer|gte:1',
            'perPage' => 'nullable|integer|gte:1',
            'filters' => 'nullable|array',
            'sort'    => 'nullable|array',
        ];
    }
}
