# Insight: Fitur Vendor Wedding Organizer

## Konsep Utama

Customer memilih paket wedding → Paket memiliki kategori vendor wajib (Catering, Dekorasi, dll) → Customer memilih vendor spesifik untuk setiap kategori → Total biaya = harga paket dasar + total harga vendor terpilih.

## Arsitektur Data

### 1. Vendor Category (Kategori Vendor)
Contoh: Catering, Dekorasi, Fotografi & Videografi, Musik/DJ, MC, Sound System, Transportasi, Hair & Make Up.

- **Fungsi**: Mengelompokkan vendor sejenis
- **Satu paket** bisa membutuhkan **beberapa kategori** (misal: Gold butuh Catering + Dekorasi + Fotografi)

### 2. Vendor (Penyedia Jasa)
- Terikat ke **satu kategori**
- Memiliki **harga** sendiri
- Admin mengelola: nama, deskripsi, harga, gambar

### 3. Package-Vendor Relationship
- **package_vendor_category**: Paket mana butuh kategori vendor apa
- Contoh: Paket Gold butuh [Catering, Dekorasi, Fotografi] → Customer wajib pilih 1 vendor per kategori

### 4. Order-Vendor (Pilihan Customer)
- **order_vendors**: Menyimpan pilihan vendor customer per order
- Menyimpan **harga snapshot** (harga saat order) agar perubahan harga vendor tidak mempengaruhi order lama

## Model Harga

```
Total Order = Harga Dasar Paket + Σ(Harga Vendor Terpilih)
```

- **Harga Dasar Paket**: Tetap (item yang tidak butuh vendor: koordinasi, fee admin, dll)
- **Diskon**: Diterapkan pada **total** (dasar + vendor) sebelum order dibuat
- **Backward Compatible**: Paket tanpa vendor category → total = harga paket saja (seperti sekarang)

## Alur Customer

1. **Pilih Paket** → Lihat paket + daftar kategori vendor yang wajib dipilih
2. **Pilih Vendor** → Untuk setiap kategori, pilih 1 vendor dari daftar
3. **Review Total** → Lihat breakdown: dasar paket + vendor A + vendor B + ... = total
4. **Isi Data Acara** → Tanggal, lokasi, jumlah tamu, dll
5. **Buat Order** → Order tersimpan dengan total harga & pilihan vendor

## Alur Admin

1. **Kelola Kategori Vendor** → CRUD: Catering, Dekorasi, Fotografi, dll
2. **Kelola Vendor** → CRUD per kategori: nama, deskripsi, harga
3. **Assign ke Paket** → Untuk tiap paket, tentukan kategori vendor mana yang wajib

## File & Tabel Baru

| Item | Deskripsi |
|------|-----------|
| `vendor_categories` | id, name, slug, description, icon, sort_order, is_active |
| `vendors` | id, vendor_category_id, name, description, price, image, contact_phone, contact_email, is_active |
| `package_vendor_category` | package_id, vendor_category_id |
| `order_vendors` | order_id, vendor_id, vendor_category_id, vendor_name, vendor_category_name, price (snapshot) |

## Implementasi Selesai

- **Admin**: Kelola kategori vendor & vendor (CRUD)
- **Admin**: Assign kategori vendor ke paket (di form create/edit package)
- **Customer**: Form pesanan multi-step dengan pilih vendor per kategori
- **Customer/Admin**: Detail order menampilkan vendor terpilih
- **Backward compatible**: Paket tanpa vendor category → total = harga paket (seperti sebelumnya)
