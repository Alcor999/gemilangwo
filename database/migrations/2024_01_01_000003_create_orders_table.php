<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->date('event_date');
            $table->integer('pre_event_days')->default(0);
            $table->integer('post_event_days')->default(0);
            $table->boolean('calendar_confirmed')->default(false);
            $table->string('event_location');
            $table->integer('guest_count');
            $table->text('special_request')->nullable();
            $table->decimal('total_price', 12, 2);
            // Payment scheme fields (konsolidasi langsung dari migration terpisah lama)
            $table->enum('payment_scheme', ['full_payment', 'dp_20', 'dp_30', 'dp_40', 'dp_50', 'installment_3x', 'installment_5x'])
                ->default('full_payment');
            $table->decimal('dp_percentage', 5, 2)->nullable();
            $table->decimal('total_paid', 12, 2)->default(0);
            $table->decimal('remaining_amount', 12, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'dp_paid', 'partially_paid', 'fully_paid'])
                ->default('unpaid');
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])
                ->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
