<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
                ->constrained('courses') 
                ->onDelete('cascade');

            $table->enum('file_type', ['poster', 'image', 'pdf', 'other'])->default('image');
            $table->string('file_path'); 
            $table->string('caption')->nullable(); 

            $table->timestamps();
        });
       
    }

    public function down()
    {
        Schema::dropIfExists('course_files');
    }
};
