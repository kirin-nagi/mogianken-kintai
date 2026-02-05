<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApprovalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requests = DB::table('requests')->get();

        foreach($requests as $request){
            DB::table('approvals')->insert([
                'user_id' => $request->user_id,
                'request_id' =>$request->id,
                'reason' => $request->reason,
                'status' => rand(0, 1),
                'targetdate' => $request->work_date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
