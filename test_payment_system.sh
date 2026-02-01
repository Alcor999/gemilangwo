#!/bin/bash

echo "=========================================="
echo "🧪 Manual Payment System - Full Test Suite"
echo "=========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test 1: Check Bank Data
echo -e "${YELLOW}📋 Test 1: Verify Bank Data${NC}"
php artisan tinker << 'EOF' 2>/dev/null
$banks = \App\Models\Bank::all();
echo "Total Banks: " . count($banks) . "\n";
foreach ($banks as $bank) {
    echo "  - {$bank->name} ({$bank->code}): {$bank->account_number}\n";
}
EOF
echo ""

# Test 2: Check Payment Status
echo -e "${YELLOW}📋 Test 2: Check Payment Status${NC}"
php artisan tinker << 'EOF' 2>/dev/null
$payments = \App\Models\Payment::with('order', 'bank')->get();
echo "Total Payments: " . count($payments) . "\n";
foreach ($payments as $p) {
    echo "  Payment #{$p->id}: Order {$p->order->order_number} - Status: {$p->verification_status} - Bank: {$p->bank->name}\n";
}
EOF
echo ""

# Test 3: Check Payment Routes
echo -e "${YELLOW}📋 Test 3: Verify Payment Routes${NC}"
php artisan route:list | grep -i payment | head -10
echo ""

# Test 4: Check Email Classes
echo -e "${YELLOW}📋 Test 4: Verify Email Classes${NC}"
if [ -f "app/Mail/PaymentInstructionMail.php" ]; then echo "  ✅ PaymentInstructionMail"; else echo "  ❌ PaymentInstructionMail"; fi
if [ -f "app/Mail/PaymentVerifiedMail.php" ]; then echo "  ✅ PaymentVerifiedMail"; else echo "  ❌ PaymentVerifiedMail"; fi
if [ -f "app/Mail/PaymentRejectedMail.php" ]; then echo "  ✅ PaymentRejectedMail"; else echo "  ❌ PaymentRejectedMail"; fi
echo ""

# Test 5: Check Views
echo -e "${YELLOW}📋 Test 5: Verify Views${NC}"
if [ -f "resources/views/customer/orders/payment-manual.blade.php" ]; then echo "  ✅ payment-manual.blade.php"; else echo "  ❌ payment-manual.blade.php"; fi
if [ -f "resources/views/customer/orders/payment-confirm.blade.php" ]; then echo "  ✅ payment-confirm.blade.php"; else echo "  ❌ payment-confirm.blade.php"; fi
if [ -f "resources/views/admin/payments/pending.blade.php" ]; then echo "  ✅ admin/payments/pending.blade.php"; else echo "  ❌ admin/payments/pending.blade.php"; fi
echo ""

# Test 6: Check Environment Variables
echo -e "${YELLOW}📋 Test 6: Environment Variables${NC}"
php artisan tinker << 'EOF' 2>/dev/null
echo "ADMIN_WHATSAPP_NUMBER: " . (env('ADMIN_WHATSAPP_NUMBER') ?: 'NOT SET') . "\n";
EOF
echo ""

echo -e "${GREEN}=========================================="
echo "✅ All Checks Complete!"
echo "==========================================${NC}"
echo ""
echo "Next Steps:"
echo "1. Test customer payment selection at: http://localhost:8000/customer/orders/[order_id]/payment"
echo "2. Test admin approval at: http://localhost:8000/admin/orders/[order_id]"
echo "3. Check email logs: storage/logs/laravel.log"
