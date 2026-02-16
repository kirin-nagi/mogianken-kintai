<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Detail;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_id',
        'reason',
        'status',
        'targetdate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'status' => 'integer',
        'targetdate' => 'datetime',
    ];

    public function detail()
    {
        return $this->hasOne(Detail::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}

