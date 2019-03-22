<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestimonialsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('author_image')->nullable();
            $table->text('content')->nullable();
            $table->string('author_rating')->nullable();
            $table->string('occupation')->nullable();
            $table->string('author_email')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->date('feedback_date')->nullable();
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
        Schema::dropIfExists('testimonials');
    }
}
