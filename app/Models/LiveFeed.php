<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveFeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'device_id',
        'live_feed_url',
        'timestamp',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'timestamp' => 'datetime',
    ];
}
