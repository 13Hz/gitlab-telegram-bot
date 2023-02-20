<?php

namespace App\Models\Gitlab;

use App\Models\Core\Json;

class User
{
    public int | null $id;
    public string | null $name;
    public string | null $username;
    public string | null $avatar_url;
    public string | null $email;

    public function __construct(Json $data)
    {
        $this->id = $data->get('id');
        $this->name = $data->get('name');
        $this->username = $data->get('username');
        $this->avatar_url = $data->get('avatar_url');
        $this->email = $data->get('email');
    }
}
