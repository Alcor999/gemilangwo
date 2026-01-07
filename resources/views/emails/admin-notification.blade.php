<!DOCTYPE html><html><head><meta charset="UTF-8"><style>body { font-family: Arial, sans-serif; color: #333; }.container { max-width: 600px; margin: 0 auto; padding: 20px; }.header { background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%); color: white; padding: 30px; text-align: center; border-radius: 8px; margin-bottom: 20px; }.content { padding: 20px; background: #f9f9f9; border-radius: 8px; }.info-box { background: white; padding: 15px; border-left: 4px solid #8b5cf6; margin: 20px 0; }table { width: 100%; border-collapse: collapse; }table td { padding: 8px; border-bottom: 1px solid #eee; }table td:first-child { font-weight: bold; width: 30%; }.button { display: inline-block; background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }.divider { border: none; border-top: 1px solid #ddd; margin: 20px 0; }.footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; }ul { margin-left: 20px; margin-bottom: 20px; }</style></head><body><div class="container"><div class="header">
    <div class="header">
        <h1>New Activity on Gemilang WO</h1>
        <p>Attention required</p>
    </div>

    <div class="content">
        <p>Hello Admin,</p>

        @if($type === 'new_order')
            <p>A new order has been received on Gemilang WO!</p>

            <div class="info-box">
                <strong>New Order Details:</strong>
                <table>
                    <tr>
                        <td>Order ID:</td>
                        <td><strong>#{{ $data['order_id'] }}</strong></td>
                    </tr>
                    <tr>
                        <td>Customer:</td>
                        <td>{{ $data['customer_name'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>{{ $data['customer_email'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Phone:</td>
                        <td>{{ $data['customer_phone'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Package:</td>
                        <td>{{ $data['package_name'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Order Amount:</td>
                        <td><strong>Rp {{ number_format($data['total_price'] ?? 0, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td>Event Date:</td>
                        <td>{{ $data['event_date'] ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <p>Please review and confirm this order as soon as possible.</p>

        @elseif($type === 'new_review')
            <p>A new review has been submitted and is awaiting moderation.</p>

            <div class="info-box">
                <strong>Review Details:</strong>
                <table>
                    <tr>
                        <td>Review ID:</td>
                        <td><strong>#{{ $data['review_id'] }}</strong></td>
                    </tr>
                    <tr>
                        <td>Customer:</td>
                        <td>{{ $data['customer_name'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Package:</td>
                        <td>{{ $data['package_name'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Rating:</td>
                        <td>{{ $data['rating'] ?? 'N/A' }}/5 ⭐</td>
                    </tr>
                    <tr>
                        <td>Title:</td>
                        <td>{{ $data['title'] ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <p>Please moderate this review to ensure it meets community guidelines.</p>

        @elseif($type === 'payment_received')
            <p>A payment has been received for an order.</p>

            <div class="info-box">
                <strong>Payment Details:</strong>
                <table>
                    <tr>
                        <td>Payment ID:</td>
                        <td><strong>#{{ $data['payment_id'] }}</strong></td>
                    </tr>
                    <tr>
                        <td>Order ID:</td>
                        <td>#{{ $data['order_id'] }}</td>
                    </tr>
                    <tr>
                        <td>Amount:</td>
                        <td><strong>Rp {{ number_format($data['amount'] ?? 0, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td>Payment Method:</td>
                        <td>{{ ucfirst($data['payment_method'] ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <td>Customer:</td>
                        <td>{{ $data['customer_name'] ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <p>Please verify and confirm this payment.</p>
        @endif

        <p style="text-align: center;">
            <a href="{{ config('app.url') }}/admin/dashboard" class="button">
                Go to Admin Dashboard
            </a>
        </p>

        <hr class="divider">

        <p>This is an automated notification from Gemilang WO system.</p>

        <p style="margin-top: 30px;">
            Gemilang WO Admin System
        </p>
    </div>

    <div class="footer">
        <p>© 2026 Gemilang WO. All rights reserved.</p>
    </div>
</div></div></body></html>
