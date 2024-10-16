<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    use HasFactory;
    protected $table = 'usermeta';

    protected $fillable = [
        'userId',
        'featuredImage',
        'role',
        'createBy'
    ];
}
