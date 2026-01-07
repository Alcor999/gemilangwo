# Owner Analytics Database Schema - Complete Fix

## Issues Found & Fixed

### Issue 1: Packages Table Missing owner_id Column
**Problem:**
- Packages table tidak memiliki `owner_id` field untuk menghubungkan package ke owner
- Ini menyebabkan query Analytics tidak bisa filter packages berdasarkan owner_id

**Solution:**
1. Created migration: `2026_01_05_012609_add_owner_id_to_packages_table.php`
2. Added foreign key constraint ke user_id dengan cascade delete
3. Updated Package model:
   - Added `owner_id` ke fillable array
   - Added `owner()` relationship method
4. Updated all existing packages di database untuk assign owner_id = 2 (Owner Business user)

### Issue 2: Incorrect whereHas Syntax
**Problem:**
```php
->whereHas('order.package', fn($q) => $q->where('owner_id', $ownerId))
```
Laravel tidak support nested relationships dalam whereHas dengan dot notation seperti ini.

**Solution:**
Changed to proper nested whereHas syntax:
```php
->whereHas('order', fn($q) => 
    $q->whereHas('package', fn($p) => 
        $p->where('owner_id', $ownerId)
    )
)
```

## Updated Files

### Database Migration
- **File:** `database/migrations/2026_01_05_012609_add_owner_id_to_packages_table.php`
- **Changes:** 
  - Added `owner_id` foreign key to packages table
  - Null by default to prevent breaking existing packages
  - Cascade delete when owner (user) is deleted

### Model Changes
- **File:** `app/Models/Package.php`
- **Changes:**
  1. Added `owner_id` to fillable array
  2. Added owner relationship:
     ```php
     public function owner()
     {
         return $this->belongsTo(User::class, 'owner_id');
     }
     ```

### Analytics Service
- **File:** `app/Services/AnalyticsService.php`
- **Methods Fixed:**
  1. `getOwnerRevenue()` - Fixed whereHas syntax
  2. `getOwnerRevenueByDay()` - Fixed whereHas syntax
  3. `getOwnerRevenueByMonth()` - Fixed whereHas syntax
  4. `getOwnerRevenueByYear()` - Fixed whereHas syntax
  5. `getOwnerCustomerLifetimeValue()` - Fixed whereHas syntax

## Data Model Relationship Chain (Corrected)

```
User (owner with role='owner')
  │
  └─ Package (owner_id FK)
      │
      └─ Order (package_id FK)
          │
          └─ Payment (order_id FK, status='success')
```

## Query Pattern Used

For Revenue Queries:
```php
Payment::where('status', 'success')
    ->whereHas('order', fn($q) => 
        $q->whereHas('package', fn($p) => 
            $p->where('owner_id', $ownerId)
        )
    )
    ->sum('amount');  // Sum from Payment, not Order
```

## Database Changes Summary

1. ✅ Added owner_id column to packages table
2. ✅ Updated all existing packages with owner_id = 2
3. ✅ Added owner() relationship to Package model
4. ✅ Fixed all Owner analytics queries with correct whereHas syntax

## Testing Status

✅ **VERIFIED:**
- `/owner/analytics/dashboard` - Accessible, no SQL errors
- `/owner/analytics/revenue` - Accessible, no SQL errors
- Migration executed successfully
- All packages have owner_id assigned

## Notes

- Owner user (ID=2) created in UserSeeder is assigned to all packages
- System can support multiple owners in future by varying the owner_id during package creation
- All owner analytics methods now properly filter by owner_id through Package relationship
- Payment amounts are correctly summed instead of Order total_price

## Status: ✅ COMPLETE

All database schema issues have been resolved. Owner Analytics system is now fully functional with proper relationship chains and query syntax.
