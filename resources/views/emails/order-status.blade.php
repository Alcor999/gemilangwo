<!DOCTYPE html><html><head><meta charset="UTF-8"><style>body { font-family: Arial, sans-serif; color: #333; }.container { max-width: 600px; margin: 0 auto; padding: 20px; }.header { background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%); color: white; padding: 30px; text-align: center; border-radius: 8px; margin-bottom: 20px; }.content { padding: 20px; background: #f9f9f9; border-radius: 8px; }.info-box { background: white; padding: 15px; border-left: 4px solid #8b5cf6; margin: 20px 0; }table { width: 100%; border-collapse: collapse; }table td { padding: 8px; border-bottom: 1px solid #eee; }table td:first-child { font-weight: bold; width: 30%; }.button { display: inline-block; background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }.divider { border: none; border-top: 1px solid #ddd; margin: 20px 0; }.footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; }ul { margin-left: 20px; margin-bottom: 20px; }</style></head><body><div class="container"><div class="header">
    <div class="header">
        <h1>Order Status Update</h1>
        <p>Your order status has changed</p>
    </div>

    <div class="content">
        <p>Hello {{ $customer->name }},</p>

        <p>We're writing to inform you that the status of your order has been updated.</p>

        <div class="info-box">
            <strong>Status Update:</strong>
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
                    <td>Previous Status:</td>
                    <td><span class="warning-badge">{{ ucfirst($previousStatus) }}</span></td>
                </tr>
                <tr>
                    <td>New Status:</td>
                    <td><span class="success-badge">{{ ucfirst($order->status) }}</span></td>
                </tr>
                <tr>
                    <td>Updated At:</td>
                    <td>{{ now()->format('d F Y H:i') }}</td>
                </tr>
            </table>
        </div>

        @switch($order->status)
            @case('confirmed')
                <p>Great news! Your order has been confirmed by our team. We're now preparing everything for your special event.</p>
                @break
            @case('in_progress')
                <p>Your event is currently being prepared. Our team is working hard to make your event perfect!</p>
                @break
            @case('completed')
                <p>Your event has been completed successfully! We hope everything went perfectly. Please share your feedback and rating on your order page.</p>
                @break
            @case('cancelled')
                <p>Your order has been cancelled. If this was done by mistake, please contact our support team immediately.</p>
                @break
        @endswitch

        <p style="text-align: center;">
            <a href="{{ route('customer.orders.show', $order) }}" class="button">
                View Order Details
            </a>
        </p>

        <hr class="divider">

        <p>If you have any questions about this update, please don't hesitate to contact us.</p>

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
