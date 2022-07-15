<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagsHasWallpaper extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag_id',
        'wallpaper_id'
    ];
}
