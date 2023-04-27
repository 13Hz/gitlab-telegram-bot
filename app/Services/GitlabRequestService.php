<?php

namespace App\Services;

use App\Builders\ServiceBuilder;
use App\Factories\BuilderFactory;
use App\Models\Core\Json;
use App\Models\Core\ReceivedRequest;
use App\Models\Core\TelegramMessage;
use Illuminate\Http\Response;

class GitlabRequestService extends ReceivedRequest implements ServiceBuilder
{
    public function process(): Response
    {
        $hookRequest = new \App\Models\Gitlab\Request(new Json($this->request->all()), $this->request->header('X-Gitlab-Instance'));
        $gitlabRepositoryService = new GitlabRepositoryService($hookRequest);
        $link = $gitlabRepositoryService->getLinkByUrl($hookRequest->project->web_url);
        if ($link) {
            $builder = BuilderFactory::factory($hookRequest);
            $text = TelegramMessage::build($builder);
            $ids = $gitlabRepositoryService->sendMessageToChats($link, $text);

            if (!empty($ids)) {
                return \response('Получатели: ' . implode(', ', $ids));
            }
        }
        return \response('Нет получателей');
    }
}
