<?php

namespace App\Factories;

use App\Builders\Builder;
use App\Builders\IssueBuilder;
use App\Models\Gitlab\Request;

class BuilderFactory
{
    /**
     * @param Request $request
     * @return Builder
     * @throws \Exception
     */
    public static function factory(Request $request): Builder
    {
        if($request->type === 'issue') {
            return new IssueBuilder($request);
        }

        throw new \Exception("Builder not found");
    }
}
