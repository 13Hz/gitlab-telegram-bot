<?php

namespace App\Factories;

use App\Builders\Builder;
use App\Builders\IssueBuilder;
use App\Builders\MergeRequestBuilder;
use App\Builders\OtherBuilder;
use App\Models\Gitlab\Request;

class BuilderFactory
{
    /**
     * @param Request $request
     * @return Builder
     */
    public static function factory(Request $request): Builder
    {
        if($request->type === 'issue') {
            return new IssueBuilder($request);
        }
        else if($request->type === 'merge_request')
        {
            return new MergeRequestBuilder($request);
        }
        else {
            return new OtherBuilder($request);
        }
    }
}
