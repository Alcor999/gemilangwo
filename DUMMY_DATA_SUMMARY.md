# Wedding App - Dummy Data Summary

Semua data dummy telah berhasil dibuat dan diseed ke dalam database. Berikut adalah ringkasan lengkapnya:

## Data Statistics
- **Users**: 8 (1 admin, 1 owner, 6 customers)
- **Packages**: 6 (berbagai paket WO dengan harga berbeda)
- **Orders**: 7 (berbagai status untuk testing)
- **Payments**: 6 (dengan Midtrans transaction IDs)
- **Reviews**: 3 (dari completed orders)

## Test Users (Password: password123 untuk semua)

### Admin Account
- Email: `admin@gemilangwo.test`
- Password: `password123`
- Role: Admin
- Access: Full system access, manage packages, orders, users

### Owner Account
- Email: `owner@gemilangwo.test`
- Password: `password123`
- Role: Owner
- Access: View statistics, reports, and payment analytics

### Customer Accounts
1. **Budi** - budi@gemilangwo.test (2 orders, 1 completed with review)
2. **Siti** - siti@gemilangwo.test (1 pending order)
3. **Ahmad** - ahmad@gemilangwo.test (1 completed order with review)
4. **Dewi** - dewi@gemilangwo.test (1 in-progress order)
5. **Rinto** - rinto@gemilangwo.test (1 completed order with review)
6. **Nina** - nina@gemilangwo.test (1 confirmed order)

## Wedding Packages

1. **Rose Romantic** - Rp35,000,000
   - Max Guests: 150
   - Features: Catering, Photography, Decoration, Music, Transport
   - Status: Active
   - Orders: 1 (Rinto - Completed)

2. **Bronze Standard** - Rp60,000,000
   - Max Guests: 300
   - Features: Full Catering, Professional Photography, Garden Decoration, Live Band, Car Rental, Makeup Artist
   - Status: Active
   - Orders: 2 (Ahmad - Completed, Budi - Cancelled)

3. **Silver Elegance** - Rp100,000,000
   - Max Guests: 400
   - Features: Premium Catering, 4K Photography, Grand Hall Decoration, Live Orchestra, Premium Transport, Professional Makeup, DJ
   - Status: Active
   - Orders: 1 (Budi - Confirmed with payment)

4. **Diamond Mewah** - Rp180,000,000
   - Max Guests: 600
   - Features: Luxury Catering, Cinematic Photography, Elite Decoration, Live Orchestra + DJ, VIP Transport, Top Makeup Artist, Wedding Planner
   - Status: Active
   - Orders: 1 (Nina - Confirmed with payment)

5. **Gold Premium** - Rp150,000,000
   - Max Guests: 500
   - Features: Premium Catering, Professional Photography, Themed Decoration, Live Band, Car Rental, Makeup Artist, Event Coordinator
   - Status: Active
   - Orders: 1 (Siti - Pending payment)

6. **Platinum Eksklusif** - Rp250,000,000
   - Max Guests: 800
   - Features: Michelin Chef Catering, Award-Winning Cinematography, Custom Design Decoration, Orchestra + DJ, Luxury Transport, Celebrity Makeup Artist, Executive Wedding Planner, Custom Entertainment
   - Status: Active
   - Orders: 1 (Dewi - In Progress with payment)

## Orders by Status

### Pending (1)
- **Order**: WO-[timestamp]002 (Siti)
- Package: Gold Premium (Rp150M)
- Event Date: 45 hari ke depan
- Guests: 350
- Payment Status: Pending (belum bayar)

### Confirmed (2)
1. **Order**: WO-[timestamp]001 (Budi)
   - Package: Silver Elegance (Rp100M)
   - Event Date: 30 hari ke depan
   - Guests: 250
   - Payment Status: Success (paid 5 days ago)
   - Special Request: Tema warna emas dan putih, dekorasi bunga mawar merah

2. **Order**: WO-[timestamp]006 (Nina)
   - Package: Diamond Mewah (Rp180M)
   - Event Date: 50 hari ke depan
   - Guests: 400
   - Payment Status: Success (paid 3 days ago)
   - Special Request: Dokumentasi cinematic berkualitas tinggi, koordinator profesional

