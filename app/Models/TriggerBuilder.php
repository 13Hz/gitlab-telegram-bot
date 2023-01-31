<?php

namespace App\Models;

use App\Builders\Builder;
use App\Models\Gitlab\Request;

class TriggerBuilder implements Builder
{
    private Trigger $trigger;
    private string $text = "";

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

    public function addLine(string $line): void {
        $this->text .= $line.PHP_EOL;
    }

    public function getText(): string {
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
        $this->addLine("[{$this->getTrigger()->getRepositoryName()}]({$this->getTrigger()->getRepositoryLink()})");
    }

    public function addUserActionText(): void
    {
        $this->addLine("Пользователь [{$this->getTrigger()->getUserName()}]({$this->getTrigger()->getUserProfileLink()}) {$this->getAction()} [объект #{$this->getTrigger()->getObjectId()}]({$this->getTrigger()->getObjectUrl()})");
    }

    public function addAdditionalText(): void
    {
        // TODO: Implement addAdditionalText() method.
    }

    public function getMessage(): string
    {
        return $this->getText();
    }
}
