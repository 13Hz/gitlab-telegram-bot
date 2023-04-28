<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatLink;
use App\Models\Core\ResultContainer;
use App\Models\Link;
use Illuminate\Contracts\Database\Eloquent\Builder;

class LinkService
{
    public function getLinkOrCreate(string $url): Builder|Link
    {
        return Link::firstOrCreate([
            'link' => $url
        ]);
    }
}
