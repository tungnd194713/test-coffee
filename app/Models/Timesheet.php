<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'date',
        'difficulties',
        'todo',
        'check_in',
        'check_out',
    ];

    public function tasks() {
        return $this->hasMany(Task::class, 'timesheet_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
