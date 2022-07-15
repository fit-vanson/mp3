<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use HasFactory;
    protected $fillable = [
        'tag_name'
    ];

    public function wallpaper()
    {
        return $this->belongsToMany(Wallpapers::class, TagsHasWallpaper::class, 'tag_id', 'wallpaper_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Categories::class, TagsHasCategories::class, 'tag_id', 'category_id');
    }
}
