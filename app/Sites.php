<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sites extends Model
{
    use HasFactory;

    protected $guarded = [];


//    public function categories()
//    {
//        return $this->belongsToMany(Categories::class, CategoriesHasSites::class, 'site_id', 'category_id')->withPivot('site_image');
//    }

    public function categories()
    {
        return $this->hasMany(Categories::class,'site_id');
    }

    public function list_ips()
    {
        return $this->hasMany(ListIP::class, 'id_site');
    }

    public function visitors()
    {
        return $this->belongsToMany(Visitors::class, VisitorFavorite::class, 'site_id', 'visitor_id');
    }




}
