<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignCheckpoint extends Model
{
    use HasFactory;
    protected $table = 'safety_checkpoint';
    protected $fillable = [
        'SAFID',
        'CHKID',
    ];
}
