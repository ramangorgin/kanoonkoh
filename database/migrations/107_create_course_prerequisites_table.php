<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_prerequisites', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('prerequisite_id');
            $table->timestamps();

            $table->foreign('course_id')
                ->references('id')->on('federation_courses')
                ->onDelete('cascade');

            $table->foreign('prerequisite_id')
                ->references('id')->on('federation_courses')
                ->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::dropIfExists('course_prerequisites');
    }
};
