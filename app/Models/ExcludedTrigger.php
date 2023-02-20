<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcludedTrigger extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'chat_link_id',
        'trigger_id'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];
}
