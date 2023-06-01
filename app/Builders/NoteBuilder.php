<?php

namespace App\Builders;

use App\Models\Core\TriggerBuilder;

class NoteBuilder extends TriggerBuilder
{
    protected string $objectName = 'comment';

    public function addUserActionText(): void
    {
        $userName = $this->getTrigger()->getUserName();
        $userLink = $this->getTrigger()->getUserProfileLink();
        $objectUrl = $this->getTrigger()->getObjectUrl();
        $noteableType = $this->getRequest()->objectAttributes->noteable_type;
        $iid = $this->getRequest()->iid;
        $this->addLine("Пользователь [$userName]($userLink) оставил [комментарий]($objectUrl) к $noteableType №$iid");
    }

    public function addUserActionTextMultipleMessages(int $count): void
    {
        $userName = $this->getTrigger()->getUserName();
        $userLink = $this->getTrigger()->getUserProfileLink();
        $noteableType = $this->getRequest()->objectAttributes->noteable_type;
        $objectUrl = $this->getTrigger()->getObjectUrl();
        $iid = $this->getRequest()->iid;
        $text = declOfNum($count, ['%d комментарий', '%d комментария', '%d комментариев']);
        $this->addLine("Пользователь [$userName]($userLink) оставил [$text]($objectUrl) к $noteableType №$iid");
    }
}
