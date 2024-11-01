<?php

namespace App\Models;

use App\Models\AreaUser;
use App\Models\Notification;
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
        'CreateBy',
    ];

    public function areaUsers()
    {
        return $this->hasMany(AreaUser::class, 'WSID', 'id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'WSID', 'id')
            ->whereJsonContains('WSID', $this->id);
    }
}
