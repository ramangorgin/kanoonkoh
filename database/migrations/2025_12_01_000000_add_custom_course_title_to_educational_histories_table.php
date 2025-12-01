<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('educational_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('educational_histories', 'custom_course_title')) {
                $table->string('custom_course_title')->nullable()->after('federation_course_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('educational_histories', function (Blueprint $table) {
            if (Schema::hasColumn('educational_histories', 'custom_course_title')) {
                $table->dropColumn('custom_course_title');
            }
        });
    }
};


