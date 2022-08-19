<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ringtones extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $fillable = [
        'ringtone_name',
        'ringtone_file',
        'ringtone_view_count',
        'ringtone_like_count',
        'ringtone_download_count',
        'ringtone_feature',
        'ringtone_extension',
        'ringtone_status',
        'ringtone_type',
        'ringtone_hash',

    ];

    public function categories()
    {
        return $this->hasManyDeep(
            Categories::class,
            [TagsHasRingtone::class,TagsHasCategories::class], // Intermediate models and tables, beginning at the far parent (User).
            [
                'ringtone_id', // Foreign key on the "role_user" table.
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
        return $this->belongsToMany(Tags::class, TagsHasRingtone::class, 'ringtone_id', 'tag_id');
    }
}
