# Database Schema & ERD

## Entity Relationship Diagram (Text Format)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                                                                               │
│                    WEDDING ORGANIZER BOOKING SYSTEM                         │
│                                                                               │
│  ┌──────────────────────┐        ┌──────────────────────┐                  │
│  │       USERS          │        │     PACKAGES         │                  │
│  ├──────────────────────┤        ├──────────────────────┤                  │
│  │ id (PK)              │        │ id (PK)              │                  │
│  │ name                 │        │ name                 │                  │
│  │ email (unique)       │        │ description          │                  │
│  │ password             │        │ price (decimal)      │                  │
│  │ phone                │        │ max_guests           │                  │
│  │ address              │        │ features (json)      │                  │
│  │ role (enum)*         │        │ image                │                  │
│  │ email_verified_at    │        │ status (enum)**      │                  │
│  │ created_at           │        │ created_at           │                  │
│  │ updated_at           │        │ updated_at           │                  │
│  │ deleted_at           │        │ deleted_at           │                  │
│  └──────────────────────┘        └──────────────────────┘                  │
│           │ 1                              │ 1                              │
│           │ (hasMany)                      │ (hasMany)                      │
│           │                                │                                │
│           └────────────┬───────────────────┘                               │
│                        │                                                    │
│                        │                                                    │
│           ┌────────────▼────────────┐                                      │
│           │       ORDERS            │                                      │
│           ├────────────────────────┤                                       │
│           │ id (PK)                │◄─────────────────────┐               │
│           │ user_id (FK)           │                      │               │
│           │ package_id (FK)        │                      │               │
│           │ order_number (unique)  │                      │               │
│           │ event_date             │                      │               │
│           │ event_location         │                      │               │
│           │ guest_count            │                      │               │
│           │ special_request        │                      │               │
│           │ total_price (decimal)  │                      │               │
│           │ status (enum)***       │                      │               │
│           │ created_at             │                      │               │
│           │ updated_at             │                      │               │
│           │ deleted_at             │                      │               │
│           └────────────┬────────────┘                      │ 1            │
│                        │ 1                                  │ (hasOne)    │
│                        │ (hasOne)                           │              │
│                        │                                    │              │
│           ┌────────────▼────────────┐                      │              │
│           │      PAYMENTS           │                      │              │
│           ├────────────────────────┤                       │              │
│           │ id (PK)                │                       │              │
│           │ order_id (FK)          ├───────────────────────┘              │
│           │ payment_id             │                                      │
│           │ payment_method (enum)  │                                      │
│           │ amount (decimal)       │                                      │
│           │ status (enum)****      │                                      │
│           │ midtrans_response      │                                      │
│           │ paid_at                │                                      │
│           │ created_at             │                                      │
│           │ updated_at             │                                      │
│           └────────────────────────┘                                      │
│                                                                            │
│           ┌────────────────────────┐                                      │
│           │      REVIEWS           │                                      │
│           ├────────────────────────┤                                      │
│           │ id (PK)                │                                      │
│           │ order_id (FK)          │                                      │
│           │ user_id (FK)           │                                      │
│           │ rating (1-5)           │                                      │
│           │ comment (text)         │                                      │
│           │ created_at             │                                      │
│           │ updated_at             │                                      │
│           │ deleted_at             │                                      │
│           └────────────────────────┘                                      │
│                                                                            │
│  * role enum values:                                                       │
│    - admin (pengelola aplikasi & paket)                                   │
│    - customer (pelanggan/pembuat pesanan)                                 │
│    - owner (pemilik bisnis WO)                                            │
│                                                                            │
│  ** status enum values:                                                    │
│    - active (paket tersedia untuk dibooking)                             │
│    - inactive (paket tidak tersedia)                                      │
│                                                                            │
│  *** status enum values:                                                   │
│    - pending (order baru, menunggu pembayaran)                           │
│    - confirmed (pembayaran berhasil, order dikonfirmasi)                 │
│    - in_progress (acara sedang berlangsung)                              │
│    - completed (acara selesai)                                            │
│    - cancelled (order dibatalkan)                                         │
│                                                                            │
│  **** status enum values:                                                  │
│    - pending (pembayaran menunggu)                                        │
│    - processing (pembayaran sedang diproses)                             │
│    - success (pembayaran berhasil)                                        │
│    - failed (pembayaran gagal)                                            │
│    - cancelled (pembayaran dibatalkan)                                    │
│                                                                            │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## Relationships Summary

