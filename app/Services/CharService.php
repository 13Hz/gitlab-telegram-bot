<?php

namespace App\Services;

use App\Models\Chat;
use Illuminate\Database\Eloquent\Builder;

class CharService
{
    public function createChat(string|int $chatId, string $chatType): Builder|Chat
    {
        return Chat::create([
            'chat_id' => $chatId,
            'type' => $chatType,
        ]);
    }

    public function isChatExists(string|int $chatId): bool
    {
        return Chat::where('chat_id', $chatId)->isNotEmpty();
    }
}
