-- =====================================================
-- QUERY SIDANG - WEDDING APP
-- Query Sederhana untuk Presentasi dan Demo
-- =====================================================

-- 1. QUERY: Menampilkan semua paket wedding beserta harganya
-- Menampilkan daftar paket yang tersedia untuk pelanggan
SELECT 
    id,
    name AS nama_paket,
    CONCAT('Rp ', FORMAT(price, 0, 'id_ID')) AS harga,
    max_guests AS kapasitas_tamu,
    status,
    created_at AS tanggal_dibuat
FROM packages
WHERE status = 'active'
ORDER BY price ASC;


-- 2. QUERY: Menghitung total pesanan per status
-- Melihat berapa banyak pesanan di setiap status
SELECT 
    status,
    COUNT(*) AS jumlah_pesanan,
    CONCAT('Rp ', FORMAT(SUM(total_price), 0, 'id_ID')) AS total_nilai_pesanan
FROM orders
GROUP BY status
ORDER BY jumlah_pesanan DESC;


-- 3. QUERY: Menampilkan pesanan terbaru beserta detail pelanggan
-- Melihat 10 pesanan terbaru dengan informasi lengkap
SELECT 
    o.order_number AS nomor_pesanan,
    u.name AS nama_pelanggan,
    u.email AS email_pelanggan,
    p.name AS nama_paket,
    o.event_date AS tanggal_acara,
    o.guest_count AS jumlah_tamu,
    CONCAT('Rp ', FORMAT(o.total_price, 0, 'id_ID')) AS total_harga,
    o.payment_status AS status_pembayaran,
    o.status AS status_pesanan,
    o.created_at AS tanggal_pemesanan
FROM orders o
JOIN users u ON o.user_id = u.id
JOIN packages p ON o.package_id = p.id
ORDER BY o.created_at DESC
LIMIT 10;


-- 4. QUERY: Menampilkan paket paling populer (paling banyak dipesan)
-- Melihat paket mana yang paling diminati pelanggan
SELECT 
    p.name AS nama_paket,
    COUNT(o.id) AS jumlah_pesanan,
    CONCAT('Rp ', FORMAT(p.price, 0, 'id_ID')) AS harga_paket,
    CONCAT('Rp ', FORMAT(SUM(o.total_price), 0, 'id_ID')) AS total_pendapatan
FROM packages p
LEFT JOIN orders o ON p.id = o.package_id
GROUP BY p.id, p.name, p.price
HAVING jumlah_pesanan > 0
ORDER BY jumlah_pesanan DESC;


-- 5. QUERY: Menampilkan review pelanggan dengan rating tinggi
-- Melihat testimonial pelanggan yang puas (rating 4-5)
SELECT 
    u.name AS nama_pelanggan,
    p.name AS nama_paket,
    r.rating AS rating,
    r.title AS judul_review,
    r.comment AS komentar,
    r.is_approved AS sudah_disetujui,
    r.created_at AS tanggal_review
FROM reviews r
JOIN users u ON r.user_id = u.id
JOIN packages p ON r.package_id = p.id
WHERE r.rating >= 4
ORDER BY r.rating DESC, r.created_at DESC
LIMIT 10;


-- 6. QUERY: Menghitung rata-rata rating per paket
-- Melihat kepuasan pelanggan untuk setiap paket
SELECT 
    p.name AS nama_paket,
    COUNT(r.id) AS jumlah_review,
    ROUND(AVG(r.rating), 2) AS rata_rata_rating,
    COUNT(CASE WHEN r.rating = 5 THEN 1 END) AS bintang_5,
    COUNT(CASE WHEN r.rating = 4 THEN 1 END) AS bintang_4,
    COUNT(CASE WHEN r.rating = 3 THEN 1 END) AS bintang_3
FROM packages p
LEFT JOIN reviews r ON p.id = r.package_id
GROUP BY p.id, p.name
HAVING jumlah_review > 0
ORDER BY rata_rata_rating DESC;


-- 7. QUERY: Menampilkan pesanan dengan sistem pembayaran cicilan
-- Melihat pelanggan yang memilih pembayaran DP atau cicilan
SELECT 
    o.order_number AS nomor_pesanan,
    u.name AS nama_pelanggan,
    p.name AS nama_paket,
    o.payment_scheme AS skema_pembayaran,
    CONCAT('Rp ', FORMAT(o.total_price, 0, 'id_ID')) AS total_harga,
    CONCAT('Rp ', FORMAT(o.total_paid, 0, 'id_ID')) AS sudah_dibayar,
    CONCAT('Rp ', FORMAT(o.remaining_amount, 0, 'id_ID')) AS sisa_pembayaran,
    o.payment_status AS status_pembayaran
FROM orders o
JOIN users u ON o.user_id = u.id
JOIN packages p ON o.package_id = p.id
WHERE o.payment_scheme != 'full_payment'
ORDER BY o.created_at DESC;


-- 8. QUERY: Statistik pelanggan dan pesanan
-- Ringkasan data pelanggan dan aktivitas mereka
SELECT 
    COUNT(DISTINCT u.id) AS total_pelanggan,
    COUNT(o.id) AS total_pesanan,
    COUNT(CASE WHEN o.status = 'completed' THEN 1 END) AS pesanan_selesai,
    COUNT(CASE WHEN o.status = 'pending' THEN 1 END) AS pesanan_pending,
    CONCAT('Rp ', FORMAT(SUM(o.total_price), 0, 'id_ID')) AS total_nilai_transaksi,
    CONCAT('Rp ', FORMAT(AVG(o.total_price), 0, 'id_ID')) AS rata_rata_nilai_pesanan
FROM users u
LEFT JOIN orders o ON u.id = o.user_id;


-- =====================================================
-- CATATAN UNTUK SIDANG:
-- =====================================================
-- Query-query di atas mencakup:
-- 1. Daftar produk/paket
-- 2. Laporan status pesanan
-- 3. Detail pesanan terbaru
-- 4. Analisis paket terpopuler
-- 5. Review dan testimonial
-- 6. Analisis kepuasan pelanggan
-- 7. Laporan sistem pembayaran
-- 8. Statistik umum aplikasi
--
-- Silakan pilih 1-2 query yang paling relevan 
-- dengan fitur yang ingin ditampilkan saat sidang
-- =====================================================
