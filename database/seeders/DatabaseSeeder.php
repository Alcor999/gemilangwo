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

        // Update some orders to completed
        Order::whereIn('id', [1, 3, 6])->update(['status' => 'completed']);

        // Seed reviews (Kesan Teks)
        Review::where('id', 1)->update([
            'rating' => 5,
            'title' => 'Layanan Profesional & Sangat Memuaskan',
            'content' => 'Gemilang WO benar-benar profesional. Seluruh panitia bekerja dengan dedikasi tinggi sehingga acara pernikahan kami berjalan sangat rapi dan berkesan. Terima kasih!',
            'is_approved' => true,
            'is_featured' => true,
        ]);

        Review::updateOrCreate(
            ['id' => 2],
            [
                'order_id' => 1,
                'user_id' => 3,
                'package_id' => 2,
                'rating' => 5,
                'title' => 'Dekorasi Mewah & Koordinasi Sempurna!',
                'content' => 'Sangat puas dengan layanan Gemilang WO untuk paket Gareng kami. Dekorasi pelaminan sangat mewah dan melebihi ekspektasi. Seluruh panitia sangat cekatan koordinasinya pada hari H.',
                'is_approved' => true,
                'is_featured' => true,
                'is_verified' => true,
            ]
        );

        Review::updateOrCreate(
            ['id' => 3],
            [
                'order_id' => 3,
                'user_id' => 5,
                'package_id' => 3,
                'rating' => 5,
                'title' => 'Pernikahan Impian yang Menjadi Nyata',
                'content' => 'Terima kasih banyak Gemilang WO! Paket Petruk yang kami pilih benar-benar dirancang dengan detail yang rapi. Tamu undangan kami sangat terkesan dengan susunan acara yang mengalir mulus.',
                'is_approved' => true,
                'is_featured' => true,
                'is_verified' => true,
            ]
        );

        Review::updateOrCreate(
            ['id' => 4],
            [
                'order_id' => 6,
                'user_id' => 8,
                'package_id' => 6,
                'rating' => 5,
                'title' => 'Sangat Rekomendasi untuk Pernikahan Premium',
                'content' => 'Pelayanan bintang lima! Sejak pertemuan pertama, konsep yang diajukan sangat matang. Pada hari pernikahan, kami sekeluarga bisa menikmati acara tanpa pusing memikirkan teknis. Luar biasa!',
                'is_approved' => true,
                'is_featured' => true,
                'is_verified' => true,
            ]
        );

        // Seed Video Testimonials (Kesan Video)
        \App\Models\VideoTestimonial::updateOrCreate(
            ['id' => 1],
            [
                'user_id' => 3,
                'order_id' => 1,
                'title' => 'Kebahagiaan Budi & Siti - Paket Gareng',
                'description' => 'Tonton perjalanan cinta kami dan bagaimana Gemilang WO membantu kami mewujudkan pernikahan impian kami dengan koordinasi yang tanpa celah!',
                'type' => 'youtube',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'rating' => 5,
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        \App\Models\VideoTestimonial::updateOrCreate(
            ['id' => 2],
            [
                'user_id' => 5,
                'order_id' => 3,
                'title' => 'Hari Spesial Ahmad & Dewi - Paket Petruk',
                'description' => 'Terima kasih Gemilang WO atas dekorasi yang sangat megah dan pelayanan yang ramah. Rekomendasi utama untuk wedding organizer premium!',
                'type' => 'youtube',
                'youtube_url' => 'https://www.youtube.com/watch?v=y25F4hS7QvE',
                'rating' => 5,
                'is_active' => true,
                'is_featured' => true,
            ]
        );
    }
}
