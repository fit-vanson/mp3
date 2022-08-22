<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorFavorite extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'visitor_id', 'music_id','site_id'
    ];


    public function music()
    {
        return $this->belongsTo(Musics::class,  'music_id');
    }




}
