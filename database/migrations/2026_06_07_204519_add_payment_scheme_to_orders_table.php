<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'payment_scheme')) {
                $table->enum('payment_scheme', ['full_payment', 'dp_30', 'dp_50', 'installment_3x'])
                    ->default('full_payment')
                    ->after('total_price');
            }
            if (! Schema::hasColumn('orders', 'dp_percentage')) {
                $table->decimal('dp_percentage', 5, 2)->nullable()->after('payment_scheme');
            }
            if (! Schema::hasColumn('orders', 'total_paid')) {
                $table->decimal('total_paid', 12, 2)->default(0)->after('dp_percentage');
            }
            if (! Schema::hasColumn('orders', 'remaining_amount')) {
                $table->decimal('remaining_amount', 12, 2)->default(0)->after('total_paid');
            }
            if (! Schema::hasColumn('orders', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'dp_paid', 'partially_paid', 'fully_paid'])
                    ->default('unpaid')
                    ->after('remaining_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = ['payment_scheme', 'dp_percentage', 'total_paid', 'remaining_amount', 'payment_status'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
