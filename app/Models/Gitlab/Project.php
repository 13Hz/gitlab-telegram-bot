<?php

namespace App\Models\Gitlab;

use App\Models\Json;

class Project {

    public string | null $id;
    public string | null $name;
    public string | null $description;
    public string | null $web_url;
    public string | null $path_with_namespace;

    public function __construct(Json $data)
    {
        $this->id = $data->get('id');
        $this->name = $data->get('name');
        $this->description = $data->get('description');
        $this->web_url = $data->get('web_url');
        $this->path_with_namespace = $data->get('path_with_namespace');
    }
}
