<?php

namespace App\Commands;

use App\Models\Chat;
use App\Models\Link;
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
        $linkText = trim($message->getText(true));
        if ($linkText && preg_match('/^http[s]?:\/\/\S+\.\S+?\/\S+?\/\S+?$/', $linkText)) {

            $linkText = rtrim($linkText, '/');

            $link = Link::firstOrCreate([
                'link' => $linkText
            ]);

            if ($link) {
                $chat = Chat::firstOrcreate([
                    'chat_id' => $chatId,
                    'type' => $message->getChat()->type,
                ]);

                if ($chat) {
                    try {
                        $chat->links()->attach($link->id);
                        $text = 'Ссылка успешно добавлена';
                    } catch (\Exception $exception) {
                        $text = 'В этом чате уже есть такая ссылка';
                    }
                } else {
                    $text = 'При добавлении ссылки произошла ошибка';
                }
            } else {
                $text = 'При добавлении ссылки произошла ошибка';
            }
        } else {
            $text = 'Некорректный формат ссылки'.PHP_EOL;
            $text .= '/add <ссылка>';
        }

        $data = [
            'chat_id' => $chatId,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
