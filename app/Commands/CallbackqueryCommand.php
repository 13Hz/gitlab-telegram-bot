<?php

namespace App\Commands;

use App\Models\Chat;
use App\Models\ChatLink;
use App\Services\ChatButtonService;
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
        $chatButtonService = new ChatButtonService();
        $chat = Chat::where('chat_id', $chatId)->first();
        if ($chat) {
            $chatLink = ChatLink::where('chat_id', $chat->id)->where('link_id', $data['id'])->first();
            if ($data['entity'] === 'link') {
                    $message = [
                        'chat_id' => $chatId,
                        'message_id' => $query->getMessage()->getMessageId(),
                    ];

                    if ($data['action'] === 'delete') {

                        $chat?->links()->detach($data['id']);
                    }

                    $message['text'] = match ($data['action']) {
                        'filter' => 'Выберите нужные триггеры для оповещений',
                        'show_list' => 'Список добавленных репозиториев',
                        default => 'Выберите действие'
                    };

                    $message['reply_markup'] = match ($data['action']) {
                        'filter' => $chatButtonService->getFiltersKeyboard($data['id'], $chatLink),
                        'show_buttons' => $chatButtonService->getActionsKeyboard($data['id']),
                        default => $chatButtonService->getRepositoriesKeyboard($chatId)
                    };

                    return Request::editMessageText($message);
            }
            if ($data['entity'] === 'filter' && $chatLink) {
                if ($data['action'] === 'issue') {
                    $chatLink->issue = !$chatLink->issue;
                } elseif ($data['action'] === 'merge_request') {
                    $chatLink->merge_request = !$chatLink->merge_request;
                } elseif ($data['action'] === 'note') {
                    $chatLink->note = !$chatLink->note;
                }

                $chatLink->save();

                $message = [
                    'chat_id' => $chatId,
                    'text' => 'Выберите нужные триггеры для оповещений',
                    'message_id' => $query->getMessage()->getMessageId(),
                    'reply_markup' => $chatButtonService->getFiltersKeyboard($data['id'], $chatLink),
                ];

                return Request::editMessageText($message);
            }
        }

        return Request::answerCallbackQuery([
            'callback_query_id' => $query->getId()
        ]);
    }
}
