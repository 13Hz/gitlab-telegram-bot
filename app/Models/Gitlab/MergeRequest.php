<?php

namespace App\Models\Gitlab;

use App\Models\Json;

class MergeRequest
{
    public int $assignee_id;
    public int $author_id;
    public int $iid;
    public null | string $merge_status;
    public null | string $source_branch;
    public null | string $target_branch;


    public function __construct(Json $data)
    {
        $this->assignee_id = intval($data->get('assignee_id'));
        $this->author_id = intval($data->get('author_id'));
        $this->iid = intval($data->get('iid'));
        $this->merge_status = $data->get('merge_status');
        $this->source_branch = $data->get('source_branch');
        $this->target_branch = $data->get('target_branch');
    }
}
