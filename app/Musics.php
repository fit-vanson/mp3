<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Musics extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;


    protected $guarded= [];

    protected $table = 'musics_ytb';

//



    public function categories()
    {
        return $this->hasManyDeep(
            Categories::class,
            [TagsHasMusic::class,TagsHasCategories::class], // Intermediate models and tables, beginning at the far parent (User).
            [
                'music_id', // Foreign key on the "role_user" table.
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
        return $this->belongsToMany(Tags::class, TagsHasMusic::class, 'music_id', 'tag_id');
    }



}
