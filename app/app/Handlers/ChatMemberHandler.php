<?php

namespace App\Handlers;

use App\Enums\ChatMemberStatusEnum;
use App\Handlers\History\UserHistory;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

/**
 * Class ChatMemberHandler
 * @package App\Handlers
 */
class ChatMemberHandler extends UpdateHandler
{
    /**
     * @param Update $update
     * @param TeleBot $bot
     * @return bool
     */
    public static function trigger(Update $update, TeleBot $bot): bool
    {
        return isset($update->my_chat_member);
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $chatMember = $this->update->my_chat_member;

        match ($chatMember->new_chat_member->status) {
            ChatMemberStatusEnum::KICKED->value => UserHistory::clearUserHistory($chatMember->from->id),
            default => '',
        };
    }
}
