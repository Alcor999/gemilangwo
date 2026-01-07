# 🎊 Gemilang WO - Wedding Organizer Booking System

> Aplikasi pemesanan paket Wedding Organizer dengan fitur multi-user, integrasi Midtrans, dan dashboard analytics lengkap.

[![Laravel](https://img.shields.io/badge/Laravel-11-red?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple?style=flat-square&logo=bootstrap)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

## 🌟 Features

### 👥 Multi-Role User System
- **Admin**: Kelola paket, pesanan, dan user
- **Customer**: Browse paket, membuat pesanan, pembayaran
- **Owner**: Lihat statistik bisnis dan laporan keuangan

### 📦 Package Management
- CRUD paket WO dengan deskripsi lengkap
- Upload gambar paket
- Manage kapasitas tamu maksimal
- Fitur-fitur paket (json storage)
- Status aktif/nonaktif

### 🎫 Booking System
- Browse paket yang tersedia
- Buat pesanan dengan detail acara
- Tracking status pesanan real-time
- View riwayat pesanan lengkap
- Batalkan pesanan (sebelum dibayar)

### 💳 Midtrans Payment Integration
- Snap payment gateway
- Support multiple metode pembayaran:
  - Credit Card
  - Bank Transfer
  - E-Wallet (OVO, DANA, LinkAja)
  - Cash (manual)
- Auto-confirmation setelah pembayaran
- Webhook handling untuk notifikasi
- Secure payment processing

### 📊 Owner Dashboard & Analytics
- Total orders, customers, revenue tracking
- Orders breakdown by status
- Monthly revenue data visualization
- Top performing packages
- Customer retention analysis
- Payment method statistics

### 🎯 Admin Dashboard
- Overview statistik lengkap
- Recent orders monitoring
- Quick action buttons
- User management

### 👤 Customer Dashboard
- Personal order statistics
- Quick booking actions
- Recent bookings list

---

## 🛠️ Tech Stack

| Component | Technology |
|-----------|-----------|
| Backend | Laravel 11 (PHP 8.1+) |
| Frontend | Bootstrap 5.3 |
| Database | MySQL/SQLite |
| Payment Gateway | Midtrans |
| Icons | Font Awesome 6.4 |
| Authentication | Laravel Auth |

---

## 📋 Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL 8.0+ (atau SQLite)
- Node.js & npm (untuk vite)
- Midtrans Account (sandbox testing)

---

## 🚀 Quick Start

### 1. Clone & Setup

```bash
cd /Users/mymac/Projects/gemilangwo

# Install dependencies
composer install
npm install

# Copy environment
cp .env.example .env

# Generate app key
php artisan key:generate
```

### 2. Database Configuration

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gemilangwo
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Midtrans Setup

Edit `.env`:
```env
MIDTRANS_SERVER_KEY=your-sandbox-server-key
MIDTRANS_CLIENT_KEY=your-sandbox-client-key
MIDTRANS_IS_PRODUCTION=false
```

Get keys from: https://dashboard.midtrans.com

### 4. Run Migrations

```bash
php artisan migrate
```

### 5. Start Development Server

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

Access: http://localhost:8000

---

## 📁 Project Structure

```
gemilangwo/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin management
│   │   │   ├── Customer/       # Customer booking
│   │   │   └── Owner/          # Owner analytics
│   │   └── Middleware/
│   │       └── CheckRole.php   # Role authorization
│   ├── Models/
│   │   ├── User.php
│   │   ├── Package.php
│   │   ├── Order.php
│   │   ├── Payment.php
│   │   └── Review.php
│   └── Services/
│       └── MidtransService.php # Payment service
├── database/
│   └── migrations/             # 5 migration files
├── resources/
│   └── views/                  # 18+ blade templates
│       ├── admin/
│       ├── customer/
│       ├── owner/
│       └── layouts/
├── routes/
│   └── web.php                 # Complete routing
├── config/
│   └── midtrans.php           # Payment config
└── docs/
    ├── SETUP_GUIDE.md
    ├── QUICKSTART.md
    ├── DATABASE_SCHEMA.md
    ├── TESTING_CHECKLIST.md
    └── PROJECT_SUMMARY.md
```

---

## 🗄️ Database Schema

### Tables (5)
1. **users** - User dengan role (admin, customer, owner)
2. **packages** - WO package catalog
3. **orders** - Booking orders dengan status
4. **payments** - Midtrans payment tracking
5. **reviews** - Customer reviews & ratings

[Lihat detail schema →](DATABASE_SCHEMA.md)

---

## 🔐 Security Features

- ✅ Role-based access control (middleware)
- ✅ CSRF protection (built-in Laravel)
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ Password hashing (bcrypt)
- ✅ XSS protection
- ✅ Secure payment handling (Midtrans)

---

## 📱 Features by Role

### ADMIN
```
✅ View dashboard dengan 4 stats cards
✅ CRUD paket WO lengkap
✅ Manage semua orders di sistem
✅ Change order status
✅ Manage users & change roles
✅ View recent orders
```

### CUSTOMER
```
✅ Dashboard personal dengan stats
✅ Browse active packages
✅ Create booking dengan detail acara
✅ Midtrans payment processing
✅ Track order status real-time
✅ View booking history
✅ Cancel pending orders
```

### OWNER
```
✅ Business dashboard
✅ Total orders & revenue tracking
✅ Orders status breakdown
✅ Customer retention analysis
✅ Payment method analytics
✅ Top performing packages
```

---

## 💳 Payment Test

### Sandbox Credentials
```
Card Number: 4011 1111 1111 1112
Expiry: 12/25
CVV: 123
OTP: 123456
```

[More test credentials →](https://docs.midtrans.com/en/technical-reference/sandbox-credentials)

---

## 📖 Documentation

| Document | Purpose |
|----------|---------|
| [SETUP_GUIDE.md](SETUP_GUIDE.md) | Complete setup instructions |
| [QUICKSTART.md](QUICKSTART.md) | 5-minute quick start |
| [DATABASE_SCHEMA.md](DATABASE_SCHEMA.md) | Database ERD & structure |
| [TESTING_CHECKLIST.md](TESTING_CHECKLIST.md) | Complete testing guide |
| [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) | Implementation summary |

---

## 🧪 Testing

Gunakan testing checklist lengkap untuk memastikan semua fitur bekerja:

```bash
# Run tests (jika ada test suite)
php artisan test

# Check code quality
php artisan lint
```

[Detailed Testing Checklist →](TESTING_CHECKLIST.md)

---

## 🔄 Workflow Examples

### Booking Flow
```
1. Customer registers/login
2. Browse active packages
3. Create order dengan event details
4. Proceed to Midtrans payment
5. Complete payment
6. Order auto-confirmed
7. Admin can manage order status
8. Customer can track progress
```

### Payment Flow
```
1. Customer clicks "Proceed to Payment"
2. Snap token generated via MidtransService
3. Midtrans Snap embed loads
4. Customer chooses payment method
5. Payment processing
6. Webhook notification sent
7. MidtransService handles notification
8. Order & payment status updated
9. Customer redirected to success page
```

### Admin Management
```
1. Admin login
2. View dashboard overview
3. Manage packages (create/edit/delete)
4. Monitor orders
5. Update order status
6. Change customer roles
7. View recent activity
```

---

## 🚀 Deployment

### Pre-Deployment Checklist
- [ ] All migrations run successfully
- [ ] Midtrans production keys configured
- [ ] Environment variables set
- [ ] Database backup created
- [ ] Static assets compiled
- [ ] Error logging configured

### Production Setup
```bash
# Set environment
APP_ENV=production
APP_DEBUG=false
MIDTRANS_IS_PRODUCTION=true

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Setup webhook in Midtrans Dashboard
# https://yourdomain.com/customer/orders/payment/notification
```

---

## 📞 Support & Resources

- **Laravel Documentation**: https://laravel.com/docs
- **Midtrans Documentation**: https://docs.midtrans.com
- **Bootstrap Documentation**: https://getbootstrap.com/docs
- **Font Awesome Icons**: https://fontawesome.com/icons

---

## 🐛 Troubleshooting

### Migration Errors
```bash
php artisan migrate:fresh  # Reset dan re-migrate
php artisan migrate:reset  # Reset saja
```

### Midtrans Connection Issues
1. Verify API keys di `.env`
2. Check internet connection
3. Verify Midtrans account status
4. Check sandbox/production mode setting

### View Not Found
```bash
php artisan view:clear
php artisan cache:clear
```

### Permission Issues
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

---

## 📝 Project Timeline

| Date | Milestone |
|------|-----------|
| 04 Jan 2026 | Project setup & database design |
| 04 Jan 2026 | Controllers & services implementation |
| 04 Jan 2026 | Views & routing complete |
| 04 Jan 2026 | Documentation & testing checklist |
| ✅ COMPLETE | Ready for testing & deployment |

---

## 🎯 Future Enhancements

- [ ] Invoice generation (PDF export)
- [ ] Email notifications
- [ ] SMS notifications
- [ ] Advanced search & filtering
- [ ] Customer support chat
- [ ] Package customization
- [ ] Promo codes & discounts
- [ ] Advanced analytics dashboard
- [ ] API for third-party integration
- [ ] Mobile app version

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

---

## 👨‍💻 Development Info

- **Framework**: Laravel 11
- **PHP Version**: 8.1+
- **Database**: MySQL 8.0+
- **UI Framework**: Bootstrap 5.3
- **Payment Gateway**: Midtrans
- **Created**: 4 Januari 2026

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📊 Statistics

- **Controllers**: 11
- **Models**: 5
- **Views**: 18+
- **Migrations**: 5
- **Routes**: 30+
- **Lines of Code**: 2500+
- **Documentation Pages**: 5

---

## ✨ Key Highlights

✅ **Production-Ready**: Error handling, validation, security
✅ **Scalable**: Service layer untuk payment logic
✅ **Maintainable**: Clean code architecture
✅ **User-Friendly**: Intuitive UI dengan clear workflows
✅ **Complete**: Semua fitur yang diminta sudah implemented
✅ **Documented**: Setup guides + code comments

---

## 🎉 Status

```
✅ Database Structure: COMPLETE
✅ Models & Relationships: COMPLETE
✅ Controllers & Services: COMPLETE
✅ Views & Templates: COMPLETE
✅ Routing: COMPLETE
✅ Authentication: COMPLETE
✅ Authorization: COMPLETE
✅ Payment Integration: COMPLETE
✅ Dashboard & Analytics: COMPLETE
✅ Documentation: COMPLETE
✅ Testing Checklist: COMPLETE

📊 Overall Completion: 100%
```

---

**🚀 Ready to Use!**

Start the server and begin testing:
```bash
php artisan serve
```

Happy booking! 🎊

---

**Questions?** Check the documentation files or review the inline code comments.

**Found a bug?** Please report it in the issues section.

**Want to contribute?** We welcome pull requests!

---

*Last Updated: 4 Januari 2026*
*Version: 1.0.0*

<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
