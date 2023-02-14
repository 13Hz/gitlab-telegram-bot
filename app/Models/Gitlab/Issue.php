<?php

namespace App\Models\Gitlab;

use App\Models\Json;

class Issue
{
    public int | null $assignee_id;
    public int $author_id;
    public int $iid;


    public function __construct(Json $data)
    {
        $this->assignee_id = $data->get('assignee_id');
        $this->author_id = $data->get('author_id');
        $this->iid = $data->get('iid');
    }
}
