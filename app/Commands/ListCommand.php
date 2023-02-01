<?php

namespace App\Commands;

use App\Models\Chat;
use App\Models\InlineKeyboard;
use App\Models\Link;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class ListCommand extends UserCommand
{
    protected $name = 'list';
    protected $description = 'Вывод списка добавленных репозиториев';
    protected $usage = '/list';
    protected $version = '1.0.0';

    public function execute(): ServerResponse
    {
        $chatId = $this->getMessage()->getChat()->getId();
        $linksButtons = self::getLinksButtons($chatId);
        if($linksButtons) {
            return $this->replyToChat('Список добавленных репозиториев', [
                'reply_markup' => $linksButtons,
            ]);
        }
        return $this->replyToChat('Список репозиториев пуст');
    }

    public static function getLinksButtons($chatId) {
        $chat = Chat::where('chat_id', $chatId)->first();
        if($chat) {
            $links = $chat->links()->get();

            if ($links) {
                $buttons = [];

                foreach ($links as $link) {
                    $buttons[] = [
                        [
                            'text' => $link->repository_name,
                            'callback_data' => json_encode([
                                'entity' => 'link',
                                'action' => 'show_buttons',
                                'id' => $link->id
                            ])
                        ]
                    ];
                }

                return new InlineKeyboard($buttons);
            }
        }
        return null;
    }
}
