<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // اضافه کردن فقط ستون‌های گم‌شده
            if (!Schema::hasColumn('payments', 'membership_code')) {
                $table->string('membership_code', 20)->nullable()->after('payment_date');
            }

            if (!Schema::hasColumn('payments', 'transaction_code')) {
                $table->string('transaction_code', 20)->unique()->after('membership_code');
            }

            if (!Schema::hasColumn('payments', 'status')) {
                $table->string('status', 20)->default('pending')->after('transaction_code');
            }

            if (!Schema::hasColumn('payments', 'description')) {
                $table->text('description')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'membership_code')) {
                $table->dropColumn('membership_code');
            }
            if (Schema::hasColumn('payments', 'transaction_code')) {
                $table->dropColumn('transaction_code');
            }
            if (Schema::hasColumn('payments', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('payments', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
