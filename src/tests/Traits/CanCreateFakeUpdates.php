<?php

declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Support\Str;
use WeStacks\TeleBot\Objects\Update;

/**
 * Trait CanCreateFakeUpdates
 * @package Tests\Traits
 */
trait CanCreateFakeUpdates
{
    private int $fakeTelegramId = -1;

    /**
     * @param int|null    $chatId
     * @param string|null $message
     * @return Update
     */
    public function createFakeTextMessageUpdate(?int $chatId = null, ?string $message = null): Update
    {
        $message ??= Str::random();
        $chatId  ??= $this->fakeTelegramId;

        return Update::create(
            [
                'message' => [
                    'chat' => [
                        'id' => $chatId,
                    ],
                    'text' => $message,
                ],
            ]
        );
    }

    /**
     * @param int|null $chatId
     * @return Update
     */
    public function createFakeStickerMessageUpdate(?int $chatId = null): Update
    {
        $chatId ??= $this->fakeTelegramId;

        return Update::create(
            [
                'message' => [
                    'chat'    => [
                        'id' => $chatId,
                    ],
                    'sticker' => [
                        'set_name' => Str::random(),
                    ],
                ],
            ]
        );
    }

    /**
     * @param int|null $chatId
     * @return Update
     */
    public function createFakeChatMemberUpdate(?int $chatId = null): Update
    {
        $chatId ??= $this->fakeTelegramId;

        return Update::create(
            [
                'my_chat_member' => [
                    'chat'            => [
                        'id' => $chatId,
                    ],
                    'old_chat_member' => [
                        'status' => 'kicked',
                    ],
                    'new_chat_member' => [
                        'status' => 'member',
                    ],
                ],
            ]
        );
    }
}
