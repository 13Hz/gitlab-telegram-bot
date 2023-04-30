<?php

namespace App\Commands;

use App\Services\TelegramChatService;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class StartCommand extends UserCommand
{
    protected $name = 'start';
    protected $description = 'Регистрация чата и запуск бота';
    protected $usage = '/start';
    protected $version = '1.0.0';

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat = $message->getChat();

        $chatService = new TelegramChatService();

        $data = [
            'chat_id' => $chat->getId(),
            'text' => $chatService->createChat($chat->getId(), $chat->getType())->getMessage(),
        ];

        return Request::sendMessage($data);
    }
}
