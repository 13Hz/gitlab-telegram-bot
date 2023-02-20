<?php

namespace App\Commands;

use App\Services\ChatButtonService;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class ListCommand extends UserCommand
{
    protected $name = 'list';
    protected $description = 'Вывод списка добавленных репозиториев';
    protected $usage = '/list';
    protected $version = '1.0.0';

    public function execute(): ServerResponse
    {
        $chatButtonService = new ChatButtonService();
        $chatId = $this->getMessage()->getChat()->getId();
        $linksButtons = $chatButtonService->getRepositoriesKeyboard($chatId);
        if ($linksButtons) {
            return $this->replyToChat('Список добавленных репозиториев', [
                'reply_markup' => $linksButtons,
            ]);
        }
        return $this->replyToChat('Список репозиториев пуст');
    }
}
