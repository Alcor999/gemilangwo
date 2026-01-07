# Quick Start Guide - Wedding Organizer Booking System

## 🚀 Implementasi Cepat (5 Menit)

### 1. Jalankan Migrations
```bash
cd /Users/mymac/Projects/gemilangwo
php artisan migrate
```

### 2. Setup Midtrans Keys
Edit file `.env`:
```env
MIDTRANS_SERVER_KEY=M2RwMDUwOWUtMmVkZS00ZTUwLWE5ZjItNjFkYjNiMzMyMzU2  # Ganti dengan server key Anda
MIDTRANS_CLIENT_KEY=SB-Mid-client-a1b2c3d4e5f6g7h8i  # Ganti dengan client key Anda
MIDTRANS_IS_PRODUCTION=false  # Untuk production, ubah ke true
```

Dapatkan keys dari: https://dashboard.midtrans.com

### 3. Jalankan Server
```bash
php artisan serve
```

Akses: http://localhost:8000

### 4. Login & Testing
Daftar user baru atau gunakan:
```
Email: admin@example.com
Password: password
Role: admin (untuk cek setup)
```

## 📁 Fitur Yang Sudah Diimplementasi

### ✅ Database (5 Tables)
- **users** - Multi-role system (admin, customer, owner)
- **packages** - WO package management
- **orders** - Booking orders
- **payments** - Midtrans integration
- **reviews** - Customer reviews

### ✅ Admin Panel
- Dashboard dengan statistik
- CRUD paket WO
- Manage semua orders
- Manage users dan role

### ✅ Customer Features
- Browse paket
- Create booking
- Payment dengan Midtrans
- Track order status
- View history

### ✅ Owner Dashboard
- Business statistics
- Revenue tracking
- Orders by status
- Payment analytics

### ✅ Payment Integration
- Midtrans Snap
- Multiple payment methods
- Auto-confirmation
- Webhook handling

## 🔧 Testing Payment

### Test Card (Sandbox Mode)
- Card: `4011 1111 1111 1112`
- Expiry: `12/25`
- CVV: `123`
- OTP: `123456`

Reference: https://docs.midtrans.com/en/technical-reference/sandbox-credentials

## 📋 Checklist Next Steps

- [ ] Install Midtrans package (jika belum)
  ```bash
  composer require midtrans/midtrans-php
  ```

- [ ] Create test packages di admin panel
  
- [ ] Test booking flow:
  1. Register sebagai customer
  2. Browse packages
  3. Create order
  4. Do payment (gunakan test card)
  5. Check payment status

- [ ] Setup webhook URL di Midtrans Dashboard
  ```
  https://yourdomain.com/customer/orders/payment/notification
  ```

- [ ] Deploy ke production (update MIDTRANS_IS_PRODUCTION & keys)

## 🎨 UI Framework
- Bootstrap 5.3
- Font Awesome Icons
- Custom CSS (gradient, animations)
- Responsive design

## 📞 Support & Troubleshooting

### Migration Error?
```bash
php artisan migrate:fresh --seed
```

### Midtrans Connection Error?
1. Pastikan keys di `.env` benar
2. Check internet connection
3. Verify Midtrans account aktif

### View Not Found?
```bash
php artisan view:clear
php artisan cache:clear
```

### Role Access Denied?
Logout dan login kembali dengan user yang sesuai role-nya

## 📚 File Structure Summary

```
Core Files:
├── app/Models/ → Database models
├── app/Http/Controllers/ → Business logic
├── app/Services/MidtransService.php → Payment gateway
├── database/migrations/ → Database schema
├── resources/views/ → UI templates
├── routes/web.php → All routes
├── config/midtrans.php → Midtrans config
└── .env → Environment variables

Views:
├── admin/ → Admin dashboard & management
├── customer/ → Customer booking & orders
├── owner/ → Owner statistics
└── layouts/ → Base layout

Controllers:
├── Admin/ → 4 controllers
├── Customer/ → 3 controllers
└── Owner/ → 1 controller
```

## 🔐 Security Notes

1. **Middleware Role**: Semua routes protected dengan `CheckRole` middleware
2. **CSRF Protection**: Built-in Laravel CSRF
3. **SQL Injection**: Menggunakan ORM (Eloquent)
4. **Password Hashing**: Menggunakan bcrypt
5. **Webhook Verification**: Perlu ditambahkan di production

## 🚀 Production Deployment

1. Update `.env`:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   MIDTRANS_IS_PRODUCTION=true
   MIDTRANS_SERVER_KEY=<production-server-key>
   MIDTRANS_CLIENT_KEY=<production-client-key>
   ```

2. Run:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan migrate --force
   ```

3. Setup webhook di Midtrans Dashboard

4. Update payment notification route di Midtrans

## 📞 Hubungi Support

Untuk bantuan lebih lanjut: 
- Laravel Docs: https://laravel.com/docs
- Midtrans Docs: https://docs.midtrans.com

---

**Happy Booking! 🎉**
