# Wedding Organizer Booking System

Aplikasi pemesanan paket Wedding Organizer (WO) dengan fitur multi-user, integrasi Midtrans, dan dashboard statistik lengkap.

## Features

### 1. **User Management**
- **Admin**: Mengelola paket, pesanan, dan user
- **Customer**: Browse paket, membuat pesanan, melakukan pembayaran
- **Owner**: Melihat statistik bisnis dan laporan keuangan

### 2. **Package Management (Admin)**
- CRUD package WO
- Upload gambar package
- Manage fitur dan maksimal tamu
- Status package (active/inactive)

### 3. **Booking System (Customer)**
- Browse semua paket yang tersedia
- Buat pesanan baru dengan detail acara
- Tracking status pesanan
- Lihat riwayat pesanan

### 4. **Payment Integration (Midtrans)**
- Integrasi Snap Payment
- Support berbagai metode pembayaran:
  - Credit Card
  - Bank Transfer
  - E-Wallet
  - Cash (manual)
- Webhook notifikasi pembayaran
- Auto-update status pesanan setelah pembayaran

### 5. **Dashboard & Statistics (Owner)**
- Total orders dan revenue
- Orders by status breakdown
- Monthly revenue chart data
- Top packages performance
- Customer retention analysis
- Payment method statistics
- Payment status tracking

### 6. **Admin Dashboard**
- Overview statistik
- Recent orders list
- Quick actions

### 7. **Customer Dashboard**
- Order summary
- Quick booking actions
- Recent bookings

## Database Structure

### Tables:

1. **users** (extended)
   - role: enum (admin, customer, owner)
   - phone: string
   - address: text

2. **packages**
   - name, description, price
   - max_guests, features (json)
   - image, status

3. **orders**
   - user_id, package_id
   - order_number (unique)
   - event_date, event_location
   - guest_count, special_request
   - total_price, status

4. **payments**
   - order_id, payment_id (Midtrans)
   - payment_method, amount
   - status, midtrans_response (json)
   - paid_at timestamp

5. **reviews**
   - order_id, user_id
   - rating (1-5), comment

## Installation & Setup

### 1. Prerequisites
```bash
PHP 8.1+
Composer
Laravel 11
MySQL/SQLite
Midtrans account
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database
Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gemilangwo
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Configure Midtrans
Edit `.env`:
```env
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_IS_PRODUCTION=false  # Change to true for production
```

Get your keys from [Midtrans Dashboard](https://dashboard.midtrans.com)

### 6. Run Migrations
```bash
php artisan migrate
```

### 7. Create Default Users (Optional)
```bash
php artisan tinker
# Then run:
App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'phone' => '08xx',
    'role' => 'admin'
]);

App\Models\User::create([
    'name' => 'Owner User',
    'email' => 'owner@example.com',
    'password' => bcrypt('password'),
    'phone' => '08xx',
    'role' => 'owner'
]);

App\Models\User::create([
    'name' => 'Customer User',
    'email' => 'customer@example.com',
    'password' => bcrypt('password'),
    'phone' => '08xx',
    'role' => 'customer'
]);
```

### 8. Create Sample Packages (Optional)
```bash
php artisan tinker
# Then:
App\Models\Package::create([
    'name' => 'Gold Package',
    'description' => 'Premium wedding organizer package',
    'price' => 50000000,
    'max_guests' => 500,
    'features' => json_encode(['Decoration', 'Catering', 'Musik', 'Photography']),
    'status' => 'active'
]);
```

### 9. Run Development Server
```bash
php artisan serve
npm run dev  # In another terminal
```

## File Structure

```
gemilangwo/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── PackageController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   └── UserController.php
│   │   │   ├── Customer/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── PackageController.php
│   │   │   │   └── OrderController.php
│   │   │   └── Owner/
│   │   │       └── DashboardController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Package.php
│   │   ├── Order.php
│   │   ├── Payment.php
│   │   └── Review.php
│   ├── Services/
│   │   └── MidtransService.php
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   └── migrations/
│       ├── *_add_role_to_users_table.php
│       ├── *_create_packages_table.php
│       ├── *_create_orders_table.php
│       ├── *_create_payments_table.php
│       └── *_create_reviews_table.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── packages/
│       │   ├── orders/
│       │   └── users/
│       ├── customer/
│       │   ├── dashboard.blade.php
│       │   ├── packages/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   └── orders/
│       │       ├── index.blade.php
│       │       ├── create.blade.php
│       │       ├── show.blade.php
│       │       └── payment.blade.php
│       └── owner/
│           ├── dashboard.blade.php
│           ├── statistics.blade.php
│           └── payments.blade.php
├── routes/
│   └── web.php
└── config/
    └── midtrans.php
```

## Routes Overview

### Public Routes
- `/` - Home page
- `/login` - Login page
- `/register` - Register page

### Admin Routes (Protected)
- `/admin/dashboard` - Admin dashboard
- `/admin/packages` - Manage packages (CRUD)
- `/admin/orders` - View/manage orders
- `/admin/users` - Manage users & roles

### Customer Routes (Protected)
- `/customer/dashboard` - Customer dashboard
- `/customer/packages` - Browse packages
- `/customer/orders` - My bookings
- `/customer/orders/create` - Create booking
- `/customer/orders/{order}/payment` - Payment page

### Owner Routes (Protected)
- `/owner/dashboard` - Business dashboard
- `/owner/statistics` - Detailed statistics
- `/owner/payments` - Payment reports

## API Integration

### Midtrans Endpoints
- **Snap Token Creation**: `/customer/orders/{id}/payment`
- **Notification Webhook**: `/customer/orders/payment/notification`
- **Payment Finish**: `/customer/orders/payment/finish`

## Important Notes

1. **Payment Notification**: Pastikan webhook URL Midtrans di-set ke:
   ```
   https://yourdomain.com/customer/orders/payment/notification
   ```

2. **Environment**: 
   - Development: Gunakan `MIDTRANS_IS_PRODUCTION=false`
   - Production: Ubah ke `true` dan gunakan production keys

3. **Database**: Pastikan semua migrations sudah dijalankan sebelum testing

4. **Authentication**: System menggunakan Laravel's built-in authentication

5. **Authorization**: Role-based access control menggunakan middleware `CheckRole`

## Testing Midtrans Payment

### Test Credentials (Sandbox):
- Card Number: `4011 1111 1111 1112`
- Expiry: Any future date
- CVV: Any 3 digits

Lebih lengkap di: [Midtrans Test Credentials](https://docs.midtrans.com/en/technical-reference/sandbox-credentials)

## Future Enhancements

- [ ] Review & rating system
- [ ] Invoice generation (PDF)
- [ ] Email notifications
- [ ] SMS notifications
- [ ] Advanced search & filtering
- [ ] Customer support chat
- [ ] Package customization
- [ ] Promo codes
- [ ] Analytics dashboard improvements
- [ ] API documentation (for third-party integration)

## Support

Untuk bantuan lebih lanjut, silakan hubungi tim development atau baca dokumentasi Laravel di [laravel.com](https://laravel.com)

## License

MIT License
