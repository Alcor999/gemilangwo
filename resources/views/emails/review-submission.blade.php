<!DOCTYPE html><html><head><meta charset="UTF-8"><style>body { font-family: Arial, sans-serif; color: #333; }.container { max-width: 600px; margin: 0 auto; padding: 20px; }.header { background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%); color: white; padding: 30px; text-align: center; border-radius: 8px; margin-bottom: 20px; }.content { padding: 20px; background: #f9f9f9; border-radius: 8px; }.info-box { background: white; padding: 15px; border-left: 4px solid #8b5cf6; margin: 20px 0; }table { width: 100%; border-collapse: collapse; }table td { padding: 8px; border-bottom: 1px solid #eee; }table td:first-child { font-weight: bold; width: 30%; }.button { display: inline-block; background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }.divider { border: none; border-top: 1px solid #ddd; margin: 20px 0; }.footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; }ul { margin-left: 20px; margin-bottom: 20px; }</style></head><body><div class="container"><div class="header">
    <div class="header">
        <h1>Thank You for Your Review</h1>
        <p>Your feedback has been received!</p>
    </div>

    <div class="content">
        <p>Hello {{ $customer->name }},</p>

        <p>Thank you for taking the time to share your review for <strong>{{ $package->name }}</strong>. Your feedback is invaluable to us and helps other couples make informed decisions.</p>

        <div class="info-box">
            <strong>Your Review Summary:</strong>
            <table>
                <tr>
                    <td>Package:</td>
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
                    <td>Title:</td>
                    <td>{{ $review->title }}</td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td>
                        @if($review->is_approved)
                            <span class="success-badge">Approved</span>
                        @else
                            <span class="warning-badge">Under Review</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <p>Our moderation team will review your feedback to ensure it meets our community guidelines. Once approved, your review will be published on the package page and will help other couples discover our services.</p>

        <p style="text-align: center;">
            <a href="{{ route('customer.reviews.index') }}" class="button">
                View My Reviews
            </a>
        </p>

        <hr class="divider">

        <h3 style="margin-bottom: 10px;">Why Reviews Matter</h3>
        <ul style="margin-left: 20px; margin-bottom: 20px;">
            <li>Help other couples make the right choice</li>
            <li>Provide valuable feedback to service providers</li>
            <li>Build trust in our community</li>
            <li>Earn rewards for helpful reviews</li>
        </ul>

        <p>Thank you for being part of our community!</p>

        <p style="margin-top: 30px;">
            Best regards,<br>
            <strong>Gemilang WO Team</strong>
        </p>
    </div>

    <div class="footer">
        <p>© 2026 Gemilang WO. All rights reserved.</p>
        <p>This is an automated email. Please do not reply directly to this message.</p>
    </div>
</div></div></body></html>
