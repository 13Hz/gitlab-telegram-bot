<?php

namespace App\Builders;

use App\Models\TriggerBuilder;

class IssueBuilder extends TriggerBuilder
{
    public function addUserActionText(): void
    {
        $this->addLine("Пользователь [{$this->getTrigger()->getUserName()}]({$this->getTrigger()->getUserProfileLink()}) {$this->getAction()} [issue #{$this->getTrigger()->getObjectId()}]({$this->getTrigger()->getObjectUrl()})");
    }
}
