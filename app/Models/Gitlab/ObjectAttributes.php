<?php

namespace App\Models\Gitlab;

class ObjectAttributes {

    public string $title;
    public string $description;
    public string $url;
    public string $iid;
    public string $action;
    public string $source_branch;
    public string $target_branch;

    public function __construct($data) {
        $this->title = $data['title'];
        $this->description = $data['description'] ?? "";
        $this->url = $data['url'];
        $this->iid = $data['iid'];
        $this->action = $data['action'];
        $this->source_branch = $data['source_branch'];
        $this->target_branch = $data['target_branch'];
    }
}
