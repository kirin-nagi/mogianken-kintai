<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = range(2, 7);

        foreach ($users as $userId)
            {
                $dates = collect();

                while($dates->count() < 10){
                    $dates->push(
                        Carbon::create(2026, rand(1,3), rand(1, 28))->toDateString());
                        $dates = $dates->unique();
                }

                foreach ($dates as $date)
                    {
                        DB::table('requests')->insert([
                            'user_id' => $userId,
                            'work_date' => $date,
                            'start_work' => Carbon::parse("$date 09:00:00"),
                            'end_work' => Carbon::parse("$date 18:00:00"),
                            'rest_start' => Carbon::parse("$date 12:00:00"),
                            'rest_end' => Carbon::parse("$date 13:00:00"),
                            'rest_start2' => null,
                            'rest_end2' => null,
                            'reason' => '遅延のため',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
            }
    }
}
