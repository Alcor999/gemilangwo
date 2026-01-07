# Email Notification System - Implementation Guide

## 🎯 Overview

Email Notification System untuk Gemilang WO telah berhasil diimplementasikan dengan fitur-fitur lengkap:

- ✅ Automatic email triggers untuk order, payment, review
- ✅ Beautiful HTML email templates
- ✅ Admin notification system
- ✅ Queue-based email sending (background processing)
- ✅ Production-ready configuration

---

## 📧 Mailable Classes

### 1. **OrderConfirmationMail**
```php
Use: Mail::to($customer->email)->queue(new OrderConfirmationMail($order));

Triggered when:
- New order created
- Automatically sends to customer

Email content:
- Order ID & details
- Package information
- Event date & location
- Total price
- Call-to-action button to view order
```

### 2. **PaymentReceivedMail**
```php
Use: Mail::to($customer->email)->queue(new PaymentReceivedMail($payment));

Triggered when:
- Payment status changed to 'success'
- Automatically sends to customer

Email content:
- Payment confirmation
- Order details
- Amount paid
- Payment method & date
- What happens next
```

### 3. **OrderStatusMail**
```php
Use: Mail::to($customer->email)->queue(new OrderStatusMail($order, $previousStatus));

Triggered when:
- Order status changes (pending → confirmed → in_progress → completed)
- Automatically sends to customer

Email content:
- Previous vs new status
- Status-specific message
- Order tracking link
```

### 4. **ReviewSubmissionMail**
```php
Use: Mail::to($customer->email)->queue(new ReviewSubmissionMail($review));

Triggered when:
- New review submitted
- Automatically sends to customer

Email content:
- Review summary (rating, title)
- Moderation status
- Why reviews matter
- Link to view reviews
```

### 5. **AdminNotificationMail**
```php
Use: Mail::to(config('app.admin_email'))->queue(new AdminNotificationMail('new_order', $data));

Types:
- 'new_order': New order received
- 'new_review': Review submitted (needs moderation)
- 'payment_received': Payment confirmed

Email content:
- Notification type-specific details
- Quick action links
- Dashboard link
```

---

## 🔄 Automatic Triggers

### Order Creation
```php
// Order.php - booted() method
static::created(function (Order $order) {
    // Send order confirmation to customer
    Mail::to($order->user->email)->queue(new OrderConfirmationMail($order));
    
    // Send admin notification
    Mail::to(config('app.admin_email'))->queue(new AdminNotificationMail('new_order', [...]));
});
```

### Order Status Update
```php
static::updating(function (Order $order) {
    if ($order->isDirty('status')) {
        // Send status update email to customer
        Mail::to($order->user->email)->queue(new OrderStatusMail($order, $previousStatus));
    }
});
```

### Payment Received
```php
// Payment.php - booted() method
static::updating(function (Payment $payment) {
    if ($payment->isDirty('status') && $payment->status === 'success') {
        // Send payment confirmation to customer
        Mail::to($payment->order->user->email)->queue(new PaymentReceivedMail($payment));
        
        // Send admin notification
        Mail::to(config('app.admin_email'))->queue(new AdminNotificationMail('payment_received', [...]));
    }
});
```

### Review Submission
```php
// Review.php - booted() method
static::created(function (Review $review) {
    // Send review confirmation to customer
    Mail::to($review->user->email)->queue(new ReviewSubmissionMail($review));
    
    // Send admin notification (for moderation)
    Mail::to(config('app.admin_email'))->queue(new AdminNotificationMail('new_review', [...]));
});
```

---

## 📝 Configuration

### .env Setup

```dotenv
# Email Configuration
MAIL_MAILER=log                          # Use 'log' for development, 'smtp' for production
MAIL_FROM_ADDRESS="noreply@gemilangwo.test"
MAIL_FROM_NAME="Gemilang WO"

# Admin Email
ADMIN_EMAIL=admin@gemilangwo.test

# Queue Configuration (for background email sending)
QUEUE_CONNECTION=database               # Use 'sync' for immediate sending
```

### SMTP Configuration (Production)
```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password         # Use App Password for Gmail
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Gemilang WO"
```

### Mailtrap Configuration (Testing)
```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_FROM_ADDRESS=noreply@gemilangwo.test
```

---

## 🧪 Testing Emails

### Test Routes Available

```
GET /email-test/order-confirmation/{order}
GET /email-test/payment-received/{payment}
GET /email-test/order-status/{order}
GET /email-test/review-submission/{review}
GET /email-test/admin-notification/{type}
```

### Examples

```bash
# Test order confirmation email for order #1
http://localhost:8000/email-test/order-confirmation/1

# Test payment received email for payment #1
http://localhost:8000/email-test/payment-received/1

# Test order status email for order #1
http://localhost:8000/email-test/order-status/1

# Test review submission email for review #1
http://localhost:8000/email-test/review-submission/1

# Test admin notification for new order
http://localhost:8000/email-test/admin-notification/new_order

# Test admin notification for new review
http://localhost:8000/email-test/admin-notification/new_review

# Test admin notification for payment received
http://localhost:8000/email-test/admin-notification/payment_received
```

---

