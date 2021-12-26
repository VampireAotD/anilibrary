<?php

namespace App\Handlers\Traits;

use App\Models\Anime;

/**
 * Trait CanConvertAnimeToCaption
 * @package App\Handlers\Traits
 */
trait CanConvertAnimeToCaption
{
    /**
     * @param Anime $anime
     * @return array
     */
    private function convertToCaption(Anime $anime): array
    {
        return [
            'caption' => sprintf("Название: %s\nОзвучки: %s", $anime->title, $anime->voiceActing->implode('name', ', ')),
            'photo' => $anime->image->path,
            'reply_markup' => [
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'Ссылка',
                            'url' => $anime->url,
                        ]
                    ]
                ]
            ]
        ];
    }
}
