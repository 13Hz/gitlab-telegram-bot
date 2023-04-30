<?php

namespace App\Models\Core;

use App\Models\Trigger;
use App\Services\ChatButtonService;
use App\Services\TriggerFilterService;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class FilterPressedProcess extends CallbackProcess
{
    public function process(): ServerResponse
    {
        $triggerFilterService = new TriggerFilterService();
        $chatButtonService = new ChatButtonService();
        $trigger = Trigger::find($this->getMessageData()['trigger']);
        if ($trigger) {
            $triggerFilterService->toggleTriggerState($this->getChatLink(), $trigger);
        }

        $message = [
            'chat_id' => $this->getChatId(),
            'text' => 'Выберите нужные триггеры для оповещений',
            'message_id' => $this->getMessageId(),
            'reply_markup' => $chatButtonService
                ->getFiltersKeyboard($this->getMessageData()['id'], $this->getChatLink()),
        ];

        return Request::editMessageText($message);
    }
}
