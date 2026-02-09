<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Diterima</title>
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
        .success-badge { background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        ul { margin-left: 20px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pembayaran Diterima</h1>
            <p>Terima kasih atas pembayaran Anda!</p>
        </div>

        <div class="content">
        <p>Halo {{ $customer->name }},</p>

        <p>Kami telah menerima pembayaran Anda untuk pesanan <strong>#{{ $order->id }}</strong>.</p>

        <div class="info-box">
            <strong>Detail Pembayaran:</strong>
            <table>
                <tr>
                    <td>ID Pembayaran:</td>
                    <td><strong>#{{ $payment->id }}</strong></td>
                </tr>
                <tr>
                    <td>ID Pesanan:</td>
                    <td>#{{ $order->id }}</td>
                </tr>
                <tr>
                    <td>Paket:</td>
                    <td>{{ $package->name }}</td>
                </tr>
                <tr>
                    <td>Jumlah Dibayar:</td>
                    <td><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td>Metode Pembayaran:</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                </tr>
                <tr>
                    <td>Tanggal Pembayaran:</td>
                    <td>{{ $payment->paid_at->format('d F Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td><span class="success-badge">Sudah Dibayar</span></td>
                </tr>
            </table>
        </div>

        <p>Pesanan Anda sudah dikonfirmasi dan tim kami sedang menyiapkan semuanya untuk acara spesial Anda. Kami akan terus memberikan pembaruan progresnya.</p>

        <p style="text-align: center;">
            <a href="{{ route('customer.orders.show', $order) }}" class="button">
                Lihat Pesanan & Pelacakan
            </a>
        </p>

        <hr class="divider">

        <h3 style="margin-bottom: 10px;">Apa Selanjutnya?</h3>
        <ul style="margin-left: 20px; margin-bottom: 20px;">
            <li>Pesanan Anda sudah dikonfirmasi dan sedang diproses</li>
            <li>Tim kami akan menghubungi Anda 3 hari sebelum acara</li>
            <li>Konfirmasi akhir akan dikirim 1 hari sebelum acara</li>
            <li>Kami antusias membantu membuat acara Anda spesial!</li>
        </ul>

        <p>Jika Anda memiliki pertanyaan terkait pesanan, silakan hubungi tim dukungan kami.</p>

        <p style="margin-top: 30px;">
            Terima kasih telah memilih kami!<br>
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
