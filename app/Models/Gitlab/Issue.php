<?php

namespace App\Models\Gitlab;

use App\Models\Core\Json;

class Issue
{
    public int $assignee_id;
    public int $author_id;
    public int $iid;

    public function __construct(Json $data)
    {
        $this->assignee_id = intval($data->get('assignee_id'));
        $this->author_id = intval($data->get('author_id'));
        $this->iid = intval($data->get('iid'));
    }
}
