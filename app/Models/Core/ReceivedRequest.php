<?php

namespace App\Models\Core;

use Illuminate\Http\Request;

abstract class ReceivedRequest
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
