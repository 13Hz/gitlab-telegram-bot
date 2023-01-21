<?php

namespace App\Commands;

use App\Models\Chat;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class StartCommand extends UserCommand {
    protected $private_only = false;
    protected $name = 'start';                      // Your command's name
    protected $description = 'Регистрация чата и запуск бота'; // Your command description
    protected $usage = '/start';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();            // Get Message object
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
                $text .= "Введите /add <ссылка> для получения уведомлений из указанного репозитория";
            }
            else {
                $text = "Произошла ошибка при добавлении чата в базу";
            }
        }

        $data = [                                  // Set up the new message data
            'chat_id' => $chat->getId(),                 // Set Chat ID to send the message to
            'text'    => $text, // Set message to send
        ];

        return Request::sendMessage($data);        // Send message!
    }
}
