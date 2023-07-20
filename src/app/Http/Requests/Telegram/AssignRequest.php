<?php

declare(strict_types=1);

namespace App\Http\Requests\Telegram;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AssignRequest extends FormRequest
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
            'id'         => 'required|int',
            'auth_date'  => 'required|int',
            'hash'       => 'required|string',
            'first_name' => 'nullable|string',
            'last_name'  => 'nullable|string',
            'username'   => 'nullable|string',
            'photo_url'  => 'nullable|url',
        ];
    }
}
