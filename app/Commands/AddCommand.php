<?php

namespace App\Commands;

use App\Services\ChatService;
use App\Services\TelegramChatService;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class AddCommand extends UserCommand
{
    protected $name = 'add';
    protected $description = 'Добавить гитлаб репозиторий для получения уведомлений';
    protected $usage = '/add';
    protected $version = '1.0.0';

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chatId = $message->getChat()->getId();

        $chatService = new ChatService();
        $telegramChatService = new TelegramChatService();

        $chat = $chatService->getChatOrCreate($chatId, $message->getChat()->type);

        $data = [
            'chat_id' => $chatId,
            'text' => $telegramChatService
                ->bindLinkToChat($chat, $message->getText(true))
                ->getMessage(),
        ];

        return Request::sendMessage($data);
    }
}
