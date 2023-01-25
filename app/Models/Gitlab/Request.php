<?php

namespace App\Models\Gitlab;

use App\Models\Json;

class Request {
    /**
     * @var string
     */
    public string $type;

    /**
     * @var User
     */
    public User $user;

    /**
     * @var Project
     */
    public Project $project;

    public ObjectAttributes $objectAttributes;

    public function __construct(Json $data)
    {
        $this->type = $data->get('event_type');
        $this->user = new User(new Json($data->get('user')));
        $this->project = new Project(new Json($data->get('project')));
        $this->objectAttributes = new ObjectAttributes(new Json($data->get('object_attributes')));
    }
}