| From Table | To Table | Type | Cardinality | Method |
|-----------|----------|------|-------------|--------|
| users | orders | One-to-Many | 1:M | hasMany() |
| users | reviews | One-to-Many | 1:M | hasMany() |
| packages | orders | One-to-Many | 1:M | hasMany() |
| orders | payments | One-to-One | 1:1 | hasOne() |
| orders | reviews | One-to-Many | 1:M | hasMany() |
| reviews | user | Many-to-One | M:1 | belongsTo() |
| reviews | order | Many-to-One | M:1 | belongsTo() |
| payments | order | Many-to-One | M:1 | belongsTo() |
| orders | user | Many-to-One | M:1 | belongsTo() |
| orders | package | Many-to-One | M:1 | belongsTo() |

---

## Data Types Reference

### USERS Table
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    address TEXT NULL,
    role ENUM('admin', 'customer', 'owner') DEFAULT 'customer',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);
```

### PACKAGES Table
```sql
CREATE TABLE packages (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(12, 2) NOT NULL,
    max_guests INT NULL,
    features JSON NULL,
    image VARCHAR(255) NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);
```

### ORDERS Table
```sql
CREATE TABLE orders (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    package_id BIGINT UNSIGNED NOT NULL,
    order_number VARCHAR(255) UNIQUE NOT NULL,
    event_date DATE NOT NULL,
    event_location VARCHAR(255) NOT NULL,
    guest_count INT NOT NULL,
    special_request TEXT NULL,
    total_price DECIMAL(12, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
);
```

### PAYMENTS Table
```sql
CREATE TABLE payments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    order_id BIGINT UNSIGNED NOT NULL,
    payment_id VARCHAR(255) UNIQUE NOT NULL,
    payment_method ENUM('credit_card', 'bank_transfer', 'e_wallet', 'cash') NULL,
    amount DECIMAL(12, 2) NOT NULL,
    status ENUM('pending', 'processing', 'success', 'failed', 'cancelled') DEFAULT 'pending',
    midtrans_response JSON NULL,
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
```

### REVIEWS Table
```sql
CREATE TABLE reviews (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    order_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

## Data Flow Examples

### Example Order Creation
```
User (customer) → Browse Packages (view available packages)
                  ↓
              Select Package
                  ↓
              Fill Order Form:
              - event_date
              - event_location
              - guest_count
              - special_request
                  ↓
              INSERT INTO orders
                  ↓
              order_number auto-generated: WO-1704268800
              status: pending
              total_price: from packages.price
                  ↓
              User redirected to payment page
```

### Example Payment Processing
```
User → Payment Page (Midtrans Snap)
       ↓
     Select Payment Method
       ↓
   Complete Payment
       ↓
Midtrans Webhook Notification
       ↓
MidtransService::handleNotification()
       ↓
IF success:
  - Update payments.status = 'success'
  - Update payments.paid_at = now()
  - Update orders.status = 'confirmed'
ELSE:
  - Update payments.status = 'failed'
  - Update orders.status = 'cancelled'
       ↓
User Redirected to order detail page
```

---

## Indexes Recommendation

For optimal query performance, add these indexes:

```sql
-- Users
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);

-- Orders
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_orders_package_id ON orders(package_id);
CREATE INDEX idx_orders_order_number ON orders(order_number);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_event_date ON orders(event_date);

-- Payments
CREATE INDEX idx_payments_order_id ON payments(order_id);
CREATE INDEX idx_payments_payment_id ON payments(payment_id);
CREATE INDEX idx_payments_status ON payments(status);

-- Reviews
CREATE INDEX idx_reviews_order_id ON reviews(order_id);
CREATE INDEX idx_reviews_user_id ON reviews(user_id);
```

---

**Schema Version**: 1.0
**Last Updated**: 4 Januari 2026
