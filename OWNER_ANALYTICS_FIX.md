# Owner Analytics Database Schema Fix

## Problem Identified
The Owner Analytics system was throwing a database error:
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'payment_status' in 'where clause'
```

## Root Cause
The `AnalyticsService.php` had incorrect database queries for Owner methods. The queries were:
1. Using `Order::where('payment_status', 'paid')` but Order model only has `status` field
2. Not properly filtering by owner_id through Package relationship
3. Attempting to sum `total_price` from Orders instead of `amount` from Payments

## Data Model Relationship
```
Owner (User with role=owner)
  ├── Package (owner_id)
  │   └── Order (package_id)
  │       └── Payment (order_id)
```

Payment table has the actual payment information:
- `status`: 'success', 'pending', 'failed' (not 'paid')
- `amount`: Payment amount
- `created_at`: Payment date

## Solutions Implemented

### 1. Fixed getOwnerRevenue()
**Before:**
```php
$query = Order::where('user_id', $ownerId)
    ->where('payment_status', 'paid');
```

**After:**
```php
$query = Payment::where('status', 'success')
    ->whereHas('order.package', fn($q) => $q->where('owner_id', $ownerId));
```

### 2. Fixed getOwnerRevenueByDay()
Uses Payment model with proper relationship chain to filter by owner_id through Package

### 3. Fixed getOwnerRevenueByMonth()
Uses Payment model grouped by month with payment status = 'success'

### 4. Fixed getOwnerRevenueByYear()
Uses Payment model grouped by year with payment status = 'success'

### 5. Fixed getOwnerCustomerLifetimeValue()
Uses Payment model to calculate lifetime value per customer, properly joining with Order and User

### Methods Already Correct
- ✅ getOwnerBookingStats() - Uses Order with correct relationship
- ✅ getOwnerRepeatCustomers() - Uses Order with correct relationship
- ✅ getOwnerChurnAnalysis() - Uses Order with correct relationship
- ✅ getOwnerTopPackages() - Uses Package directly with correct owner_id
- ✅ getOwnerCompletedOrders() - Uses Order with correct relationship
- ✅ getOwnerUpcomingEvents() - Uses Order with correct relationship

## Testing Results
✅ `/owner/analytics/dashboard` - Loads successfully (504.60ms)
✅ `/owner/analytics/revenue` - Loads successfully (23.74ms)
✅ All cache cleared and verified
✅ No SQL errors in logs

## Key Changes in app/Services/AnalyticsService.php
- Lines 366-378: Updated getOwnerRevenue() to use Payment model
- Lines 410-422: Updated getOwnerRevenueByDay() to use Payment model
- Lines 424-435: Updated getOwnerRevenueByMonth() to use Payment model
- Lines 437-448: Updated getOwnerRevenueByYear() to use Payment model
- Lines 454-476: Updated getOwnerCustomerLifetimeValue() to use Payment model

## Verification Checklist
- [x] Analytics routes registered correctly
- [x] Owner can access analytics dashboard
- [x] Revenue queries use Payment model
- [x] Customer lifetime value calculated from payments
- [x] All queries properly filter by owner_id
- [x] No SQL errors in application logs

## Status
✅ **RESOLVED** - Owner analytics fully functional with correct database queries
