<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Vendor;
use App\Models\VendorCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VendorPackageSyncSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Categories exist from SQL
        $categoriesData = [
            ['id' => 1, 'name' => 'Catering', 'slug' => 'catering'],
            ['id' => 2, 'name' => 'Dekorasi', 'slug' => 'dekorasi'],
            ['id' => 3, 'name' => 'Fotografi & Videografi', 'slug' => 'fotografi-videografi'],
            ['id' => 4, 'name' => 'Musik & DJ', 'slug' => 'musik-dj'],
            ['id' => 5, 'name' => 'Master of Ceremony', 'slug' => 'master-of-ceremony'],
            ['id' => 6, 'name' => 'Hair & Make Up', 'slug' => 'hair-makeup'],
            ['id' => 7, 'name' => 'Transportasi', 'slug' => 'transportasi'],
        ];

        foreach ($categoriesData as $cat) {
            VendorCategory::updateOrCreate(['id' => $cat['id']], [
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'is_active' => true
            ]);
        }

        // 2. Ensure Vendors exist from SQL
        $vendorsData = [
            // Catering
            ['id' => 1, 'vendor_category_id' => 1, 'name' => 'Catering Prima', 'price' => 25000000.00],
            ['id' => 2, 'vendor_category_id' => 1, 'name' => 'Catering Royal', 'price' => 35000000.00],
            ['id' => 3, 'vendor_category_id' => 1, 'name' => 'Catering Nusantara', 'price' => 20000000.00],
            // Dekorasi
            ['id' => 4, 'vendor_category_id' => 2, 'name' => 'Dekorasi Mewah', 'price' => 15000000.00],
            ['id' => 5, 'vendor_category_id' => 2, 'name' => 'Dekorasi Elegan', 'price' => 12000000.00],
            ['id' => 6, 'vendor_category_id' => 2, 'name' => 'Dekorasi Minimalis', 'price' => 10000000.00],
            // Fotografi
            ['id' => 7, 'vendor_category_id' => 3, 'name' => 'Studio Foto Pro', 'price' => 12000000.00],
            ['id' => 8, 'vendor_category_id' => 3, 'name' => 'Cinematic Video', 'price' => 15000000.00],
            ['id' => 9, 'vendor_category_id' => 3, 'name' => 'Foto & Video Paket', 'price' => 20000000.00],
            // Musik
            ['id' => 10, 'vendor_category_id' => 4, 'name' => 'DJ Entertainment', 'price' => 5000000.00],
            ['id' => 11, 'vendor_category_id' => 4, 'name' => 'Live Band', 'price' => 15000000.00],
            ['id' => 12, 'vendor_category_id' => 4, 'name' => 'Orchestra', 'price' => 25000000.00],
            // MC
            ['id' => 13, 'vendor_category_id' => 5, 'name' => 'MC Profesional', 'price' => 5000000.00],
            ['id' => 14, 'vendor_category_id' => 5, 'name' => 'MC Berpengalaman', 'price' => 8000000.00],
            // Hair & Make Up
            ['id' => 15, 'vendor_category_id' => 6, 'name' => 'Beauty Studio', 'price' => 3000000.00],
            ['id' => 16, 'vendor_category_id' => 6, 'name' => 'Make Up Artist Premium', 'price' => 5000000.00],
            // Transportasi
            ['id' => 17, 'vendor_category_id' => 7, 'name' => 'Rental Mobil Mewah', 'price' => 8000000.00],
            ['id' => 18, 'vendor_category_id' => 7, 'name' => 'Transport Premium', 'price' => 12000000.00],
        ];

        foreach ($vendorsData as $v) {
            Vendor::updateOrCreate(['id' => $v['id']], [
                'vendor_category_id' => $v['vendor_category_id'],
                'name' => $v['name'],
                'description' => 'Layanan ' . $v['name'],
                'price' => $v['price'],
                'is_active' => true
            ]);
        }

        // 3. Sync Packages with Vendor Categories and Default Vendors
        // Definition: [PackageName => [CategoryId => DefaultVendorId]]
        $packageConfigs = [
            'Semar' => [1 => 3, 5 => 13], // Catering Nusantara, MC Pro
            'Gareng' => [1 => 3, 2 => 6, 5 => 13], // + Dekor Minimalis
            'Petruk' => [1 => 3, 2 => 6, 3 => 7, 5 => 13], // + Foto Studio
            'Bagong' => [1 => 3, 2 => 6, 3 => 7, 4 => 10, 5 => 13], // + DJ
            'Deluxe' => [1 => 3, 2 => 6, 3 => 7, 4 => 11, 5 => 13, 6 => 15], // + Live Band, Beauty
            'Silver' => [1 => 3, 2 => 6, 3 => 7, 4 => 11, 5 => 13, 6 => 15, 7 => 17], // + Rental Mobil
            'Premium' => [1 => 1, 2 => 5, 3 => 8, 4 => 11, 5 => 14, 6 => 16, 7 => 18], // Higher level defaults
            'All in 1' => [1 => 2, 2 => 4, 3 => 9, 4 => 12, 5 => 14, 6 => 16, 7 => 18], // Top level defaults
            'All in 2' => [1 => 2, 2 => 4, 3 => 9, 4 => 12, 5 => 14, 6 => 16, 7 => 18],
        ];

        foreach ($packageConfigs as $name => $categories) {
            $package = Package::where('name', $name)->first();
            if ($package) {
                $syncData = [];
                foreach ($categories as $catId => $defVendorId) {
                    $syncData[$catId] = ['default_vendor_id' => $defVendorId];
                }
                $package->vendorCategories()->sync($syncData);
            }
        }
    }
}
