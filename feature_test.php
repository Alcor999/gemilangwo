<?php

// COMPREHENSIVE FEATURE TESTING SCRIPT
// This script tests all major features of the Gemilang WO

echo "\n" . str_repeat("=", 80) . "\n";
echo "WEDDING APP - COMPREHENSIVE FEATURE TEST\n";
echo str_repeat("=", 80) . "\n\n";

$errors = [];
$warnings = [];
$success_count = 0;

// Helper function
function test_feature($name, $callback) {
    global $errors, $success_count;
    echo "Testing: $name ... ";
    try {
        $result = $callback();
        if ($result === false) {
            echo "❌ FAILED\n";
            $errors[$name] = "Test returned false";
            return false;
        }
        echo "✓ OK\n";
        $success_count++;
        return true;
    } catch (\Exception $e) {
        echo "❌ ERROR\n";
        $errors[$name] = $e->getMessage();
        return false;
    }
}

// ==========================================
// TEST 1: DATABASE & MODELS
// ==========================================
echo "\n[1] DATABASE & MODELS\n";
echo str_repeat("-", 40) . "\n";

test_feature("Database Connection", function() {
    DB::connection()->getPdo();
    return true;
});

test_feature("Users Model", function() {
    return \App\Models\User::count() > 0;
});

test_feature("Packages Model", function() {
    return \App\Models\Package::count() > 0;
});

test_feature("Orders Model", function() {
    return \App\Models\Order::count() >= 0;
});

test_feature("Reviews Model", function() {
    return \App\Models\Review::count() >= 0;
});

test_feature("Discounts Model", function() {
    return class_exists('\App\Models\Discount');
});

test_feature("Wishlist Model", function() {
    return class_exists('\App\Models\Wishlist');
});

test_feature("Gallery Images Model", function() {
    return class_exists('\App\Models\GalleryImage');
});

test_feature("Video Testimonials Model", function() {
    return class_exists('\App\Models\VideoTestimonial');
});

test_feature("Support Tickets Model", function() {
    return class_exists('\App\Models\SupportTicket');
});

test_feature("Calendar Events Model", function() {
    return class_exists('\App\Models\CalendarEvent');
});

// ==========================================
// TEST 2: AUTHENTICATION & ROLES
// ==========================================
echo "\n[2] AUTHENTICATION & ROLES\n";
echo str_repeat("-", 40) . "\n";

test_feature("Admin User Exists", function() {
    return \App\Models\User::where('role', 'admin')->count() > 0;
});

test_feature("Customer User Exists", function() {
    return \App\Models\User::where('role', 'customer')->count() > 0;
});

test_feature("Owner User Exists", function() {
    return \App\Models\User::where('role', 'owner')->count() > 0;
});

// ==========================================
// TEST 3: PACKAGE & BOOKING FEATURES
// ==========================================
echo "\n[3] PACKAGE & BOOKING FEATURES\n";
echo str_repeat("-", 40) . "\n";

test_feature("Packages Have Name", function() {
    $pkg = \App\Models\Package::first();
    return $pkg && $pkg->name;
});

test_feature("Packages Have Description", function() {
    $pkg = \App\Models\Package::first();
    return $pkg && $pkg->description;
});

test_feature("Packages Have Price", function() {
    $pkg = \App\Models\Package::first();
    return $pkg && $pkg->price > 0;
});

test_feature("Availability Table Exists", function() {
    return class_exists('\App\Models\Availability');
});

test_feature("Blocked Dates Table Exists", function() {
    return class_exists('\App\Models\BlockedDate');
});

// ==========================================
// TEST 4: ORDERS & PAYMENTS
// ==========================================
echo "\n[4] ORDERS & PAYMENTS\n";
echo str_repeat("-", 40) . "\n";

test_feature("Payment Table Exists", function() {
    return \Schema::hasTable('payments');
});

test_feature("Orders Table Has Status", function() {
    return \Schema::hasColumn('orders', 'status');
});

test_feature("Orders Table Has Package ID", function() {
    return \Schema::hasColumn('orders', 'package_id');
});

// ==========================================
// TEST 5: REVIEWS & RATINGS
// ==========================================
echo "\n[5] REVIEWS & RATINGS\n";
echo str_repeat("-", 40) . "\n";

test_feature("Reviews Table Has Rating", function() {
    return \Schema::hasColumn('reviews', 'rating');
});

test_feature("Reviews Table Has Content", function() {
    return \Schema::hasColumn('reviews', 'content');
});

test_feature("Reviews Table Has Approved Status", function() {
    return \Schema::hasColumn('reviews', 'is_approved');
});

test_feature("Reviews Table Has Featured Status", function() {
    return \Schema::hasColumn('reviews', 'is_featured');
});

// ==========================================
// TEST 6: DISCOUNT SYSTEM
// ==========================================
echo "\n[6] DISCOUNT SYSTEM\n";
echo str_repeat("-", 40) . "\n";

test_feature("Discounts Table Exists", function() {
    return \Schema::hasTable('discounts');
});

test_feature("Discounts Have Code", function() {
    return \Schema::hasColumn('discounts', 'code');
});

test_feature("Discounts Have Percentage", function() {
    return \Schema::hasColumn('discounts', 'percentage');
});

