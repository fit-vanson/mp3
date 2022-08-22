<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagsHasMusic extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'tag_id',
        'music_id'
    ];
}
