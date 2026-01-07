# Advanced Booking Calendar - Quick Reference Guide

## 🚀 Quick Start

### Untuk Owner (Vendor)

1. **Akses Kalender**
   - Login → Dashboard → Kelola Kalender
   - URL: `/owner/calendar`

2. **Blokir Tanggal**
   - Klik "Blokir Tanggal"
   - Isi tanggal mulai & akhir
   - Pilih jenis blokir
   - Klik "Blokir Tanggal"

3. **Lihat Heatmap**
   - Hijau = Tersedia
   - Merah = Terblokir
   - Kuning = Ada Acara

4. **Edit/Hapus Blokir**
   - Di sidebar "Tanggal Terblokir"
   - Klik edit/hapus

5. **Export Kalender**
   - Klik "Export" → Pilih tipe
   - File .ics akan didownload

### Untuk Customer (Calon Pengantin)

1. **Lihat Ketersediaan**
   - Browse Paket → Klik "Lihat Ketersediaan"
   - URL: `/customer/calendar/booking/{package}`

2. **Pilih Tanggal**
   - Klik tanggal hijau (tersedia)
   - Atau lihat "Tanggal Tersedia Berikutnya"

3. **Booking**
   - Dari kalender klik "Pesan Sekarang"
   - Isi detail pemesanan

4. **Konfirmasi Acara**
   - Login → Kalender Konfirmasi
   - URL: `/customer/calendar/confirmation`
   - Klik acara → "Konfirmasi Acara"

5. **Export Kalender**
   - Klik "Export iCal"
   - Bagikan dengan vendor/keluarga

## 📱 Fitur-Fitur

### Calendar Features
| Fitur | Owner | Customer |
|-------|-------|----------|
| Lihat Heatmap | ✅ | ✅ |
| Blokir Tanggal | ✅ | ❌ |
| Edit Blokir | ✅ | ❌ |
| Hapus Blokir | ✅ | ❌ |
| Lihat Acara | ✅ | ✅ |
| Konfirmasi Acara | ❌ | ✅ |
| Export iCal | ✅ | ✅ |
| Hitung Hari Acara | Auto | Auto |

### Jenis Blokir
- **Unavailable** 🚫: Paket tidak tersedia untuk booking
- **Maintenance** 🔧: Sedang dalam perbaikan/upgrade
- **Reserved** 📌: Sudah dipesan atau ditahan
- **Personal** 👤: Alasan personal pemilik

## 🎯 Common Tasks

### Task: Blokir Liburan (1-31 Des 2026)
```
1. Buka /owner/calendar
2. Klik "Blokir Tanggal"
3. Start Date: 2026-12-01
4. End Date: 2026-12-31
5. Type: personal
6. Reason: "Liburan Akhir Tahun"
7. Submit
```

### Task: Cek Ketersediaan Paket
```
1. Customer: /customer/calendar/booking/{package-id}
2. Lihat heatmap bulan yang diinginkan
3. Klik tanggal hijau untuk booking
```

### Task: Konfirmasi Acara
```
1. Login sebagai customer
2. Ke /customer/calendar/confirmation
3. Klik acara yang ingin dikonfirmasi
4. Klik "Konfirmasi Acara"
5. Check ✓ untuk dikonfirmasi
```

### Task: Export ke Google Calendar
```
1. Buka kalender (owner atau customer)
2. Klik "Export iCal"
3. File .ics akan didownload
4. Buka Google Calendar → Import → Select file
5. Acara akan muncul di kalender
```

## 🔄 Status & Warna

### Kalender
```
🟢 Hijau   = Tersedia (bisa booking)
🔴 Merah   = Terblokir (tidak bisa booking)
🟡 Kuning  = Ada Acara (sudah dipesan)
⚪ Abu-abu = Hari Lalu (tidak aktif)
```

### Event Status
```
⏳ Belum Dikonfirmasi = Pending (belum final)
✅ Dikonfirmasi       = Confirmed (sudah final)
🔄 Sedang Berlangsung = In Progress (hari H)
✓ Selesai             = Completed (sudah selesai)
```

## 📊 Useful Information

### Auto-Calculate
- **Pre-event Days**: Hari sebelum acara untuk setup
- **Post-event Days**: Hari sesudah untuk cleanup
- **Total Event Days**: Pre + Main + Post

Contoh:
- Event Date: 20 Jan
- Pre-event: 2 hari → 18-19 Jan
- Event Day: 1 hari → 20 Jan  
- Post-event: 1 hari → 21 Jan
- **Total: 4 hari**

### Next Available Dates
- Sistem otomatis tampilkan 5 tanggal tersedia berikutnya
- Hanya tanggal yang tidak terblokir dan masa depan
- Update real-time setiap kali ada perubahan

## 🛠️ Technical Details

### Database Tables
- `blocked_dates`: Menyimpan tanggal blokir
- `calendar_events`: Menyimpan event acara
- `orders`: Updated dengan pre_event_days, post_event_days

### API Endpoints
- `GET /customer/calendar/booking/{package}/data`: Get calendar data
- `GET /owner/calendar/data/{package}`: Get heatmap data
- `POST /customer/calendar/event/{event}/confirm`: Confirm event

### File Format (iCal)
- Format: RFC 5545 (Standard)
- Extension: .ics
- Compatible: Google Calendar, Outlook, Apple Calendar, dll

## 💡 Tips & Tricks

1. **Bulk Block Dates**: Blokir satu range besar, bukan per hari
2. **Reason for Blocked**: Selalu isi alasan untuk reference
3. **Pre/Post Event Days**: Set realistically untuk preparation
4. **Export Regularly**: Export kalender untuk backup
5. **Check Overlaps**: Sistem otomatis cegah overlapping
6. **Mobile Friendly**: Kalender responsive di mobile
7. **Dark Mode**: Auto-detect system theme preference

## ⚠️ Important Notes

- ❌ Tidak bisa blokir tanggal yang sudah lewat
- ❌ Tidak bisa edit tanggal order yang sudah confirmed
- ✅ Tanggal terblokir otomatis tidak tersedia untuk booking
- ✅ iCal file bisa di-sync across multiple devices
- ✅ Soft delete enabled untuk recovery data

## 🆘 Need Help?

### Common Issues & Solutions

**Masalah: Tanggal tidak muncul di kalender**
- Solusi: Refresh halaman atau clear browser cache

**Masalah: iCal tidak bisa di-import**
- Solusi: Download ulang file, atau coba dengan type 'all'

**Masalah: Event days calculated salah**
- Solusi: Check pre_event_days & post_event_days di order

**Masalah: Konfirmasi acara tidak bisa**
- Solusi: Pastikan order sudah 'confirmed' status

## 📞 Contact

Untuk bantuan lebih lanjut, hubungi tim support atau baca dokumentasi lengkap di `CALENDAR_FEATURE_DOCUMENTATION.md`.

---

**Version:** 1.0.0  
**Updated:** 5 Januari 2026
