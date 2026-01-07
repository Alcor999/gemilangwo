# SMS & WhatsApp Notification System - Implementation Guide

## 🎯 Overview

SMS & WhatsApp notification system telah diimplementasikan menggunakan **Twilio** dengan fitur-fitur lengkap:

- ✅ SMS & WhatsApp notifications via Twilio
- ✅ Beautiful message templates (Indonesian)
- ✅ Automatic triggers untuk order, payment, review
- ✅ User notification preferences
- ✅ SMS logging untuk audit trail
- ✅ Phone number formatting
- ✅ Fallback dari WhatsApp ke SMS
- ✅ Production-ready architecture

---

## 📱 Services & Classes

### SmsService
```php
// Location: app/Services/SmsService.php

Methods:
- sendSms(phoneNumber, message) → bool
- sendWhatsApp(phoneNumber, message) → bool
- sendSmsTemplate(phoneNumber, templateKey, data) → bool
- sendWhatsAppTemplate(phoneNumber, templateKey, data) → bool
- formatPhoneNumber(phone) → string (static)
```

### NotificationService
```php
// Location: app/Services/NotificationService.php

Methods:
- notifyOrderConfirmation($order)
- notifyPaymentReminder($order, $paymentUrl)
- notifyPaymentConfirmation($payment)
- notifyEventReminder3Days($order)
- notifyEventReminder1Day($order, $teamInfo)
- notifyEventCompleted($order)
- notifyReviewThankYou($review)
```

### SendsNotifications Trait
```php
// Location: app/Traits/SendsNotifications.php

Usage:
use App\Traits\SendsNotifications;

class MyModel extends Model {
    use SendsNotifications;
    
    $this->sendOrderConfirmation();
    $this->sendPaymentReminder();
    // ... etc
}
```

---

## 📝 Message Templates

7 built-in templates tersedia (Indonesian):

### 1. order_confirmation
```
Pesanan Anda telah diterima!

No. Pesanan: {order_id}
Paket: {package_name}
Tanggal Event: {event_date}
Total: Rp {total_price}

Terima kasih telah memilih kami! 🎉
```

### 2. payment_reminder
```
Pengingat Pembayaran 💰

No. Pesanan: {order_id}
Total: Rp {total_price}

Segera lakukan pembayaran untuk mengamankan tanggal event Anda.

Link pembayaran: {payment_link}
```

### 3. payment_confirmation
```
Pembayaran Berhasil ✅

No. Pesanan: {order_id}
Jumlah: Rp {amount}

Terima kasih! Event Anda sudah dikonfirmasi.
```

### 4. event_reminder_3days
```
Pengingat Event 📅

Event Anda tinggal 3 hari lagi!

Tanggal: {event_date}
Lokasi: {event_location}

Siap-siap untuk hari istimewa Anda! 🎊
```

### 5. event_reminder_1day
```
Pengingat Event Akhir 📅

Event Anda besok!

Tanggal: {event_date}
Lokasi: {event_location}
Tim: {team_info}

See you tomorrow! 🎉
```

### 6. event_completed
```
Event Selesai! 🎊

Terima kasih atas kepercayaan Anda!
Kami harap event Anda sempurna.

Berikan rating & review untuk event Anda di aplikasi.
```

### 7. review_thank_you
```
Terima Kasih atas Review ⭐

Review Anda membantu kami untuk terus meningkatkan kualitas layanan.

Harga spesial untuk booking berikutnya! 🎁
```

---

## ⚙️ Configuration

### 1. Setup Twilio Account

Visit: https://www.twilio.com/

```bash
1. Create Twilio account
2. Get Account SID & Auth Token
3. Setup phone number for SMS
4. Setup WhatsApp Sandbox (optional)
```

### 2. Update .env

```dotenv
TWILIO_ACCOUNT_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_PHONE_NUMBER=+1234567890          # SMS phone number
TWILIO_WHATSAPP_NUMBER=+1234567890       # WhatsApp number
```

### 3. Install Twilio SDK

```bash
composer require twilio/sdk
```

---

## 🚀 How to Use

### Automatic Triggers

Orders otomatis send SMS when:
```php
// Order created
Order::create([...])
// → Sends order confirmation SMS/WhatsApp

// Order status changes to 'confirmed'
$order->update(['status' => 'confirmed'])
// → Sends payment reminder SMS/WhatsApp

// Order status changes to 'completed'
$order->update(['status' => 'completed'])
// → Can trigger event completed SMS
```

