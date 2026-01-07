<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Order 1 - Budi (Silver Package) - Completed with Payment
        $order1 = Order::create([
            'user_id' => 3, // Budi
            'package_id' => 2, // Silver
            'order_number' => 'WO-' . time() . '001',
            'event_date' => now()->addDays(30)->format('Y-m-d'),
            'event_location' => 'Bandung Convention Center',
            'guest_count' => 250,
            'special_request' => 'Ingin tema warna emas dan putih, dengan dekorasi bunga mawar merah',
            'total_price' => 100000000,
            'status' => 'confirmed',
        ]);

        Payment::create([
            'order_id' => $order1->id,
            'payment_id' => 'MIDTRANS-' . time() . '001',
            'payment_method' => 'credit_card',
            'amount' => 100000000,
            'status' => 'success',
            'paid_at' => now()->subDays(5),
        ]);

        // Order 2 - Siti (Gold Package) - Pending Payment
        $order2 = Order::create([
            'user_id' => 4, // Siti
            'package_id' => 1, // Gold
            'order_number' => 'WO-' . time() . '002',
            'event_date' => now()->addDays(45)->format('Y-m-d'),
            'event_location' => 'Yogyakarta Palace Hall',
            'guest_count' => 350,
            'special_request' => 'Menggabungkan tradisi Jawa dengan modern. Perlu live gamelan',
            'total_price' => 150000000,
            'status' => 'pending',
        ]);

        Payment::create([
            'order_id' => $order2->id,
            'payment_id' => 'MIDTRANS-' . time() . '002',
            'payment_method' => null,
            'amount' => 150000000,
            'status' => 'pending',
        ]);

        // Order 3 - Ahmad (Bronze Package) - Completed
        $order3 = Order::create([
            'user_id' => 5, // Ahmad
            'package_id' => 3, // Bronze
            'order_number' => 'WO-' . time() . '003',
            'event_date' => now()->addDays(60)->format('Y-m-d'),
            'event_location' => 'Medan Grand Hotel',
            'guest_count' => 200,
            'special_request' => 'Tema modern minimalis. Dokumentasi lengkap dimulai dari pengiring pengantin',
            'total_price' => 60000000,
            'status' => 'confirmed',
        ]);

        Payment::create([
            'order_id' => $order3->id,
            'payment_id' => 'MIDTRANS-' . time() . '003',
            'payment_method' => 'bank_transfer',
            'amount' => 60000000,
            'status' => 'success',
            'paid_at' => now()->subDays(10),
        ]);

        // Order 4 - Dewi (Platinum Package) - In Progress
        $order4 = Order::create([
            'user_id' => 6, // Dewi
            'package_id' => 4, // Platinum
            'order_number' => 'WO-' . time() . '004',
            'event_date' => now()->addDays(20)->format('Y-m-d'),
            'event_location' => 'Makassar Seaside Resort',
            'guest_count' => 500,
            'special_request' => 'Tema tepi pantai dengan sunset ceremony. Perlu coordinator yang responsif',
            'total_price' => 250000000,
            'status' => 'in_progress',
        ]);

        Payment::create([
            'order_id' => $order4->id,
            'payment_id' => 'MIDTRANS-' . time() . '004',
            'payment_method' => 'e_wallet',
            'amount' => 250000000,
            'status' => 'success',
            'paid_at' => now()->subDays(15),
        ]);

        // Order 5 - Rinto (Rose Package) - Completed
        $order5 = Order::create([
            'user_id' => 7, // Rinto
            'package_id' => 5, // Rose
            'order_number' => 'WO-' . time() . '005',
            'event_date' => now()->addDays(15)->format('Y-m-d'),
            'event_location' => 'Malang City Hotel',
            'guest_count' => 100,
            'special_request' => 'Acara sederhana dan hangat dengan keluarga besar',
            'total_price' => 35000000,
            'status' => 'completed',
        ]);

        Payment::create([
            'order_id' => $order5->id,
            'payment_id' => 'MIDTRANS-' . time() . '005',
            'payment_method' => 'bank_transfer',
            'amount' => 35000000,
            'status' => 'success',
            'paid_at' => now()->subDays(20),
        ]);

        // Order 6 - Nina (Diamond Package) - Confirmed
        $order6 = Order::create([
            'user_id' => 8, // Nina
            'package_id' => 6, // Diamond
            'order_number' => 'WO-' . time() . '006',
            'event_date' => now()->addDays(50)->format('Y-m-d'),
            'event_location' => 'Semarang International Convention Center',
            'guest_count' => 400,
            'special_request' => 'Menginginkan dokumentasi cinematic berkualitas tinggi dan koordinator profesional',
            'total_price' => 180000000,
            'status' => 'confirmed',
        ]);

        Payment::create([
            'order_id' => $order6->id,
            'payment_id' => 'MIDTRANS-' . time() . '006',
            'payment_method' => 'credit_card',
            'amount' => 180000000,
            'status' => 'success',
            'paid_at' => now()->subDays(3),
        ]);

        // Order 7 - Budi (Bronze Package) - Cancelled
        Order::create([
            'user_id' => 3, // Budi
            'package_id' => 3, // Bronze
            'order_number' => 'WO-' . time() . '007',
            'event_date' => now()->addDays(90)->format('Y-m-d'),
            'event_location' => 'Bandung City Resort',
            'guest_count' => 150,
            'special_request' => 'Tema outdoor garden party',
            'total_price' => 60000000,
            'status' => 'cancelled',
        ]);
    }
}
