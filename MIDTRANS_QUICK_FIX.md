# 🔧 Solusi: Midtrans VA Invalid - Quick Fix Guide

## ✅ Masalah Sudah Diperbaiki!

Sebelumnya, nomor Virtual Account (VA) yang di-generate oleh sistem tidak valid ketika dimasukkan ke Midtrans Simulator. Masalah ini telah diperbaiki dengan beberapa perubahan kunci:

### ✨ Perubahan Utama:

1. **Format Order Number** - Dipendekkan agar compatible dengan VA
2. **Sandbox Credentials** - Diupdate dengan key yang valid
3. **Bank Transfer Config** - Dikonfigurasi dengan benar di Midtrans SDK
4. **Database Enhancement** - Ditambah kolom untuk menyimpan VA details

## 🚀 Cara Menggunakan Sekarang:

### Step 1️⃣: Testing di Browser
```
1. Login sebagai customer
2. Browse Packages → Pilih paket
3. Create Order → Isi form
4. Go to Payment Page
```

### Step 2️⃣: Pilih Bank Transfer
Di halaman pembayaran:
- Klik "Bank Transfer"
- Pilih bank (BNI, BCA, Mandiri, etc)
- Sistem akan generate nomor VA unik

### Step 3️⃣: Test di Simulator
```
1. Catat nomor VA (contoh: 9884423314107408)
2. Buka: https://simulator.sandbox.midtrans.com/
3. Pilih bank yang sama dengan langkah 2
4. Paste nomor VA (TANPA SPASI!)
5. Isi amount sesuai order
6. Click "BAYAR"
```

✅ **Hasilnya**: Payment akan terdeteksi sebagai valid!

## 📊 Contoh Hasil Setelah Fix

**Sebelum (Error):**
```
Virtual account number not found / incorrect ❌
```

**Setelah (Valid):**
```
Payment Status: Pending ✓
Bank: BNI
VA Number: 9884423314107408
Amount: Rp 100,000,000
Status: Awaiting Payment
```

## ⚠️ Tips Penting

| ✓ DO | ✗ DON'T |
|------|---------|
| Copy VA tanpa spasi | Copy dengan separator (988-844-233...) |
| Gunakan bank yang sama | Ganti bank antara order & simulator |
| Tunggu VA fully generated | Langsung test sebelum ready |
| Cocokkan amount exact | Kurang/lebih dari amount order |

## 🔍 Troubleshooting

### ❓ "Virtual account number not found"
- [ ] Pastikan format nomor VA benar (no spaces/separators)
- [ ] Pastikan bank di simulator = bank di order
- [ ] Pastikan amount tepat sesuai order

### ❓ "Payment not detected in system"
- Check notification webhook di Midtrans Dashboard
- Pastikan MIDTRANS_IS_PRODUCTION=false di .env
- Check logs: `tail -f storage/logs/laravel.log`

### ❓ "Snap payment page blank"
- Clear browser cache
- Check console (F12) untuk error
- Verify client key ada di script tag

## 📝 Technical Changes (untuk Developer)

### Files Diubah:
1. `app/Http/Controllers/Customer/OrderController.php` - Order number format
2. `app/Services/MidtransService.php` - Bank transfer config & VA handling
3. `.env` - Updated credentials
4. `config/midtrans.php` - Merchant ID & snap URL
5. `database/migrations/2026_01_27_add_va_fields_to_payments_table.php` - New columns

### Database:
```sql
ALTER TABLE payments ADD COLUMN va_number VARCHAR(255) NULL;
ALTER TABLE payments ADD COLUMN bank VARCHAR(50) NULL;
```

## 🧪 Test Credentials

**Environment**: Sandbox (Testing)
```
Server Key: SB-Mid-server-W87r8nj8kBZnXGOqrA7dTvXM
Client Key: SB-Mid-client-pFCvnqxAk1nBB3yJ
Merchant ID: G141532679
```

## 📚 Dokumentasi Lengkap
Lihat: [MIDTRANS_VA_FIX.md](MIDTRANS_VA_FIX.md) untuk detail teknis lengkap

## ✅ Checklist Sebelum Production

- [ ] Update credentials ke production keys
- [ ] Change MIDTRANS_IS_PRODUCTION=true
- [ ] Setup webhook URL di dashboard
- [ ] Test dengan amount kecil dulu
- [ ] Monitor logs untuk errors
- [ ] Backup database

---

**Status**: ✅ Fixed & Tested  
**Last Updated**: 27 Jan 2026
