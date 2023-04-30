<?php

namespace App\Models\Core;

use App\Models\Chat;
use App\Models\ChatLink;
use App\Models\Link;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

abstract class CallbackProcess
{
    private CallbackQuery $query;
    private Chat|null $chat;
    private Link|null $link;
    private ChatLink|null $chatLink;
    private array $data;

    public function __construct(CallbackQuery $query, Chat $chat = null, Link $link = null, ChatLink $chatLink = null)
    {
        $this->query = $query;
        $this->chat = $chat;
        $this->link = $link;
        $this->chatLink = $chatLink;
        $this->data = json_decode($query->getData(), true);
    }

    protected function getQuery(): CallbackQuery
    {
        return $this->query;
    }

    protected function getChatId(): int
    {
        return $this->query->getMessage()->getChat()->getId();
    }

    protected function getMessageId(): int
    {
        return $this->query->getMessage()->getMessageId();
    }

    protected function getMessageData(): array
    {
        return $this->data;
    }

    protected function getChat(): Chat|null
    {
        return $this->chat;
    }

    protected function getLink(): Link|null
    {
        return $this->link;
    }

    protected function getChatLink(): ChatLink|null
    {
        return $this->chatLink;
    }

    public function process(): ServerResponse
    {
        return Request::answerCallbackQuery([
            'callback_query_id' => $this->getQuery()->getId()
        ]);
    }
}
