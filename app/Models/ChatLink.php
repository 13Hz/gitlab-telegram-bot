<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class ChatLink extends Model
{
    use HasFactory;

    protected $table = 'chat_link';

    public function chat(): HasOne
    {
        return $this->hasOne(Chat::class);
    }

    public function link(): HasOne
    {
        return $this->hasOne(Link::class);
    }
}
