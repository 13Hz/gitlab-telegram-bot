<?php

namespace App\Models\Gitlab;

class User {
    public int $id; // json:id Required
    public string $name; // json:name Required
    public string $username; // json:username Required
    public string $avatar_url; // json:avatar_url Required
    public string $email; // json:email Required

    /**
     * @param int $id
     * @param string $name
     * @param string $username
     * @param string $avatarURL
     * @param string $email
     */
    public function __construct($data) {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->username = $data['username'];
        $this->avatar_url = $data['avatar_url'];
        $this->email = $data['email'];
    }
}