### In Progress (1)
- **Order**: WO-[timestamp]004 (Dewi)
- Package: Platinum Eksklusif (Rp250M)
- Event Date: 20 hari ke depan (soonest event!)
- Guests: 500
- Payment Status: Success (paid 15 days ago)
- Special Request: Tema tepi pantai dengan sunset ceremony

### Completed (2)
1. **Order**: WO-[timestamp]003 (Ahmad)
   - Package: Bronze Standard (Rp60M)
   - Guests: 200
   - Payment Status: Success (paid via bank transfer)
   - Review: 4 stars - "Paket cukup lengkap dengan dokumentasi bagus"

2. **Order**: WO-[timestamp]005 (Rinto)
   - Package: Rose Romantic (Rp35M)
   - Guests: 100
   - Payment Status: Success (paid 20 days ago)
   - Review: 5 stars - "Layanan luar biasa, tim sangat profesional!"

### Cancelled (1)
- **Order**: WO-[timestamp]007 (Budi)
- Package: Bronze Standard (Rp60M)
- Event Date: 90 hari ke depan
- Guests: 150
- Special Request: Tema outdoor garden party

## Payment Methods in Orders
- Credit Card: 2 orders
- Bank Transfer: 2 orders
- E-Wallet: 1 order
- Pending: 1 order

## Reviews
- **Order 5 (Rinto)**: 5 stars - "Layanan luar biasa! Tim profesional, detail, responsif. Acara sempurna sesuai visi kami. Sangat rekomendasikan!"
- **Order 3 (Ahmad)**: 4 stars - "Paket sudah cukup lengkap. Dokumentasi bagus, dekorasi sesuai keinginan. Ada beberapa hal kecil tapi overall puas."
- **Order 1 (Budi)**: 5 stars - "Paket silver dengan upgrade dekorasi benar-benar spektakuler. Semua vendor berkualitas tinggi. Terima kasih atas dedikasi!"

## How to Test

### 1. Test Admin Features
```
Login: admin@gemilangwo.test / password123
- Akses /admin/dashboard untuk lihat statistik
- Manage packages di /admin/packages
- Lihat semua orders di /admin/orders
- Manage users di /admin/users
```

### 2. Test Customer Features
```
Login: budi@gemilangwo.test / password123
- Lihat dashboard customer di /customer/dashboard
- Browse packages di /customer/packages
- Lihat orders history di /customer/orders
- Detail order dengan review di /customer/orders/[id]
```

### 3. Test Owner Features
```
Login: owner@gemilangwo.test / password123
- Akses /owner/dashboard untuk statistik bisnis
- Lihat analytics di /owner/statistics
- Laporan pembayaran di /owner/payments
```

### 4. Test Booking Flow (New Customer)
```
Login dengan customer baru: siti@gemilangwo.test / password123
- Browse packages yang ada
- Lihat order pending (belum ada payment)
- Simulasi payment flow
```

### 5. Test Payment Integration
- Orders dengan status "confirmed" atau "in_progress" memiliki payment record
- Lihat Midtrans payment IDs di payment table
- Test payment notification webhook di /customer/orders/payment/notification

## Seeding Command
```bash
php artisan db:seed
```

Atau untuk refresh database completely:
```bash
php artisan migrate:fresh --seed
```

## Files Created/Modified
- `database/seeders/UserSeeder.php` - 8 test users
- `database/seeders/PackageSeeder.php` - 6 wedding packages
- `database/seeders/OrderSeeder.php` - 7 test orders dengan berbagai status
- `database/seeders/ReviewSeeder.php` - 3 reviews dari customers
- `database/seeders/DatabaseSeeder.php` - Updated untuk memanggil semua seeders
- `routes/web.php` - Fixed syntax error (closing brace untuk owner routes)

## Next Steps
1. Jalankan aplikasi dengan `php artisan serve`
2. Login dengan salah satu test account
3. Explore features sesuai role masing-masing
4. Test complete booking flow
5. Verify data muncul dengan benar di semua halaman
