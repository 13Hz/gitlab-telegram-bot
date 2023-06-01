<?php

namespace App\Models\Gitlab;

use App\Models\Core\Json;

class Request
{
    public ?string $type;
    public User $user;
    public Project $project;
    public ObjectAttributes $objectAttributes;
    public MergeRequest $mergeRequest;
    public Issue $issue;
    public Commit $commit;
    public ?string $host;
    public ?int $iid;

    public function __construct(Json $data, $host = null)
    {
        $this->type = $data->get('event_type') ?? $data->get('object_kind');
        $this->user = new User(new Json($data->get('user')));
        $this->project = new Project(new Json($data->get('project')));
        $this->objectAttributes = new ObjectAttributes(new Json($data->get('object_attributes')));
        $this->mergeRequest = new MergeRequest(new Json($data->get('merge_request')));
        $this->issue = new Issue(new Json($data->get('issue')));
        $this->commit = new Commit(new Json($data->get('commit')));
        $this->iid = match ($this->objectAttributes->noteable_type) {
            'MergeRequest' => $this->mergeRequest->iid,
            'Issue' => $this->issue->iid,
        };
        $this->host = $host;
    }
}
