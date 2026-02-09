<!DOCTYPE html><html><head><meta charset="UTF-8"><style>body { font-family: Arial, sans-serif; color: #333; }.container { max-width: 600px; margin: 0 auto; padding: 20px; }.header { background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%); color: white; padding: 30px; text-align: center; border-radius: 8px; margin-bottom: 20px; }.content { padding: 20px; background: #f9f9f9; border-radius: 8px; }.info-box { background: white; padding: 15px; border-left: 4px solid #b8860b; margin: 20px 0; }table { width: 100%; border-collapse: collapse; }table td { padding: 8px; border-bottom: 1px solid #eee; }table td:first-child { font-weight: bold; width: 30%; }.button { display: inline-block; background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }.divider { border: none; border-top: 1px solid #ddd; margin: 20px 0; }.footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; }ul { margin-left: 20px; margin-bottom: 20px; }</style></head><body><div class="container"><div class="header">
    <div class="header">
        <h1>Pembaruan Status Pesanan</h1>
        <p>Status pesanan Anda berubah</p>
    </div>

    <div class="content">
        <p>Halo {{ $customer->name }},</p>

        <p>Kami ingin menginformasikan bahwa status pesanan Anda telah diperbarui.</p>

        <div class="info-box">
            <strong>Pembaruan Status:</strong>
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
                    <td>Status Sebelumnya:</td>
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
                        <span class="warning-badge">{{ $statusLabels[$previousStatus] ?? ucfirst(str_replace('_', ' ', $previousStatus)) }}</span>
                    </td>
                </tr>
                <tr>
                    <td>Status Baru:</td>
                    <td><span class="success-badge">{{ $statusLabels[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status)) }}</span></td>
                </tr>
                <tr>
                    <td>Diperbarui Pada:</td>
                    <td>{{ now()->format('d F Y H:i') }}</td>
                </tr>
            </table>
        </div>

        @switch($order->status)
            @case('confirmed')
                <p>Kabar baik! Pesanan Anda sudah dikonfirmasi oleh tim kami. Kami sedang menyiapkan semuanya untuk acara spesial Anda.</p>
                @break
            @case('in_progress')
                <p>Acara Anda sedang dipersiapkan. Tim kami bekerja keras untuk membuat acara Anda sempurna!</p>
                @break
            @case('completed')
                <p>Acara Anda telah selesai dengan sukses! Semoga semuanya berjalan lancar. Silakan berikan ulasan dan rating pada halaman pesanan Anda.</p>
                @break
            @case('cancelled')
                <p>Pesanan Anda telah dibatalkan. Jika ini terjadi karena kesalahan, silakan segera hubungi tim dukungan kami.</p>
                @break
        @endswitch

        <p style="text-align: center;">
            <a href="{{ route('customer.orders.show', $order) }}" class="button">
                Lihat Detail Pesanan
            </a>
        </p>

        <hr class="divider">

        <p>Jika Anda memiliki pertanyaan terkait pembaruan ini, jangan ragu untuk menghubungi kami.</p>

        <p style="margin-top: 30px;">
            Salam hangat,<br>
            <strong>Tim Gemilang WO</strong>
        </p>
    </div>

    <div class="footer">
        <p>© 2026 Gemilang WO. Hak cipta dilindungi undang-undang.</p>
        <p>Ini adalah email otomatis. Mohon jangan membalas langsung pesan ini.</p>
    </div>
</div></div></body></html>
