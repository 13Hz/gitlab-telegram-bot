<?php

namespace App\Models\Gitlab;

use App\Models\Json;

class Request {

    public string $type;

    public User $user;

    public Project $project;

    public ObjectAttributes $objectAttributes;

    public string $host;

    public function __construct(Json $data, $host = null)
    {
        $this->type = $data->get('event_type');
        $this->user = new User(new Json($data->get('user')));
        $this->project = new Project(new Json($data->get('project')));
        $this->objectAttributes = new ObjectAttributes(new Json($data->get('object_attributes')));

        $this->host = $host;
    }
}
