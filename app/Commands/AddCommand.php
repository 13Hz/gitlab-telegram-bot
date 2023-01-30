<?php

namespace App\Commands;

use App\Models\Chat;
use App\Models\Link;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class AddCommand extends UserCommand {
    protected $name = 'add';                      // Your command's name
    protected $description = 'Добавить гитлаб репозиторий для получения уведомлений'; // Your command description
    protected $usage = '/add';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID
        $linkText = trim($message->getText(true));
        if($linkText && preg_match('/^http[s]?:\/\/\S+\.\S+?\/\S+?\/\S+?$/', $linkText)) {

            $linkText = rtrim($linkText, '/');

            $link = Link::firstOrCreate([
                'link' => $linkText
            ]);

            if($link)
            {
                $chat = Chat::firstOrcreate([
                    'chat_id' => $chat_id,
                    'type' => $message->getChat()->type,
                ]);

                if($chat) {
                    try {
                        $chat->links()->attach($link->id);
                        $text = "Ссылка успешно добавлена";
                    }
                    catch (\Exception $exception) {
                        $text = "В этом чате уже есть такая ссылка";
                    }
                }
                else
                {
                    $text = "При добавлении ссылки произошла ошибка";
                }
            }
            else
            {
                $text = "При добавлении ссылки произошла ошибка";
            }
        }
        else
        {
            $text = "Некорректный формат ссылки\n";
            $text .= "/add <ссылка>";
        }
        $data = [                                  // Set up the new message data
            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
            'text'    => $text, // Set message to send
        ];

        return Request::sendMessage($data);        // Send message!
    }
}
