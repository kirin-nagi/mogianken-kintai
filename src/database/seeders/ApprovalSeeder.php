<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;

class ApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\Models\User::where('role', 0)->get();

        foreach ($users as $user){

    $attendances = Attendance::where('user_id', $user->id)
    ->inRandomOrder()
    ->take(10)
    ->get();

    $statusCounts = [
        0 => 0,
        1 => 0,
    ];

    foreach($attendances as $attendance){

    if($statusCounts[0] < 5){
        $status = 0;
        $statusCounts[0]++;
    } elseif ($statusCounts[1] < 5){
        $status = 1;
        $statusCounts[1]++;
    } else {
        break;
    }

            DB::table('approvals')->insert([
                'user_id' => $attendance->user_id,
                'attendance_id' => $attendance->id,
                'targetdate' => $attendance->start_work,
                'reason' => '遅延のため',
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

}
