<?php

namespace App\Handlers\Traits;

use App\Enums\AnimeCaptionEnum;
use App\Enums\CallbackQueryEnum;
use App\Models\Anime;
use Illuminate\Pagination\LengthAwarePaginator;

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
    private function convertToCaption(
        Anime $anime,
        ?int $userId = null,
        ?LengthAwarePaginator $pagination = null
    ): array
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

        if ($pagination) {
            $pages = [];

            if ($pagination->previousPageUrl()) {
                $pages[] = [
                    'text' => '<',
                    'callback_data' => sprintf(
                        'command=%s&page=%d',
                        CallbackQueryEnum::PAGINATION->value,
                        $pagination->currentPage() - 1
                    )
                ];
            }

            if ($pagination->nextPageUrl()) {
                $pages[] = [
                    'text' => '>',
                    'callback_data' => sprintf(
                        'command=%s&page=%d',
                        CallbackQueryEnum::PAGINATION->value,
                        $pagination->currentPage() + 1
                    )
                ];
            }

            $response['reply_markup']['inline_keyboard'][] = $pages;
        }

        return $response;
    }
}
