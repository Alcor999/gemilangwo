# 🚀 QUICK START GUIDE - Manual Payment System

## 📍 For Customers

### How to Pay
1. View your order in **"My Orders"**
2. Click **"Proceed to Payment"**
3. **Select a bank** (BCA, BNI, Mandiri, or Permata)
4. **Confirm payment** - You'll see:
   - Bank account number to transfer to
   - Amount to transfer (Rp)
   - Copy button for account number
   - WhatsApp button to notify admin
5. **Transfer the money** to the account shown
6. **Click WhatsApp** to notify admin that transfer is done
7. **Wait for confirmation** - Admin will verify and you'll get email

### Bank Information
| Bank | Account | Holder |
|------|---------|--------|
| BCA | 1234567890 | PT Gemilang WO |
| BNI | 0987654321 | PT Gemilang WO |
| Mandiri | 1122334455 | PT Gemilang WO |
| Permata | 5544332211 | PT Gemilang WO |

---

## 📍 For Admin

### How to Verify Payments

**Approve Payment:**
1. Go to **Admin Dashboard** → **Orders**
2. Find order with **"Pending"** status
3. Click on order to open details
4. Scroll down to **"Payment Verification"** section
5. Check payment status and bank details
6. (Optional) Add verification notes
7. Click **"✅ Approve Payment"** button
8. Customer automatically gets confirmation email

**Reject Payment:**
1. Same as above, but scroll to **"Reject Payment"** section
2. Enter **reason for rejection** (required)
3. Click **"❌ Reject Payment"** button
4. Customer gets rejection email with your reason

---

## 🔧 Configuration

### Update Bank Accounts (For Real Data)

Edit: `database/seeders/BankSeeder.php`

```php
// Change these to real account numbers and names
Bank::create([
    'name' => 'BCA',
    'code' => 'bca',
    'account_number' => 'YOUR_REAL_ACCOUNT_NUMBER', // Change this
    'account_holder' => 'YOUR_COMPANY_NAME', // Change this
    ...
]);
```

Then run:
```bash
php artisan db:seed --class=BankSeeder
```

### Update Admin WhatsApp Number

Edit `.env` file:
```
ADMIN_WHATSAPP_NUMBER=6281234567890  # Change to real number
```

### Setup Email Delivery

Edit `.env` file:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io  # Your email provider
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

---

## 📞 Routes & URLs

### Customer Routes
```
GET  /customer/orders/{order}/payment           # Select bank
POST /customer/orders/{order}/select-bank       # Process selection
GET  /customer/orders/{order}/payment-confirm   # Show confirmation
```

### Admin Routes
```
POST /admin/payments/{payment}/approve          # Approve payment
POST /admin/payments/{payment}/reject           # Reject payment
```

### Example URLs (Local)
```
http://localhost:8000/customer/orders/12/payment
http://localhost:8000/admin/orders/12
```

---

## 💾 Database Quick Reference

### Banks Table
```sql
SELECT * FROM banks;
-- Shows: id, name, code, account_number, account_holder, logo_path, instruction, active
```

### Payments Table
```sql
SELECT * FROM payments WHERE order_id = 12;
-- Shows: id, order_id, bank_id, payment_method, amount, status
          verification_status, verified_by, verification_notes, paid_at
```

### Check Pending Payments
```sql
SELECT p.id, o.order_number, b.name as bank, p.verification_status
FROM payments p
JOIN orders o ON p.order_id = o.id
LEFT JOIN banks b ON p.bank_id = b.id
WHERE p.verification_status = 'pending';
```

---

## 📧 Email Templates

### Sent to Customer

1. **Payment Instruction Email**
   - When: After bank selection
   - Contains: Transfer instructions, bank details, account number
   - File: `resources/views/emails/payment-instruction.blade.php`

2. **Payment Verified Email**
   - When: After admin approval
   - Contains: Success message, order details
   - File: `resources/views/emails/payment-verified.blade.php`

3. **Payment Rejected Email**
   - When: After admin rejection
   - Contains: Rejection reason, retry instructions
   - File: `resources/views/emails/payment-rejected.blade.php`

