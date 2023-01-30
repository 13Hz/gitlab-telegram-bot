<?php

namespace App\Commands;

use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class HelpCommand extends UserCommand {
    protected $name = 'help';                      // Your command's name
    protected $description = 'Список доступных команд'; // Your command description
    protected $usage = '/help';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    private function getCommandsText($commands) {
        $text = "";
        foreach ($commands as $command) {
            $name = $command->getName();
            $description = $command->getDescription();

            if($name && $description) {
                $text .= "/$name - $description\n";
            }
        }
        return $text;
    }

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();            // Get Message object
        $chat = $message->getChat();   // Get the current Chat ID

        $commands = $this->telegram->getCommandsList();

        $userCommands = array_filter($commands, function ($command){
            return $command->isUserCommand();
        });

        $adminCommands = array_filter($commands, function ($command){
            return $command->isAdminCommand();
        });

        $text = "Список доступных команд:\n\n";
        $text .= $this->getCommandsText($userCommands);

        if($adminCommands)
        {
            $text .= "\n===== АДМИН-ОБЛАСТЬ =====\n\n";
            $text .= $this->getCommandsText($adminCommands);
        }

        $data = [                                  // Set up the new message data
            'chat_id' => $chat->getId(),                 // Set Chat ID to send the message to
            'text'    => $text, // Set message to send
        ];

        return Request::sendMessage($data);        // Send message!
    }
}
