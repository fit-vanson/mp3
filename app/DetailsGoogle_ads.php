<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailsGoogle_ads extends Model
{
    use HasFactory;
    protected $fillable = [
        'google_ads_id',
        'ip_address',
        'device_name',
        'device_name_full',
        'country'
    ];
}
