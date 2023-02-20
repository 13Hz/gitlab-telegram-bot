<?php

namespace App\Models\Core;

use App\Builders\Builder;
use App\Models\Gitlab\Request;

class TriggerBuilder implements Builder
{
    private Trigger $trigger;
    private string $text = '';
    protected string $objectName = 'объект';

    public function __construct(Request $request)
    {
        $this->trigger = new Trigger($request);
    }

    public function getTrigger(): Trigger
    {
        return $this->trigger;
    }

    public function getRequest(): Request
    {
        return $this->trigger->getRequest();
    }

    public function addLine(string $line): void
    {
        $this->text .= $line.PHP_EOL;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getAction(): string
    {
        return match ($this->getRequest()->objectAttributes->action) {
            "close" => "закрыл",
            "reopen" => "пересоздал",
            "update" => "изменил",
            "open" => "создал",
            default => "затронул",
        };
    }

    public function addRepositoryLink(): void
    {
        $repositoryName = $this->getTrigger()->getRepositoryName();
        $repositoryLink = $this->getTrigger()->getRepositoryLink();

        $this->addLine("[$repositoryName]($repositoryLink)");
    }

    public function addUserActionText(): void
    {
        $userName = $this->getTrigger()->getUserName();
        $userLink = $this->getTrigger()->getUserProfileLink();
        $objectId = $this->getTrigger()->getObjectId();
        $objectUrl = $this->getTrigger()->getObjectUrl();
        $action = $this->getAction();

        $this->addLine("Пользователь [$userName]($userLink) $action $this->objectName [№$objectId]($objectUrl)");
    }

    public function getMessage(): string
    {
        return $this->getText();
    }
}
