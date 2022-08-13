<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagsHasRingtone extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'tag_id',
        'ringtone_id'
    ];
}
