<!DOCTYPE html><html><head><meta charset="UTF-8"><style>body { font-family: Arial, sans-serif; color: #333; }.container { max-width: 600px; margin: 0 auto; padding: 20px; }.header { background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%); color: white; padding: 30px; text-align: center; border-radius: 8px; margin-bottom: 20px; }.content { padding: 20px; background: #f9f9f9; border-radius: 8px; }.info-box { background: white; padding: 15px; border-left: 4px solid #b8860b; margin: 20px 0; }table { width: 100%; border-collapse: collapse; }table td { padding: 8px; border-bottom: 1px solid #eee; }table td:first-child { font-weight: bold; width: 30%; }.button { display: inline-block; background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }.divider { border: none; border-top: 1px solid #ddd; margin: 20px 0; }.footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; }ul { margin-left: 20px; margin-bottom: 20px; }</style></head><body><div class="container"><div class="header">
    <div class="header">
        <h1>Aktivitas Baru di Gemilang WO</h1>
        <p>Perlu perhatian</p>
    </div>

    <div class="content">
        <p>Halo Admin,</p>

        @if($type === 'new_order')
            <p>Ada pesanan baru yang masuk di Gemilang WO!</p>

            <div class="info-box">
                <strong>Detail Pesanan Baru:</strong>
                <table>
                    <tr>
                        <td>ID Pesanan:</td>
                        <td><strong>#{{ $data['order_id'] }}</strong></td>
                    </tr>
                    <tr>
                        <td>Pelanggan:</td>
                        <td>{{ $data['customer_name'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>{{ $data['customer_email'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Telepon:</td>
                        <td>{{ $data['customer_phone'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Paket:</td>
                        <td>{{ $data['package_name'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Total Pesanan:</td>
                        <td><strong>Rp {{ number_format($data['total_price'] ?? 0, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td>Tanggal Acara:</td>
                        <td>{{ $data['event_date'] ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <p>Silakan tinjau dan konfirmasi pesanan ini secepatnya.</p>

        @elseif($type === 'new_review')
            <p>Ada ulasan baru yang menunggu moderasi.</p>

            <div class="info-box">
                <strong>Detail Ulasan:</strong>
                <table>
                    <tr>
                        <td>ID Ulasan:</td>
                        <td><strong>#{{ $data['review_id'] }}</strong></td>
                    </tr>
                    <tr>
                        <td>Pelanggan:</td>
                        <td>{{ $data['customer_name'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Paket:</td>
                        <td>{{ $data['package_name'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Rating:</td>
                        <td>{{ $data['rating'] ?? '-' }}/5 ⭐</td>
                    </tr>
                    <tr>
                        <td>Judul:</td>
                        <td>{{ $data['title'] ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <p>Silakan moderasi ulasan ini agar sesuai dengan pedoman komunitas.</p>

        @elseif($type === 'payment_received')
            <p>Ada pembayaran yang diterima untuk sebuah pesanan.</p>

            <div class="info-box">
                <strong>Detail Pembayaran:</strong>
                <table>
                    <tr>
                        <td>ID Pembayaran:</td>
                        <td><strong>#{{ $data['payment_id'] }}</strong></td>
                    </tr>
                    <tr>
                        <td>ID Pesanan:</td>
                        <td>#{{ $data['order_id'] }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah:</td>
                        <td><strong>Rp {{ number_format($data['amount'] ?? 0, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td>Metode Pembayaran:</td>
                        <td>{{ ucfirst($data['payment_method'] ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td>Pelanggan:</td>
                        <td>{{ $data['customer_name'] ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <p>Silakan verifikasi dan konfirmasi pembayaran ini.</p>
        @endif

        <p style="text-align: center;">
            <a href="{{ config('app.url') }}/admin/dashboard" class="button">
                Buka Dasbor Admin
            </a>
        </p>

        <hr class="divider">

        <p>Ini adalah notifikasi otomatis dari sistem Gemilang WO.</p>

        <p style="margin-top: 30px;">
            Sistem Admin Gemilang WO
        </p>
    </div>

    <div class="footer">
        <p>© 2026 Gemilang WO. Hak cipta dilindungi undang-undang.</p>
    </div>
</div></div></body></html>
