<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 0 0 8px 8px; }
        .error-box { background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 15px 0; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 20px; }
        .button { display: inline-block; background: #b8860b; color: white; padding: 12px 24px; border-radius: 4px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Pembayaran Ditolak</h2>
            <p>Order: {{ $order->order_number }}</p>
        </div>
        
        <div class="content">
            <p>Halo {{ $order->user->name }},</p>
            
            <div class="error-box">
                <strong>✗ Pembayaran Anda tidak dapat diverifikasi</strong><br>
                Kami tidak dapat memverifikasi pembayaran Anda. Silakan cek informasi di bawah dan hubungi kami.
            </div>
            
            @if ($reason)
                <h4>Alasan Penolakan</h4>
                <p style="background: white; padding: 15px; border-left: 4px solid #dc3545;">
                    {{ $reason }}
                </p>
            @endif
            
            <h4>Detail Pesanan</h4>
            <ul>
                <li><strong>Order Number:</strong> {{ $order->order_number }}</li>
                <li><strong>Paket:</strong> {{ $order->package->name }}</li>
                <li><strong>Jumlah yang Seharusnya Ditransfer:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</li>
            </ul>
            
            <h4>Apa yang Harus Dilakukan?</h4>
            <ol>
                <li>Periksa nominal pembayaran yang telah Anda transfer</li>
                <li>Pastikan transfer dilakukan ke rekening yang benar</li>
                <li>Hubungi kami via WhatsApp untuk bantuan lebih lanjut</li>
                <li>Anda dapat mengulang pembayaran atau meminta verifikasi ulang</li>
            </ol>
            
            <p style="text-align: center; margin-top: 30px;">
                <a href="{{ config('app.url') }}/customer/orders/{{ $order->id }}" class="button">
                    Lihat Order Detail
                </a>
            </p>
            
            <p style="color: #666; font-size: 14px; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 20px;">
                Jika Anda memiliki pertanyaan atau mengalami masalah dengan pembayaran, <br>
                silakan hubungi tim support kami melalui WhatsApp atau email.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Gemilang WO. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
