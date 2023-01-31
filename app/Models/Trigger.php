<?php

namespace App\Models;

use App\Models\Gitlab\Request;

class Trigger
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getRepositoryLink(): string
    {
        return $this->request->project->web_url;
    }

    public function getRepositoryName(): string
    {
        return $this->request->project->path_with_namespace;
    }

    public function getUserName(): string
    {
        return $this->request->user->username;
    }

    public function getUserProfileLink(): string
    {
        $site = $this->request->host;

        return "$site/{$this->request->user->username}";
    }

    public function getObjectId(): mixed
    {
        return $this->request->objectAttributes->iid;
    }

    public function getObjectUrl(): string
    {
        return $this->request->objectAttributes->url;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
