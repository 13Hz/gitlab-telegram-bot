<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'type',
    ];

    public function links(): BelongsToMany {
        return $this->belongsToMany(Link::class);
    }
}
