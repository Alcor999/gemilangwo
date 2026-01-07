# How to Use Discount & Flash Sale Feature

## 📋 Quick Start Guide

### For Admins

#### 1. Access Discount Management
```
Login as Admin
→ Sidebar: "Discounts & Promos" 
→ URL: http://yourapp.com/admin/discounts
```

#### 2. Create New Discount

**Step 1: Go to Create Page**
- Click "Create New Discount" button
- Or navigate to: `/admin/discounts/create`

**Step 2: Fill Form**

*Basic Information*
```
Name: "Year End Sale 2025"
Description: "Get 30% discount on all packages!"
```

*Discount Settings*
```
Type: Select "Percentage (%)"
Value: 30
```

*Time Period*
```
Start Date: January 1, 2026
End Date: February 1, 2026  (leave empty for unlimited)
```

*Usage Control*
```
Usage Limit: Leave empty for unlimited
            OR Enter number like: 50, 100, etc.
```

*Apply to Packages*
```
Option 1: Leave empty → Applies to ALL packages
Option 2: Select specific packages → Only those packages
```

*Status*
```
Toggle "Active" checkbox to enable/disable
```

**Step 3: Submit**
- Click "Create Discount" button
- You'll be redirected to discount list

---

#### 3. Edit Existing Discount

- From discount list, click Edit (pencil icon)
- Update any fields
- Click "Update Discount"

#### 4. View Discount Details

- From discount list, click View (eye icon)
- See all details including:
  - Discount amount per package
  - Original vs final price
  - Creator and timestamps
  - Usage statistics (if limited)

#### 5. Delete Discount

- From discount list or detail page
- Click Delete (trash icon)
- Confirm deletion

---

## 💡 Real World Examples

### Example 1: Valentine Flash Sale
```
Name: "Valentine Love Special"
Type: Percentage
Value: 25
Start: February 1, 2026
End: February 14, 2026
Usage Limit: 100 (only 100 bookings)
Packages: Select "Paket Gold Premium" & "Paket Silver Elegance"
Active: Yes
```
**Result**: Limited time + limited slots = FOMO effect ✨

### Example 2: Early Bird Discount (Ongoing)
```
Name: "Early Bird Booking Discount"
Type: Percentage
Value: 15
Start: January 4, 2026
End: Leave empty (no expiry)
Usage Limit: Leave empty (unlimited)
Packages: Leave empty (all packages)
Active: Yes
```
**Result**: Always available, rewards early planning 🐦

### Example 3: Fixed Amount Promo
```
Name: "Summer Wedding Save Rp 2 Million"
Type: Fixed Amount
Value: 2000000
Start: June 1, 2026
End: August 31, 2026
Usage Limit: Leave empty
Packages: Leave empty (all packages)
Active: Yes
```
**Result**: Flat discount across all packages 💰

### Example 4: Specific Package Deal
```
Name: "Bronze Package Promotion"
Type: Percentage
Value: 40
Start: January 4, 2026
End: January 31, 2026
Usage Limit: Leave empty
Packages: Select only "Paket Bronze Standard"
Active: Yes
```
**Result**: Clearance-like promo for specific tier 🎯

---

## 🎨 How Discounts Display on Home Page

### Without Discount
```
┌──────────────────────────┐
│  💜 Paket Gold Premium    │
│  Rp 150,000,000          │ ← Single price
│  Up to 500 Guests        │
└──────────────────────────┘
```

### With Discount
```
┌──────────────────────────┐
│  💜 Paket Gold Premium    │
│                          │
│  🔥 30% OFF              │ ← Badge with animation
│  Rp 150,000,000  ~~      │ ← Original (strikethrough)
│  Rp 105,000,000          │ ← Final price (big & bold)
│  Up to 500 Guests        │
└──────────────────────────┘
```

---

## ⚙️ Configuration Reference

### Discount Type: Percentage
```
Percentage (%)
- Value range: 0-100
- Example: 30 = 30% off
- Formula: Final = Original × (1 - Value/100)
- Use case: Sale events, seasonal promos
```

### Discount Type: Fixed Amount
```
Fixed Amount (Rp)
- Value in Rupiah
- Example: 1000000 = Rp 1 juta off
- Formula: Final = Original - Value (min: Rp 0)
- Use case: Absolute savings, special offers
```

---

## 📊 Discount List View

