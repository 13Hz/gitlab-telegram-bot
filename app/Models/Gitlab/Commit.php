<?php

namespace App\Models\Gitlab;

use App\Models\Core\Json;

class Commit
{
    public string $id;
    public string $message;
    public string $title;
    public string $url;

    public function __construct(Json $data)
    {
        $this->id = $data->get('id');
        $this->message = $data->get('message');
        $this->title = $data->get('title');
        $this->url = $data->get('url');
    }
}
