<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Enums\Telegram\ChatMemberStatusEnum;
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
     * @param array    $newChatMember
     * @return Update
     */
    public function createFakeChatMemberUpdate(?int $chatId = null, array $newChatMember = []): Update
    {
        $chatId        ??= $this->fakeTelegramId;
        $newChatMember = array_merge(['user' => [], 'status' => ChatMemberStatusEnum::MEMBER->value], $newChatMember);

        return Update::create(
            [
                'my_chat_member' => [
                    'chat'            => [
                        'id' => $chatId,
                    ],
                    'old_chat_member' => [
                        'status' => 'member',
                    ],
                    'new_chat_member' => $newChatMember,
                ],
            ]
        );
    }

    public function createFakeCallbackQueryUpdate(?int $chatId = null, string $query = ''): Update
    {
        $chatId ??= $this->fakeTelegramId;

        return Update::create(
            [
                'callback_query' => [
                    'data'    => $query,
                    'message' => [
                        'chat' => [
                            'id' => $chatId,
                        ],
                    ],
                ],
            ]
        );
    }
}
