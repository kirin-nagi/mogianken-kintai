<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('attendance_id')->constrained('attendances')->cascadeOnDelete();
            $table->foreignId('approval_id')->constrained('approvals')->cascadeOnDelete();
            $table->date('work_date');
            $table->datetime('start_work');
            $table->datetime('end_work');
            $table->datetime('rest_start');
            $table->datetime('rest_end');
            $table->datetime('rest_start2')->nullable();
            $table->datetime('rest_end2')->nullable();
            $table->string('reason')->comment('申請理由');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
