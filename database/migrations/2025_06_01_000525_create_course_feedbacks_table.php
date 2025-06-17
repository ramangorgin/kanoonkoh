<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->boolean('is_anonymous')->default(false);
            $table->tinyInteger('content_quality')->nullable();
            $table->tinyInteger('teaching_skill')->nullable();
            $table->tinyInteger('materials_quality')->nullable();
            $table->tinyInteger('usefulness')->nullable();
            $table->tinyInteger('instructor_behavior')->nullable();
            $table->text('feedback_text')->nullable();
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
        Schema::dropIfExists('course_feedbacks');
    }
};
