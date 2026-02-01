<?php

use App\Models\Order;
use App\Models\Payment;
use App\Models\Bank;
use App\Models\User;
use App\Services\PaymentService;

// Create test data
$user = User::first();
$package = \App\Models\Package::first();

if (!$user || !$package) {
    echo "❌ No user or package found\n";
    exit(1);
}

echo "🧪 Testing Manual Payment System\n";
echo "================================\n\n";

// Step 1: Create order
echo "Step 1: Creating test order...\n";
$order = Order::create([
    'user_id' => $user->id,
    'package_id' => $package->id,
    'order_number' => 'TEST-' . time(),
    'event_date' => now()->addMonths(3),
    'guest_count' => 100,
    'venue' => 'Test Venue',
    'notes' => 'Test payment cycle',
    'status' => 'pending',
    'total_price' => $package->price,
]);
echo "✅ Order created: {$order->order_number} (ID: {$order->id})\n\n";

// Step 2: Select bank and create payment
echo "Step 2: Selecting bank and creating payment...\n";
$bank = Bank::where('code', 'bca')->first();
if (!$bank) {
    echo "❌ BCA bank not found\n";
    exit(1);
}

$paymentService = app(PaymentService::class);
$paymentService->createManualPayment($order, $bank);

$payment = Payment::where('order_id', $order->id)->first();
echo "✅ Payment created: #{$payment->id}\n";
echo "   Bank: {$bank->name}\n";
echo "   Status: {$payment->verification_status}\n";
echo "   Account: {$bank->account_number}\n\n";

// Step 3: Test WhatsApp link generation
echo "Step 3: Testing WhatsApp link generation...\n";
$order->load('payment.bank', 'package');
$whatsappLink = $paymentService->generateWhatsAppLink($order, $bank);
if ($whatsappLink) {
    echo "✅ WhatsApp link generated:\n";
    echo "   " . substr($whatsappLink, 0, 80) . "...\n\n";
} else {
    echo "❌ Failed to generate WhatsApp link\n\n";
}

// Step 4: Test admin approval
echo "Step 4: Testing admin payment approval...\n";
$admin = User::where('role', 'admin')->first() ?? $user;
$paymentService->verifyPayment($payment, $admin, 'Test approval - payment verified');

$payment->refresh();
echo "✅ Payment approved\n";
echo "   Status: {$payment->verification_status}\n";
echo "   Verified by: {$admin->name}\n";
echo "   Order status: {$payment->order->status}\n\n";

// Step 5: Test admin rejection (create another payment)
echo "Step 5: Testing admin payment rejection...\n";
$order2 = Order::create([
    'user_id' => $user->id,
    'package_id' => $package->id,
    'order_number' => 'TEST-REJECT-' . time(),
    'event_date' => now()->addMonths(4),
    'guest_count' => 150,
    'venue' => 'Test Venue 2',
    'notes' => 'Test rejection',
    'status' => 'pending',
    'total_price' => $package->price,
]);

$paymentService->createManualPayment($order2, $bank);
$payment2 = Payment::where('order_id', $order2->id)->first();

$paymentService->rejectPayment($payment2, $admin, 'Test rejection - amount mismatch');
$payment2->refresh();

echo "✅ Payment rejected\n";
echo "   Status: {$payment2->verification_status}\n";
echo "   Rejection reason: {$payment2->verification_notes}\n\n";

echo "================================\n";
echo "✅ All tests completed successfully!\n";
echo "================================\n";
