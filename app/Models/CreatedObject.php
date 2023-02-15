<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreatedObject extends Model
{
    use HasFactory;

    protected $fillable = [
        'object_type',
        'object_id',
        'chat_id',
        'message_id'
    ];
}
