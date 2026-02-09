<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pesanan</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%); color: white; padding: 30px; text-align: center; border-radius: 8px; margin-bottom: 20px; }
        .content { padding: 20px; background: #f9f9f9; border-radius: 8px; }
        .info-box { background: white; padding: 15px; border-left: 4px solid #b8860b; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 8px; border-bottom: 1px solid #eee; }
        table td:first-child { font-weight: bold; width: 30%; }
        .button { display: inline-block; background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .divider { border: none; border-top: 1px solid #ddd; margin: 20px 0; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; }
        .warning-badge { background: #fbbf24; color: #78350f; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        ul { margin-left: 20px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Konfirmasi Pesanan</h1>
            <p>Pesanan paket pernikahan Anda sudah kami terima!</p>
        </div>

        <div class="content">
            <p>Halo {{ $customer->name }},</p>

            <p>Terima kasih telah memilih kami! Pesanan Anda berhasil dibuat dan sedang diproses.</p>

            <div class="info-box">
                <strong>Detail Pesanan:</strong>
                <table>
                    <tr>
                        <td>ID Pesanan:</td>
                        <td><strong>#{{ $order->id }}</strong></td>
                    </tr>
                    <tr>
                        <td>Paket:</td>
                        <td>{{ $package->name }}</td>
                    </tr>
                    <tr>
                        <td>Harga Paket:</td>
                        <td>Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total:</td>
                        <td><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td>Tanggal Acara:</td>
                        <td>{{ \Carbon\Carbon::parse($order->event_date)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Status:</td>
                        <td>
                            @php
                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'confirmed' => 'Dikonfirmasi',
                                    'in_progress' => 'Sedang Berlangsung',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ];
                            @endphp
                            <span class="warning-badge">{{ $statusLabels[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                        </td>
                    </tr>
                </table>
            </div>

            <p>Tim kami sedang meninjau pesanan Anda dan akan segera menghubungi Anda untuk mengonfirmasi detail serta mendiskusikan kebutuhan khusus.</p>

            <p style="text-align: center;">
                <a href="{{ route('customer.orders.show', $order) }}" class="button">
                    Lihat Detail Pesanan
                </a>
            </p>

            <hr class="divider">

            <h3 style="margin-bottom: 10px;">Apa Selanjutnya?</h3>
            <ul>
                <li>Kami akan meninjau pesanan Anda dalam 24 jam</li>
                <li>Anda akan menerima email konfirmasi dari tim kami</li>
                <li>Kami akan menghubungi Anda untuk membahas detail akhir</li>
                <li>Setelah dikonfirmasi, Anda dapat melanjutkan pembayaran</li>
            </ul>

            <p>Jika ada pertanyaan, jangan ragu untuk menghubungi kami.</p>

            <p style="margin-top: 30px;">
                Salam hangat,<br>
                <strong>Tim Gemilang WO</strong>
            </p>
        </div>

        <div class="footer">
            <p>© 2026 Gemilang WO. Hak cipta dilindungi undang-undang.</p>
            <p>Ini adalah email otomatis. Mohon jangan membalas langsung pesan ini.</p>
        </div>
    </div>
</body>
</html>
