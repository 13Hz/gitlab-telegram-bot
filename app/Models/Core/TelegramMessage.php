<?php

namespace App\Models\Core;

use App\Builders\Builder;

class TelegramMessage
{
    public static function build(Builder $builder): ?string
    {
        $builder->addRepositoryLink();
        $builder->addUserActionText();

        return $builder->getMessage();
    }
}
