<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'restaurant_id',
        'description',
        'price',
        'thumbnail_image',
    ];

    public function restaurant() {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
}
