# Discount & Flash Sale Feature Documentation

## Overview
Gemilang WO sekarang memiliki sistem **Discount & Flash Sale** yang komprehensif. Admin dapat membuat berbagai jenis promosi dengan dua tipe potongan harga (percentage atau fixed amount), dan semua ditampilkan dengan menarik di halaman home dengan harga yang dicoret.

## Features

### 1. **Admin Discount Management**
- **Create Discounts**: Admin dapat membuat promosi baru melalui `/admin/discounts/create`
- **Edit Discounts**: Ubah detail promosi kapan saja
- **Delete Discounts**: Hapus promosi yang tidak lagi berlaku
- **View Discounts**: Lihat list semua promosi dengan status dan detail

### 2. **Discount Types**
#### Percentage Discount
```
Type: percentage
Value: 30 (untuk 30%)
Contoh: "Year End Sale 2025" - 30% off all packages
Harga Original: Rp 150,000,000 → Final: Rp 105,000,000
```

#### Fixed Amount Discount
```
Type: fixed
Value: 1000000 (dalam Rupiah)
Contoh: "Valentine Special" - Save Rp 1,000,000
Harga Original: Rp 150,000,000 → Final: Rp 149,000,000
```

### 3. **Promo Configuration Options**

| Field | Description | Required |
|-------|-------------|----------|
| `name` | Nama promosi/discount | ✅ Yes |
| `description` | Deskripsi untuk customers | ❌ No |
| `type` | `percentage` atau `fixed` | ✅ Yes |
| `value` | Nilai discount (0-100 untuk %, angka untuk Rp) | ✅ Yes |
| `start_date` | Mulai berlaku | ✅ Yes |
| `end_date` | Berakhir (kosong = unlimited) | ❌ No |
| `is_active` | Enable/disable promosi | ✅ Yes |
| `usage_limit` | Batas jumlah penggunaan (kosong = unlimited) | ❌ No |
| `packages` | Package mana saja yang dapat diskon (kosong = semua) | ❌ No |

### 4. **Package-Specific Discounts**
Admin dapat memilih package mana saja yang mendapat diskon:
- **All Packages**: Kosongkan selection (auto-apply ke semua)
- **Specific Packages**: Pilih package tertentu saja (contoh: hanya Gold dan Silver)

### 5. **Flash Sale & Limited Edition**
Dukung promosi terbatas dengan:
- **Usage Limit**: Batasi berapa kali promosi bisa digunakan
- **Time Limit**: Set start dan end date untuk promosi musiman
- **Status Badge**: Otomatis tampil sebagai "Flash Sale" dengan animasi pulse

## Frontend Display

### Home Page Package Card
Setiap package card sekarang menampilkan:

1. **Discount Badge** (jika ada)
   ```
   🔥 30% OFF  atau  🔥 Save Rp 1,000,000
   ```
   - Tampil dengan gradient purple-pink
   - Animated pulse effect untuk menarik perhatian

2. **Original Price** (jika ada discount)
   ```
   Rp 150,000,000  (dicoret/strikethrough)
   ```
   - Warna abu-abu (gray)
   - Line-through decoration

3. **Final/Discounted Price**
   ```
   Rp 105,000,000
   ```
   - Warna gradient purple-pink
   - Ukuran besar (prominent)

### Example Display
```
┌─────────────────────────┐
│  💜 Paket Gold Premium   │
│                         │
│  🔥 30% OFF             │
│  Rp 150,000,000 (coret) │
│  Rp 105,000,000         │
│                         │
│  Up to 500 Guests       │
│                         │
│  [Book Now Button]      │
└─────────────────────────┘
```

## Database Schema

### `discounts` Table
```sql
CREATE TABLE discounts (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    type ENUM('percentage', 'fixed'),
    value DECIMAL(10, 2),
    start_date DATETIME,
    end_date DATETIME (nullable),
    is_active BOOLEAN DEFAULT true,
    usage_limit INTEGER (nullable),
    usage_count INTEGER DEFAULT 0,
    created_by BIGINT (foreign key to users),
    timestamps
);
```

### `discount_package` Pivot Table
```sql
CREATE TABLE discount_package (
    discount_id BIGINT,
    package_id BIGINT,
    PRIMARY KEY (discount_id, package_id)
);
```

## Models & Relationships

### Discount Model (`App\Models\Discount`)
```php
// Methods
public function packages()          // Many-to-many with Package
public function creator()           // Belongs to User
public function isActive()          // Check if discount currently active
public function calculateDiscount()  // Calculate discount amount
public function getDiscountedPrice() // Get final price after discount
```

### Package Model (Updated)
```php
// New Methods
public function discounts()          // Many-to-many with Discount
public function activeDiscounts()    // Get active discounts for this package
public function getDiscountedPrice() // Get discounted price
public function getActiveDiscount()  // Get first active discount
```

## Routes

