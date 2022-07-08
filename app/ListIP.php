<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListIP extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'id_site',
        'count',
    ];
}
