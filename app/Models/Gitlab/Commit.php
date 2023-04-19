<?php

namespace App\Models\Gitlab;

use App\Models\Core\Json;

class Commit
{
    public mixed $id;
    public mixed $message;
    public mixed $title;
    public mixed $url;

    public function __construct(Json $data)
    {
        $this->id = $data->get('id');
        $this->message = $data->get('message');
        $this->title = $data->get('title');
        $this->url = $data->get('url');
    }
}
