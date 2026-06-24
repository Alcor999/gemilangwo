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
            $table->foreignId('bank_id')->nullable()->constrained('banks')->nullOnDelete();
            $table->enum('payment_method', ['credit_card', 'bank_transfer', 'e_wallet', 'cash'])->nullable();
            $table->enum('payment_type', ['full', 'dp', 'installment', 'remaining'])->default('full');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'processing', 'success', 'failed', 'cancelled'])->default('pending');
            $table->json('midtrans_response')->nullable();
            $table->integer('installment_number')->nullable();
            $table->date('due_date')->nullable();
            $table->string('payment_proof_path', 255)->nullable();
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('verification_notes')->nullable();
            $table->text('payment_note')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
