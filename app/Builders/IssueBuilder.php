<?php

namespace App\Builders;

use App\Models\TriggerBuilder;

class IssueBuilder extends TriggerBuilder implements Builder
{
    public function addRepositoryLink(): void
    {
        $this->addLine("[{$this->getTrigger()->getRepositoryName()}]({$this->getTrigger()->getRepositoryLink()})");
    }

    public function addUserActionText(): void
    {
        $action = match ($this->getRequest()->objectAttributes->action) {
            "close" => "закрыл",
            "reopen" => "пересоздал",
            "update" => "изменил",
            "open" => "создал",
            default => "затронул",
        };

        $this->addLine("Пользователь [{$this->getTrigger()->getUserName()}]({$this->getTrigger()->getUserProfileLink('https://example.com')}) $action [issue #{$this->getTrigger()->getObjectId()}]({$this->getTrigger()->getObjectUrl()})");
    }

    public function addAdditionalText(): void
    {

    }

    public function getMessage(): string
    {
        return $this->getText();
    }
}
