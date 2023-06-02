<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'open_time',
        'close_time',
        'crowded_time',
        'district',
        'name',
        'logo',
        'view',
        'is_confirm',
    ];

    protected $casts = [
        'is_confirm' => 'boolean',
        'close_time' => 'time',
        'open_time' => 'time',
        'crowded_time' => 'time',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
}
