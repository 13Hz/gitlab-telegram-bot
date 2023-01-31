<?php

namespace App\Builders;

use App\Models\TriggerBuilder;

class MergeRequestBuilder extends TriggerBuilder
{
    public function addUserActionText(): void
    {
        $this->addLine("Пользователь [{$this->getTrigger()->getUserName()}]({$this->getTrigger()->getUserProfileLink()}) {$this->getAction()} [merge request #{$this->getTrigger()->getObjectId()}]({$this->getTrigger()->getObjectUrl()})");
    }
}
