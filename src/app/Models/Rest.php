<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stamp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'rest_start',
        'rest_end',
        'rest_time',
    ];
}
