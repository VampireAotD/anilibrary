<?php

declare(strict_types=1);

namespace App\Http\Requests\Anime;

use App\Models\AnimeUrl;
use App\Rules\Telegram\SupportedUrlRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
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
            'url' => ['required', 'url', new SupportedUrlRule(), Rule::unique(AnimeUrl::class, 'url')],
        ];
    }
}
