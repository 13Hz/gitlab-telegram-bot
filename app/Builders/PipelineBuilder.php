<?php

namespace App\Builders;

use App\Models\Core\TriggerBuilder;

class PipelineBuilder extends TriggerBuilder
{
    protected string $objectName = 'pipeline';

    const AVAILABLE_STATUSES = [
        'pending' => '⏸️',
        'running' => '▶️',
        'success' => '✅',
        'canceled' => '🚫',
        'failed' => '❌'
    ];

    public function addUserActionText(): void
    {
        $id = $this->getRequest()->objectAttributes->id;
        $pipelineLink = $this->getTrigger()->getRepositoryLink()."/pipelines/$id";
        $status = $this->getRequest()->objectAttributes->status;
        $commitTitle = $this->getRequest()->commit->title;
        $commitUrl = $this->getRequest()->commit->url;
        if (in_array($status, array_keys(self::AVAILABLE_STATUSES))) {
            $emoji = self::AVAILABLE_STATUSES[$status];
            $this->addLine("$emoji Пайплайн [#$id]($pipelineLink) перешел в статус *$status*");
        } else {
            $this->addLine("Пайплайн [#$id]($pipelineLink) перешел в статус *$status*");
        }
        $this->addLine("Инициатор - [$commitTitle]($commitUrl)");
    }
}
