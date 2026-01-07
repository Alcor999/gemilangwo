<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create an owner user
        $owner = \App\Models\User::where('role', 'owner')->first();
        if (!$owner) {
            $owner = \App\Models\User::create([
                'name' => 'Wedding Owner',
                'email' => 'owner@wedding.test',
                'password' => bcrypt('password'),
                'role' => 'owner',
                'phone' => '62812345678',
            ]);
        }

        $owner_id = $owner->id;

        // Gold Package
        Package::create([
            'name' => 'Paket Gold Premium',
            'description' => 'Paket premium terlengkap untuk acara pernikahan Anda. Termasuk dekorasi mewah, catering berkualitas, dan layanan profesional dari awal hingga akhir acara.',
            'price' => 150000000,
            'max_guests' => 500,
            'features' => json_encode([
                'Dekorasi Mewah',
                'Catering Berkualitas (3 Menu)',
                'Musik Live + DJ',
                'Fotografi & Videografi',
                'Master of Ceremony',
                'Lighting & Sound System',
                'Transportasi Pengantin',
                'Hair & Make Up untuk Pengantin',
                'Honeymoon Planning',
                'Post Wedding Photoshoot'
            ]),
            'image' => null,
            'status' => 'active',
            'owner_id' => $owner_id,
        ]);

        // Silver Package
        Package::create([
            'name' => 'Paket Silver Elegance',
            'description' => 'Paket unggulan dengan penawaran terbaik untuk acara pernikahan yang elegan. Mencakup dekorasi cantik, catering dengan menu pilihan, dan dokumentasi profesional.',
            'price' => 100000000,
            'max_guests' => 400,
            'features' => json_encode([
                'Dekorasi Elegan',
                'Catering Berkualitas (2 Menu)',
                'Musik Live + DJ',
                'Fotografi & Videografi Profesional',
                'Master of Ceremony',
                'Sound System',
                'Transportasi Pengantin',
                'Hair & Make Up',
                'Dokumentasi Digital'
            ]),
            'image' => null,
            'status' => 'active',
            'owner_id' => $owner_id,
        ]);

        // Bronze Package
        Package::create([
            'name' => 'Paket Bronze Standard',
            'description' => 'Paket standar yang terjangkau namun lengkap untuk acara pernikahan Anda. Mencakup dekorasi sederhana, catering, dan dokumentasi dasar.',
            'price' => 60000000,
            'max_guests' => 300,
            'features' => json_encode([
                'Dekorasi Sederhana',
                'Catering (1 Menu)',
                'DJ Entertainment',
                'Fotografi',
                'Master of Ceremony',
                'Sound System',
                'Dokumentasi Foto Digital'
            ]),
            'image' => null,
            'status' => 'active',
            'owner_id' => $owner_id,
        ]);

        // Platinum Package (Eksklusif)
        Package::create([
            'name' => 'Paket Platinum Eksklusif',
            'description' => 'Paket termahal dan paling eksklusif dengan semua fasilitas terbaik dan customization penuh. Dirancang khusus untuk acara pernikahan yang benar-benar istimewa.',
            'price' => 250000000,
            'max_guests' => 800,
            'features' => json_encode([
                'Dekorasi Mewah Customizable',
                'Catering Premium (5 Menu)',
                'Musik Live + Orchestra + DJ',
                'Fotografi & Videografi Profesional + Drone',
                'Master of Ceremony Berpengalaman',
                'Lighting & Sound System Premium',
                'Transportasi Pengantin Mewah',
                'Hair & Make Up Premium + Paramedis',
                'Honeymoon Planning',
                'Post Wedding + Pre Wedding Photoshoot',
                'Event Coordinator 24/7',
                'Guest Experience Manager'
            ]),
            'image' => null,
            'status' => 'active',
            'owner_id' => $owner_id,
        ]);

        // Rose Package (Budget)
        Package::create([
            'name' => 'Paket Rose Romantic',
            'description' => 'Paket budget-friendly yang sempurna untuk acara pernikahan sederhana namun tetap romantis dan berkesan.',
            'price' => 35000000,
            'max_guests' => 150,
            'features' => json_encode([
                'Dekorasi Tema Pilihan',
                'Catering Sederhana',
                'DJ Entertainment',
                'Fotografi Dasar',
                'Sound System',
                'Master of Ceremony'
            ]),
            'image' => null,
            'status' => 'active',
            'owner_id' => $owner_id,
        ]);

        // Diamond Package
        Package::create([
            'name' => 'Paket Diamond Mewah',
            'description' => 'Paket mewah dengan semua layanan premium untuk menciptakan pernikahan impian Anda. Kombinasi sempurna antara kemewahan dan kehangatan.',
            'price' => 180000000,
            'max_guests' => 600,
            'features' => json_encode([
                'Dekorasi Mewah',
                'Catering Premium (4 Menu)',
                'Musik Live + DJ Premium',
                'Fotografi & Videografi Profesional + Cinematic',
                'Master of Ceremony',
                'Lighting & Sound System Premium',
                'Transportasi Pengantin Eksklusif',
                'Hair & Make Up + Stylist',
                'Honeymoon Arrangement',
                'Post Wedding Photoshoot',
                'Event Coordinator'
            ]),
            'image' => null,
            'status' => 'active',
            'owner_id' => $owner_id,
        ]);
    }
}
