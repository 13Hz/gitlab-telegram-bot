<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Builder
 */
class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'is_admin',
        'type',
    ];

    protected $casts = [
        'is_admin' => 'boolean'
    ];

    public function links(): BelongsToMany
    {
        return $this->belongsToMany(Link::class);
    }
}
