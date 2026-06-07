<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'payment_type')) {
                $table->enum('payment_type', ['full', 'dp', 'installment', 'remaining'])
                    ->default('full')
                    ->after('payment_method');
            }
            if (! Schema::hasColumn('payments', 'installment_number')) {
                $table->integer('installment_number')->nullable()->after('payment_type');
            }
            if (! Schema::hasColumn('payments', 'due_date')) {
                $table->date('due_date')->nullable()->after('installment_number');
            }
            if (! Schema::hasColumn('payments', 'payment_note')) {
                $table->text('payment_note')->nullable()->after('verification_notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $columns = ['payment_type', 'installment_number', 'due_date', 'payment_note'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
