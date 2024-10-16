<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkpoints extends Model
{
    use HasFactory;
    protected $table = 'checkpoints';
    protected $fillable = [
        'title',
        'Description',
        'Images',
        'Videos',
        'CreatedBy'
    ];

}
