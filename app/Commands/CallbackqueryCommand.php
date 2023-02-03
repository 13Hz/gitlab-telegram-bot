<?php

namespace App\Commands;

use App\Models\Chat;

use App\Models\InlineKeyboard;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class CallbackqueryCommand extends SystemCommand
{
    protected $name = 'callbackquery';
    protected $description = 'Handle the callback query';
    protected $version = '1.2.0';

    public function execute(): ServerResponse
    {
        $query = $this->getCallbackQuery();
        $chatId = $query->getMessage()->getChat()->getId();
        $data = json_decode($query->getData(), true);
        if ($data['entity'] === 'link') {
            $message = [
                'chat_id' => $chatId,
                'text' => 'Выберите действие',
                'message_id' => $query->getMessage()->getMessageId(),
            ];

            if ($data['action'] === 'show_buttons') {
                $message['reply_markup'] = new InlineKeyboard([
                    [
                        [
                            'text' => 'Удалить',
                            'callback_data' => json_encode([
                                'entity' => 'link',
                                'action' => 'delete',
                                'id' => $data['id']
                            ])
                        ]
                    ],
                    [
                        [
                            'text' => 'Назад',
                            'callback_data' => json_encode([
                                'entity' => 'link',
                                'action' => 'show_list',
                                'id' => $data['id']
                            ])
                        ]
                    ]
                ]);
            } elseif ($data['action'] === 'show_list') {
                $message['text'] = 'Список добавленных репозиториев';
                $message['reply_markup'] = ListCommand::getLinksButtons($chatId);
            } elseif ($data['action'] === 'delete') {
                $chat = Chat::where('chat_id', $chatId)->first();
                $chat?->links()->detach($data['id']);

                $message['text'] = 'Список добавленных репозиториев';
                $message['reply_markup'] = ListCommand::getLinksButtons($chatId);
            }

            return Request::editMessageText($message);
        }

        return Request::answerCallbackQuery([
            'callback_query_id' => $query->getId()
        ]);
    }
}
