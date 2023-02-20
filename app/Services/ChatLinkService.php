<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatLink;
use App\Models\Core\InlineKeyboard;
use App\Models\ExcludedTrigger;
use App\Models\Link;
use App\Models\Trigger;

class ChatLinkService
{
    /**Получить модель связи чата со ссылкой по их моделям
     * @param Chat $chat Модель чата
     * @param Link $link Модель ссылки
     * @return ChatLink|null
     */
    public function getChatLinkByEntitiesId(Chat $chat, Link $link): ChatLink|null
    {
        return ChatLink::where('chat_id', $chat->id)->where('link_id', $link->id)->first();
    }
}
