<?php

namespace App\Models\Core;

class Json
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function get($key)
    {
        return $this->data[$key] ?? null;
    }
}
