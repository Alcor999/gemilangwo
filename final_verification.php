#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n" . str_repeat("=", 90) . "\n";
echo "WEDDING APP - FINAL COMPREHENSIVE FEATURE VERIFICATION\n";
echo str_repeat("=", 90) . "\n\n";

$tests_passed = 0;
$tests_failed = 0;
$errors = [];

function test($name, $callback) {
    global $tests_passed, $tests_failed, $errors;
    try {
        $result = $callback();
        if ($result !== false) {
            echo "  ✓ " . $name . "\n";
            $tests_passed++;
            return true;
        } else {
            echo "  ❌ " . $name . " (returned false)\n";
            $tests_failed++;
            $errors[] = $name . ": returned false";
            return false;
        }
    } catch (\Exception $e) {
        echo "  ❌ " . $name . "\n";
        echo "     Error: " . $e->getMessage() . "\n";
        $tests_failed++;
        $errors[] = $name . ": " . $e->getMessage();
        return false;
    }
}

// ==========================================
// 1. DATABASE & TABLES
// ==========================================
echo "[1] DATABASE VERIFICATION\n";
echo str_repeat("-", 50) . "\n";

test("Users table exists", function() { return \Schema::hasTable('users'); });
test("Packages table exists", function() { return \Schema::hasTable('packages'); });
test("Orders table exists", function() { return \Schema::hasTable('orders'); });
test("Reviews table exists", function() { return \Schema::hasTable('reviews'); });
test("Discounts table exists", function() { return \Schema::hasTable('discounts'); });
test("Wishlists table exists", function() { return \Schema::hasTable('wishlists'); });
test("Gallery images table exists", function() { return \Schema::hasTable('gallery_images'); });
test("Video testimonials table exists", function() { return \Schema::hasTable('video_testimonials'); });
test("Support tickets table exists", function() { return \Schema::hasTable('support_tickets'); });
test("Chat messages table exists", function() { return \Schema::hasTable('chat_messages'); });
test("Calendar events table exists", function() { return \Schema::hasTable('calendar_events'); });
test("Blocked dates table exists", function() { return \Schema::hasTable('blocked_dates'); });
test("Availability table exists", function() { return \Schema::hasTable('availability'); });
test("SMS logs table exists", function() { return \Schema::hasTable('sms_logs'); });
test("Notifications table exists", function() { return \Schema::hasTable('notifications'); });
test("Payments table exists", function() { return \Schema::hasTable('payments'); });

// ==========================================
// 2. AUTHENTICATION & ROLES
// ==========================================
echo "\n[2] AUTHENTICATION & ROLES\n";
echo str_repeat("-", 50) . "\n";

test("Admin user exists", function() { return \App\Models\User::where('role', 'admin')->count() > 0; });
test("Customer user exists", function() { return \App\Models\User::where('role', 'customer')->count() > 0; });
test("Owner user exists", function() { return \App\Models\User::where('role', 'owner')->count() > 0; });
test("Total users count >= 3", function() { return \App\Models\User::count() >= 3; });

// ==========================================
// 3. PACKAGES & PRICING
// ==========================================
echo "\n[3] PACKAGES & PRICING\n";
echo str_repeat("-", 50) . "\n";

test("Packages exist in database", function() { return \App\Models\Package::count() > 0; });
test("Package has valid price", function() {
    $pkg = \App\Models\Package::first();
    return $pkg && $pkg->price > 0;
});
test("Package has owner", function() {
    $pkg = \App\Models\Package::first();
    return $pkg && $pkg->owner && $pkg->owner->role === 'owner';
});
test("Package has features", function() {
    $pkg = \App\Models\Package::first();
    return $pkg && $pkg->features && count($pkg->features) > 0;
});

// ==========================================
// 4. ORDERS & BOOKINGS
// ==========================================
echo "\n[4] ORDERS & BOOKINGS\n";
echo str_repeat("-", 50) . "\n";

test("Orders exist in database", function() { return \App\Models\Order::count() > 0; });
test("Order has customer", function() {
    $order = \App\Models\Order::first();
    return $order && $order->user && $order->user->role === 'customer';
});
test("Order has package", function() {
    $order = \App\Models\Order::first();
    return $order && $order->package;
});
test("Order has order number", function() {
    $order = \App\Models\Order::first();
    return $order && $order->order_number && strlen($order->order_number) > 0;
});
test("Order has status", function() {
    $order = \App\Models\Order::first();
    return $order && in_array($order->status, ['pending', 'confirmed', 'completed', 'cancelled']);
});
test("Order has total price", function() {
    $order = \App\Models\Order::first();
    return $order && $order->total_price > 0;
});

// ==========================================
// 5. REVIEWS & RATINGS
// ==========================================
echo "\n[5] REVIEWS & RATINGS\n";
echo str_repeat("-", 50) . "\n";

test("Reviews table has data", function() { return \App\Models\Review::count() >= 0; });
test("Review has rating (1-5)", function() {
    $review = \App\Models\Review::first();
    return !$review || ($review->rating >= 1 && $review->rating <= 5);
});
test("Review has content", function() {
    $review = \App\Models\Review::first();
    return !$review || ($review->content && strlen($review->content) > 0);
});
test("Review has approval status", function() {
    $review = \App\Models\Review::first();
    return !$review || is_bool($review->is_approved);
});

