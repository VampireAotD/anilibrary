<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Enums\Telegram\ChatMemberStatusEnum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use WeStacks\TeleBot\Objects\Update;

/**
 * Trait CanCreateFakeUpdates
 * @package Tests\Traits
 */
trait CanCreateFakeUpdates
{
    use WithFaker;

    private const FAKE_TELEGRAM_ID = -1;

    /**
     * @param int|null    $chatId
     * @param string|null $message
     * @return Update
     */
    public function createFakeTextMessageUpdate(?int $chatId = self::FAKE_TELEGRAM_ID, ?string $message = null): Update
    {
        $message ??= Str::random();

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

    public function createFakeCommandUpdate(string $commandAlias, ?int $chatId = self::FAKE_TELEGRAM_ID)
    {
        return Update::create(
            [
                'message' => [
                    'from'     => [
                        'id'         => $this->faker->numberBetween(),
                        'first_name' => $this->faker->firstName,
                        'last_name'  => $this->faker->lastName,
                        'username'   => $this->faker->userName,
                    ],
                    'chat'     => [
                        'id' => $chatId,
                    ],
                    'text'     => $commandAlias,
                    'entities' => [
                        [
                            'offset' => 0,
                            'length' => strlen($commandAlias),
                            'type'   => 'bot_command',
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * @param int|null $chatId
     * @return Update
     */
    public function createFakeStickerMessageUpdate(?int $chatId = self::FAKE_TELEGRAM_ID): Update
    {
        return Update::create(
            [
                'message' => [
                    'chat'    => [
                        'id' => $chatId,
                    ],
                    'sticker' => [
                        'set_name' => $this->faker->name,
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
    public function createFakeChatMemberUpdate(?int $chatId = self::FAKE_TELEGRAM_ID, array $newChatMember = []): Update
    {
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

    public function createFakeCallbackQueryUpdate(?int $chatId = self::FAKE_TELEGRAM_ID, string $query = ''): Update
    {
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
