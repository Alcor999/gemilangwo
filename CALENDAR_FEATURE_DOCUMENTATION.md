# Advanced Booking Calendar Feature - Dokumentasi Lengkap

## 📋 Ringkasan Fitur

Fitur Advanced Booking Calendar menyediakan sistem manajemen kalender terintegrasi untuk wedding app dengan kemampuan:

### Fitur Utama

1. **📅 Calendar Heatmap (Peta Panas Ketersediaan)**
   - Visualisasi grafis tanggal tersedia/terblokir
   - Warna berbeda untuk status (hijau=tersedia, merah=terblokir, kuning=sibuk)
   - Navigasi bulan sebelumnya/selanjutnya
   - Data real-time

2. **🚫 Block Dates untuk Admin**
   - Admin bisa memblokir tanggal tertentu
   - Jenis blokir: unavailable, maintenance, reserved, personal
   - Alasan blokir (opsional)
   - Edit dan hapus blokir tanggal
   - Validasi tanggal

3. **📊 Auto-Calculate Event Days**
   - Otomatis hitung jumlah hari acara
   - Support pre-event dan post-event days
   - Tracking tanggal yang terpakai
   - Perhitungan otomatis dari order

4. **✅ Booking Confirmation Calendar**
   - Pelanggan lihat acara mereka
   - Konfirmasi acara via kalender
   - Timeline acara lengkap
   - Status tracking

5. **📲 iCal Export**
   - Export ke format iCal/ICS
   - Kompatibel dengan Google Calendar, Outlook, Apple Calendar
   - Support multiple export types (all, events, blocked)
   - File download otomatis

## 🏗️ Struktur Database

### Tabel: blocked_dates
```sql
CREATE TABLE blocked_dates (
    id BIGINT PRIMARY KEY,
    package_id BIGINT (FK packages),
    start_date DATE,
    end_date DATE,
    reason VARCHAR(255) NULLABLE,
    block_type ENUM('unavailable', 'maintenance', 'reserved', 'personal'),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    KEY idx_package_dates (package_id, start_date, end_date)
);
```

### Tabel: calendar_events
```sql
CREATE TABLE calendar_events (
    id BIGINT PRIMARY KEY,
    order_id BIGINT (FK orders),
    package_id BIGINT (FK packages),
    event_date DATE,
    event_start DATE NULLABLE,
    event_end DATE NULLABLE,
    status ENUM('pending', 'confirmed', 'in_progress', 'completed'),
    notes TEXT NULLABLE,
    is_confirmed BOOLEAN DEFAULT FALSE,
    confirmed_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    KEY idx_package_date (package_id, event_date),
    KEY idx_order (order_id),
    KEY idx_status (status)
);
```

### Perubahan: orders Table
```sql
ALTER TABLE orders ADD COLUMN pre_event_days INT DEFAULT 0;
ALTER TABLE orders ADD COLUMN post_event_days INT DEFAULT 0;
ALTER TABLE orders ADD COLUMN calendar_confirmed BOOLEAN DEFAULT FALSE;
```

## 📂 File Structure

### Models
```
app/Models/
├── BlockedDate.php          # Model tanggal terblokir
├── CalendarEvent.php        # Model event acara
└── User.php                 # Updated dengan relationship
```

### Controllers
```
app/Http/Controllers/
├── Owner/
│   └── CalendarController.php       # Kelola blokir tanggal, heatmap
└── Customer/
    └── CalendarController.php       # Lihat kalender, konfirmasi acara
```

### Services
```
app/Services/
└── ICalExportService.php    # Export ke format iCal
```

### Views
```
resources/views/
├── owner/calendar/
│   ├── index.blade.php              # Kalender owner dengan heatmap
│   ├── create-blocked.blade.php      # Form blokir tanggal baru
│   └── edit-blocked.blade.php        # Form edit blokir tanggal
└── customer/calendar/
    ├── booking.blade.php             # Kalender booking dengan heatmap
    ├── confirmation.blade.php        # Kalender konfirmasi acara
    └── event-details.blade.php       # Detail acara
```

### JavaScript & CSS
```
public/
├── js/
│   └── booking-calendar.js           # Calendar interaktif, heatmap
└── css/
    └── booking-calendar.css          # Styling kalender
```

## 🛣️ Routes

### Owner Routes
```
GET  /owner/calendar                          # List kalender
GET  /owner/calendar/data/{package}           # Get calendar data (AJAX)
GET  /owner/calendar/blocked/create           # Form blokir tanggal
POST /owner/calendar/{package}/blocked        # Simpan blokir tanggal
GET  /owner/calendar/blocked/{blockedDate}/edit  # Edit blokir tanggal
PUT  /owner/calendar/blocked/{blockedDate}    # Update blokir tanggal
DELETE /owner/calendar/blocked/{blockedDate}  # Hapus blokir tanggal
GET  /owner/calendar/{package}/export         # Export iCal
```

### Customer Routes
```
GET  /customer/calendar/booking/{package}                # Kalender booking
GET  /customer/calendar/booking/{package}/export         # Export booking iCal
GET  /customer/calendar/booking/{package}/data           # Get event data (AJAX)
GET  /customer/calendar/confirmation                     # Kalender konfirmasi
GET  /customer/calendar/confirmation/export              # Export konfirmasi iCal
GET  /customer/calendar/event/{event}                    # Detail acara
POST /customer/calendar/event/{event}/confirm            # Konfirmasi acara
```

## 🔄 Workflow

### 1. Owner - Kelola Tanggal Blokir

