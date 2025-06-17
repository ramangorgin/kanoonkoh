<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('photos')->nullable();
        
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->boolean('has_transport')->default(true);

            $table->dateTime('departure_dateTime_tehran')->nullable();
            $table->string('departure_place_tehran')->nullable();
            $table->decimal('departure_lat_tehran', 10, 7)->nullable();
            $table->decimal('departure_lon_tehran', 10, 7)->nullable();
        
            $table->dateTime('departure_dateTime_karaj')->nullable();
            $table->string('departure_place_karaj')->nullable();
            $table->decimal('departure_lat_karaj', 10, 7)->nullable();
            $table->decimal('departure_lon_karaj', 10, 7)->nullable();
        
            $table->text('required_equipment')->nullable();
            $table->text('required_meals')->nullable();
        
            $table->boolean('is_free')->default(false);
            $table->integer('member_cost')->nullable();
            $table->integer('guest_cost')->nullable();
        
            $table->string('card_number')->nullable();
            $table->string('sheba_number')->nullable();
            $table->string('card_holder')->nullable();
            $table->string('bank_name')->nullable();
        
        
            $table->boolean('is_registration_open')->default(true);
            $table->dateTime('registration_deadline')->nullable();
        
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('programs');
    }
};
