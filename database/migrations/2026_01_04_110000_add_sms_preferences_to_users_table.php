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
        Schema::table('users', function (Blueprint $table) {
            // Add SMS preference columns if they don't exist
            if (!Schema::hasColumn('users', 'prefer_whatsapp')) {
                $table->boolean('prefer_whatsapp')->default(true)->comment('Prefer WhatsApp notifications');
            }
            if (!Schema::hasColumn('users', 'prefer_sms')) {
                $table->boolean('prefer_sms')->default(true)->comment('Prefer SMS notifications');
            }
            if (!Schema::hasColumn('users', 'prefer_email')) {
                $table->boolean('prefer_email')->default(true)->comment('Prefer Email notifications');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['prefer_whatsapp', 'prefer_sms', 'prefer_email']);
        });
    }
};
