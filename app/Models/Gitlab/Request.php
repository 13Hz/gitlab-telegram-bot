<?php

namespace App\Models\Gitlab;

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

    public function __construct($data)
    {
        $this->type = $data['event_type'];
        $this->user = new User($data['user']);
        $this->project = new Project($data['project']);
        $this->objectAttributes = new ObjectAttributes($data['object_attributes']);
    }
}
