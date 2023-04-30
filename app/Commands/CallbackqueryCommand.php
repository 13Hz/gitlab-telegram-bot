<?php

namespace App\Commands;

use App\Factories\CallbackProcessFactory;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class CallbackqueryCommand extends SystemCommand
{
    protected $name = 'callbackquery';
    protected $description = 'Handle the callback query';
    protected $version = '1.2.0';

    public function execute(): ServerResponse
    {
        $query = $this->getCallbackQuery();

        return CallbackProcessFactory::factory($query)->process();
    }
}
