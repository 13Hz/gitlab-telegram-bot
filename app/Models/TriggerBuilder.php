<?php

namespace App\Models;

use App\Models\Gitlab\Request;

class TriggerBuilder
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
}