## 📊 Email Templates

All email templates are located in `resources/views/emails/`

### Files Created
- `layout.blade.php` - Base email layout with styling
- `order-confirmation.blade.php` - Order confirmation email
- `payment-received.blade.php` - Payment confirmation email
- `order-status.blade.php` - Order status update email
- `review-submission.blade.php` - Review submission confirmation
- `admin-notification.blade.php` - Admin notifications

### Template Features
- 📱 Responsive design
- 🎨 Modern styling with CSS
- 🔗 Action buttons (CTA)
- 📊 Detailed information tables
- ✨ Status badges
- 📧 Professional branding

---

## 🚀 Queue System

### Setup Queue (Background Processing)

For development (use sync):
```dotenv
QUEUE_CONNECTION=sync    # Emails sent immediately
```

For production (use database):
```dotenv
QUEUE_CONNECTION=database    # Emails queued and sent in background
```

### Run Queue Worker (Production)
```bash
php artisan queue:work --tries=3 --timeout=60
```

### Monitor Queued Jobs
```bash
php artisan queue:failed-table    # Create failed_jobs table
php artisan queue:table           # Create jobs table
php artisan migrate
```

---

## 📱 Notification Model

### Features
- Store in-app notifications
- Track read/unread status
- Mark as read/unread
- Query unread notifications
- Filter by type

### Database Table
```
notifications
├─ id (primary key)
├─ user_id (foreign key)
├─ type (order, payment, review, message)
├─ title
├─ message
├─ data (JSON)
├─ action_url
├─ is_read
├─ read_at
└─ timestamps
```

### Usage Example
```php
// Get unread notifications for user
$notifications = auth()->user()->notifications()->unread()->get();

// Mark as read
$notification->markAsRead();

// Get specific type
$orderNotifications = $user->notifications()->ofType('order')->get();
```

---

## 🔧 Implementation Checklist

- [x] Create 5 Mailable classes
- [x] Create 6 email template views
- [x] Add automatic triggers in models
- [x] Create Notification model
- [x] Create notifications table migration
- [x] Setup .env configuration
- [x] Create test email controller
- [x] Add test routes
- [x] Configure queue system
- [x] Add User relationships

---

## 📋 Next Steps

### Phase 2: SMS/WhatsApp Integration
- Integrate Twilio or WhatsApp Business API
- Send SMS notifications for orders
- WhatsApp notifications for payment reminders
- Event reminders via WhatsApp

### Phase 3: In-App Notifications
- Create notification dashboard
- Real-time notifications with Pusher
- Notification preferences/settings
- Notification history

### Phase 4: Email Templates Enhancement
- Add email preference center
- Unsubscribe functionality
- Email frequency settings
- A/B testing

---

## 🛠️ Troubleshooting

### Emails not sending?

1. Check .env MAIL_MAILER is set correctly
2. Check queue connection: `php artisan queue:work`
3. For development, use `MAIL_MAILER=log` to test locally
4. Check logs: `storage/logs/laravel.log`

### Queue not processing?

```bash
# Start queue worker
php artisan queue:work

# Monitor queue
php artisan queue:monitor

# Clear failed jobs
php artisan queue:flush
```

### Test if emails are being queued

```bash
php artisan tinker
>>> \App\Models\Order::count()
>>> \App\Models\Payment::count()
>>> \App\Models\Review::count()
```

---

## 📚 Files Created/Modified

### New Files
- `app/Mail/OrderConfirmationMail.php`
- `app/Mail/PaymentReceivedMail.php`
- `app/Mail/OrderStatusMail.php`
- `app/Mail/ReviewSubmissionMail.php`
- `app/Mail/AdminNotificationMail.php`
- `app/Models/Notification.php`
- `app/Http/Controllers/EmailTestController.php`
- `resources/views/emails/layout.blade.php`
- `resources/views/emails/order-confirmation.blade.php`
- `resources/views/emails/payment-received.blade.php`
- `resources/views/emails/order-status.blade.php`
- `resources/views/emails/review-submission.blade.php`
- `resources/views/emails/admin-notification.blade.php`
- `database/migrations/2026_01_04_100000_create_notifications_table.php`

### Modified Files
- `app/Models/Order.php` - Added email triggers
- `app/Models/Payment.php` - Added email triggers
- `app/Models/Review.php` - Added email triggers
- `app/Models/User.php` - Added notifications relationship
- `routes/web.php` - Added test email routes
- `.env` - Added email configuration

---

## ✨ Features Summary

✅ **Automatic Email Triggers**
- Order creation
- Payment received
- Order status updates
- Review submission

✅ **Beautiful HTML Templates**
- Responsive design
- Professional styling
- Clear CTAs
- Status badges

✅ **Admin Notifications**
- New order alerts
- Review moderation alerts
- Payment confirmations

✅ **Queue System Ready**
- Background email processing
- Retry logic
- Failed job tracking

✅ **Testing Tools**
- Test email routes
- Easy email preview
- Flexible admin email

✅ **Production Ready**
- SMTP configuration support
- Email service provider integration
- Scalable architecture

---

**🎉 Email Notification System is Ready for Production!**

Last Updated: January 4, 2026
