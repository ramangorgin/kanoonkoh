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
        Schema::create('program_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('planning_quality')->nullable();         // کیفیت برنامه‌ریزی
            $table->tinyInteger('execution_quality')->nullable();        // اجرای برنامه
            $table->tinyInteger('leadership_quality')->nullable();       // مدیریت سرپرست
            $table->tinyInteger('team_spirit')->nullable();              // روحیه تیمی
            $table->tinyInteger('safety_and_support')->nullable();       // ایمنی و پشتیبانی
            $table->text('feedback_text')->nullable();
            $table->boolean('is_anonymous')->default(false);
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
        Schema::dropIfExists('program_surveys');
    }
};
