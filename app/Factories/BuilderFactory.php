<?php

namespace App\Factories;

use App\Builders\Builder;
use App\Builders\IssueBuilder;
use App\Builders\MergeRequestBuilder;
use App\Builders\NoteBuilder;
use App\Builders\OtherBuilder;
use App\Builders\PipelineBuilder;
use App\Models\Gitlab\Request;

class BuilderFactory
{
    /**
     * @param Request $request
     * @return Builder
     */
    public static function factory(Request $request): Builder
    {
        return match ($request->type) {
            'issue' => new IssueBuilder($request),
            'merge_request' => new MergeRequestBuilder($request),
            'note' => new NoteBuilder($request),
            'pipeline' => new PipelineBuilder($request),
            default => new OtherBuilder($request)
        };
    }
}
