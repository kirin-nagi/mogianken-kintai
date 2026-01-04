<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_work',
        'end_work',
        'rest_time',
        'total_work',
    ];

    protected $casts = [
        'start_work' => 'datetime',
        'end_work' => 'datetime',
    ];

    // 休憩何回もできる
    public function rests()
    {
        return $this->hasMany(Rest::class);
    }


    // 当日の勤務
    public static function todayForUser(){

        return self::where('user_id',auth()->id())
        ->whereDate('start_work', today())
        ->first();
    }

    // 退勤してるか
    public function isFinished()
    {
        return !is_null($this->end_work);
    }

    // 休憩中か
    public function isOnRest()
    {
        return $this->rests()->whereNull('rest_end')->exists();
    }

}
