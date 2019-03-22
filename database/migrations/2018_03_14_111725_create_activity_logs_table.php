<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('device_id', 191)->nullable();
            $table->tinyInteger('activity_id')->nullable();
            $table->string('message', 191);
            $table->string('method', 191);
            $table->text('request_data');
            $table->text('response_data');
            $table->string('ip_address', 191);
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}
