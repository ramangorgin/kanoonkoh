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
        Schema::create('program_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->boolean('is_anonymous')->default(false);
            $table->tinyInteger('program_coordination')->nullable();
            $table->tinyInteger('leader_performance')->nullable();
            $table->tinyInteger('route_safety')->nullable();
            $table->tinyInteger('group_spirit')->nullable();
            $table->tinyInteger('equipment_handling')->nullable();
            $table->tinyInteger('comfort_and_logistics')->nullable();
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
        Schema::dropIfExists('program_feedbacks');
    }
};
