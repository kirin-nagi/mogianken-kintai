<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Rest;
use App\Models\User;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = User::where('role', 0)->get();

        $startDate = Carbon::create(2026, 1, 1);
        $endDate = Carbon::create(2026, 3, 31);

        foreach ($users as $user)
        {
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
            {
                if (rand(1, 10) <= 2)
                {
                    continue;
                }

                $start = $date->copy()->setTime(9, 0);
                $end = $date->copy()->setTime(18,0);

                // 分単位にした方が計算しやすい
                $restMinutes = 60;
                $totalMinutes = $end->diffInMinutes($start);
                $totalWork = $totalMinutes - $restMinutes;

                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'start_work' => $start,
                    'end_work' => $end,
                    'rest_time' => $restMinutes,
                    'total_work' => $totalWork,
                ]);

                Rest::create([
                    'attendance_id' => $attendance->id,
                    'start_time' => $date->copy()->setTime(12, 0),
                    'end_time' => $date->copy()->setTime(13, 0),
                ]);
            }
        }
    }
}
