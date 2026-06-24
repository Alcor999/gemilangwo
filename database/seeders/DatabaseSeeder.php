<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\CalendarEvent;
use App\Models\ChatMessage;
use App\Models\Discount;
use App\Models\GalleryImage;
use App\Models\Order;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Review;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorCategory;
use App\Models\Video;
use App\Models\VideoTestimonial;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Nonaktifkan event model untuk seeding agar email/SMS tidak terkirim
        Order::flushEventListeners();
        Payment::flushEventListeners();
        Review::flushEventListeners();

        // ============================================================
        // 1. USERS
        // ============================================================
        $this->command->info('Seeding Users...');

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gemilangwo.test',
            'phone' => '08123456789',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'address' => 'Jakarta, Indonesia',
        ]);

        $owner = User::create([
            'name' => 'Owner Business',
            'email' => 'owner@gemilangwo.test',
            'phone' => '08234567890',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'role' => 'owner',
            'address' => 'Surabaya, Indonesia',
        ]);

        $customers = [];
        $customerData = [
            ['Budi Santoso', 'budi@gemilangwo.test', '08111111111', 'Bandung, Jawa Barat'],
            ['Siti Rahayu', 'siti@gemilangwo.test', '08222222222', 'Yogyakarta, DI Yogyakarta'],
            ['Ahmad Wijaya', 'ahmad@gemilangwo.test', '08333333333', 'Medan, Sumatera Utara'],
            ['Dewi Lestari', 'dewi@gemilangwo.test', '08444444444', 'Makassar, Sulawesi Selatan'],
            ['Rinto Harahap', 'rinto@gemilangwo.test', '08555555555', 'Malang, Jawa Timur'],
            ['Nina Kusuma', 'nina@gemilangwo.test', '08666666666', 'Semarang, Jawa Tengah'],
        ];

        foreach ($customerData as $data) {
            $customers[] = User::create([
                'name' => $data[0],
                'email' => $data[1],
                'phone' => $data[2],
                'email_verified_at' => now(),
                'password' => bcrypt('password123'),
                'role' => 'customer',
                'address' => $data[3],
            ]);
        }

        // ============================================================
        // 2. PACKAGES
        // ============================================================
        $this->command->info('Seeding Packages...');

        $packagesData = [
            [
                'name' => 'Paket Gold Premium',
                'description' => 'Paket premium terlengkap untuk acara pernikahan Anda. Termasuk dekorasi mewah, catering berkualitas, dan layanan profesional dari awal hingga akhir acara.',
                'price' => 150000000.00,
                'max_guests' => 500,
                'features' => ['Dekorasi Mewah', 'Catering Berkualitas (3 Menu)', 'Musik Live + DJ', 'Fotografi & Videografi', 'Master of Ceremony', 'Lighting & Sound System', 'Transportasi Pengantin', 'Hair & Make Up untuk Pengantin', 'Honeymoon Planning', 'Post Wedding Photoshoot'],
            ],
            [
                'name' => 'Paket Silver Elegance',
                'description' => 'Paket unggulan dengan penawaran terbaik untuk acara pernikahan yang elegan. Mencakup dekorasi cantik, catering dengan menu pilihan, dan dokumentasi profesional.',
                'price' => 100000000.00,
                'max_guests' => 400,
                'features' => ['Dekorasi Elegan', 'Catering Berkualitas (2 Menu)', 'Musik Live + DJ', 'Fotografi & Videografi Profesional', 'Master of Ceremony', 'Sound System', 'Transportasi Pengantin', 'Hair & Make Up', 'Dokumentasi Digital'],
            ],
            [
                'name' => 'Paket Bronze Standard',
                'description' => 'Paket standar yang terjangkau namun lengkap untuk acara pernikahan Anda. Mencakup dekorasi sederhana, catering, dan dokumentasi dasar.',
                'price' => 60000000.00,
                'max_guests' => 300,
                'features' => ['Dekorasi Sederhana', 'Catering (1 Menu)', 'DJ Entertainment', 'Fotografi', 'Master of Ceremony', 'Sound System', 'Dokumentasi Foto Digital'],
            ],
            [
                'name' => 'Paket Platinum Eksklusif',
                'description' => 'Paket termahal dan paling eksklusif dengan semua fasilitas terbaik dan customization penuh. Dirancang khusus untuk acara pernikahan yang benar-benar istimewa.',
                'price' => 250000000.00,
                'max_guests' => 800,
                'features' => ['Dekorasi Mewah Customizable', 'Catering Premium (5 Menu)', 'Musik Live + Orchestra + DJ', 'Fotografi & Videografi Profesional + Drone', 'Master of Ceremony Berpengalaman', 'Lighting & Sound System Premium', 'Transportasi Pengantin Mewah', 'Hair & Make Up Premium + Paramedis', 'Honeymoon Planning', 'Post Wedding + Pre Wedding Photoshoot', 'Event Coordinator 24/7', 'Guest Experience Manager'],
            ],
            [
                'name' => 'Paket Rose Romantic',
                'description' => 'Paket budget-friendly yang sempurna untuk acara pernikahan sederhana namun tetap romantis dan berkesan.',
                'price' => 35000000.00,
                'max_guests' => 150,
                'features' => ['Dekorasi Tema Pilihan', 'Catering Sederhana', 'DJ Entertainment', 'Fotografi Dasar', 'Sound System', 'Master of Ceremony'],
            ],
            [
                'name' => 'Paket Diamond Mewah',
                'description' => 'Paket mewah dengan semua layanan premium untuk menciptakan pernikahan impian Anda. Kombinasi sempurna antara kemewahan dan kehangatan.',
                'price' => 180000000.00,
                'max_guests' => 600,
                'features' => ['Dekorasi Mewah', 'Catering Premium (4 Menu)', 'Musik Live + DJ Premium', 'Fotografi & Videografi Profesional + Cinematic', 'Master of Ceremony', 'Lighting & Sound System Premium', 'Transportasi Pengantin Eksklusif', 'Hair & Make Up + Stylist', 'Honeymoon Arrangement', 'Post Wedding Photoshoot', 'Event Coordinator'],
            ],
        ];

        $packages = [];
        foreach ($packagesData as $data) {
            $packages[] = Package::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'max_guests' => $data['max_guests'],
                'features' => $data['features'],
                'status' => 'active',
                'owner_id' => $owner->id,
            ]);
        }

        // ============================================================
        // 3. BANKS (Manual Payment)
        // ============================================================
        $this->command->info('Seeding Banks...');

        $banks = [];
        $banksData = [
            ['Bank Central Asia (BCA)', 'bca', '0123456789', 'PT Gemilang Wedding', 'Silakan transfer ke rekening BCA berikut dalam 24 jam.'],
            ['Bank Negara Indonesia (BNI)', 'bni', '0123456790', 'PT Gemilang Wedding', 'Silakan transfer ke rekening BNI berikut dalam 24 jam.'],
            ['Bank Mandiri', 'mandiri', '0123456791', 'PT Gemilang Wedding', 'Silakan transfer ke rekening Mandiri berikut dalam 24 jam.'],
        ];
        foreach ($banksData as $data) {
            $banks[] = Bank::create([
                'name' => $data[0],
                'code' => $data[1],
                'account_number' => $data[2],
                'account_holder' => $data[3],
                'instruction' => $data[4],
                'active' => true,
            ]);
        }

        // ============================================================
        // 4. PAYMENT SCHEMES
        // ============================================================
        $this->command->info('Seeding Payment Schemes...');
        $this->call(PaymentSchemeSeeder::class);

        // ============================================================
        // 5. ORDERS
        // ============================================================
        $this->command->info('Seeding Orders...');

        $ordersData = [
            ['user_id' => $customers[0]->id, 'package_id' => $packages[1]->id, 'order_number' => 'WO-1000000001', 'event_date' => now()->addDays(30)->toDateString(), 'event_location' => 'Bandung Convention Center', 'guest_count' => 250, 'special_request' => 'Ingin tema warna emas dan putih, dengan dekorasi bunga mawar merah', 'total_price' => 100000000, 'status' => 'confirmed', 'payment_scheme' => 'full_payment', 'total_paid' => 100000000, 'remaining_amount' => 0, 'payment_status' => 'fully_paid'],
            ['user_id' => $customers[1]->id, 'package_id' => $packages[0]->id, 'order_number' => 'WO-1000000002', 'event_date' => now()->addDays(45)->toDateString(), 'event_location' => 'Yogyakarta Palace Hall', 'guest_count' => 350, 'special_request' => 'Menggabungkan tradisi Jawa dengan modern. Perlu live gamelan', 'total_price' => 150000000, 'status' => 'pending', 'payment_scheme' => 'dp_30', 'total_paid' => 0, 'remaining_amount' => 150000000, 'payment_status' => 'unpaid'],
            ['user_id' => $customers[2]->id, 'package_id' => $packages[2]->id, 'order_number' => 'WO-1000000003', 'event_date' => now()->addDays(60)->toDateString(), 'event_location' => 'Medan Grand Hotel', 'guest_count' => 200, 'special_request' => 'Tema modern minimalis. Dokumentasi lengkap dimulai dari pengiring pengantin', 'total_price' => 60000000, 'status' => 'confirmed', 'payment_scheme' => 'full_payment', 'total_paid' => 60000000, 'remaining_amount' => 0, 'payment_status' => 'fully_paid'],
            ['user_id' => $customers[3]->id, 'package_id' => $packages[3]->id, 'order_number' => 'WO-1000000004', 'event_date' => now()->addDays(15)->toDateString(), 'event_location' => 'Makassar Seaside Resort', 'guest_count' => 500, 'special_request' => 'Tema tepi pantai dengan sunset ceremony. Perlu coordinator yang responsif', 'total_price' => 250000000, 'status' => 'in_progress', 'payment_scheme' => 'dp_50', 'total_paid' => 125000000, 'remaining_amount' => 125000000, 'payment_status' => 'dp_paid'],
            ['user_id' => $customers[4]->id, 'package_id' => $packages[0]->id, 'order_number' => 'WO-1000000005', 'event_date' => now()->subDays(10)->toDateString(), 'event_location' => 'Malang City Hotel', 'guest_count' => 100, 'special_request' => 'Acara sederhana dan hangat dengan keluarga besar', 'total_price' => 35000000, 'status' => 'completed', 'payment_scheme' => 'full_payment', 'total_paid' => 35000000, 'remaining_amount' => 0, 'payment_status' => 'fully_paid'],
            ['user_id' => $customers[5]->id, 'package_id' => $packages[5]->id, 'order_number' => 'WO-1000000006', 'event_date' => now()->addDays(75)->toDateString(), 'event_location' => 'Semarang International Convention Center', 'guest_count' => 400, 'special_request' => 'Menginginkan dokumentasi cinematic berkualitas tinggi dan koordinator profesional', 'total_price' => 180000000, 'status' => 'confirmed', 'payment_scheme' => 'installment_3x', 'total_paid' => 72000000, 'remaining_amount' => 108000000, 'payment_status' => 'partially_paid'],
            ['user_id' => $customers[0]->id, 'package_id' => $packages[2]->id, 'order_number' => 'WO-1000000007', 'event_date' => now()->addDays(90)->toDateString(), 'event_location' => 'Bandung City Resort', 'guest_count' => 150, 'special_request' => 'Tema outdoor garden party', 'total_price' => 60000000, 'status' => 'cancelled', 'payment_scheme' => 'full_payment', 'total_paid' => 0, 'remaining_amount' => 60000000, 'payment_status' => 'unpaid'],
        ];

        foreach ($ordersData as $data) {
            Order::create($data);
        }

        // ============================================================
        // 6. PAYMENTS
        // ============================================================
        $this->command->info('Seeding Payments...');

        $paymentsData = [
            ['order_id' => 1, 'payment_id' => 'MIDTRANS-1000000001', 'bank_id' => null, 'payment_method' => 'credit_card', 'payment_type' => 'full', 'amount' => 100000000, 'status' => 'success', 'paid_at' => now()->subDays(2)],
            ['order_id' => 2, 'payment_id' => 'MIDTRANS-1000000002', 'bank_id' => null, 'payment_method' => null, 'payment_type' => 'full', 'amount' => 150000000, 'status' => 'pending', 'paid_at' => null],
            ['order_id' => 3, 'payment_id' => 'MIDTRANS-1000000003', 'bank_id' => $banks[1]->id, 'payment_method' => 'bank_transfer', 'payment_type' => 'full', 'amount' => 60000000, 'status' => 'success', 'paid_at' => now()->subDays(3)],
            ['order_id' => 4, 'payment_id' => 'MIDTRANS-1000000004', 'bank_id' => null, 'payment_method' => 'e_wallet', 'payment_type' => 'dp', 'amount' => 125000000, 'status' => 'success', 'paid_at' => now()->subDays(5), 'installment_number' => 1],
            ['order_id' => 5, 'payment_id' => 'MIDTRANS-1000000005', 'bank_id' => $banks[0]->id, 'payment_method' => 'bank_transfer', 'payment_type' => 'full', 'amount' => 35000000, 'status' => 'success', 'paid_at' => now()->subDays(15)],
            ['order_id' => 6, 'payment_id' => 'MIDTRANS-1000000006', 'bank_id' => null, 'payment_method' => 'credit_card', 'payment_type' => 'installment', 'amount' => 72000000, 'status' => 'success', 'paid_at' => now()->subDays(7), 'installment_number' => 1],
        ];

        foreach ($paymentsData as $data) {
            Payment::create($data);
        }

        // ============================================================
        // 7. DISCOUNTS
        // ============================================================
        $this->command->info('Seeding Discounts...');

        $discounts = [];
        $discountsData = [
            ['name' => 'Year End Sale 2025', 'description' => 'Celebration ending - Get 30% discount on all wedding packages!', 'type' => 'percentage', 'value' => 30, 'start_date' => now()->subDays(10), 'end_date' => now()->addDays(20)],
            ['name' => 'Valentine Special', 'description' => "Limited time offer for Valentine's Day bookings", 'type' => 'fixed', 'value' => 1000000, 'start_date' => now()->subDays(5), 'end_date' => now()->addDays(25)],
            ['name' => 'Early Bird Special', 'description' => 'Book 3+ months in advance and save 20%', 'type' => 'percentage', 'value' => 20, 'start_date' => now()->subDays(10), 'end_date' => null],
            ['name' => 'Flash Sale - Limited Time!', 'description' => 'Flash sale with limited slots - only 50 bookings!', 'type' => 'percentage', 'value' => 15, 'start_date' => now()->subDays(10), 'end_date' => now()->subDays(3), 'usage_limit' => 50],
        ];

        foreach ($discountsData as $data) {
            $discounts[] = Discount::create(array_merge($data, [
                'is_active' => true,
                'usage_count' => 0,
                'created_by' => $admin->id,
            ]));
        }

        // Attach discounts to packages
        foreach ($packages as $package) {
            $package->discounts()->attach([$discounts[0]->id, $discounts[2]->id]);
        }
        $packages[0]->discounts()->attach([$discounts[1]->id]);
        foreach ([$packages[0]->id, $packages[1]->id, $packages[2]->id] as $pkgId) {
            DB::table('discount_package')->insertOrIgnore([
                'discount_id' => $discounts[3]->id,
                'package_id' => $pkgId,
            ]);
        }

        // ============================================================
        // 8. REVIEWS
        // ============================================================
        $this->command->info('Seeding Reviews...');

        Review::create([
            'order_id' => 5,
            'user_id' => $customers[4]->id,
            'package_id' => $packages[0]->id,
            'rating' => 5,
            'title' => 'Layanan Sangat Memuaskan!',
            'content' => 'Tim Gemilang benar-benar profesional. Setiap detail diperhatikan dengan sempurna. Acara kami berjalan lancar tanpa hambatan. Terima kasih atas pelayanan luar biasa!',
            'helpful_count' => 8,
            'unhelpful_count' => 1,
            'is_verified' => true,
            'is_approved' => true,
            'is_featured' => true,
        ]);

        Review::create([
            'order_id' => 3,
            'user_id' => $customers[2]->id,
            'package_id' => $packages[2]->id,
            'rating' => 4,
            'title' => 'Paket yang Cukup',
            'content' => 'Paketnya menawarkan nilai uang yang cukup baik. Timnya profesional, meskipun ada beberapa masalah kecil yang tidak ditangani dengan cepat. Secara keseluruhan dapat diterima.',
            'helpful_count' => 5,
            'unhelpful_count' => 3,
            'is_verified' => true,
            'is_approved' => true,
            'is_featured' => false,
        ]);

        // ============================================================
        // 9. VENDOR CATEGORIES & VENDORS
        // ============================================================
        $this->command->info('Seeding Vendor Categories & Vendors...');

        $vendorCategoriesData = [
            ['name' => 'Catering', 'icon' => 'fa-utensils', 'description' => 'Layanan makanan & minuman untuk acara'],
            ['name' => 'Dekorasi', 'icon' => 'fa-palette', 'description' => 'Dekorasi venue & panggung pernikahan'],
            ['name' => 'Fotografi & Videografi', 'icon' => 'fa-camera', 'description' => 'Dokumentasi profesional acara'],
            ['name' => 'Entertainment', 'icon' => 'fa-music', 'description' => 'Hiburan musik & MC'],
            ['name' => 'Makeup & Fashion', 'icon' => 'fa-spa', 'description' => 'Tata rias & busana pengantin'],
            ['name' => 'Transportasi', 'icon' => 'fa-car', 'description' => 'Kendaraan untuk pengantin & keluarga'],
        ];

        $vendorCategories = [];
        foreach ($vendorCategoriesData as $idx => $data) {
            $vendorCategories[] = VendorCategory::create(array_merge($data, [
                'slug' => \Str::slug($data['name']),
                'sort_order' => $idx,
                'is_active' => true,
            ]));
        }

        $vendorsData = [
            [0, 'Catering Nusantara Premium', 'Catering makanan tradisional & internasional', 25000000],
            [0, 'Royal Catering Service', 'Catering premium dengan chef berpengalaman', 35000000],
            [1, 'Dekorasi Mawar Emas', 'Spesialis dekorasi bunga & panggung mewah', 30000000],
            [1, 'Elegant Decor Studio', 'Dekorasi modern minimalis & elegan', 20000000],
            [2, 'Captured Moments Studio', 'Fotografi & videografi cinematic', 15000000],
            [2, 'Wedding Lens Pro', 'Dokumentasi pernikahan profesional + drone', 20000000],
            [3, 'Harmoni Live Music', 'Band live & musik tradisional', 18000000],
            [3, 'DJ Beat Master', 'DJ entertainment & sound system', 12000000],
            [4, 'Glamour Makeup Artist', 'Makeup pengantin premium', 8000000],
            [4, 'Bridal Stylist Pro', 'Styling busana & rias pengantin', 10000000],
            [5, 'Luxury Car Rental', 'Sewa mobil mewah untuk pengantin', 15000000],
            [5, 'Vintage Transport Service', 'Kendaraan vintage & klasik', 12000000],
        ];

        foreach ($vendorsData as $idx => $data) {
            Vendor::create([
                'vendor_category_id' => $vendorCategories[$data[0]]->id,
                'name' => $data[1],
                'description' => $data[2],
                'price' => $data[3],
                'contact_phone' => '08' . rand(1000000000, 9999999999),
                'contact_email' => strtolower(\Str::slug($data[1])) . '@vendor.com',
                'sort_order' => $idx,
                'is_active' => true,
            ]);
        }

        // ============================================================
        // 10. CALENDAR EVENTS
        // ============================================================
        $this->command->info('Seeding Calendar Events...');

        CalendarEvent::create([
            'order_id' => 1,
            'package_id' => $packages[1]->id,
            'event_date' => now()->addDays(30)->toDateString(),
            'status' => 'confirmed',
            'is_confirmed' => true,
            'confirmed_at' => now(),
            'notes' => 'Acara konfirmasi - Bandung Convention Center',
        ]);

        // ============================================================
        // 11. VIDEOS
        // ============================================================
        $this->command->info('Seeding Videos...');

        Video::create([
            'package_id' => $packages[0]->id,
            'title' => 'Sample Wedding Video - Gold Premium',
            'description' => 'Cuplikan acara pernikahan dengan paket Gold Premium',
            'type' => 'youtube',
            'youtube_url' => 'https://www.youtube.com/watch?v=FkpYd8D2dMo',
            'is_active' => true,
            'order' => 1,
        ]);

        // ============================================================
        // 12. VIDEO TESTIMONIALS
        // ============================================================
        $this->command->info('Seeding Video Testimonials...');

        VideoTestimonial::create([
            'user_id' => $customers[0]->id,
            'order_id' => null,
            'title' => 'Our Dream Wedding!',
            'description' => 'The team made our wedding day absolutely perfect. From start to finish, every detail was handled professionally and with care. We cannot thank them enough!',
            'type' => 'youtube',
            'youtube_url' => 'https://youtu.be/lFR4utbwliw',
            'rating' => 5.00,
            'is_featured' => true,
            'is_active' => true,
            'views' => 45,
        ]);

        // ============================================================
        // 13. WISHLISTS
        // ============================================================
        $this->command->info('Seeding Wishlists...');

        Wishlist::create(['user_id' => $customers[0]->id, 'package_id' => $packages[3]->id]);
        Wishlist::create(['user_id' => $customers[0]->id, 'package_id' => $packages[5]->id]);
        Wishlist::create(['user_id' => $customers[1]->id, 'package_id' => $packages[0]->id]);
        Wishlist::create(['user_id' => $customers[2]->id, 'package_id' => $packages[3]->id]);

        // ============================================================
        // 14. SUPPORT TICKETS & CHAT MESSAGES
        // ============================================================
        $this->command->info('Seeding Support Tickets...');

        $ticket = SupportTicket::create([
            'user_id' => $customers[0]->id,
            'order_id' => 1,
            'subject' => 'Pertanyaan tentang jadwal dekorasi',
            'description' => 'Halo, saya ingin menanyakan kapan tim dekorasi akan mulai menyiapkan venue untuk acara saya?',
            'category' => 'order',
            'priority' => 'medium',
            'status' => 'resolved',
            'assigned_to' => $admin->id,
            'first_response_at' => now()->subDays(2),
            'resolved_at' => now()->subDays(1),
            'response_count' => 2,
        ]);

        ChatMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_id' => $customers[0]->id,
            'message' => 'Halo, saya ingin menanyakan kapan tim dekorasi akan mulai menyiapkan venue?',
            'sender_type' => 'customer',
            'is_read' => true,
            'read_at' => now()->subDays(2),
        ]);

        ChatMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_id' => $admin->id,
            'message' => 'Halo! Tim dekorasi akan mulai H-1 hari acara pukul 08.00. Apakah ada permintaan khusus?',
            'sender_type' => 'admin',
            'is_read' => true,
            'read_at' => now()->subDays(1),
        ]);

        $ticket2 = SupportTicket::create([
            'user_id' => $customers[1]->id,
            'order_id' => 2,
            'subject' => 'Konfirmasi pembayaran DP',
            'description' => 'Mohon info bagaimana cara pembayaran DP 30% untuk paket Gold Premium saya.',
            'category' => 'payment',
            'priority' => 'high',
            'status' => 'open',
            'response_count' => 0,
        ]);

        ChatMessage::create([
            'support_ticket_id' => $ticket2->id,
            'sender_id' => $customers[1]->id,
            'message' => 'Mohon info bagaimana cara pembayaran DP 30%?',
            'sender_type' => 'customer',
            'is_read' => false,
        ]);

        // ============================================================
        // 15. GALLERY IMAGES (sample)
        // ============================================================
        $this->command->info('Seeding Gallery Images...');

        foreach ($packages as $package) {
            GalleryImage::create([
                'package_id' => $package->id,
                'image_path' => 'gallery/package-' . $package->id . '-main.jpg',
                'title' => $package->name . ' - Main Display',
                'description' => 'Tampilan utama untuk ' . $package->name,
                'order' => 0,
            ]);
        }

        // ============================================================
        // 16. CALL OPTIONAL SEEDERS
        // ============================================================
        // June2026BookingSeeder dijalankan hanya jika diperlukan
        // $this->call(June2026BookingSeeder::class);

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('Test Accounts (password: password123):');
        $this->command->info('  Admin    : admin@gemilangwo.test');
        $this->command->info('  Owner    : owner@gemilangwo.test');
        $this->command->info('  Customer : budi@gemilangwo.test, siti@gemilangwo.test, dll');
    }
}
