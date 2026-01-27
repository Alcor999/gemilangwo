# 🧪 Testing Guide: Midtrans VA Payment Flow

## ✅ Pre-Testing Checklist

- [ ] Migration sudah dijalankan: `php artisan migrate`
- [ ] Config sudah di-cache: `php artisan config:cache`
- [ ] Laravel server running: `php artisan serve`
- [ ] Database connected & healthy
- [ ] `.env` credentials updated

## 🚀 End-to-End Testing Steps

### Phase 1: User Registration & Setup

#### Step 1.1: Register as Customer
```
1. Go to: http://localhost:8000/register
2. Fill form:
   - Name: Test Customer
   - Email: test@example.com
   - Phone: 081234567890
   - Password: password123
3. Click "Register"
4. Verify email (if required)
5. Login with credentials
```

**Expected**: 
- ✅ Redirect to dashboard
- ✅ See customer menu

---

### Phase 2: Browse & Create Order

#### Step 2.1: View Available Packages
```
1. From dashboard, click "Browse Packages"
2. See available wedding packages
3. Check prices & details
```

**Expected**:
- ✅ See list of packages
- ✅ Each package shows: name, price, features

#### Step 2.2: Create New Order
```
1. Click package → "Book Now"
2. Fill order form:
   - Event Date: [Choose future date]
   - Event Location: Jakarta, Indonesia
   - Guest Count: 200
   - Special Request: (optional)
3. Click "Create Order"
```

**Expected**:
- ✅ Order created successfully
- ✅ Redirect to order details
- ✅ See order number (format: WO-XXXXXXXX)
- ✅ Status = "Pending"

**Check Database**:
```sql
SELECT id, order_number, total_price, status 
FROM orders 
ORDER BY id DESC LIMIT 1;

-- Expected output:
-- id: 1
-- order_number: WO-95161883 (or similar)
-- total_price: 150000000.00
-- status: pending
```

---

### Phase 3: Payment Processing

#### Step 3.1: Initiate Payment
```
1. From order details, click "Pay Now"
2. Wait for Midtrans Snap payment form to load
3. Form should display:
   - Order Number
   - Package name
   - Total Amount
   - Payment methods list
```

**Expected**:
- ✅ Snap payment form loaded
- ✅ No JavaScript errors (check F12 console)
- ✅ Can see all payment methods

#### Step 3.2: Select Bank Transfer
```
1. In Snap payment form, click "Bank Transfer"
2. List of banks appears:
   - BNI Virtual Account
   - BCA Virtual Account
   - Mandiri Virtual Account
   - etc.
3. Click "BNI Virtual Account" (or preferred bank)
```

**Expected**:
- ✅ Bank selected
- ✅ Form shows VA generation in progress

#### Step 3.3: Get Virtual Account Number
```
1. Wait 2-3 seconds for VA to generate
2. You should see:
   - Virtual Account Number: 9884423314107408 (example)
   - Bank: BNI
   - Amount: Rp XXX,XXX,XXX
3. **COPY the VA NUMBER (without spaces)**
```

**Expected**:
- ✅ VA number displayed clearly
- ✅ Can copy to clipboard
- ✅ Format is 15-20 digits (no spaces/separators)

**Check Payment in Database**:
```sql
SELECT id, payment_id, va_number, bank, status 
FROM payments 
ORDER BY id DESC LIMIT 1;

-- Expected output:
-- id: 1
-- payment_id: [transaction ID]
-- va_number: 9884423314107408
-- bank: bni
-- status: pending
```

---

### Phase 4: Simulator Testing

#### Step 4.1: Open Midtrans Simulator
```
1. Open in new tab: https://simulator.sandbox.midtrans.com/
2. You should see "BNI Virtual Account Simulator"
```

#### Step 4.2: Test VA in Simulator
```
1. Simulator form shows:
   - Bank selector (should be BNI)
   - Virtual Account Number field
   - Amount field

2. Fill form:
   - Bank: BNI (should be pre-selected)
   - VA Number: [PASTE the VA from Step 3.3]
   - Amount: [SAME as order amount]
   
3. Click "BAYAR" (PAY)
```

**⚠️ IMPORTANT**:
- ❌ DON'T include spaces in VA number
- ✅ DO copy exact number: `9884423314107408`
- ✅ DO use same amount as order
- ✅ DO use same bank as order

#### Step 4.3: Simulator Response
```
If VALID, you should see:
✅ "Pembayaran berhasil" (Payment successful)
✅ Transaction ID displayed
✅ Amount confirmed

If INVALID, you might see:
❌ "Virtual account number not found"
   → Check VA number format (no spaces)
   → Verify amount matches
   
❌ "Bank not found"
   → Verify bank matches between order and simulator
```

---

### Phase 5: Application Status Update

