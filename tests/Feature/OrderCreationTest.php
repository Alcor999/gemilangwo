<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Package;
use App\Models\PaymentScheme;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed payment schemes
        $this->seed(\Database\Seeders\PaymentSchemeSeeder::class);
    }

    public function test_order_creation_with_far_future_date_succeeds()
    {
        $user = User::create([
            'name' => 'Budi Test',
            'email' => 'budi.test@gemilangwo.test',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        $package = Package::create([
            'name' => 'Gold Suite Test',
            'description' => 'Test package description',
            'price' => 100000000.00,
            'max_guests' => 500,
            'status' => 'active',
        ]);

        // 120 days from now
        $eventDate = Carbon::now()->addDays(120)->format('Y-m-d');

        $response = $this->actingAs($user)
            ->post(route('customer.orders.store'), [
                'package_id' => $package->id,
                'event_date' => $eventDate,
                'event_location' => 'Luxury Hall A',
                'guest_count' => 100,
                'payment_scheme' => 'installment_5x', // requires 90 days
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'package_id' => $package->id,
            'event_date' => $eventDate . ' 00:00:00',
            'payment_scheme' => 'installment_5x',
        ]);
    }

    public function test_order_creation_with_exactly_90_days_succeeds()
    {
        $user = User::create([
            'name' => 'Budi Test',
            'email' => 'budi.test@gemilangwo.test',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        $package = Package::create([
            'name' => 'Gold Suite Test',
            'description' => 'Test package description',
            'price' => 100000000.00,
            'max_guests' => 500,
            'status' => 'active',
        ]);

        // Exactly 90 days from now
        $eventDate = Carbon::now()->addDays(90)->format('Y-m-d');

        $response = $this->actingAs($user)
            ->post(route('customer.orders.store'), [
                'package_id' => $package->id,
                'event_date' => $eventDate,
                'event_location' => 'Luxury Hall A',
                'guest_count' => 100,
                'payment_scheme' => 'installment_5x', // requires 90 days
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'package_id' => $package->id,
            'event_date' => $eventDate . ' 00:00:00',
            'payment_scheme' => 'installment_5x',
        ]);
    }

    public function test_order_creation_with_89_days_fails_validation()
    {
        $user = User::create([
            'name' => 'Budi Test',
            'email' => 'budi.test@gemilangwo.test',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        $package = Package::create([
            'name' => 'Gold Suite Test',
            'description' => 'Test package description',
            'price' => 100000000.00,
            'max_guests' => 500,
            'status' => 'active',
        ]);

        // 89 days from now
        $eventDate = Carbon::now()->addDays(89)->format('Y-m-d');

        $response = $this->actingAs($user)
            ->post(route('customer.orders.store'), [
                'package_id' => $package->id,
                'event_date' => $eventDate,
                'event_location' => 'Luxury Hall A',
                'guest_count' => 100,
                'payment_scheme' => 'installment_5x', // requires 90 days
            ]);

        // Should redirect back with session error
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('orders', [
            'user_id' => $user->id,
        ]);
    }
}
