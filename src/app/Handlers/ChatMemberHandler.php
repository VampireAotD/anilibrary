<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Enums\ChatMemberStatusEnum;
use App\Handlers\History\UserHistory;
use WeStacks\TeleBot\Handlers\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

/**
 * Class ChatMemberHandler
 * @package App\Handlers
 */
class ChatMemberHandler extends UpdateHandler
{
    /**
     * @return bool
     */
    public function trigger(): bool
    {
        return isset($this->update->my_chat_member);
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $chatMember = $this->update->my_chat_member;

        match ($chatMember->new_chat_member->status) {
            ChatMemberStatusEnum::KICKED->value => UserHistory::clearUserExecutedCommandsHistory($chatMember->from->id),
            default                             => '',
        };
    }
}