Payments otomatis send SMS when:
```php
// Payment status updated to 'success'
$payment->update(['status' => 'success'])
// → Sends payment confirmation SMS/WhatsApp
```

Reviews otomatis send SMS when:
```php
// Review created
Review::create([...])
// → Email sent (via Email system)
// → Admin notified (via Email system)
```

### Manual Triggers

```php
use App\Services\NotificationService;

$service = app(NotificationService::class);

// Send order confirmation
$service->notifyOrderConfirmation($order);

// Send payment reminder
$service->notifyPaymentReminder($order, $paymentUrl);

// Send payment confirmation
$service->notifyPaymentConfirmation($payment);

// Send event reminder (3 days)
$service->notifyEventReminder3Days($order);

// Send event reminder (1 day)
$service->notifyEventReminder1Day($order, 'Tim A & Tim B');

// Send event completed
$service->notifyEventCompleted($order);

// Send review thank you
$service->notifyReviewThankYou($review);
```

### Using Trait

```php
use App\Traits\SendsNotifications;

class Order extends Model {
    use SendsNotifications;
}

// Usage
$order->sendOrderConfirmation();
$order->sendPaymentReminder();
$order->sendEventReminder3Days();
```

### Phone Number Formatting

```php
use App\Services\SmsService;

// All these formats work:
SmsService::formatPhoneNumber('0812345678');  // Indonesia
SmsService::formatPhoneNumber('62812345678');
SmsService::formatPhoneNumber('+62812345678');
// All return: +62812345678

// In model
$user->formatPhone('0812345678'); // Uses trait
```

---

## 🧪 Testing

### Test Routes Available

```
SMS & WhatsApp Tests:
GET /sms-test/order-confirmation/{order}
GET /sms-test/payment-reminder/{order}
GET /sms-test/payment-confirmation/{payment}
GET /sms-test/event-reminder-3days/{order}
GET /sms-test/event-reminder-1day/{order}
GET /sms-test/event-completed/{order}
GET /sms-test/review-thank-you/{review}
GET /sms-test/format-phone/{phone}
GET /sms-test/logs
```

### Examples

```bash
# Test order confirmation for order #1
http://localhost:8000/sms-test/order-confirmation/1

# Test payment reminder for order #1
http://localhost:8000/sms-test/payment-reminder/1

# Test payment confirmation for payment #1
http://localhost:8000/sms-test/payment-confirmation/1

# Test event reminder 3 days
http://localhost:8000/sms-test/event-reminder-3days/1

# Test event reminder 1 day
http://localhost:8000/sms-test/event-reminder-1day/1

# Test event completed
http://localhost:8000/sms-test/event-completed/1

# Test review thank you
http://localhost:8000/sms-test/review-thank-you/1

# Format phone number
http://localhost:8000/sms-test/format-phone/0812345678

# Get SMS logs
http://localhost:8000/sms-test/logs
```

---

## 📊 Database Schema

### users table (added columns)
```sql
ALTER TABLE users ADD COLUMN prefer_whatsapp BOOLEAN DEFAULT 1;
ALTER TABLE users ADD COLUMN prefer_sms BOOLEAN DEFAULT 1;
ALTER TABLE users ADD COLUMN prefer_email BOOLEAN DEFAULT 1;
```

### sms_logs table (new)
```sql
CREATE TABLE sms_logs (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED (nullable),
    phone_number VARCHAR(255),
    message TEXT,
    type ENUM('sms', 'whatsapp') DEFAULT 'sms',
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    template_key VARCHAR(255) (nullable),
    template_data JSON (nullable),
    twilio_sid VARCHAR(255) (nullable),
    error_message TEXT (nullable),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX (user_id),
    INDEX (status),
    INDEX (type),
    INDEX (phone_number)
);
```

---

## 📱 Notification Preferences

### User Settings

```php
// User model
$user->prefer_whatsapp = true;  // Prefer WhatsApp
$user->prefer_sms = false;       // Disable SMS
$user->prefer_email = true;      // Prefer Email

$user->save();

// System will automatically:
// 1. Try WhatsApp if prefer_whatsapp = true
// 2. Fallback to SMS if WhatsApp fails or prefer_sms = true
// 3. Send Email based on prefer_email
```

---

## 📋 Models & Relationships

### User Model
```php
// New columns
$user->prefer_whatsapp;
$user->prefer_sms;
$user->prefer_email;

// New relationship
$user->smsLogs()  // HasMany SmsLog
```

