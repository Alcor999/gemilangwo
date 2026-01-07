<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Event days calculation - how many days before and after event
            $table->integer('pre_event_days')->default(0)->after('event_date');
            $table->integer('post_event_days')->default(0)->after('pre_event_days');
            $table->boolean('calendar_confirmed')->default(false)->after('post_event_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['pre_event_days', 'post_event_days', 'calendar_confirmed']);
        });
    }
};
