<?php

namespace App\Models\Core;

use App\Services\ChatButtonService;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class LinkPressedProcess extends CallbackProcess
{
    public function process(): ServerResponse
    {
        $chatButtonService = new ChatButtonService();

        $message = [
            'chat_id' => $this->getChatId(),
            'message_id' => $this->getMessageId(),
        ];

        if ($this->getMessageData()['action'] === 'delete') {
            $this->getChat()->links()->detach($this->getMessageData()['id']);
        }

        $message['text'] = match ($this->getMessageData()['action']) {
            'filter' => 'Выберите нужные триггеры для оповещений',
            'show_list' => 'Список добавленных репозиториев',
            default => 'Выберите действие'
        };

        $message['reply_markup'] = match ($this->getMessageData()['action']) {
            'filter' => $chatButtonService->getFiltersKeyboard($this->getMessageData()['id'], $this->getChatLink()),
            'show_buttons' => $chatButtonService->getActionsKeyboard($this->getMessageData()['id']),
            default => $chatButtonService->getRepositoriesKeyboard($this->getChatId())
        };

        return Request::editMessageText($message);
    }
}
