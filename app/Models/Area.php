<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $fillable = [
        'WSID',	'CreateBy',	'Area_Name',	'Orin_Device_ID',	'Orin_Device_Key'
    ];
}
