<!DOCTYPE html><html><head><meta charset="UTF-8"><style>body { font-family: Arial, sans-serif; color: #333; }.container { max-width: 600px; margin: 0 auto; padding: 20px; }.header { background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%); color: white; padding: 30px; text-align: center; border-radius: 8px; margin-bottom: 20px; }.content { padding: 20px; background: #f9f9f9; border-radius: 8px; }.info-box { background: white; padding: 15px; border-left: 4px solid #b8860b; margin: 20px 0; }table { width: 100%; border-collapse: collapse; }table td { padding: 8px; border-bottom: 1px solid #eee; }table td:first-child { font-weight: bold; width: 30%; }.button { display: inline-block; background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }.divider { border: none; border-top: 1px solid #ddd; margin: 20px 0; }.footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; }ul { margin-left: 20px; margin-bottom: 20px; }</style></head><body><div class="container"><div class="header">
    <div class="header">
        <h1>Terima Kasih atas Ulasan Anda</h1>
        <p>Masukan Anda sudah kami terima!</p>
    </div>

    <div class="content">
        <p>Halo {{ $customer->name }},</p>

        <p>Terima kasih telah meluangkan waktu untuk membagikan ulasan Anda untuk <strong>{{ $package->name }}</strong>. Masukan Anda sangat berarti bagi kami dan membantu pasangan lain mengambil keputusan yang tepat.</p>

        <div class="info-box">
            <strong>Ringkasan Ulasan Anda:</strong>
            <table>
                <tr>
                    <td>Paket:</td>
                    <td>{{ $package->name }}</td>
                </tr>
                <tr>
                    <td>Rating:</td>
                    <td>
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                ⭐
                            @else
                                ☆
                            @endif
                        @endfor
                        {{ $review->rating }}/5
                    </td>
                </tr>
                <tr>
                    <td>Judul:</td>
                    <td>{{ $review->title }}</td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td>
                        @if($review->is_approved)
                            <span class="success-badge">Disetujui</span>
                        @else
                            <span class="warning-badge">Sedang Ditinjau</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <p>Tim moderasi kami akan meninjau ulasan Anda untuk memastikan sesuai dengan pedoman komunitas. Setelah disetujui, ulasan Anda akan ditampilkan pada halaman paket dan membantu pasangan lain mengenal layanan kami.</p>

        <p style="text-align: center;">
            <a href="{{ route('customer.reviews.index') }}" class="button">
                Lihat Ulasan Saya
            </a>
        </p>

        <hr class="divider">

        <h3 style="margin-bottom: 10px;">Mengapa Ulasan Penting</h3>
        <ul style="margin-left: 20px; margin-bottom: 20px;">
            <li>Membantu pasangan lain memilih yang tepat</li>
            <li>Memberikan masukan berharga untuk penyedia layanan</li>
            <li>Membangun kepercayaan di komunitas</li>
            <li>Mendapatkan reward dari ulasan yang bermanfaat</li>
        </ul>

        <p>Terima kasih telah menjadi bagian dari komunitas kami!</p>

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
