<?php

declare(strict_types=1);

namespace App\Http\Requests\UserAnimeList;

use App\Enums\UserAnimeList\StatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateAnimeInListRequest extends FormRequest
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
            'status'   => ['required', Rule::enum(StatusEnum::class)],
            'rating'   => 'nullable|numeric|between:1,10',
            'episodes' => 'nullable|integer|gte:1',
        ];
    }
}
