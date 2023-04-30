<?php

namespace App\Services;

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

    public function getLinkById(string|int $id): Link|null
    {
        return Link::find($id);
    }
}
