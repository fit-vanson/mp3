<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesHasWallpaper extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'wallpaper_id'
    ];
}
