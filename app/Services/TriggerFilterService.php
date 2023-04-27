<?php

namespace App\Services;

use App\Models\ChatLink;
use App\Models\Trigger;
use App\Models\ExcludedTrigger;

class TriggerFilterService
{
    /**Получить состояние триггера
     * @param ChatLink $chatLink Модель связи чата со ссылкой
     * @param Trigger $trigger Модель триггера
     * @return bool Должен ли бот оповестить чат по этому триггеру
     */
    public function getTriggerState(ChatLink $chatLink, Trigger $trigger): bool
    {
        $excluded = ExcludedTrigger::where('chat_link_id', $chatLink->id)->where('trigger_id', $trigger->id)->first();
        return !($excluded && $excluded->active === true);
    }

    /**Переключить состояние триггера в указанном чате
     * @param ChatLink $chatLink Модель связанного чата со ссылкой
     * @param Trigger $trigger Модель триггера
     * @return void Создает запись или меняет флаг активности
     */
    public function toggleTriggerState(ChatLink $chatLink, Trigger $trigger): void
    {
        $excluded = ExcludedTrigger::where('chat_link_id', $chatLink->id)->where('trigger_id', $trigger->id)->first();
        if ($excluded) {
            $excluded->active = !$excluded->active;
            $excluded->save();
        } else {
            ExcludedTrigger::create([
                'chat_link_id' => $chatLink->id,
                'trigger_id' => $trigger->id
            ]);
        }
    }
}
