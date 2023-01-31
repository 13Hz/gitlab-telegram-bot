<?php

namespace App\Models;

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
