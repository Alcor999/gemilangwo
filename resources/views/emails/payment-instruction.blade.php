<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 0 0 8px 8px; }
        .account-box { background: white; padding: 15px; border-left: 4px solid #b8860b; margin: 15px 0; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 20px; }
        .button { display: inline-block; background: #b8860b; color: white; padding: 12px 24px; border-radius: 4px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Instruksi Pembayaran</h2>
            <p>Order: {{ $order->order_number }}</p>
        </div>
        
        <div class="content">
            <p>Halo {{ $order->user->name }},</p>
            
            <p>Terima kasih telah melakukan pemesanan paket pernikahan kami. Untuk menyelesaikan pesanan Anda, silakan lakukan pembayaran ke rekening berikut:</p>
            
            <div class="account-box">
                <strong>{{ $bank->name }}</strong><br>
                No. Rekening: <strong>{{ $bank->account_number }}</strong><br>
                Atas Nama: <strong>{{ $bank->account_holder }}</strong><br>
                <br>
                <strong style="color: #b8860b; font-size: 18px;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                @if ($bank->instruction)
                    <br><br>
                    <em>{{ $bank->instruction }}</em>
                @endif
            </div>
            
            <h4>Detail Pesanan</h4>
            <ul>
                <li><strong>Paket:</strong> {{ $order->package->name }}</li>
                <li><strong>Tanggal Event:</strong> {{ $order->event_date->format('d M Y') }}</li>
                <li><strong>Lokasi:</strong> {{ $order->event_location }}</li>
                <li><strong>Jumlah Tamu:</strong> {{ $order->guest_count }} orang</li>
            </ul>
            
            <h4>Instruksi Pembayaran</h4>
            <ol>
                <li>Transfer jumlah yang tertera ke rekening di atas</li>
                <li>Sertakan nomor referensi <strong>{{ $order->order_number }}</strong> di catatan transfer jika memungkinkan</li>
                <li>Hubungi kami via WhatsApp untuk konfirmasi pembayaran</li>
                <li>Tunggu verifikasi dari tim kami (1-2 jam kerja)</li>
            </ol>
            
            <p style="text-align: center; margin-top: 30px;">
                <a href="{{ config('app.url') }}/customer/orders/{{ $order->id }}/payment-confirm" class="button">
                    Lihat Detail Pembayaran
                </a>
            </p>
            
            <p style="color: #666; font-size: 14px; margin-top: 20px;">
                Jika ada pertanyaan, hubungi kami via WhatsApp atau email support.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Gemilang WO. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
