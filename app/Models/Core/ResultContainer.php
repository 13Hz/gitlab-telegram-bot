<?php

namespace App\Models\Core;

class ResultContainer
{
    private bool $isSuccess;
    private string $resultMessage;

    public function __construct(bool $isSuccess = false, string $resultMessage = '')
    {
        $this->isSuccess = $isSuccess;
        $this->resultMessage = $resultMessage;
    }

    public function addLine(string $text): self
    {
        $this->resultMessage .= $text.PHP_EOL;

        return $this;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function setSuccess(bool $success): self
    {
        $this->isSuccess = $success;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->resultMessage;
    }

    public function setMessage(string $message): self
    {
        $this->resultMessage = $message;

        return $this;
    }
}
