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

    public function details_google_ads()
    {
        return $this->hasMany(DetailsGoogle_ads::class,  'google_ads_id');
    }
}
