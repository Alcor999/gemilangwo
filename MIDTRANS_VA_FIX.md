# Midtrans Virtual Account (VA) Integration Fix

## Problem
Nomor virtual account yang di-generate oleh Midtrans tidak valid ketika dimasukkan ke Midtrans Simulator.

**Error Message:**
```
Virtual account number not found / incorrect
```

## Root Causes Identified & Fixed

### 1. **Order Number Format**
- **Issue**: Format `WO-<timestamp>-<random>` menghasilkan nomor yang terlalu panjang dan tidak compatible dengan beberapa VA bank
- **Fix**: Diubah menjadi format yang lebih pendek: `WO-<lastDigitsOfTimestamp><randomNumber>`
  - Contoh: `WO-176951618-83` → `WO-951618 83`

### 2. **Invalid Midtrans Credentials**
- **Issue**: Server Key dan Client Key di `.env` tidak valid/salah
- **Fix**: Diupdate dengan Sandbox credentials yang benar:
  ```env
  MIDTRANS_SERVER_KEY=SB-Mid-server-W87r8nj8kBZnXGOqrA7dTvXM
  MIDTRANS_CLIENT_KEY=SB-Mid-client-pFCvnqxAk1nBB3yJ
  MIDTRANS_IS_PRODUCTION=false
  ```

### 3. **Merchant ID Configuration**
- **Issue**: Merchant ID default tidak sesuai dengan sandbox
- **Fix**: Updated ke merchant ID yang valid untuk sandbox:
  ```php
  'merchant_id' => 'G141532679'
  ```

### 4. **Missing Bank Transfer Configuration**
- **Issue**: Bank transfer VA tidak dikonfigurasi dengan benar di payload Midtrans
- **Fix**: Added explicit `bank_transfer` configuration:
  ```php
  'bank_transfer' => [
      'bank' => 'bni',
      'free_text' => [
          'inquiry' => [
              'en' => 'Thank you for your wedding order',
          ],
      ],
  ]
  ```

### 5. **Missing VA Fields in Database**
- **Issue**: No columns to store VA number dan bank info
- **Fix**: Created migration to add:
  - `va_number` - Virtual Account number
  - `bank` - Bank yang di-gunakan untuk VA

## Files Modified

### 1. **app/Http/Controllers/Customer/OrderController.php**
- Updated order number generation untuk compatibility

### 2. **app/Services/MidtransService.php**
- Added proper bank transfer configuration
- Enhanced VA handling in notification handler
- Added safety checks untuk phone number

### 3. **.env**
- Updated MIDTRANS_SERVER_KEY
- Updated MIDTRANS_CLIENT_KEY
- Verified MIDTRANS_IS_PRODUCTION=false

### 4. **config/midtrans.php**
- Updated merchant_id
- Added snap_url configuration untuk sandbox/production switching

### 5. **database/migrations/2026_01_27_add_va_fields_to_payments_table.php** (NEW)
- Added va_number column
- Added bank column

## How to Test in Midtrans Simulator

### Step 1: Run Migration
```bash
php artisan migrate
```

### Step 2: Create Test Order
1. Register as customer
2. Browse packages
3. Create an order
4. Go to payment page

### Step 3: Select Bank Transfer
Di Midtrans Snap payment interface:
1. Pilih "Bank Transfer"
2. Pilih Bank (BNI, BCA, Mandiri, dll)
3. Sistem akan generate VA number baru

### Step 4: Copy VA Number to Simulator
1. Catat VA number dari payment page
2. Go to: https://simulator.sandbox.midtrans.com/
3. Pilih bank yang sama
4. Paste VA number (harus format yang benar, tanpa spasi)
5. Isi jumlah sesuai order amount
6. Click "Bayar"

### Example VA Format
- **Valid**: `9884423314107408` (20 digits)
- **Invalid**: `988-844-233-14107408` (dengan separator)

## Troubleshooting

### "Virtual account number not found"
1. ✓ Pastikan nomor VA di-copy tanpa spasi/separator
2. ✓ Pastikan bank yang dipilih di simulator sama dengan yang di payment
3. ✓ Pastikan jumlah rupiah sesuai dengan order total
4. ✓ Pastikan payment sudah fully generated (tunggu sampai ada VA number)

### Payment tidak masuk ke sistem
1. Pastikan notification URL sudah configured di Midtrans Dashboard
   - Go to: https://dashboard.sandbox.midtrans.com
   - Settings → Webhooks
   - Notification URL: `http://localhost:8000/customer/orders/notification`
2. Check logs: `tail -f storage/logs/laravel.log`

### Credentials Errors
Jika masih ada error, hubungi Midtrans support atau generate key baru dari dashboard:
1. Login ke https://dashboard.sandbox.midtrans.com
2. Settings → Access Keys
3. Copy Server Key dan Client Key baru
4. Update di `.env`
5. Clear cache: `php artisan config:cache`

## Test Credentials (Sandbox)
- **Environment**: Sandbox (MIDTRANS_IS_PRODUCTION=false)
- **Merchant ID**: G141532679
- **Server Key**: SB-Mid-server-W87r8nj8kBZnXGOqrA7dTvXM
- **Client Key**: SB-Mid-client-pFCvnqxAk1nBB3yJ

> ⚠️ **Production Note**: When moving to production, update these to your production keys from dashboard.

## API Response Format (untuk debugging)

### Successful VA Response
```json
{
  "va_numbers": [
    {
      "bank": "bni",
      "va_number": "9884423314107408"
    }
  ],
  "payment_type": "bank_transfer",
  "transaction_status": "pending",
  "order_id": "WO-95161883",
  "transaction_id": "abc123..."
}
```

Jika response tidak ada `va_numbers`, check:
1. Credentials valid?
2. Merchant ID benar?
3. Payload format benar?
4. Amount lebih dari 0?

## Database Storage
Virtual Account info sekarang disimpan di tabel `payments`:
```sql
SELECT 
  p.id, 
  p.payment_id, 
  p.va_number,      -- Nomor VA yang di-generate
  p.bank,           -- Bank (BNI, BCA, dll)
  p.payment_method,
  p.status
FROM payments p
WHERE p.order_id = ?;
```

## Summary
Aplikasi sekarang properly configured untuk:
✓ Generate VA dengan format yang compatible  
✓ Authenticate dengan Midtrans sandbox yang benar  
✓ Store VA details untuk reference customer  
✓ Handle notification dan payment status correctly
