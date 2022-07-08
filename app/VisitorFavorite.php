<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorFavorite extends Model
{
    use HasFactory;
    protected $fillable = [
        'visitor_id', 'wallpaper_id','site_id'
    ];

    public function wallpaper()
    {
        return $this->belongsTo(Wallpapers::class,  'wallpaper_id');
    }




}
