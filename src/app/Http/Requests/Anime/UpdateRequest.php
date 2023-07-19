<?php

declare(strict_types=1);

namespace App\Http\Requests\Anime;

use App\Enums\AnimeStatusEnum;
use App\Models\Anime;
use App\Models\AnimeSynonym;
use App\Models\AnimeUrl;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateRequest extends FormRequest
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
        $animeId = $this->anime->id;

        return [
            'title'          => ['required', 'string', Rule::unique(Anime::class)->ignore($animeId)],
            'status'         => ['required', new Enum(AnimeStatusEnum::class)],
            'episodes'       => 'required|string',
            'rating'         => 'required|numeric',
            'image'          => 'nullable|image',
            'urls'           => 'required|array',
            'urls.*'         => ['url', Rule::unique(AnimeUrl::class, 'url')->ignore($animeId, 'anime_id')],
            'synonyms'       => 'nullable|array',
            'synonyms.*'     => [
                'string',
                Rule::unique(AnimeSynonym::class, 'synonym')->ignore($animeId, 'anime_id'),
            ],
            'voice_acting'   => 'nullable|array',
            'voice_acting.*' => 'uuid|exists:voice_acting,id',
            'genres'         => 'nullable|array',
            'genres.*'       => 'uuid|exists:genres,id',
        ];
    }
}
