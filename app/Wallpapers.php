<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallpapers extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
//    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    protected $fillable = [
        'wallpaper_name',
        'wallpaper_image',
        'wallpaper_view_count',
        'wallpaper_like_count',
        'wallpaper_download_count',
        'wallpaper_feature',
        'image_extension',
    ];

//    public function categories()
//    {
//        return $this->belongsToMany(Categories::class, CategoriesHasWallpaper::class, 'wallpaper_id', 'category_id');
//    }

    public function categories()
    {
        return $this->hasManyDeep(
            Categories::class,
            [TagsHasWallpaper::class,TagsHasCategories::class], // Intermediate models and tables, beginning at the far parent (User).
            [
                'wallpaper_id', // Foreign key on the "role_user" table.
                'tag_id',      // Foreign key on the "permission_role" table
                'id'  // Foreign key on the "permissions" table. (local key)
            ],
            [
                'id',         // Local key on the "users" table.
                'tag_id', // Local key on the "role_user" table (foreign key).
                'category_id'   // Local key on the "permission_role" table.
            ]
        );
    }

    public function tags()
    {
        return $this->belongsToMany(Tags::class, TagsHasWallpaper::class, 'wallpaper_id', 'tag_id');
    }

    public function visitor_favorites()
    {
        return $this->hasMany(VisitorFavorite::class, 'wallpaper_id');
    }
}
