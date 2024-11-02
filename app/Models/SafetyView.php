<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafetyView extends Model
{
    protected $table = 'safetyview';
    protected $fillable = [
        'safetyID',
        'userId',
    ];
}
