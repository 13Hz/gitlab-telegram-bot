<?php

namespace App\Models\Gitlab;

use App\Models\Json;

class MergeRequest
{
    public int | null $assignee_id;
    public int $author_id;
    public int $iid;
    public string $merge_status;
    public string $source_branch;
    public string $target_branch;


    public function __construct(Json $data)
    {
        $this->assignee_id = $data->get('assignee_id');
        $this->author_id = $data->get('author_id');
        $this->iid = $data->get('iid');
        $this->merge_status = $data->get('merge_status');
        $this->source_branch = $data->get('source_branch');
        $this->target_branch = $data->get('target_branch');
    }
}
