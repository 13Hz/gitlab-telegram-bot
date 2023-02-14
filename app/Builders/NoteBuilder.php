<?php

namespace App\Builders;

use App\Models\TriggerBuilder;

class NoteBuilder extends TriggerBuilder
{
    protected string $objectName = 'comment';

    public function addUserActionText(): void
    {
        $userName = $this->getTrigger()->getUserName();
        $userLink = $this->getTrigger()->getUserProfileLink();
        $objectUrl = $this->getTrigger()->getObjectUrl();
        $noteableType = $this->getRequest()->objectAttributes->noteable_type;
        $iid = $this->getRequest()->objectAttributes->iid;

        $this->addLine("Пользователь [$userName]($userLink) оставил [комментарий]($objectUrl) к $noteableType №$iid");
    }
}
