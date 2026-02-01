<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\VendorCategory;
use Illuminate\Database\Seeder;

class PackageVendorCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = VendorCategory::pluck('id', 'slug')->toArray();

        $assignments = [
            'Paket Gold Premium' => ['catering', 'dekorasi', 'fotografi-videografi', 'musik-dj', 'mc', 'hair-makeup', 'transportasi'],
            'Paket Silver Elegance' => ['catering', 'dekorasi', 'fotografi-videografi', 'musik-dj', 'mc', 'hair-makeup'],
            'Paket Bronze Standard' => ['catering', 'dekorasi', 'fotografi-videografi', 'musik-dj', 'mc'],
            'Paket Platinum Eksklusif' => ['catering', 'dekorasi', 'fotografi-videografi', 'musik-dj', 'mc', 'hair-makeup', 'transportasi'],
            'Paket Rose Romantic' => ['catering', 'dekorasi', 'fotografi-videografi', 'musik-dj', 'mc'],
            'Paket Diamond Mewah' => ['catering', 'dekorasi', 'fotografi-videografi', 'musik-dj', 'mc', 'hair-makeup', 'transportasi'],
        ];

        foreach ($assignments as $packageName => $slugs) {
            $package = Package::where('name', $packageName)->first();
            if (!$package) continue;

            $ids = [];
            foreach ($slugs as $slug) {
                if (isset($categories[$slug])) {
                    $ids[] = $categories[$slug];
                }
            }
            $package->vendorCategories()->sync($ids);
        }
    }
}
