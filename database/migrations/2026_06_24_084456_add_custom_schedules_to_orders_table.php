<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->json('custom_schedules')->nullable()->after('payment_scheme');
            $table->decimal('extra_guest_charge', 12, 2)->default(0)->after('total_price');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['custom_schedules', 'extra_guest_charge']);
        });
    }
};
