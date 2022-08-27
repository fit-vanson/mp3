<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Musics extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $fillable = [
        'uuid',
        'music_name',
        'music_image',
        'music_url_image',
        'music_file',
        'music_view_count',
        'music_like_count',
        'music_download_count',
        'music_feature',
        'music_status',
        'music_type',
        'music_hash',
        'music_link_1',
        'music_link_2',
        'music_id_ytb',
        'music_url_link_ytb',
        'time_get_url_ytb',
        'music_url_download',
    ];

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
