<?php

namespace Database\Seeders;

use App\Models\VendorCategory;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Catering', 'slug' => 'catering', 'description' => 'Layanan catering dan makanan', 'icon' => 'fa-utensils', 'sort_order' => 1],
            ['name' => 'Dekorasi', 'slug' => 'dekorasi', 'description' => 'Dekorasi dan penataan venue', 'icon' => 'fa-palette', 'sort_order' => 2],
            ['name' => 'Fotografi & Videografi', 'slug' => 'fotografi-videografi', 'description' => 'Dokumentasi foto dan video', 'icon' => 'fa-camera', 'sort_order' => 3],
            ['name' => 'Musik & DJ', 'slug' => 'musik-dj', 'description' => 'Entertainment musik dan DJ', 'icon' => 'fa-music', 'sort_order' => 4],
            ['name' => 'Master of Ceremony', 'slug' => 'mc', 'description' => 'Pembawa acara profesional', 'icon' => 'fa-microphone', 'sort_order' => 5],
            ['name' => 'Hair & Make Up', 'slug' => 'hair-makeup', 'description' => 'Tata rias dan styling', 'icon' => 'fa-spa', 'sort_order' => 6],
            ['name' => 'Transportasi', 'slug' => 'transportasi', 'description' => 'Transport pengantin dan tamu', 'icon' => 'fa-car', 'sort_order' => 7],
        ];

        foreach ($categories as $cat) {
            $category = VendorCategory::create($cat);

            // Create sample vendors per category
            $samples = [
                'Catering' => [
                    ['Catering Prima', 25000000],
                    ['Catering Royal', 35000000],
                    ['Catering Nusantara', 20000000],
                ],
                'Dekorasi' => [
                    ['Dekorasi Mewah', 15000000],
                    ['Dekorasi Elegan', 12000000],
                    ['Dekorasi Minimalis', 10000000],
                ],
                'Fotografi & Videografi' => [
                    ['Studio Foto Pro', 12000000],
                    ['Cinematic Video', 15000000],
                    ['Foto & Video Paket', 20000000],
                ],
                'Musik & DJ' => [
                    ['DJ Entertainment', 5000000],
                    ['Live Band', 15000000],
                    ['Orchestra', 25000000],
                ],
                'Master of Ceremony' => [
                    ['MC Profesional', 5000000],
                    ['MC Berpengalaman', 8000000],
                ],
                'Hair & Make Up' => [
                    ['Beauty Studio', 3000000],
                    ['Make Up Artist Premium', 5000000],
                ],
                'Transportasi' => [
                    ['Rental Mobil Mewah', 8000000],
                    ['Transport Premium', 12000000],
                ],
            ];

            $vendorList = $samples[$category->name] ?? [['Vendor ' . $category->name, 5000000]];
            foreach ($vendorList as $i => [$name, $price]) {
                Vendor::create([
                    'vendor_category_id' => $category->id,
                    'name' => $name,
                    'description' => 'Layanan ' . $name . ' untuk ' . $category->name,
                    'price' => $price,
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}