#### Step 5.1: Check Payment Status (Immediate)
```
After successful simulator test:

1. Go back to application tab
2. Refresh payment page
3. Check payment status section
4. Should show: "Payment Pending" or "Payment Successful"
```

#### Step 5.2: Check Database (After Webhook)
```
Wait 5-10 seconds, then check:

SELECT p.id, p.status, p.va_number, p.bank, p.payment_method
FROM payments p
JOIN orders o ON p.order_id = o.id
WHERE o.order_number = 'WO-95161883';

-- Expected after successful payment:
-- status: success (or settlement/pending)
-- va_number: 9884423314107408
-- bank: bni
-- payment_method: bank_transfer
```

#### Step 5.3: Check Order Status
```
SELECT id, order_number, status, updated_at 
FROM orders 
WHERE order_number = 'WO-95161883';

-- Expected after successful payment:
-- status: confirmed
```

---

### Phase 6: Error Testing (Optional)

#### Test Invalid VA
```
1. Go back to payment page
2. Try different VA number (fake)
3. Simulator should reject with error
→ This is CORRECT behavior
```

#### Test Wrong Amount
```
1. Copy correct VA number
2. In simulator, enter WRONG amount
3. Simulator should reject
→ This is CORRECT behavior
```

#### Test Wrong Bank
```
1. Order used BNI
2. In simulator, try BCA
3. Should get error or not match
→ This is CORRECT behavior
```

---

## 📊 Expected Results Summary

| Step | Expected Result | Status |
|------|-----------------|--------|
| Register | Account created | ✅ |
| Create Order | Order with WO-XXXXXXXX | ✅ |
| Payment Page | Snap form loads | ✅ |
| Select Bank | VA generation starts | ✅ |
| Get VA | Valid 15-20 digit number | ✅ |
| Simulator Test | "Pembayaran berhasil" | ✅ |
| Payment Update | Status changed to success | ✅ |
| Database | All fields populated | ✅ |

---

## 🔍 Debugging Checklist

If something goes wrong:

### Issue: Snap payment form not loading
```
Debug:
1. Open F12 → Console tab
2. Look for JavaScript errors
3. Check that client_key is in script tag
4. Clear browser cache: Cmd+Shift+Delete
5. Try incognito mode
```

### Issue: VA number not generated
```
Debug:
1. Check console for Midtrans errors
2. Verify MIDTRANS_SERVER_KEY is correct
3. Verify MIDTRANS_IS_PRODUCTION=false
4. Check logs: tail -f storage/logs/laravel.log
5. Restart Laravel server
```

### Issue: Simulator says "Invalid VA"
```
Debug:
1. Check VA format: should be numbers only
2. Verify NO SPACES in VA number
3. Verify amount matches order exactly
4. Verify bank matches order's bank
5. Check VA wasn't already used
```

### Issue: Payment status not updating
```
Debug:
1. Check if order exists:
   SELECT * FROM orders WHERE order_number = 'WO-95161883';
2. Check webhook notification:
   SELECT * FROM payments ORDER BY id DESC LIMIT 1;
3. Check logs for notification handler:
   grep -i "notification\|payment" storage/logs/laravel.log | tail -20
4. Verify webhook URL configured in Midtrans Dashboard
```

---

## 📝 Test Report Template

Use this to document your testing:

```markdown
## Test Report - [DATE]

### Environment
- Server: http://localhost:8000
- DB: gemilangwo
- Midtrans: Sandbox
- Browser: [Chrome/Firefox/Safari]

### Test Results
- [ ] Customer Registration: PASS/FAIL
- [ ] Order Creation: PASS/FAIL
- [ ] Payment Page Load: PASS/FAIL
- [ ] Bank Selection: PASS/FAIL
- [ ] VA Generation: PASS/FAIL
- [ ] Simulator Test: PASS/FAIL
- [ ] Payment Update: PASS/FAIL

### VA Numbers Tested
1. WO-95161883 → 9884423314107408 → ✅ SUCCESS
2. (add more as needed)

### Issues Found
(List any issues encountered)

### Resolution
(Describe how issues were resolved)

### Signed Off By: _______________
```

---

## ✅ Sign-Off Checklist

When testing complete & all passes:
- [ ] Created test account(s)
- [ ] Created test order(s)
- [ ] Tested VA generation
- [ ] Tested simulator payment
- [ ] Verified database updates
- [ ] Checked logs for errors
- [ ] No console errors
- [ ] Payment status updates correctly
- [ ] Ready for production preparation

---

## 🎯 Success Criteria

✅ **All tests pass if:**
1. VA number generated in correct format
2. Simulator accepts VA without "not found" error
3. Payment status updates after simulator test
4. Order status changes from pending to confirmed
5. VA details stored in database
6. No JavaScript errors in console
7. No server errors in logs

---

**Last Updated**: 27 Jan 2026  
**Version**: 1.0  
**Status**: Ready for Testing
