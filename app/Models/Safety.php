<?php

namespace App\Models;
use App\Models\SafetyView;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Safety extends Model
{
    use HasFactory;
    protected $table = 'safety';
    protected $fillable = [
        'icon',
        'Images',
        'title',
        'description',
        'CreatedBy',
    ];

    public function safetyView()
    {
        return $this->hasOne(SafetyView::class, 'safetyID', 'id');
    }
}
