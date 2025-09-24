<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('federation_courses', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary(); // کد رسمی فدراسیون
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('federation_courses');
    }
};
