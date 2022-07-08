<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    public function wallpaper()
    {
        return $this->belongsToMany(Wallpapers::class, CategoriesHasWallpaper::class, 'category_id', 'wallpaper_id');
    }

    public function sites()
    {
        return $this->belongsToMany(Sites::class, CategoriesHasSites::class, 'category_id', 'site_id')->withPivot('site_image');
    }
}
