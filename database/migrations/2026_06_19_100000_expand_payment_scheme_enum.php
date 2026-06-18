<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support ALTER COLUMN for enums, so we handle it differently
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // For SQLite, we just ensure the column accepts the new values by recreating if needed
            // Since SQLite uses TEXT for enums, no change needed — just update seed data
            return;
        }

        // For MySQL/PostgreSQL: expand the enum values
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_scheme ENUM('full_payment','dp_20','dp_30','dp_40','dp_50','installment_3x','installment_5x') DEFAULT 'full_payment'");
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        // Revert to original enum values
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_scheme ENUM('full_payment','dp_30','dp_50','installment_3x') DEFAULT 'full_payment'");
    }
};
