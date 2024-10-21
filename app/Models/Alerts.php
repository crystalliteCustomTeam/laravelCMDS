<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerts extends Model
{
    use HasFactory;
    protected $table = 'alerts';
    protected $fillable = [
        'alert_code', 'area_code', 'risk_level', 'description', 'captured_image_url',
    ];
}
