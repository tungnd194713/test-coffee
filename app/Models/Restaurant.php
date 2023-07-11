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
        'end_crowded_time',
        'district',
        'name',
        'logo',
        'view',
        'is_confirm',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'is_confirm' => 'boolean',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'restaurant_service');
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function items() {
        return $this->hasMany(Item::class, 'restaurant_id', 'id');
    }
}
