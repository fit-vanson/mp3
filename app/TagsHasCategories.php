<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagsHasCategories extends Model
{
    use HasFactory;
    protected $fillable = [
        'tag_id',
        'category_id'
    ];
}
