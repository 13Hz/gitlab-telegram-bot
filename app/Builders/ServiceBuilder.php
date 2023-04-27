<?php

namespace App\Builders;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface ServiceBuilder
{
    public function process(): Response;
}
