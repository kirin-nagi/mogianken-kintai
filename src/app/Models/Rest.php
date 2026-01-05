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

    protected $casts = [
        'rest_start' => 'datetime',
        'rest_end' => 'datetime',
    ];

    // 休憩合計を1:00で表示する
    public function getRestTimeFormattedAttribute()
    {
        if(!$this->rest_time){
            return '0.00';
        }

        $hours = floor($this->rest_time / 60);
        $minutes = $this->rest_time % 60;

        return sprintf('%d:%02d', $hours, $minutest);
    }
}
