<?php

namespace App\Builders;

interface Builder
{
    public function addRepositoryLink(): void;
    public function addUserActionText(): void;

    public function getMessage(): string;
}
