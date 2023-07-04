<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Enums\Telegram\Middlewares\ChatMemberStatusEnum;
use Illuminate\Foundation\Testing\WithFaker;
use WeStacks\TeleBot\Objects\Update;

/**
 * Trait CanCreateFakeUpdates
 * @package Tests\Traits
 */
trait CanCreateFakeUpdates
{
    use WithFaker;

    private const FAKE_TELEGRAM_ID = -1;

    public function createFakeTextMessageUpdate(?string $message = null, int $chatId = self::FAKE_TELEGRAM_ID): Update
    {
        $message ??= $this->faker->sentence;

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

    public function createFakeCommandUpdate(string $commandAlias, int $chatId = self::FAKE_TELEGRAM_ID)
    {
        return Update::create(
            [
                'message' => [
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

    public function createFakeCommandUpdateWithUser(string $commandAlias, int $chatId = self::FAKE_TELEGRAM_ID)
    {
        return Update::create(
            [
                'message' => [
                    'from'     => [
                        'id'         => $chatId,
                        'first_name' => $this->faker->firstName,
                        'last_name'  => $this->faker->lastName,
                        'username'   => $this->faker->userName,
                        'is_bot'     => false,
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

    public function createFakeCommandUpdateWithBot(string $commandAlias, int $chatId = self::FAKE_TELEGRAM_ID)
    {
        return Update::create(
            [
                'message' => [
                    'from'     => [
                        'id'         => $this->faker->randomNumber(),
                        'first_name' => $this->faker->firstName,
                        'last_name'  => $this->faker->lastName,
                        'username'   => $this->faker->userName,
                        'is_bot'     => true,
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

    public function createFakeStickerMessageUpdate(int $chatId = self::FAKE_TELEGRAM_ID): Update
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

    public function createFakeChatMemberUpdate(array $newChatMember = [], int $chatId = self::FAKE_TELEGRAM_ID): Update
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

    public function createFakeCallbackQueryUpdate(string $query = '', int $chatId = self::FAKE_TELEGRAM_ID): Update
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