```
Owner Login
├── Navigate ke /owner/calendar
├── Pilih Paket
├── Lihat Heatmap Ketersediaan
├── Klik "Blokir Tanggal"
├── Isi Form:
│   ├── Tanggal Mulai
│   ├── Tanggal Akhir
│   ├── Jenis (unavailable/maintenance/reserved/personal)
│   └── Alasan (opsional)
├── Submit
└── Lihat update di kalender dan heatmap
```

### 2. Customer - Booking dengan Kalender

```
Customer Login
├── Browse Paket
├── Klik "Lihat Ketersediaan"
├── View Kalender Booking
├── Lihat Tanggal Hijau (tersedia)
├── Pilih Tanggal
├── Klik "Pesan"
└── Proses Booking
```

### 3. Customer - Konfirmasi Acara

```
Customer Login
├── Navigate ke /customer/calendar/confirmation
├── Lihat Daftar Acara
├── Klik Acara
├── Lihat Timeline Acara
├── Klik "Konfirmasi Acara"
├── Export ke iCal jika diperlukan
└── Bagikan dengan vendor
```

### 4. Export iCal

```
Owner/Customer
├── Buka Kalender
├── Klik "Export iCal"
├── Pilih Tipe (all/events/blocked)
└── File .ics didownload
    └── Buka di: Google Calendar, Outlook, Apple Calendar, dll
```

## 💻 API Documentation

### Get Calendar Data (AJAX)

**Endpoint:** `GET /customer/calendar/booking/{package}/data`

**Parameters:**
- `month` (required): Bulan (1-12)
- `year` (required): Tahun

**Response:**
```json
{
    "success": true,
    "blockedDates": ["2026-01-15", "2026-01-16"],
    "eventDates": ["2026-01-20", "2026-01-21"]
}
```

### Export iCal

**Endpoint:** `GET /owner/calendar/{package}/export`

**Parameters:**
- `type` (required): 'all', 'events', 'blocked'

**Response:** File .ics (text/calendar)

## 🎨 Warna & Status

### Status Kalender
- 🟢 **Hijau** (#10b981): Tersedia untuk booking
- 🔴 **Merah** (#ef4444): Terblokir/Tidak tersedia
- 🟡 **Kuning** (#f59e0b): Sedang dipesan (ada acara)
- ⚪ **Abu-abu** (#d1d5db): Hari yang lalu

### Jenis Blokir
- **Unavailable** (Tidak Tersedia): Paket tidak bisa dipesan
- **Maintenance** (Maintenance): Sedang dalam perbaikan
- **Reserved** (Dipesan): Sudah dipesan/dipegang
- **Personal** (Personal): Alasan personal pemilik

## 🔐 Keamanan

### Authorization
- Owner hanya bisa kelola paket mereka sendiri
- Customer hanya bisa lihat acara mereka sendiri
- Admin bisa kelola semua

### Validasi
- Tanggal mulai <= tanggal akhir
- Tidak bisa blokir tanggal di masa lalu
- Duplikasi blokir dicegah
- CSRF token untuk form

## 📊 Database Queries

### Cek Tanggal Terblokir
```php
BlockedDate::isDateBlocked($packageId, $date);
```

### Cek Overlapping Tanggal
```php
BlockedDate::hasOverlapInRange($packageId, $startDate, $endDate);
```

### Get Tanggal Terblokir dalam Range
```php
BlockedDate::getBlockedDatesInRange($packageId, $startDate, $endDate);
```

### Auto-Create Calendar Event dari Order
```php
CalendarEvent::createFromOrder($order);
```

### Calculate Event Days
```php
$event->calculateEventDays(); // Return: int
```

### Get Occupied Dates
```php
$event->getOccupiedDates(); // Return: array of date strings
```

## 🧪 Testing Checklist

- [ ] Owner bisa blokir tanggal
- [ ] Owner bisa edit blokir tanggal
- [ ] Owner bisa hapus blokir tanggal
- [ ] Heatmap update real-time
- [ ] Customer lihat tanggal terblokir
- [ ] Customer tidak bisa booking tanggal terblokir
- [ ] Customer lihat next available dates
- [ ] Auto-calculate event days bekerja
- [ ] Calendar event created on order confirmed
- [ ] Event confirmation works
- [ ] iCal export valid format
- [ ] iCal bisa di-import ke Google Calendar
- [ ] iCal bisa di-import ke Outlook
- [ ] Permission checks bekerja
- [ ] Pagination kalender smooth
- [ ] Mobile responsive
- [ ] Dark mode support

## 🚀 Performance Tips

1. **Index Database**: Sudah ada composite index untuk query cepat
2. **Caching**: Gunakan Redis untuk cached calendar data
3. **Pagination**: Kalender load per bulan saja
4. **AJAX Loading**: Heatmap di-load via AJAX
5. **Optimization**: Minimize CSS/JS files

## 📝 Notes

- Zona waktu default: Asia/Jakarta
- Format tanggal: YYYY-MM-DD (ISO 8601)
- iCal format: RFC 5545 compliant
- Soft delete enabled untuk data recovery

## 🆘 Troubleshooting

### Tanggal tidak muncul di kalender
- Check: `blocked_dates.is_active = true`
- Check: Tanggal dalam range `start_date` - `end_date`
- Check: `package_id` match

### iCal tidak bisa import
- Ensure: Format RFC 5545 compliant
- Check: Timezone TZID valid
- Try: Re-export dengan type 'all'

### Event days calculation salah
- Check: `pre_event_days` dan `post_event_days` di orders table
- Ensure: `event_date` is set correctly
- Re-create event: `CalendarEvent::createFromOrder()`

## 📞 Support

Untuk bantuan lebih lanjut, hubungi tim development atau check git logs untuk historical changes.

---

**Version:** 1.0.0  
**Last Updated:** 5 Januari 2026  
**Author:** Wedding App Development Team
