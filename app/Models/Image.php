<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    
    protected $table = 'images';
    protected $fillable = ['image_path','image_title', 'save_image_by']; // Add save_image_by to fillable fields
}
