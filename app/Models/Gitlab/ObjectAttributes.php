<?php

namespace App\Models\Gitlab;

class ObjectAttributes {

    public string $title;
    public string | null $description;
    public string $url;
    public string $iid;
    public string | null $action;
    public string | null $source_branch;
    public string | null $target_branch;

    public function __construct($data) {
        $this->title = $data['title'];
        $this->description = $data['description'] ?? null;
        $this->url = $data['url'];
        $this->iid = $data['iid'];
        $this->action = $data['action'] ?? null;
        $this->source_branch = $data['source_branch'] ?? null;
        $this->target_branch = $data['target_branch'] ?? null;
    }
}