### SmsLog Model
```php
$log->user()      // BelongsTo User
$log->scopeSent() // Query sent logs
$log->scopeFailed()  // Query failed logs
$log->scopeOfType($type)  // Query by type
$log->scopeWhatsApp()  // Query WhatsApp logs
$log->scopeSms()  // Query SMS logs
```

---

## 🔄 Event Reminders (Scheduled)

### Setup Scheduled Commands

Create command untuk event reminders:

```bash
php artisan make:command SendEventReminders
php artisan make:command SendEventReminder1Day
php artisan make:command SendEventReminder3Days
```

Add to `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Send reminders 3 days before event
    $schedule->command('send:event-reminders-3days')
        ->dailyAt('09:00')
        ->timezone('Asia/Jakarta');

    // Send reminders 1 day before event
    $schedule->command('send:event-reminders-1day')
        ->dailyAt('10:00')
        ->timezone('Asia/Jakarta');

    // Send completed notifications
    $schedule->command('send:event-completed')
        ->dailyAt('18:00')
        ->timezone('Asia/Jakarta');
}
```

---

## 🛠️ Troubleshooting

### SMS not sending?

1. Check Twilio credentials in .env
2. Check user has valid phone number
3. Check user has prefer_whatsapp or prefer_sms enabled
4. Check logs: `http://localhost:8000/sms-test/logs`
5. Check Laravel logs: `storage/logs/laravel.log`

### WhatsApp not working?

1. Verify WhatsApp Sandbox is setup in Twilio
2. Send "join" message to WhatsApp number first
3. Check WhatsApp number format
4. Check Twilio WhatsApp number configuration

### Phone number formatting issues?

Test formatting:
```bash
http://localhost:8000/sms-test/format-phone/0812345678
http://localhost:8000/sms-test/format-phone/62812345678
http://localhost:8000/sms-test/format-phone/+62812345678
```

---

## 📊 Files Created/Modified

### New Files
- `app/Services/SmsService.php`
- `app/Services/NotificationService.php`
- `app/Traits/SendsNotifications.php`
- `app/Http/Controllers/SmsTestController.php`
- `app/Models/SmsLog.php`
- `database/migrations/2026_01_04_110000_add_sms_preferences_to_users_table.php`
- `database/migrations/2026_01_04_110001_create_sms_logs_table.php`

### Modified Files
- `app/Models/Order.php` - Added SMS triggers
- `app/Models/Payment.php` - Added SMS triggers
- `app/Models/User.php` - Added SMS columns & relationships
- `config/services.php` - Added Twilio config
- `routes/web.php` - Added SMS test routes
- `.env` - Added Twilio credentials

---

## ✨ Features Summary

✅ **Multiple Notification Types**
- SMS notifications
- WhatsApp notifications
- Automatic fallback SMS if WhatsApp fails

✅ **7 Built-in Templates**
- Order confirmation
- Payment reminder
- Payment confirmation
- Event reminders (3 days, 1 day)
- Event completed
- Review thank you

✅ **User Preferences**
- WhatsApp preference
- SMS preference
- Email preference
- Can be toggled per user

✅ **Automatic Triggers**
- Order creation
- Payment confirmation
- Order status changes
- Review submission

✅ **Phone Formatting**
- Automatic phone number formatting
- Support multiple formats
- E.164 format conversion

✅ **Logging & Audit Trail**
- All SMS/WhatsApp logged
- Success/failure tracking
- Twilio SID for reference
- Error message tracking

✅ **Testing Tools**
- Easy test routes
- Phone formatting test
- SMS log viewing
- No setup required

✅ **Production Ready**
- Error handling
- Logging
- Twilio integration
- Fallback mechanisms

---

## 🚀 Next Steps

### Phase 3: In-App Notifications & Live Chat
- Real-time notifications with Pusher
- Notification center dashboard
- Live chat widget
- Admin chat panel

### Phase 4: Advanced Features
- Scheduled event reminders (Laravel jobs)
- Email templates customization
- SMS response handling
- Two-way messaging

### Phase 5: Analytics & Reporting
- SMS delivery analytics
- WhatsApp engagement metrics
- Notification performance dashboard

---

## 📚 Documentation Files

- This file: SMS_WHATSAPP_GUIDE.md
- EMAIL_NOTIFICATION_GUIDE.md - Email system docs
- EMAIL_SYSTEM_COMPLETE.md - Email summary

---

**🎉 SMS & WhatsApp System is Production Ready!**

Last Updated: January 4, 2026
