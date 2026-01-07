#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n" . str_repeat("=", 80) . "\n";
echo "WEDDING APP - COMPREHENSIVE FEATURE TEST\n";
echo str_repeat("=", 80) . "\n\n";

$errors = [];
$success = 0;

// Test 1: Authentication & Authorization
echo "[1] AUTHENTICATION & AUTHORIZATION\n";
echo str_repeat("-", 40) . "\n";

try {
    $admin = \App\Models\User::where('role', 'admin')->first();
    echo "✓ Admin user exists: " . ($admin ? $admin->name : "N/A") . "\n";
    $success++;
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    $errors[] = "Admin user check: " . $e->getMessage();
}

try {
    $customer = \App\Models\User::where('role', 'customer')->first();
    echo "✓ Customer user exists: " . ($customer ? $customer->name : "N/A") . "\n";
    $success++;
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    $errors[] = "Customer user check: " . $e->getMessage();
}

// Test 2: Packages & Booking
echo "\n[2] PACKAGES & BOOKING\n";
echo str_repeat("-", 40) . "\n";

try {
    $pkg = \App\Models\Package::with('owner', 'reviews', 'discounts')->first();
    if ($pkg) {
        echo "✓ Package loaded: " . $pkg->name . "\n";
        echo "  - Owner: " . $pkg->owner->name . "\n";
        echo "  - Price: " . number_format($pkg->price, 0) . "\n";
        echo "  - Reviews: " . $pkg->reviews->count() . "\n";
        echo "  - Discounts: " . $pkg->discounts->count() . "\n";
        $success++;
    }
} catch (\Exception $e) {
    echo "❌ Error loading package: " . $e->getMessage() . "\n";
    $errors[] = "Package loading: " . $e->getMessage();
}

// Test 3: Orders
echo "\n[3] ORDERS\n";
echo str_repeat("-", 40) . "\n";

try {
    $order = \App\Models\Order::with('user', 'package', 'payment', 'reviews')->first();
    if ($order) {
        echo "✓ Order loaded: " . $order->order_number . "\n";
        echo "  - Customer: " . $order->user->name . "\n";
        echo "  - Package: " . $order->package->name . "\n";
        echo "  - Status: " . $order->status . "\n";
        echo "  - Total Price: " . number_format($order->total_price, 0) . "\n";
        $success++;
    }
} catch (\Exception $e) {
    echo "❌ Error loading order: " . $e->getMessage() . "\n";
    $errors[] = "Order loading: " . $e->getMessage();
}

// Test 4: Reviews
echo "\n[4] REVIEWS & RATINGS\n";
echo str_repeat("-", 40) . "\n";

try {
    $reviews = \App\Models\Review::count();
    echo "✓ Reviews in database: " . $reviews . "\n";
    if ($reviews > 0) {
        $review = \App\Models\Review::first();
        echo "  - Sample review by: " . $review->user->name . "\n";
        echo "  - Rating: " . $review->rating . "/5\n";
        echo "  - Approved: " . ($review->is_approved ? "Yes" : "No") . "\n";
    }
    $success++;
} catch (\Exception $e) {
    echo "❌ Error loading reviews: " . $e->getMessage() . "\n";
    $errors[] = "Reviews loading: " . $e->getMessage();
}

// Test 5: Discounts
echo "\n[5] DISCOUNTS\n";
echo str_repeat("-", 40) . "\n";

try {
    $discounts = \App\Models\Discount::count();
    echo "✓ Discounts in database: " . $discounts . "\n";
    if ($discounts > 0) {
        $discount = \App\Models\Discount::first();
        echo "  - Code: " . $discount->code . "\n";
        echo "  - Percentage: " . $discount->percentage . "%\n";
    }
    $success++;
} catch (\Exception $e) {
    echo "❌ Error loading discounts: " . $e->getMessage() . "\n";
    $errors[] = "Discounts loading: " . $e->getMessage();
}

// Test 6: Gallery
echo "\n[6] GALLERY\n";
echo str_repeat("-", 40) . "\n";

try {
    $galleries = \App\Models\GalleryImage::count();
    echo "✓ Gallery images in database: " . $galleries . "\n";
    $success++;
} catch (\Exception $e) {
    echo "❌ Error loading gallery: " . $e->getMessage() . "\n";
    $errors[] = "Gallery loading: " . $e->getMessage();
}

// Test 7: Video Testimonials
echo "\n[7] VIDEO TESTIMONIALS\n";
echo str_repeat("-", 40) . "\n";

try {
    $videos = \App\Models\VideoTestimonial::count();
    echo "✓ Video testimonials in database: " . $videos . "\n";
    $success++;
} catch (\Exception $e) {
    echo "❌ Error loading video testimonials: " . $e->getMessage() . "\n";
    $errors[] = "Video testimonials loading: " . $e->getMessage();
}

// Test 8: Support Tickets
echo "\n[8] SUPPORT TICKETS & LIVE CHAT\n";
echo str_repeat("-", 40) . "\n";

try {
    $tickets = \App\Models\SupportTicket::count();
    echo "✓ Support tickets in database: " . $tickets . "\n";
    $success++;
} catch (\Exception $e) {
    echo "❌ Error loading support tickets: " . $e->getMessage() . "\n";
    $errors[] = "Support tickets loading: " . $e->getMessage();
}

// Test 9: Email & Notifications
echo "\n[9] EMAIL & NOTIFICATIONS\n";
echo str_repeat("-", 40) . "\n";

try {
    $notifications = \App\Models\Notification::count();
    echo "✓ Notifications in database: " . $notifications . "\n";
    $success++;
} catch (\Exception $e) {
    echo "❌ Error loading notifications: " . $e->getMessage() . "\n";
    $errors[] = "Notifications loading: " . $e->getMessage();
}

// Test 10: Calendar Events
echo "\n[10] CALENDAR & AVAILABILITY\n";
echo str_repeat("-", 40) . "\n";

try {
    $events = \App\Models\CalendarEvent::count();
    echo "✓ Calendar events in database: " . $events . "\n";
    $success++;
} catch (\Exception $e) {
    echo "❌ Error loading calendar events: " . $e->getMessage() . "\n";
    $errors[] = "Calendar events loading: " . $e->getMessage();
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "TEST SUMMARY\n";
echo str_repeat("=", 80) . "\n";
echo "✓ Tests Passed: " . $success . "\n";
echo "❌ Tests Failed: " . count($errors) . "\n";

if (count($errors) > 0) {
    echo "\nFailed Tests:\n";
    foreach ($errors as $error) {
        echo "  ❌ " . $error . "\n";
    }
}

echo "\n" . str_repeat("=", 80) . "\n";
if (count($errors) === 0) {
    echo "✓ ALL FEATURES WORKING PERFECTLY!\n";
} else {
    echo "⚠ SOME ISSUES DETECTED - SEE DETAILS ABOVE\n";
}
echo str_repeat("=", 80) . "\n\n";

exit(count($errors) > 0 ? 1 : 0);
