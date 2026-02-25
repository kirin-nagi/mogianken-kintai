<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Rest;
use App\Models\User;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_date',
        'start_work',
        'end_work',
        'total_work',
    ];

    protected $casts = [
        'work_date' => 'date',
        'start_work' => 'datetime',
        'end_work' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 休憩何回もできる
    public function rests()
    {
        return $this->hasMany(Rest::class);
    }


    // 当日の勤務
    public static function todayForUser(){

        return self::where('user_id',auth()->id())
        ->whereDate('work_date', today())
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

    // 勤務一覧で休憩合計を1:00にする
    public function getTotalRestFormattedAttribute()
    {
        $minutes = $this->rests->sum('rest_time');

        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;

        return sprintf('%d:%02d', $hours, $minutes);
    }

    // 勤務一覧で勤務合計を8:00にする
    public function getTotalWorkFormattedAttribute()
    {
        if(!$this->total_work === null){
            return '0:00';
        }

        $hours = floor($this->total_work / 60);
        $minutes = $this->total_work % 60;

        return sprintf('%d:%02d', $hours, $minutes);
    }

    public function getTotalWorkSecondsAttribute()
    {
        if (!$this->end_work){
            return 0;
        }

        return $this->end_work->diffInSeconds($this->start_work) - ($this->rests->sum('rest_time') * 60);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    public function details()
    {
        return $this->hsMany(Detail::class);
    }

}