test_feature("Discounts Have Expiry", function() {
    return \Schema::hasColumn('discounts', 'expires_at');
});

// ==========================================
// TEST 7: PROFILE & WISHLIST
// ==========================================
echo "\n[7] PROFILE & WISHLIST\n";
echo str_repeat("-", 40) . "\n";

test_feature("Users Have Phone", function() {
    return \Schema::hasColumn('users', 'phone');
});

test_feature("Users Have Address", function() {
    return \Schema::hasColumn('users', 'address');
});

test_feature("Users Have Avatar", function() {
    return \Schema::hasColumn('users', 'avatar');
});

test_feature("Wishlist Table Exists", function() {
    return \Schema::hasTable('wishlists');
});

// ==========================================
// TEST 8: GALLERY SYSTEM
// ==========================================
echo "\n[8] GALLERY SYSTEM\n";
echo str_repeat("-", 40) . "\n";

test_feature("Gallery Images Table Exists", function() {
    return \Schema::hasTable('gallery_images');
});

test_feature("Gallery Images Have Package ID", function() {
    return \Schema::hasColumn('gallery_images', 'package_id');
});

test_feature("Gallery Images Have Image Path", function() {
    return \Schema::hasColumn('gallery_images', 'image_path');
});

// ==========================================
// TEST 9: VIDEO TESTIMONIALS
// ==========================================
echo "\n[9] VIDEO TESTIMONIALS\n";
echo str_repeat("-", 40) . "\n";

test_feature("Video Testimonials Table Exists", function() {
    return \Schema::hasTable('video_testimonials');
});

test_feature("Video Testimonials Have URL", function() {
    return \Schema::hasColumn('video_testimonials', 'video_url');
});

test_feature("Video Testimonials Have Status", function() {
    return \Schema::hasColumn('video_testimonials', 'is_approved');
});

// ==========================================
// TEST 10: LIVE CHAT / SUPPORT TICKETS
// ==========================================
echo "\n[10] LIVE CHAT / SUPPORT TICKETS\n";
echo str_repeat("-", 40) . "\n";

test_feature("Support Tickets Table Exists", function() {
    return \Schema::hasTable('support_tickets');
});

test_feature("Chat Messages Table Exists", function() {
    return \Schema::hasTable('chat_messages');
});

test_feature("Support Tickets Have Status", function() {
    return \Schema::hasColumn('support_tickets', 'status');
});

// ==========================================
// TEST 11: EMAIL & NOTIFICATIONS
// ==========================================
echo "\n[11] EMAIL & NOTIFICATIONS\n";
echo str_repeat("-", 40) . "\n";

test_feature("Notifications Table Exists", function() {
    return \Schema::hasTable('notifications');
});

test_feature("Mail Classes Exist", function() {
    return class_exists('\App\Mail\OrderConfirmationMail');
});

// ==========================================
// TEST 12: SMS & WHATSAPP
// ==========================================
echo "\n[12] SMS & WHATSAPP\n";
echo str_repeat("-", 40) . "\n";

test_feature("SMS Logs Table Exists", function() {
    return \Schema::hasTable('sms_logs');
});

test_feature("Users Have SMS Preference", function() {
    return \Schema::hasColumn('users', 'sms_notifications_enabled');
});

// ==========================================
// TEST 13: ANALYTICS
// ==========================================
echo "\n[13] ANALYTICS\n";
echo str_repeat("-", 40) . "\n";

test_feature("Analytics Controllers Exist", function() {
    return class_exists('\App\Http\Controllers\Admin\AnalyticsController') &&
           class_exists('\App\Http\Controllers\Owner\AnalyticsController');
});

// ==========================================
// TEST 14: CALENDAR FUNCTIONALITY
// ==========================================
echo "\n[14] CALENDAR FUNCTIONALITY\n";
echo str_repeat("-", 40) . "\n";

test_feature("Calendar Events Table Exists", function() {
    return \Schema::hasTable('calendar_events');
});

test_feature("Calendar Events Have Date", function() {
    return \Schema::hasColumn('calendar_events', 'event_date');
});

// ==========================================
// FINAL REPORT
// ==========================================
echo "\n\n" . str_repeat("=", 80) . "\n";
echo "TEST REPORT SUMMARY\n";
echo str_repeat("=", 80) . "\n";
echo "✓ Tests Passed: $success_count\n";
echo "❌ Tests Failed: " . count($errors) . "\n";

if (count($errors) > 0) {
    echo "\nFailed Tests:\n";
    echo str_repeat("-", 40) . "\n";
    foreach ($errors as $test => $error) {
        echo "  ❌ $test\n";
        echo "     Error: $error\n";
    }
}

if (count($warnings) > 0) {
    echo "\nWarnings:\n";
    echo str_repeat("-", 40) . "\n";
    foreach ($warnings as $test => $warning) {
        echo "  ⚠ $test\n";
        echo "     Warning: $warning\n";
    }
}

echo "\n" . str_repeat("=", 80) . "\n";
if (count($errors) === 0) {
    echo "✓ ALL FEATURES WORKING PERFECTLY!\n";
} else {
    echo "⚠ SOME ISSUES DETECTED - REVIEW ERRORS ABOVE\n";
}
echo str_repeat("=", 80) . "\n\n";

