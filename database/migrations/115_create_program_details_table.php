<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');

            $table->longText('report')->nullable();

            $table->string('weather')->nullable();
            $table->string('temperature')->nullable();
            $table->string('wind_speed')->nullable();
            $table->string('vegetation')->nullable();
            $table->string('wildlife')->nullable();
            $table->string('local_language')->nullable();
            $table->string('historical_sites')->nullable();
            $table->text('route_description')->nullable();
            $table->text('important_notes')->nullable();

            $table->json('execution_schedule')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_details');
    }
};
