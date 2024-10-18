<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSite extends Model
{
    use HasFactory;
    protected $table = 'worksite';

    protected $fillable = [
        'Name',
        'Start_Date',
        'End_Date',
        'Description',
        'FeaturedImage',
        'CreateBy'
    ];
}