### Admin Routes
```
GET    /admin/discounts              - List all discounts
GET    /admin/discounts/create       - Show create form
POST   /admin/discounts              - Store new discount
GET    /admin/discounts/{id}         - Show discount details
GET    /admin/discounts/{id}/edit    - Show edit form
PUT    /admin/discounts/{id}         - Update discount
DELETE /admin/discounts/{id}         - Delete discount
```

## Usage Examples

### Example 1: Year End Sale
```
Name: "Year End Sale 2025"
Type: Percentage
Value: 30
Start: 2026-01-04
End: 2026-02-04
Packages: All (kosongkan selection)
Active: Yes
```

**Result**: Semua packages dapat diskon 30%, harga original dicoret, final price tampil

### Example 2: Valentine Promo
```
Name: "Valentine Special"
Type: Fixed
Value: 1000000
Start: 2026-02-04
End: 2026-03-04
Packages: Paket Gold Premium, Paket Silver Elegance
Active: Yes
```

**Result**: Hanya 2 package yang dapat diskon Rp 1 juta

### Example 3: Early Bird Flash Sale
```
Name: "Flash Sale - Limited Time!"
Type: Percentage
Value: 15
Start: 2026-01-04
End: 2026-01-11
Usage Limit: 50
Packages: All
Active: Yes
```

**Result**: Diskon 15% untuk 50 bookings pertama dalam 1 minggu, dengan badge "Flash Sale" yang berkilau

## Admin Interface

### Discount List Page
- Table dengan kolom: Name, Type, Value, Period, Packages, Status, Actions
- Pagination (15 per halaman)
- Quick actions: View, Edit, Delete
- Badge untuk status active/inactive
- Color coding untuk tipe discount (percentage vs fixed)

### Create/Edit Form
- Form wizard dengan sections:
  - Basic Info (name, description)
  - Discount Settings (type, value)
  - Time Period (start, end date)
  - Constraints (usage limit)
  - Package Selection
  - Status toggle
- Real-time label update untuk percentage vs fixed
- Tips panel dengan contoh promo

### Details Page
- Summary of all discount details
- Table showing impact pada setiap package:
  - Original Price
  - Discount Amount
  - Final Price
- Creator info dan timestamps
- Edit/Delete buttons

## Sidebar Menu
Admin dapat akses discount management melalui menu:
```
Admin Dashboard
├── Dashboard
├── Manage Packages
├── Discounts & Promos  ← NEW
├── Orders
└── Users
```

## Sample Data
Database sudah di-seed dengan 4 contoh discount:

1. **Year End Sale 2025** (30% off all packages, active now)
2. **Valentine Special** (Rp 1juta off, Feb 4 - Mar 4)
3. **Early Bird Special** (20% off for Gold Premium)
4. **Flash Sale** (15% off all, limited to 50 uses, expires Jan 11)

## Code Examples

### Get Package with Discount
```php
$package = Package::find(1);

// Get active discount
$discount = $package->getActiveDiscount();

// Get final price
$finalPrice = $package->getDiscountedPrice();

// Calculate discount amount
if ($discount) {
    $discountAmount = $discount->calculateDiscount($package->price);
}
```

### Calculate Discount in Blade
```blade
@php
    $discount = $package->getActiveDiscount();
    $finalPrice = $discount 
        ? $discount->getDiscountedPrice($package->price) 
        : $package->price;
@endphp

@if ($discount)
    <div class="discount-badge">
        @if ($discount->type === 'percentage')
            {{ $discount->value }}% OFF
        @else
            Save Rp {{ number_format($discount->value, 0, ',', '.') }}
        @endif
    </div>
    <div class="price-original">
        Rp {{ number_format($package->price, 0, ',', '.') }}
    </div>
@endif

<div class="price-final">
    Rp {{ number_format($finalPrice, 0, ',', '.') }}
</div>
```

## Key Features Summary

✅ **Two Discount Types**: Percentage atau Fixed Amount
✅ **Time-Based**: Set start & end dates
✅ **Usage Limiting**: Batas jumlah penggunaan (flash sales)
✅ **Package Specific**: Bisa untuk all packages atau pilihan tertentu
✅ **Visual Appeal**: Strikethrough original price + animated badge
✅ **Admin Control**: Full CRUD management interface
✅ **Active Status**: System otomatis check if discount berlaku
✅ **Real-time Calculation**: Harga dinamis berdasarkan discount aktif
✅ **Sample Data**: Pre-seeded dengan 4 contoh promo
✅ **Responsive Design**: Mobile-friendly admin interface

## Future Enhancements

Possible additions untuk versi depan:
- [ ] Discount coupon codes untuk customers
- [ ] Automatic discount expiry notifications
- [ ] Discount usage analytics & reports
- [ ] Buy-one-get-one (BOGO) promotions
- [ ] Tiered discounts (multiple discount rules)
- [ ] Customer group-specific discounts
- [ ] Discount effectiveness reporting
