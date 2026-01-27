<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Admin\DiscountController as AdminDiscountController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SupportController as AdminSupportController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\PackageController as CustomerPackageController;
use App\Http\Controllers\Customer\ReviewController as CustomerReviewController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\WishlistController as CustomerWishlistController;
use App\Http\Controllers\Customer\GalleryController as CustomerGalleryController;
use App\Http\Controllers\Customer\AvailabilityController as CustomerAvailabilityController;
use App\Http\Controllers\Customer\SupportTicketController as CustomerSupportTicketController;
use App\Http\Controllers\Customer\TestimonialController as CustomerTestimonialController;
use App\Http\Controllers\Customer\CalendarController as CustomerCalendarController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\AnalyticsController as OwnerAnalyticsController;
use App\Http\Controllers\Owner\CalendarController as OwnerCalendarController;
use App\Http\Controllers\EmailTestController;
use App\Http\Controllers\SmsTestController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Home Route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes (using Laravel's built-in auth)
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
});

Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Analytics & Reporting
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/dashboard', [AdminAnalyticsController::class, 'dashboard'])->name('dashboard');
        Route::get('/revenue', [AdminAnalyticsController::class, 'revenue'])->name('revenue');
        Route::get('/customers', [AdminAnalyticsController::class, 'customers'])->name('customers');
        Route::get('/packages', [AdminAnalyticsController::class, 'packages'])->name('packages');
        Route::get('/conversion', [AdminAnalyticsController::class, 'conversion'])->name('conversion');
        Route::get('/payments', [AdminAnalyticsController::class, 'payments'])->name('payments');
        Route::get('/export', [AdminAnalyticsController::class, 'export'])->name('export');
    });
    
    // Package Management
    Route::get('/packages', [AdminPackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/create', [AdminPackageController::class, 'create'])->name('packages.create');
    Route::post('/packages', [AdminPackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{package}', [AdminPackageController::class, 'show'])->name('packages.show');
    Route::get('/packages/{package}/edit', [AdminPackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{package}', [AdminPackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}', [AdminPackageController::class, 'destroy'])->name('packages.destroy');
    
    // Discount Management
    Route::resource('discounts', AdminDiscountController::class);
    
    // Review Management
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [AdminReviewController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::post('/reviews/{review}/feature', [AdminReviewController::class, 'feature'])->name('reviews.feature');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Order Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
    
    // Payment Verification (integrated in orders)
    Route::post('/payments/{payment}/approve', [AdminOrderController::class, 'approvePayment'])->name('payments.approve');
    Route::post('/payments/{payment}/reject', [AdminOrderController::class, 'rejectPayment'])->name('payments.reject');
    
    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [AdminUserController::class, 'deactivate'])->name('users.deactivate');
    
    // Support & Chat
    Route::get('/support/tickets', [AdminSupportController::class, 'index'])->name('support.tickets.index');
    Route::get('/support/tickets/{id}', [AdminSupportController::class, 'show'])->name('support.tickets.show');
    Route::patch('/support/tickets/{id}/assign', [AdminSupportController::class, 'assign'])->name('support.tickets.assign');
    Route::patch('/support/tickets/{id}/status', [AdminSupportController::class, 'updateStatus'])->name('support.tickets.updateStatus');
    Route::post('/support/tickets/{id}/messages', [AdminSupportController::class, 'addMessage'])->name('support.tickets.addMessage');
    Route::patch('/support/tickets/{id}/notes', [AdminSupportController::class, 'addNotes'])->name('support.tickets.addNotes');
    Route::get('/support/tickets/{id}/messages', [AdminSupportController::class, 'getNewMessages'])->name('support.tickets.getNewMessages');
    Route::get('/support/recent-tickets', [AdminSupportController::class, 'recentTickets'])->name('support.recentTickets');
    
    // Additional Package Routes
    Route::post('/packages/{package}/upload-image', [AdminPackageController::class, 'uploadImage'])->name('packages.uploadImage');
    Route::get('/packages/{package}/gallery', [AdminPackageController::class, 'gallery'])->name('packages.gallery');
    Route::post('/packages/{package}/gallery', [AdminPackageController::class, 'storeGallery'])->name('packages.storeGallery');
    Route::delete('/gallery/{image}', [AdminPackageController::class, 'destroyGalleryImage'])->name('packages.destroyGallery');
    Route::get('/packages/{package}/availability', [AdminPackageController::class, 'availability'])->name('packages.availability');
    Route::post('/packages/{package}/availability', [AdminPackageController::class, 'storeAvailability'])->name('packages.storeAvailability');
    Route::delete('/availability/{availability}', [AdminPackageController::class, 'destroyAvailability'])->name('packages.destroyAvailability');
    
    // Video Management
    Route::get('/videos', [AdminVideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/package/{package}', [AdminVideoController::class, 'show'])->name('videos.show');
    Route::get('/videos/package/{package}/create', [AdminVideoController::class, 'create'])->name('videos.create');
    Route::post('/videos/package/{package}', [AdminVideoController::class, 'store'])->name('videos.store');
    Route::get('/videos/{video}/edit', [AdminVideoController::class, 'edit'])->name('videos.edit');
    Route::put('/videos/{video}', [AdminVideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{video}', [AdminVideoController::class, 'destroy'])->name('videos.destroy');
    Route::post('/videos/{video}/toggle', [AdminVideoController::class, 'toggle'])->name('videos.toggle');
    Route::post('/videos/package/{package}/reorder', [AdminVideoController::class, 'reorder'])->name('videos.reorder');
    
    // Testimonial Approvals
    Route::get('/testimonials', [AdminTestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('/testimonials/{testimonial}', [AdminTestimonialController::class, 'show'])->name('testimonials.show');
    Route::post('/testimonials/{testimonial}/approve', [AdminTestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::post('/testimonials/{testimonial}/reject', [AdminTestimonialController::class, 'reject'])->name('testimonials.reject');
    Route::post('/testimonials/{testimonial}/feature', [AdminTestimonialController::class, 'feature'])->name('testimonials.feature');
    Route::post('/testimonials/{testimonial}/unfeature', [AdminTestimonialController::class, 'unfeature'])->name('testimonials.unfeature');
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    
    // Browse Packages
    Route::get('/packages', [CustomerPackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/{package}', [CustomerPackageController::class, 'show'])->name('packages.show');
    
    // Orders
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [CustomerOrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [CustomerOrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
    
    // Payment Routes
    Route::get('/orders/{order}/payment', [CustomerOrderController::class, 'payment'])->name('orders.payment');
    Route::post('/orders/{order}/select-bank', [CustomerOrderController::class, 'selectBank'])->name('orders.selectBank');
    Route::get('/orders/{order}/payment-confirm', [CustomerOrderController::class, 'paymentConfirm'])->name('orders.paymentConfirm');
    
    // Notification (Midtrans backward compatibility)
    Route::post('/orders/payment/notification', [CustomerOrderController::class, 'notification'])->name('orders.notification');
    
    // Reviews
    Route::get('/reviews', [CustomerReviewController::class, 'index'])->name('reviews.index');
    Route::get('/orders/{order}/review', [CustomerReviewController::class, 'create'])->name('reviews.create');
    Route::post('/orders/{order}/review', [CustomerReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/helpful', [CustomerReviewController::class, 'markHelpful'])->name('reviews.helpful');
    Route::post('/reviews/{review}/unhelpful', [CustomerReviewController::class, 'markUnhelpful'])->name('reviews.unhelpful');

    // Profile
    Route::get('/profile', [CustomerProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [CustomerProfileController::class, 'uploadAvatar'])->name('profile.avatar');

    // Wishlist
    Route::get('/wishlist', [CustomerWishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{package}', [CustomerWishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{wishlist}', [CustomerWishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/toggle/{package}', [CustomerWishlistController::class, 'toggleAjax'])->name('wishlist.toggle');
    Route::get('/wishlist/check/{package}', [CustomerWishlistController::class, 'isInWishlist'])->name('wishlist.check');

    // Gallery
    Route::get('/gallery/{package}', [CustomerGalleryController::class, 'show'])->name('gallery.show');
    Route::get('/gallery/{package}/lightbox', [CustomerGalleryController::class, 'lightbox'])->name('gallery.lightbox');

    // Availability & Calendar
    Route::get('/availability/check', [CustomerAvailabilityController::class, 'checkAvailability'])->name('availability.check');
    Route::get('/availability/calendar/{package}', [CustomerAvailabilityController::class, 'getCalendar'])->name('availability.calendar');
    Route::post('/availability/check-range', [CustomerAvailabilityController::class, 'checkDateRange'])->name('availability.checkRange');
    
    // Support & Chat
    Route::get('/support/tickets', [CustomerSupportTicketController::class, 'index'])->name('support.tickets.index');
    Route::get('/support/tickets/create', [CustomerSupportTicketController::class, 'create'])->name('support.tickets.create');
    Route::post('/support/tickets', [CustomerSupportTicketController::class, 'store'])->name('support.tickets.store');
    Route::get('/support/tickets/{id}', [CustomerSupportTicketController::class, 'show'])->name('support.tickets.show');
    Route::post('/support/tickets/{id}/messages', [CustomerSupportTicketController::class, 'addMessage'])->name('support.tickets.addMessage');
    Route::post('/support/tickets/{id}/close', [CustomerSupportTicketController::class, 'close'])->name('support.tickets.close');
    Route::get('/support/tickets/{id}/messages', [CustomerSupportTicketController::class, 'getNewMessages'])->name('support.tickets.getNewMessages');
    
    // Testimonials
    Route::get('/testimonials', [CustomerTestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('/testimonials/create', [CustomerTestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [CustomerTestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials/{testimonial}/edit', [CustomerTestimonialController::class, 'edit'])->name('testimonials.edit');
    Route::put('/testimonials/{testimonial}', [CustomerTestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [CustomerTestimonialController::class, 'destroy'])->name('testimonials.destroy');
    
    // Calendar
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/booking/{package}', [CustomerCalendarController::class, 'bookingCalendar'])->name('booking');
        Route::get('/booking/{package}/export', [CustomerCalendarController::class, 'exportBookingCalendar'])->name('export-booking');
        Route::get('/booking/{package}/data', [CustomerCalendarController::class, 'getEventData'])->name('booking-data');
        Route::get('/confirmation', [CustomerCalendarController::class, 'confirmationCalendar'])->name('confirmation');
        Route::get('/confirmation/export', [CustomerCalendarController::class, 'exportConfirmationCalendar'])->name('export-confirmation');
        Route::get('/event/{event}', [CustomerCalendarController::class, 'eventDetails'])->name('event-details');
        Route::get('/event/{event}/export', [CustomerCalendarController::class, 'exportEvent'])->name('export-event');
        Route::post('/event/{event}/confirm', [CustomerCalendarController::class, 'confirmEvent'])->name('confirm-event');
    });
    
    // Payment
    Route::get('/orders/{order}/payment', [CustomerOrderController::class, 'payment'])->name('orders.payment');
    Route::get('/orders/payment/finish', [CustomerOrderController::class, 'paymentFinish'])->name('orders.paymentFinish');
    Route::post('/orders/payment/notification', [CustomerOrderController::class, 'notification'])->name('orders.notification');
});

// Owner Routes
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [OwnerDashboardController::class, 'statistics'])->name('statistics');
    Route::get('/payments', [OwnerDashboardController::class, 'payments'])->name('payments');
    
    // Analytics & Reporting
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/dashboard', [OwnerAnalyticsController::class, 'dashboard'])->name('dashboard');
        Route::get('/revenue', [OwnerAnalyticsController::class, 'revenue'])->name('revenue');
        Route::get('/bookings', [OwnerAnalyticsController::class, 'bookings'])->name('bookings');
        Route::get('/customer-value', [OwnerAnalyticsController::class, 'customerValue'])->name('customerValue');
        Route::get('/churn', [OwnerAnalyticsController::class, 'churn'])->name('churn');
        Route::get('/export', [OwnerAnalyticsController::class, 'export'])->name('export');
    });
    
    // Calendar Management
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', [OwnerCalendarController::class, 'index'])->name('index');
        Route::get('/data/{package}', [OwnerCalendarController::class, 'getCalendarData'])->name('data');
        Route::get('/blocked/create', [OwnerCalendarController::class, 'createBlocked'])->name('blocked.create');
        Route::post('/{package}/blocked', [OwnerCalendarController::class, 'storeBlocked'])->name('blocked.store');
        Route::get('/blocked/{blockedDate}/edit', [OwnerCalendarController::class, 'editBlocked'])->name('blocked.edit');
        Route::put('/blocked/{blockedDate}', [OwnerCalendarController::class, 'updateBlocked'])->name('blocked.update');
        Route::delete('/blocked/{blockedDate}', [OwnerCalendarController::class, 'destroyBlocked'])->name('blocked.destroy');
        Route::get('/{package}/export', [OwnerCalendarController::class, 'exportCalendar'])->name('export');
    });
});

// Email Testing Routes (Development only)
Route::middleware('auth')->prefix('email-test')->name('email-test.')->group(function () {
    Route::get('/order-confirmation/{order}', [EmailTestController::class, 'testOrderConfirmation'])->name('order-confirmation');
    Route::get('/payment-received/{payment}', [EmailTestController::class, 'testPaymentReceived'])->name('payment-received');
    Route::get('/order-status/{order}', [EmailTestController::class, 'testOrderStatus'])->name('order-status');
    Route::get('/review-submission/{review}', [EmailTestController::class, 'testReviewSubmission'])->name('review-submission');
    Route::get('/admin-notification/{type}', [EmailTestController::class, 'testAdminNotification'])->name('admin-notification');
});

// SMS & WhatsApp Testing Routes (Development only)
Route::middleware('auth')->prefix('sms-test')->name('sms-test.')->group(function () {
    Route::get('/order-confirmation/{order}', [SmsTestController::class, 'testOrderConfirmation'])->name('order-confirmation');
    Route::get('/payment-reminder/{order}', [SmsTestController::class, 'testPaymentReminder'])->name('payment-reminder');
    Route::get('/payment-confirmation/{payment}', [SmsTestController::class, 'testPaymentConfirmation'])->name('payment-confirmation');
    Route::get('/event-reminder-3days/{order}', [SmsTestController::class, 'testEventReminder3Days'])->name('event-reminder-3days');
    Route::get('/event-reminder-1day/{order}', [SmsTestController::class, 'testEventReminder1Day'])->name('event-reminder-1day');
    Route::get('/event-completed/{order}', [SmsTestController::class, 'testEventCompleted'])->name('event-completed');
    Route::get('/review-thank-you/{review}', [SmsTestController::class, 'testReviewThankYou'])->name('review-thank-you');
    Route::get('/format-phone/{phone}', [SmsTestController::class, 'testFormatPhone'])->name('format-phone');
    Route::get('/logs', [SmsTestController::class, 'getSmsLogs'])->name('logs');
});
