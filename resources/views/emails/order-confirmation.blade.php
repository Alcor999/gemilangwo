<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
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
            <h1>Order Confirmation</h1>
            <p>Your wedding package order has been received!</p>
        </div>

        <div class="content">
            <p>Hello {{ $customer->name }},</p>

            <p>Thank you for choosing us! Your order has been successfully created and is now being processed.</p>

            <div class="info-box">
                <strong>Order Details:</strong>
                <table>
                    <tr>
                        <td>Order ID:</td>
                        <td><strong>#{{ $order->id }}</strong></td>
                    </tr>
                    <tr>
                        <td>Package:</td>
                        <td>{{ $package->name }}</td>
                    </tr>
                    <tr>
                        <td>Package Price:</td>
                        <td>Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total Price:</td>
                        <td><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td>Event Date:</td>
                        <td>{{ \Carbon\Carbon::parse($order->event_date)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Status:</td>
                        <td><span class="warning-badge">{{ ucfirst($order->status) }}</span></td>
                    </tr>
                </table>
            </div>

            <p>Our team is reviewing your order and will contact you shortly to confirm the details and discuss any customizations.</p>

            <p style="text-align: center;">
                <a href="{{ route('customer.orders.show', $order) }}" class="button">
                    View Order Details
                </a>
            </p>

            <hr class="divider">

            <h3 style="margin-bottom: 10px;">What's Next?</h3>
            <ul>
                <li>We will review your order within 24 hours</li>
                <li>You will receive confirmation email from our team</li>
                <li>We will contact you to discuss final details</li>
                <li>Once confirmed, you can proceed with payment</li>
            </ul>

            <p>If you have any questions, please don't hesitate to contact us.</p>

            <p style="margin-top: 30px;">
                Best regards,<br>
                <strong>Gemilang WO Team</strong>
            </p>
        </div>

        <div class="footer">
            <p>© 2026 Gemilang WO. All rights reserved.</p>
            <p>This is an automated email. Please do not reply directly to this message.</p>
        </div>
    </div>
</body>
</html>
