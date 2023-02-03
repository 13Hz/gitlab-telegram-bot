<?php

namespace App\Builders;

use App\Models\TriggerBuilder;

class MergeRequestBuilder extends TriggerBuilder
{
    protected string $objectName = 'merge request';

    public function addUserActionText(): void
    {
        $userName = $this->getTrigger()->getUserName();
        $userLink = $this->getTrigger()->getUserProfileLink();
        $objectId = $this->getTrigger()->getObjectId();
        $objectUrl = $this->getTrigger()->getObjectUrl();

        if ($this->getRequest()->objectAttributes->action === 'merge') {
            $this->addLine("Пользователь [$userName]($userLink) слил изменения из ветки `{$this->getRequest()->objectAttributes->source_branch}` в `{$this->getRequest()->objectAttributes->target_branch}`");
            $this->addLine("Merge request [№$objectId]($objectUrl)");
        } else {
            parent::addUserActionText();
        }
    }
}
