# Rating & Review System - Quick Start Guide

## 🎯 What's New?

Your Gemilang WO now has a complete **5-Star Rating & Review System** that allows customers to share their experiences and helps you build social proof.

---

## 📋 Quick Access

### For Customers
- **Review Form URL:** `/customer/orders/{order_id}/review`
- **Access:** Click "Write Review" button on completed orders

### For Admins
- **Admin Dashboard:** `/admin/reviews`
- **Key Stats:** Total reviews, pending approvals, approved, featured

---

## 🚀 Getting Started

### Step 1: Ensure Database is Updated
```bash
php artisan migrate
```

### Step 2: Load Sample Data (Optional)
```bash
php artisan db:seed --class=ReviewSeeder
```

### Step 3: Start Using!
- Customers can now submit reviews on completed orders
- Admins can manage reviews in the admin panel

---

## 👥 User Workflows

### Customer: Write a Review

1. Go to **My Orders** section
2. Click **Write Review** on any completed order
3. Select 1-5 stars
4. Enter a title (max 255 characters)
5. Write your review (10-2000 characters)
6. Submit!
7. Wait for admin approval

**Review Submission Form Features:**
- ⭐ Interactive 5-star selector
- 📝 Title and content fields with character counters
- 💡 Tips for writing helpful reviews
- ✅ Form validation with helpful error messages

### Admin: Manage Reviews

1. Go to **Admin Dashboard** → **Reviews**
2. See overview stats at the top
3. Use filter tabs to find specific reviews
4. Click eye icon to view full details
5. For pending reviews: **Approve** or **Reject**
6. For approved reviews: **Feature** as testimonial or **Delete**

**Admin Features:**
- 📊 Dashboard stats (Total, Pending, Approved, Featured)
- 🔍 Filter tabs for quick navigation
- 📄 Detailed review view with customer info
- ⚡ Quick action buttons
- 🔗 Links to customer and order pages

---

## 🎨 Display Reviews on Package Page

To show ratings and reviews on your package detail page:

```blade
<!-- Show Average Rating -->
@if($package->getTotalReviews() > 0)
    <div class="rating-display">
        <div class="rating-stars">
            @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star {{ $i <= $package->getAverageRating() ? 'filled' : '' }}"></i>
            @endfor
        </div>
        <span class="rating-value">
            {{ number_format($package->getAverageRating(), 1) }}/5 
            ({{ $package->getTotalReviews() }} reviews)
        </span>
    </div>
@endif

<!-- Show Customer Reviews -->
<div class="reviews-section">
    @foreach($package->approvedReviews()->take(5) as $review)
        <div class="review-card">
            <h5>{{ $review->title }}</h5>
            <div class="stars">
                @for($i = 1; $i <= $review->rating; $i++)
                    <i class="fas fa-star filled"></i>
                @endfor
            </div>
            <p>{{ $review->content }}</p>
            <footer>{{ $review->user->name }} • {{ $review->created_at->format('M d, Y') }}</footer>
        </div>
    @endforeach
</div>
```

---

## 📊 Package Model Methods

### Get Average Rating
```php
$package->getAverageRating()  // Returns: 4.5 (or 0 if no approved reviews)
```

### Count Total Reviews
```php
$package->getTotalReviews()   // Returns: 15 (number of approved reviews)
```

### Get Rating Distribution
```php
$distribution = $package->getRatingDistribution();
// Returns: [1 => 2, 2 => 1, 3 => 3, 4 => 5, 5 => 4]
```

### Get Approved Reviews
```php
$reviews = $package->approvedReviews()->get();
// Featured reviews shown first, then by newest
```

---

## 🛣️ API Routes

### Admin Routes (Protected: `auth` + `role:admin`)
```
GET     /admin/reviews                      List all reviews
GET     /admin/reviews/{id}                 View review details
POST    /admin/reviews/{id}/approve         Approve review
POST    /admin/reviews/{id}/reject          Reject review
POST    /admin/reviews/{id}/feature         Toggle featured status
DELETE  /admin/reviews/{id}                 Delete review
```

