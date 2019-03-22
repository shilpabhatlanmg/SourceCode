<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chat_id')->unsigned()->nullable();
            $table->integer('chat_user_id')->unsigned()->nullable();
            $table->string('chat_user_name')->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->string('product_title')->nullable();
            $table->datetime('chat_date_time')->nullable();
            $table->text('latest_msg')->nullable();
            $table->integer('user_id')->unsigned();
            $table->string('chat_user_image')->nullable();
            $table->integer('count')->unsigned();
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
        Schema::dropIfExists('chat_records');
    }
}
