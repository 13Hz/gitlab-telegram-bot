<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Builder
 */
class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'link'
    ];

    protected $appends = [
        'repository_name'
    ];

    public function getRepositoryNameAttribute()
    {
        $link = $this->link;
        $matches = null;
        preg_match('/^http[s]?:\/\/.*?\/(.*)$/', $link, $matches);
        if ($matches) {
            return $matches[1];
        }

        return null;
    }

    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class);
    }
}