---

## 🧪 Testing Commands

### Create Test Order with Payment
```bash
php artisan tinker

# Then run these commands in tinker:
$user = \App\Models\User::first();
$package = \App\Models\Package::first();
$bank = \App\Models\Bank::first();

$order = \App\Models\Order::create([
    'user_id' => $user->id,
    'package_id' => $package->id,
    'order_number' => 'TEST-' . time(),
    'event_date' => now()->addMonths(3),
    'event_location' => 'Test',
    'guest_count' => 100,
    'status' => 'pending',
    'total_price' => $package->price,
]);

app(\App\Services\PaymentService::class)->createManualPayment($order, $bank);
```

### Approve Payment
```bash
php artisan tinker

$payment = \App\Models\Payment::first();
$admin = \App\Models\User::first();
app(\App\Services\PaymentService::class)->verifyPayment($payment, $admin, 'Test');
```

---

## 🎨 UI Elements

### Bank Selection Page
- Shows 4 bank cards with:
  - Bank logo/icon
  - Bank name
  - Account number
  - Radio button for selection
- Form submission to create payment

### Payment Confirmation Page
- Order summary (number, package, date, guests)
- Bank details (name, account number, account holder)
- Amount to transfer (in Rp)
- Copy button for account number
- WhatsApp button for confirmation
- Status badge (Pending, Verified, Rejected)

### Admin Order View
- Payment Verification section integrated
- Shows payment status with color badge
- Bank information display
- Approve form (optional notes)
- Reject form (required reason)
- Verification history

---

## 🔍 Debugging

### View Payment Status
```bash
php artisan tinker
$payment = \App\Models\Payment::find(1);
echo "Status: {$payment->verification_status}";
```

### Check Email Logs
```bash
tail -f storage/logs/laravel.log | grep Mail
```

### View WhatsApp Link
```bash
php artisan tinker
$order = \App\Models\Order::find(1)->load('payment.bank', 'package');
$bank = $order->payment->bank;
echo app(\App\Services\PaymentService::class)->generateWhatsAppLink($order, $bank);
```

### Clear Cache
```bash
php artisan config:cache
php artisan cache:clear
```

---

## ✅ Checklist for Deployment

- [ ] Updated real bank account numbers
- [ ] Updated real admin WhatsApp number
- [ ] Configured email (MAIL_* variables)
- [ ] Ran migrations: `php artisan migrate`
- [ ] Seeded banks: `php artisan db:seed --class=BankSeeder`
- [ ] Tested complete payment flow
- [ ] Tested admin approval
- [ ] Tested admin rejection
- [ ] Checked emails are being sent
- [ ] Verified WhatsApp links work
- [ ] Set APP_ENV=production
- [ ] Set APP_DEBUG=false

---

## 📚 Complete Documentation

- **[PROJECT_COMPLETION_STATUS.md](PROJECT_COMPLETION_STATUS.md)** - Full project overview
- **[MANUAL_PAYMENT_SYSTEM_COMPLETE.md](MANUAL_PAYMENT_SYSTEM_COMPLETE.md)** - Feature documentation
- **[MANUAL_PAYMENT_TEST_REPORT.md](MANUAL_PAYMENT_TEST_REPORT.md)** - Test results

---

## 💡 Key Points to Remember

1. **No Payment Gateway Needed** - Direct bank transfer saves fees
2. **Manual Verification** - Admin controls approval, prevents fraud
3. **Customer Friendly** - Simple process, clear instructions
4. **WhatsApp Integration** - Easy customer-admin communication
5. **Email Notifications** - All status changes emailed to customer
6. **Audit Trail** - Track who verified what and when
7. **Automatic Updates** - Order status changes automatically

---

## 🆘 Need Help?

1. Check logs: `storage/logs/laravel.log`
2. Run tests: See "Testing Commands" section above
3. Review code: Check file paths in main documentation
4. Test database: Use SQL commands in "Database" section

---

**Last Updated:** 28 January 2026  
**Version:** 1.0  
**Status:** ✅ Production Ready
