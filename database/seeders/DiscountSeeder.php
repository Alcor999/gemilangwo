<?php

namespace Database\Seeders;

use App\Models\Discount;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::first();
        }

        $packages = Package::all();

        // Year End Sale - 30% off for all packages
        if ($packages->count() > 0) {
            $yearEndSale = Discount::create([
                'name' => 'Year End Sale 2025',
                'description' => 'Celebration ending - Get 30% discount on all wedding packages!',
                'type' => 'percentage',
                'value' => 30,
                'start_date' => now(),
                'end_date' => now()->addMonths(1),
                'is_active' => true,
                'created_by' => $admin->id,
            ]);
            $yearEndSale->packages()->sync($packages->pluck('id')->toArray());
        }

        // Valentine Special - Rp 1,000,000 off for selected packages
        if ($packages->count() >= 2) {
            $valentineDiscount = Discount::create([
                'name' => 'Valentine Special',
                'description' => 'Limited time offer for Valentine\'s Day bookings',
                'type' => 'fixed',
                'value' => 1000000,
                'start_date' => now()->addMonths(1),
                'end_date' => now()->addMonths(2),
                'is_active' => true,
                'created_by' => $admin->id,
            ]);
            $valentineDiscount->packages()->sync($packages->take(2)->pluck('id')->toArray());
        }

        // Early Bird Discount - 20% off for bookings made 3+ months in advance
        if ($packages->count() >= 1) {
            $earlyBird = Discount::create([
                'name' => 'Early Bird Special',
                'description' => 'Book 3+ months in advance and save 20%',
                'type' => 'percentage',
                'value' => 20,
                'start_date' => now(),
                'is_active' => true,
                'created_by' => $admin->id,
            ]);
            $earlyBird->packages()->sync($packages->first()->id);
        }

        // Flash Sale - 15% off with limited usage
        if ($packages->count() > 0) {
            $flashSale = Discount::create([
                'name' => 'Flash Sale - Limited Time!',
                'description' => 'Flash sale with limited slots - only 50 bookings!',
                'type' => 'percentage',
                'value' => 15,
                'start_date' => now(),
                'end_date' => now()->addWeeks(1),
                'is_active' => true,
                'usage_limit' => 50,
                'usage_count' => 0,
                'created_by' => $admin->id,
            ]);
            $flashSale->packages()->sync($packages->pluck('id')->toArray());
        }
    }
}
