<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'link'
    ];

    public function chats(): BelongsToMany {
        return $this->belongsToMany(Chat::class);
    }
}
