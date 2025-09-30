<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('profile_image')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('biography')->nullable();
            $table->text('skills')->nullable();        // میشه JSON یا متن ساده
            $table->text('certificates')->nullable();  // میشه JSON یا متن ساده
            $table->timestamps();
        });  
    }

    public function down()
    {
        Schema::dropIfExists('teachers');
    }
};
