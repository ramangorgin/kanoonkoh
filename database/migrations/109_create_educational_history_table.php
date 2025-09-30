<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('educational_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('federation_course_id'); // وصل به جدول فدراسیون
            $table->date('completion_date')->nullable();
            $table->string('certificate_file')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('federation_course_id')
                ->references('id')->on('federation_courses')
                ->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};