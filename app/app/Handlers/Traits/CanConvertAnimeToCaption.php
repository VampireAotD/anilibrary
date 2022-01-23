<?php

namespace App\Handlers\Traits;

use App\Enums\AnimeCaptionEnum;
use App\Models\Anime;

/**
 * Trait CanConvertAnimeToCaption
 * @package App\Handlers\Traits
 */
trait CanConvertAnimeToCaption
{
    /**
     * @param Anime $anime
     * @param int|null $userId
     * @return array
     */
    private function convertToCaption(Anime $anime, ?int $userId = null): array
    {
        $response = [
            'caption' => sprintf(
                "Название: %s\nСтатус: %s\nЭпизоды: %s\nОценка: %s\nОзвучки: %s\nЖанры: %s\nТеги: %s",
                $anime->title,
                $anime->status,
                $anime->episodes,
                $anime->rating,
                $anime->voiceActing->implode('name', ', '),
                $anime->genres->implode('name', ', '),
                $anime->tags->implode('name', ', '),
            ),
            'photo' => $anime->image->path,
            'reply_markup' => [
                'inline_keyboard' => [
                    [
                        [
                            'text' => AnimeCaptionEnum::LINK->value,
                            'url' => $anime->url,
                        ],
                    ],
                ]
            ],
        ];

        if ($userId) {
            $response['chat_id'] = $userId;
        }

        return $response;
    }
}
