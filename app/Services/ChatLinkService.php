<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatLink;
use App\Models\Link;

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
