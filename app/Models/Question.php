<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'body',
        'type',
        'lat',
        'long',
        'edited_id',
    ];
}
 