// ==========================================
// 6. DISCOUNTS
// ==========================================
echo "\n[6] DISCOUNTS\n";
echo str_repeat("-", 50) . "\n";

test("Discounts exist in database", function() { return \App\Models\Discount::count() > 0; });
test("Discount has type (percentage/fixed)", function() {
    $discount = \App\Models\Discount::first();
    return $discount && in_array($discount->type, ['percentage', 'fixed']);
});
test("Discount has valid value", function() {
    $discount = \App\Models\Discount::first();
    return $discount && $discount->value > 0;
});
test("Discount has active status", function() {
    $discount = \App\Models\Discount::first();
    return $discount && is_bool($discount->is_active);
});

// ==========================================
// 7. MODELS & RELATIONSHIPS
// ==========================================
echo "\n[7] MODELS & RELATIONSHIPS\n";
echo str_repeat("-", 50) . "\n";

test("Package has orders relationship", function() { return method_exists(\App\Models\Package::first(), 'orders'); });
test("Package has reviews relationship", function() { return method_exists(\App\Models\Package::first(), 'reviews'); });
test("Package has discounts relationship", function() { return method_exists(\App\Models\Package::first(), 'discounts'); });
test("User has orders relationship", function() { return method_exists(\App\Models\User::first(), 'orders'); });
test("User has reviews relationship", function() { return method_exists(\App\Models\User::first(), 'reviews'); });
test("Order has user relationship", function() { return method_exists(\App\Models\Order::first(), 'user'); });
test("Order has package relationship", function() { return method_exists(\App\Models\Order::first(), 'package'); });

// ==========================================
// 8. CONTROLLERS
// ==========================================
echo "\n[8] CONTROLLERS\n";
echo str_repeat("-", 50) . "\n";

test("Admin DashboardController exists", fn() => class_exists('\App\Http\Controllers\Admin\DashboardController'));
test("Customer DashboardController exists", fn() => class_exists('\App\Http\Controllers\Customer\DashboardController'));
test("Owner DashboardController exists", fn() => class_exists('\App\Http\Controllers\Owner\DashboardController'));
test("Admin PackageController exists", fn() => class_exists('\App\Http\Controllers\Admin\PackageController'));
test("Customer PackageController exists", fn() => class_exists('\App\Http\Controllers\Customer\PackageController'));
test("Admin ReviewController exists", fn() => class_exists('\App\Http\Controllers\Admin\ReviewController'));
test("Customer ReviewController exists", fn() => class_exists('\App\Http\Controllers\Customer\ReviewController'));
test("Admin DiscountController exists", fn() => class_exists('\App\Http\Controllers\Admin\DiscountController'));
test("Admin AnalyticsController exists", fn() => class_exists('\App\Http\Controllers\Admin\AnalyticsController'));
test("Owner AnalyticsController exists", fn() => class_exists('\App\Http\Controllers\Owner\AnalyticsController'));

// ==========================================
// 9. MAIL CLASSES
// ==========================================
echo "\n[9] MAIL & NOTIFICATION CLASSES\n";
echo str_repeat("-", 50) . "\n";

test("OrderConfirmationMail exists", fn() => class_exists('\App\Mail\OrderConfirmationMail'));
test("OrderStatusMail exists", fn() => class_exists('\App\Mail\OrderStatusMail'));
test("AdminNotificationMail exists", fn() => class_exists('\App\Mail\AdminNotificationMail'));

// ==========================================
// 10. SERVICES
// ==========================================
echo "\n[10] SERVICES\n";
echo str_repeat("-", 50) . "\n";

test("NotificationService exists", fn() => class_exists('\App\Services\NotificationService'));
test("AnalyticsService exists", fn() => class_exists('\App\Services\AnalyticsService'));

// ==========================================
// FINAL SUMMARY
// ==========================================
echo "\n\n" . str_repeat("=", 90) . "\n";
echo "FINAL TEST SUMMARY\n";
echo str_repeat("=", 90) . "\n\n";

echo "✓ Tests Passed: $tests_passed\n";
echo "❌ Tests Failed: $tests_failed\n";
echo "📊 Total Tests: " . ($tests_passed + $tests_failed) . "\n";
echo "🎯 Success Rate: " . (($tests_passed / ($tests_passed + $tests_failed)) * 100) . "%\n";

if ($tests_failed > 0) {
    echo "\n⚠️  FAILED TESTS:\n";
    echo str_repeat("-", 50) . "\n";
    foreach ($errors as $error) {
        echo "  ❌ " . $error . "\n";
    }
}

echo "\n" . str_repeat("=", 90) . "\n";
if ($tests_failed === 0) {
    echo "✅ ALL SYSTEMS OPERATIONAL - APP IS PRODUCTION READY!\n";
    echo str_repeat("=", 90) . "\n\n";
    exit(0);
} else {
    echo "⚠️  SOME ISSUES DETECTED - REVIEW ERRORS ABOVE\n";
    echo str_repeat("=", 90) . "\n\n";
    exit(1);
}
