<?php

namespace App\Commands;

use App\Services\CharService;
use App\Services\TelegramChatService;
use App\Traits\ParserTraits;
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

        $chatService = new CharService();
        $telegramChatService = new TelegramChatService();

        $chat = $chatService->createChat($chatId, $message->getChat()->type);

        $data = [
            'chat_id' => $chatId,
            'text' => $telegramChatService
                ->bindLinkToChat($chat, $message->getText(true))
                ->getMessage(),
        ];

        return Request::sendMessage($data);
    }
}
