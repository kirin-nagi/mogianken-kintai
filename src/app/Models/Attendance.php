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
        'total_work',
    ];

    // 休憩何回もできる
    public function rests(): HasMany
    {
        return $this->hasMany(Rest::class);
    }

    // 当日の勤務
    public static function todayForUser(): ?self{
        return self::where('user_id', auth()->id())
        ->whereDate('start_work', today())
        ->first();
    }

    // 退勤してるか
    public function isFinished(): bool
    {
        return !is_null($this->end_work);
    }

    // 休憩中か
    public function isOnRest(): bool
    {
        $latestRest = $this->Rests()
        ->latest()
        ->first();

        return $latestRest && is_null($latestRest->rest_end);
    }

}
