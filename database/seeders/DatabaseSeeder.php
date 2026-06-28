<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed database sesuai dump gemilangwo-9.sql (sumber kebenaran).
     */
    public function run(): void
    {
        Order::flushEventListeners();
        Payment::flushEventListeners();
        Review::flushEventListeners();

        $this->call(GemilangWoSqlSeeder::class);
    }
}
