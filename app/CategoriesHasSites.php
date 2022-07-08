<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesHasSites extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'site_id',
        'site_image',
    ];
}
