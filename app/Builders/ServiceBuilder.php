<?php

namespace App\Builders;

use Illuminate\Http\Response;

interface ServiceBuilder
{
    public function process(): Response;
}
