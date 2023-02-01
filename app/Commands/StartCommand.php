<?php

namespace App\Commands;

use App\Models\Chat;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class StartCommand extends UserCommand
{
    protected $private_only = false;
    protected $name = 'start';
    protected $description = 'Регистрация чата и запуск бота';
    protected $usage = '/start';
    protected $version = '1.0.0';

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat = $message->getChat();

        $exist = Chat::where('chat_id', $chat->getId())->first();

        if($exist)
        {
            $text = "Чат уже присутствует в базе";
        }
        else
        {
            $model = Chat::create([
                'chat_id' => $chat->getId(),
                'type' => $chat->getType(),
            ]);

            if($model) {
                $text = "Чат успешно зарегистрирован\n";
                $text .= "Введите /help для получения списка доступных команд";
            }
            else {
                $text = "Произошла ошибка при добавлении чата в базу";
            }
        }

        $data = [
            'chat_id' => $chat->getId(),
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
