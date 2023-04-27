<?php

namespace App\Models\Gitlab;

use App\Models\Core\Json;

class ObjectAttributes
{
    public string|null $title;
    public string|null $description;
    public string|null $url;
    public string|null $iid;
    public int|null $id;
    public string|null $action;
    public string|null $source_branch;
    public string|null $target_branch;
    public string|null $noteable_type;
    public string|null $note;
    public string|null $status;
    public int|null $duration;
    public int|null $queued_duration;

    public function __construct(Json $data)
    {
        $this->title = $data->get('title');
        $this->description = $data->get('description');
        $this->url = $data->get('url');
        $this->iid = $data->get('iid');
        $this->id = $data->get('id');
        $this->action = $data->get('action');
        $this->source_branch = $data->get('source_branch');
        $this->target_branch = $data->get('target_branch');
        $this->noteable_type = $data->get('noteable_type');
        $this->note = $data->get('note');
        $this->status = $data->get('status');
        $this->duration = $data->get('duration');
        $this->queued_duration = $data->get('queued_duration');
    }
}
