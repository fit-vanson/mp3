<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
//    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;


    protected $guarded = [];


    public function music()
    {
        return $this->hasManyDeep(
            Musics::class,
            [TagsHasCategories::class,TagsHasMusic::class], // Intermediate models and tables, beginning at the far parent (User).
            [
                'category_id', // Foreign key on the "role_user" table.
                'tag_id',      // Foreign key on the "permission_role" table
                'id'  // Foreign key on the "permissions" table. (local key)
            ],
            [
                'id',         // Local key on the "users" table.
                'tag_id', // Local key on the "role_user" table (foreign key).
                'music_id'   // Local key on the "permission_role" table.
            ]
        );
    }


    public function tags()
    {
        return $this->belongsToMany(Tags::class, TagsHasCategories::class, 'category_id', 'tag_id');
    }

    public function sites()
    {
        return $this->belongsTo(Sites::class,  'site_id');
    }
}
