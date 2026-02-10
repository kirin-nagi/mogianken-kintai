<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('reason')->comment('申請理由');
            $table->tinyInteger('status')->default(0)->comment('0:承認待ち 1:承認済み');
            $table->datetime('targetdate')->comment('対象日時');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void済み
     */
    public function down()
    {
        Schema::dropIfExists('approvals');
    }
}
