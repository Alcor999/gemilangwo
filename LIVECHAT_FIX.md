## Live Chat Auto-Refresh Fix

**Masalah yang diperbaiki:**
- ❌ Auto refresh setiap 3 detik mengganggu pengetikan
- ❌ `location.reload()` membuat halaman penuh refresh
- ❌ Tidak ada deteksi apakah user sedang fokus mengetik

**Solusi yang diimplementasikan:**

### 1. **Smart Polling System**
- Polling berhenti ketika user fokus pada input (sedang mengetik)
- Polling resume 1 detik setelah user blur dari input
- Frekuensi polling berkurang dari **3 detik → 5 detik** untuk reduce server load

### 2. **AJAX Message Loading (No Page Reload)**
- Alih-alih `location.reload()`, pesan baru ditambahkan ke DOM secara dinamis
- Chat history tetap intact, hanya pesan baru yang ditambahkan
- Smooth scrolling ke pesan terbaru

### 3. **Message ID Tracking**
- Sistem melacak `last_message_id` untuk menghindari duplikasi
- Query parameter `?last_message_id=` dikirim ke server

### 4. **XSS Protection**
- `escapeHtml()` function untuk prevent XSS attacks
- Semua user input di-escape sebelum ditampilkan

**File yang diubah:**
- ✅ `/resources/views/customer/support/tickets/show.blade.php`
- ✅ `/resources/views/admin/support/tickets/show.blade.php`

**Cara kerja:**
1. User membuka chat live chat
2. JavaScript mulai polling setiap 5 detik
3. Saat user klik input → polling STOP (user bisa mengetik dengan tenang)
4. Saat user blur dari input → polling resume 1 detik kemudian
5. Pesan baru datang → ditambahkan ke chat tanpa page reload
6. Chat auto-scroll ke pesan terbaru

**Benefit:**
✅ User experience lebih baik (tidak terganggu saat mengetik)
✅ Server load berkurang (polling interval lebih panjang)
✅ Chat lebih responsive (AJAX bukan full page reload)
✅ Pesan tidak hilang saat typing
