<?php

namespace App\Commands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class HelpCommand extends UserCommand
{
    protected $name = 'help';
    protected $description = 'Список доступных команд';
    protected $usage = '/help';
    protected $version = '1.0.0';

    private function getCommandsText($commands)
    {
        $text = '';
        foreach ($commands as $command) {
            $name = $command->getName();
            $description = $command->getDescription();

            if ($name && $description) {
                $text .= "/$name - $description".PHP_EOL;
            }
        }

        return $text;
    }

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat = $message->getChat();

        $commands = $this->telegram->getCommandsList();

        $userCommands = array_filter($commands, function ($command) {
            return $command->isUserCommand();
        });

        $adminCommands = array_filter($commands, function ($command) {
            return $command->isAdminCommand();
        });

        $text = "Список доступных команд:\n\n";
        $text .= $this->getCommandsText($userCommands);

        if ($adminCommands) {
            $text .= "\n===== АДМИН-ОБЛАСТЬ =====\n\n";
            $text .= $this->getCommandsText($adminCommands);
        }

        $data = [
            'chat_id' => $chat->getId(),
            'text' => $text,
        ];

        return Request::sendMessage($data);
    }
}
