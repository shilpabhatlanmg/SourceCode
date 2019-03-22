<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('organization_id')->unsigned()->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->integer('becon_major_id')->unsigned()->nullable();
            $table->foreign('becon_major_id')->references('id')->on('becon_majors')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('contact_number')->nullable();
            $table->string('country_code')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('password')->nullable();
            $table->text('temp_password')->nullable();
            $table->string('otp')->nullable();
            $table->datetime('otp_created_at')->nullable();
            $table->text('token')->nullable();
            $table->string('timezone')->nullable();
            $table->rememberToken();
            $table->text('device_token')->nullable();
            $table->enum('user_type', ['Security', 'Visitor'])->comment('1=>Security, 2=> Visitor')->default('Visitor');
            $table->enum('invitation_status', ['pending','resend-invitation','complete'])->default('Pending');
            $table->integer('badge_count')->unsigned()->default('0');
            $table->enum('status', ['Active', 'Inactive'])->default('Inactive');
            $table->datetime('last_respond_incident')->nullable();
            $table->datetime('last_login')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
