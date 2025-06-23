<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->nullable()->constrained()->onDelete('set null');
        
            $table->string('title');
            $table->text('content')->nullable();
            $table->json('gallery')->nullable();
            $table->boolean('approved')->default(false);
                    
            $table->string('type')->nullable();
            $table->string('area')->nullable();
            $table->integer('peak_height')->nullable();
            $table->integer('start_height')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('writer_name')->nullable();
            $table->string('technical_level')->nullable();
            $table->string('road_type')->nullable();
            $table->json('transportation')->nullable();
            $table->json('water_type')->nullable();
            $table->json('required_equipment')->nullable();
            $table->json('required_skills')->nullable();
            $table->string('difficulty')->nullable();
            
            $table->text('natural_description')->nullable();
            $table->string('weather')->nullable();
            $table->string('wind_speed')->nullable();
            $table->string('temperature')->nullable();
            $table->string('vegetation')->nullable();
            $table->string('wildlife')->nullable();
            $table->string('local_language')->nullable();
            $table->string('historical_sites')->nullable();
            $table->text('important_notes')->nullable();
            $table->string('food_availability')->nullable();
            
            $table->json('route_points')->nullable();
            $table->json('execution_schedule')->nullable();

            $table->string('pdf_path')->nullable();
            $table->string('track_file_path')->nullable();

            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
