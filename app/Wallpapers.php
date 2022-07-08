<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallpapers extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallpaper_name',
        'wallpaper_image',
        'wallpaper_view_count',
        'wallpaper_like_count',
        'wallpaper_download_count',
        'wallpaper_feature',
        'image_extension',
    ];

    public function categories()
    {
        return $this->belongsToMany(Categories::class, CategoriesHasWallpaper::class, 'wallpaper_id', 'category_id');
    }
}
