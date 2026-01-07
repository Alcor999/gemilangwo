<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use App\Models\Package;
use App\Models\Order;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create sample users, packages, and orders
        $users = User::where('role', 'customer')->limit(5)->get();
        $packages = Package::limit(3)->get();

        if ($users->isEmpty() || $packages->isEmpty()) {
            $this->command->info('Skipping ReviewSeeder: Not enough users or packages in database.');
            return;
        }

        $sampleReviews = [
            [
                'title' => 'Layanan Luar Biasa, Sangat Direkomendasikan!',
                'content' => 'Tim wedding organizer sangat profesional dan memperhatikan setiap detail. Mereka mengubah hari pernikahan kami menjadi sesuatu yang benar-benar magis. Dari konsultasi awal hingga acara akhir, semuanya ditangani dengan peduli dan keahlian. Pasti akan menggunakan layanan mereka lagi untuk perayaan di masa depan!',
                'rating' => 5,
                'is_approved' => true,
                'is_featured' => true,
                'is_verified' => true,
                'helpful_count' => 24,
                'unhelpful_count' => 1,
            ],
            [
                'title' => 'Kualitas Pekerjaan Sangat Baik',
                'content' => 'Secara keseluruhan, kami sangat puas dengan paket dan layanannya. Stafnya ramah dan profesional. Ada beberapa keterlambatan kecil pada hari-H, namun mereka menangani semuanya dengan lancar. Kualitas pekerjaan melebihi ekspektasi kami.',
                'rating' => 5,
                'is_approved' => true,
                'is_featured' => true,
                'is_verified' => true,
                'helpful_count' => 18,
                'unhelpful_count' => 0,
            ],
            [
                'title' => 'Pengalaman yang Luar Biasa',
                'content' => 'Kami memiliki pengalaman yang sangat luar biasa bekerja dengan tim ini. Mereka responsif, kreatif, dan profesional selama seluruh proses perencanaan. Pada hari acara, semuanya berjalan dengan lancar. Harga yang wajar untuk kualitas layanan yang diberikan. Sangat puas!',
                'rating' => 4,
                'is_approved' => true,
                'is_featured' => false,
                'is_verified' => true,
                'helpful_count' => 12,
                'unhelpful_count' => 1,
            ],
            [
                'title' => 'Layanan Baik tapi Ada Beberapa Masalah',
                'content' => 'Layanannya secara umum baik, dan tim berusaha sebaik mungkin. Namun, ada beberapa masalah komunikasi selama perencanaan yang bisa lebih baik. Hasil akhirnya memuaskan, meskipun kami mengharapkan sedikit lebih banyak perhatian terhadap detail.',
                'rating' => 3,
                'is_approved' => true,
                'is_featured' => false,
                'is_verified' => true,
                'helpful_count' => 8,
                'unhelpful_count' => 2,
            ],
            [
                'title' => 'Paket yang Cukup',
                'content' => 'Paketnya menawarkan nilai uang yang cukup baik. Timnya profesional, meskipun tidak luar biasa luar biasa. Ada beberapa masalah kecil yang tidak ditangani dengan cepat. Untuk harganya, itu dapat diterima tetapi tidak istimewa.',
                'rating' => 3,
                'is_approved' => true,
                'is_featured' => false,
                'is_verified' => true,
                'helpful_count' => 5,
                'unhelpful_count' => 3,
            ],
        ];

        // Create reviews with existing users, packages, and orders
        $userIndex = 0;
        $packageIndex = 0;

        foreach ($sampleReviews as $reviewData) {
            $user = $users[$userIndex % $users->count()];
            $package = $packages[$packageIndex % $packages->count()];

            // Use existing completed orders only
            $order = Order::where('user_id', $user->id)
                ->where('status', 'completed')
                ->first();

            // Skip if no completed order exists for this user
            if (!$order) {
                $userIndex++;
                continue;
            }

            // Update package_id if needed (for this review)
            $order->update(['package_id' => $package->id]);

            // Check if review already exists
            if (!Review::where('order_id', $order->id)->exists()) {
                Review::create(array_merge($reviewData, [
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'order_id' => $order->id,
                ]));
            }

            $userIndex++;
            if ($userIndex % 2 === 0) {
                $packageIndex++;
            }
        }

        $this->command->info('ReviewSeeder completed! Sample reviews created.');
    }
}