### Table Columns Explained
| Column | Meaning |
|--------|---------|
| Discount Name | Name with Active badge |
| Type | Percentage (%) or Fixed (Rp) |
| Value | Discount amount |
| Period | Start - End dates |
| Packages | How many packages affected |
| Status | Enabled/Disabled toggle |
| Actions | View, Edit, Delete buttons |

### Color Coding
- **Green badge**: Discount is active
- **Yellow badge**: Not active (future/expired)
- **Blue badge**: Percentage type
- **Purple badge**: Fixed amount type

---

## 🚀 Pro Tips

### Tip 1: Multiple Discounts per Package
If a package has multiple active discounts, the FIRST ONE in the system will apply. (Based on creation order)

### Tip 2: Time-Based Marketing
```
Create discounts ahead of time:
- January: "New Year Sale"
- February: "Valentine Special" 
- June-August: "Summer Season"
- December: "Year End Clearance"
```

### Tip 3: Flash Sales Strategy
```
Combine:
- Short time window (e.g., 1 week)
- Usage limit (e.g., 50 uses)
- High discount % (e.g., 30%)
= Creates urgency & drives conversions
```

### Tip 4: Package-Specific Promos
```
Leave "Packages" empty = better for general sales
Select packages = better for clearing specific tiers
Example: Low season? Discount only Premium packages
         High season? Discount only Budget packages
```

### Tip 5: Soft Launch
```
Create discount first but leave "Active" unchecked
Set the dates
When ready to launch:
- Just toggle "Active" checkbox
- No need to recreate everything
```

---

## ❌ Common Mistakes to Avoid

### ❌ Mistake 1: Wrong Start Date
```
Current: January 4, 2026
Start Date: January 5, 2026
Problem: Discount won't apply until tomorrow
Solution: Check date/time before saving
```

### ❌ Mistake 2: Forgetting End Date
```
Type: Percentage
Value: 50
End Date: (left empty)
Problem: 50% discount runs forever (bad for business!)
Solution: Always set end date for time-limited promos
```

### ❌ Mistake 3: Too High Usage Limit
```
Flash sale with limit: 1000
Type: Limited edition promo
Problem: Not really "limited" anymore
Solution: Keep flash sale limits realistic (50-200)
```

### ❌ Mistake 4: Wrong Package Selection
```
Want: Apply to Gold & Silver only
Did: Apply to Gold & Silver & Bronze
Problem: Wrong packages get discount
Solution: Carefully select each package before saving
```

---

## 📱 Customer View

### On Home Page
Customers see:
1. **Discount badge** with animated pulse effect
2. **Original price** with strikethrough (gray)
3. **Final discounted price** (prominent, colorful)
4. **Book Now** button (same as before)

### On Package Details Page
Same information displays for more impact

### During Checkout
- Order total calculates automatically
- Discount amount shows as line item
- Customer sees savings amount

---

## 🔐 Access Control

**Only Admins can:**
- Create discounts
- Edit discounts
- Delete discounts
- View discount list & details

**Customers can:**
- See active discounts on home page
- See discounted prices
- Book with discounted rates

**Guests can:**
- See active discounts (no login needed)
- But need to login to book

---

## 📈 Monitoring Discounts

### For Limited Discounts
From the discount details page, you can see:
```
Usage Count: 24 / 50
Progress: ████████░░░░░░░░░░░░
```

### Time-Based Status
System automatically shows:
- 🟢 Currently Active
- 🟡 Not Active Yet (future)
- ⚫ Expired (past end date)

---

## 🆘 Troubleshooting

### Q: Discount not showing on home page?
**A:** Check:
1. Is "Active" checkbox enabled?
2. Is current date within start/end date?
3. Is status shown as "Currently Active"?
4. Did you select the right packages? (empty = all)

### Q: Wrong discount amount showing?
**A:** 
- Percentage: Check if value is 0-100
- Fixed: Check if value is in Rupiah
- Check calculation: original × discount = expected amount

### Q: Can't access discount management?
**A:** 
- You must be logged in as Admin
- Check your role in Users page
- Refresh page after role change

### Q: Discount showing but wrong packages?
**A:** 
- Re-edit the discount
- Check which packages are selected
- Resave to clear cache

---

## 📞 Need Help?

For issues or questions:
1. Check DISCOUNT_FEATURE.md (technical details)
2. Review database discounts with SQL
3. Check Laravel logs in storage/logs/
4. Ask the development team

---

**Happy Discounting! 🎉**
