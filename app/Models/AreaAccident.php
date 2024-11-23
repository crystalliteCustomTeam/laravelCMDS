<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaAccident extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'accident_code',
        'description',
        'severity_level',
        'timestamp',
        'captured_image_url',
        'reported_by',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];
}
