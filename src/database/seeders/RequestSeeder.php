<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Approval;

class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendances = Attendance::all();

        foreach ($attendances as $attendance)
            {

                $rest = $attendance->rests()->first();

                $approval = Approval::where('attendance_id', $attendance->id)->first();

                if(!$approval) continue;

                DB::table('requests')->insert([
                    'user_id' => $attendance->user_id,
                    'approval_id' => $approval->id,
                    'attendance_id' => $attendance->id,
                    'work_date' => $attendance->start_work,
                    'start_work' => $attendance->start_work,
                    'end_work' => $attendance->end_work,
                    'rest_start' => optional($rest)->rest_start,
                    'rest_end' => optional($rest)->rest_end,
                    'rest_start2' => null,
                    'rest_end2' => null,
                    'reason' => '遅延のため',
                    'created_at' => now(),
                    'updated_at' => now(),
                        ]);
            }
    }
}
