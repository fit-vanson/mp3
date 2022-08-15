<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
//    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    protected $fillable = [

        'category_name',
        'category_order',
        'category_image',
        'category_view_count',
        'category_checked_ip'
        ];

//    public function wallpaper()
//    {
//        return $this->belongsToMany(Wallpapers::class, CategoriesHasWallpaper::class, 'category_id', 'wallpaper_id');
//    }

    public function wallpaper()
    {
        return $this->hasManyDeep(
            Wallpapers::class,
            [TagsHasCategories::class,TagsHasWallpaper::class], // Intermediate models and tables, beginning at the far parent (User).
            [
                'category_id', // Foreign key on the "role_user" table.
                'tag_id',      // Foreign key on the "permission_role" table
                'id'  // Foreign key on the "permissions" table. (local key)
            ],
            [
                'id',         // Local key on the "users" table.
                'tag_id', // Local key on the "role_user" table (foreign key).
                'wallpaper_id'   // Local key on the "permission_role" table.
            ]
        );
    }


    public function ringtone()
    {
        return $this->hasManyDeep(
            Ringtones::class,
            [TagsHasCategories::class,TagsHasRingtone::class], // Intermediate models and tables, beginning at the far parent (User).
            [
                'category_id', // Foreign key on the "role_user" table.
                'tag_id',      // Foreign key on the "permission_role" table
                'id'  // Foreign key on the "permissions" table. (local key)
            ],
            [
                'id',         // Local key on the "users" table.
                'tag_id', // Local key on the "role_user" table (foreign key).
                'ringtone_id'   // Local key on the "permission_role" table.
            ]
        );
    }

    public function tags()
    {
        return $this->belongsToMany(Tags::class, TagsHasCategories::class, 'category_id', 'tag_id');
    }

//    public function sites()
//    {
//        return $this->belongsToMany(Sites::class, CategoriesHasSites::class, 'category_id', 'site_id')->withPivot('site_image');
//    }

    public function sites()
    {
        return $this->belongsTo(Sites::class,  'site_id');
    }
}
