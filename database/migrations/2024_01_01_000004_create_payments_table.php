<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('payment_id')->unique();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->enum('payment_method', ['credit_card', 'bank_transfer', 'e_wallet', 'cash'])->nullable();
            $table->enum('payment_type', ['full', 'dp', 'installment', 'remaining'])->default('full');
            $table->integer('installment_number')->nullable();
            $table->date('due_date')->nullable();
            $table->string('payment_proof_path')->nullable();
            $table->string('va_number')->nullable();
            $table->string('bank')->nullable();
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'processing', 'success', 'failed', 'cancelled'])->default('pending');
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->text('verification_notes')->nullable();
            $table->text('payment_note')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
