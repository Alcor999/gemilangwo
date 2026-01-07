<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Received</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%); color: white; padding: 30px; text-align: center; border-radius: 8px; margin-bottom: 20px; }
        .content { padding: 20px; background: #f9f9f9; border-radius: 8px; }
        .info-box { background: white; padding: 15px; border-left: 4px solid #8b5cf6; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 8px; border-bottom: 1px solid #eee; }
        table td:first-child { font-weight: bold; width: 30%; }
        .button { display: inline-block; background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .divider { border: none; border-top: 1px solid #ddd; margin: 20px 0; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; }
        .success-badge { background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        ul { margin-left: 20px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payment Received</h1>
            <p>Thank you for your payment!</p>
        </div>

        <div class="content">
        <p>Hello {{ $customer->name }},</p>

        <p>We have successfully received your payment for order <strong>#{{ $order->id }}</strong>.</p>

        <div class="info-box">
            <strong>Payment Details:</strong>
            <table>
                <tr>
                    <td>Payment ID:</td>
                    <td><strong>#{{ $payment->id }}</strong></td>
                </tr>
                <tr>
                    <td>Order ID:</td>
                    <td>#{{ $order->id }}</td>
                </tr>
                <tr>
                    <td>Package:</td>
                    <td>{{ $package->name }}</td>
                </tr>
                <tr>
                    <td>Amount Paid:</td>
                    <td><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td>Payment Method:</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                </tr>
                <tr>
                    <td>Payment Date:</td>
                    <td>{{ $payment->paid_at->format('d F Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td><span class="success-badge">Paid</span></td>
                </tr>
            </table>
        </div>

        <p>Your order is now confirmed and our team is preparing everything for your special event. We will keep you updated on the progress.</p>

        <p style="text-align: center;">
            <a href="{{ route('customer.orders.show', $order) }}" class="button">
                View Order & Tracking
            </a>
        </p>

        <hr class="divider">

        <h3 style="margin-bottom: 10px;">What Happens Next?</h3>
        <ul style="margin-left: 20px; margin-bottom: 20px;">
            <li>Your order is now confirmed and processing</li>
            <li>Our team will contact you 3 days before the event</li>
            <li>Final confirmation will be sent 1 day before</li>
            <li>We're excited to make your event special!</li>
        </ul>

        <p>If you have any questions about your order, please contact our support team.</p>

        <p style="margin-top: 30px;">
            Thank you for choosing us!<br>
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
