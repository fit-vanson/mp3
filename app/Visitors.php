<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitors extends Model
{
    use HasFactory;
    protected $fillable = [
        'device_id'
    ];

    public function sites()
    {
        return $this->belongsToMany(Sites::class, VisitorFavorite::class, 'visitor_id', 'site_id');
    }
    public function wallpapers(){
        return $this->belongsToMany(Wallpapers::class,'visitor_favorites','visitor_id','wallpaper_id')->withPivot('site_id');
    }
}
