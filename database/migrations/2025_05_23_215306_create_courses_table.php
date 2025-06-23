<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('teacher')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('place')->nullable();
            $table->decimal('place_lat', 10, 7)->nullable();
            $table->decimal('place_lon', 10, 7)->nullable();
            $table->integer('capacity')->nullable();
            $table->boolean('is_free')->default(false);
            $table->integer('member_cost')->nullable();
            $table->integer('guest_cost')->nullable();      
            $table->string('card_number')->nullable();
            $table->string('sheba_number')->nullable();
            $table->string('card_holder')->nullable();
            $table->string('bank_name')->nullable();       
            $table->boolean('is_registration_open')->default(true);
            $table->string('registration_deadline')->nullable();       
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
