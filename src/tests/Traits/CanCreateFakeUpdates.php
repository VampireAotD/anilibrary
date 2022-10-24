<?php
declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Support\Str;
use WeStacks\TeleBot\Objects\Update;

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

        return Update::create([
            'message' => [
                'chat' => [
                    'id' => $chatId,
                ],
                'text' => $message,
            ],
        ]);
    }

    /**
     * @param int|null $chatId
     * @return Update
     */
    public function createFakeStickerMessageUpdate(?int $chatId = null): Update
    {
        $chatId ??= $this->fakeTelegramId;

        return Update::create([
            'message' => [
                'chat'    => [
                    'id' => $chatId,
                ],
                'sticker' => [
                    'set_name' => Str::random(),
                ],
            ],
        ]);
    }
}
