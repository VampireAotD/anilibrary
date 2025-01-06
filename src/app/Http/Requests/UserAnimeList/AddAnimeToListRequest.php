<?php

declare(strict_types=1);

namespace App\Http\Requests\UserAnimeList;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class AddAnimeToListRequest extends FormRequest
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
     * @return array<string, ValidationRule|list<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'anime_id' => 'required|uuid|exists:animes,id',
        ];
    }
}
