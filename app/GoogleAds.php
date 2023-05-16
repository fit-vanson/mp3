<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleAds extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'site_redirect',
        'country_value',
        'devices_value',
        'html',
        'url_block',
        'is_Devices',
    ];
}
