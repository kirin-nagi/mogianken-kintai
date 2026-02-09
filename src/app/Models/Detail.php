<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Detail extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'user_id',
        'work_date',
        'start_work',
        'end_work',
        'rest_start',
        'rest_end',
        'rest_start2',
        'rest_end2',
        'reason',
    ];

    protected $casts = [
        'work_date' => 'datetime',
        'start_work' => 'datetime',
        'end_work' => 'datetime',
        'rest_start' => 'datetime',
        'rest_end' => 'datetime',
        'rest_start2' => 'datetime',
        'rest_end2' => 'datetime',
    ];
}