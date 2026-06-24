<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('phone_number');
            $table->text('message');
            $table->enum('type', ['sms', 'whatsapp'])->default('sms');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->string('template_key', 255)->nullable();
            $table->json('template_data')->nullable();
            $table->string('twilio_sid', 255)->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
