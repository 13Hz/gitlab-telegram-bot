<?php

namespace App\Models\Gitlab;

class Project {

    public $id;
    public $name;
    public $description;
    public $web_url;
    public $path_with_namespace;

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->web_url = $data['web_url'];
        $this->path_with_namespace = $data['path_with_namespace'];
    }
}
