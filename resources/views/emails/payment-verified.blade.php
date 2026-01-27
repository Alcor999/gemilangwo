<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 0 0 8px 8px; }
        .success-box { background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 15px 0; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-check-circle"></i> Pembayaran Dikonfirmasi</h2>
            <p>Order: {{ $order->order_number }}</p>
        </div>
        
        <div class="content">
            <p>Halo {{ $order->user->name }},</p>
            
            <div class="success-box">
                <strong>✓ Pembayaran Anda telah dikonfirmasi!</strong><br>
                Terima kasih telah menyelesaikan pembayaran. Pesanan Anda sekarang dalam status <strong>Confirmed</strong>.
            </div>
            
            <h4>Detail Pesanan Anda</h4>
            <ul>
                <li><strong>Order Number:</strong> {{ $order->order_number }}</li>
                <li><strong>Paket:</strong> {{ $order->package->name }}</li>
                <li><strong>Tanggal Event:</strong> {{ $order->event_date->format('d M Y') }}</li>
                <li><strong>Lokasi:</strong> {{ $order->event_location }}</li>
                <li><strong>Total Pembayaran:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</li>
            </ul>
            
            <h4>Langkah Selanjutnya</h4>
            <ol>
                <li>Tim kami akan menghubungi Anda dalam 24 jam untuk diskusi detail acara</li>
                <li>Kami akan mengirimkan kontrak dan dokumentasi lainnya</li>
                <li>Mulai merencanakan acara pernikahan impian Anda!</li>
            </ol>
            
            <p style="color: #666; font-size: 14px; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 20px;">
                Jika ada pertanyaan atau ada yang ingin didiskusikan, jangan ragu untuk menghubungi kami.<br>
                Kami siap membantu mewujudkan acara pernikahan impian Anda.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Gemilang WO. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