### Customer Routes (Protected: `auth` + `role:customer`)
```
GET     /customer/orders/{id}/review        Show review form
POST    /customer/orders/{id}/review        Submit review
POST    /customer/reviews/{id}/helpful      Vote helpful
POST    /customer/reviews/{id}/unhelpful    Vote unhelpful
```

---

## 📝 Review Validation Rules

```php
rating:    required, integer, 1-5
title:     required, string, max 255 characters
content:   required, string, 10-2000 characters
```

### Business Rules
- ✅ Only for completed orders
- ✅ One review per user per package
- ✅ Customer must own the order
- ✅ Auto-marked as verified purchase

---

## 📁 Files Created/Modified

### New Controllers
- `app/Http/Controllers/Admin/ReviewController.php`
- `app/Http/Controllers/Customer/ReviewController.php`

### New Views
- `resources/views/admin/reviews/index.blade.php`
- `resources/views/admin/reviews/show.blade.php`
- `resources/views/customer/reviews/create.blade.php`

### Database
- `database/migrations/2026_01_04_091903_update_reviews_table_add_new_fields.php`
- `database/seeders/ReviewSeeder.php` (updated)

### Documentation
- `REVIEW_SYSTEM.md` - Complete technical documentation
- `REVIEW_FEATURE_COMPLETE.md` - Implementation summary

---

## 🧪 Testing Checklist

- [ ] Customer can see "Write Review" on completed orders
- [ ] Review form loads with package information
- [ ] Star selector works with mouse hover and click
- [ ] Title and content fields accept input
- [ ] Character counters update in real-time
- [ ] Form validation shows error messages
- [ ] Admin can see all reviews in dashboard
- [ ] Filter tabs (All, Pending, Approved, Featured) work
- [ ] Admin can view review details
- [ ] Admin can approve pending reviews
- [ ] Admin can feature approved reviews
- [ ] Admin can delete reviews
- [ ] Ratings display on package pages
- [ ] Average rating calculates correctly

---

## ⚙️ Configuration Tips

### Change Reviews Per Page
Edit `app/Http/Controllers/Admin/ReviewController.php` line 14:
```php
->paginate(15)  // Change 15 to desired number
```

### Change Review Character Limits
Edit `resources/views/customer/reviews/create.blade.php`:
```blade
maxlength="255"     <!-- Title limit -->
maxlength="2000"    <!-- Content limit -->
minlength="10"      <!-- Content minimum -->
```

### Customize Star Colors
Edit view files to change `.fa-star` color classes:
```blade
<i class="fas fa-star text-warning"></i>  <!-- Filled star -->
<i class="fas fa-star text-muted"></i>    <!-- Empty star -->
```

---

## 🐛 Troubleshooting

**Q: Why doesn't my review appear after I submit it?**
- A: Reviews must be approved by admin first. Check Admin → Reviews → Pending

**Q: Can I edit my review?**
- A: Current version doesn't support editing. Delete and resubmit (if feature is available)

**Q: Why can't I review an order?**
- A: You can only review completed orders

**Q: What if I get an error about order_number?**
- A: Run `php artisan migrate` to ensure all migrations are applied

**Q: How do I approve all reviews at once?**
- A: Use admin panel and approve individually (bulk approve feature coming soon)

---

## 📈 Next Features (Coming Soon)

- Photo uploads with reviews
- Review responses from admins
- Review search and advanced filters
- Email notifications for new reviews
- Review editing and deletion by customers
- Bulk moderation actions
- Review moderation workflow
- Email reminders to review completed orders

---

## 📞 Support Resources

- **Full Documentation:** See `REVIEW_SYSTEM.md`
- **Implementation Details:** See `REVIEW_FEATURE_COMPLETE.md`
- **Code Examples:** Check controller files for usage patterns

---

## ✅ Status

**Feature Status:** ✅ Production Ready  
**Last Updated:** January 4, 2026  
**Version:** 1.0  

---

**Ready to get started?** 
1. Run migrations: `php artisan migrate`
2. Seed test data: `php artisan db:seed --class=ReviewSeeder`
3. Visit admin panel: `/admin/reviews`
4. See reviews in action!

