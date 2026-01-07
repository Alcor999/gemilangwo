# Review & Rating System - Implementation Guide

## Overview

The Review & Rating System is a comprehensive feature that enables customers to submit ratings and reviews for completed orders, while allowing admins to moderate and feature testimonials on the platform.

**Key Features:**
- ⭐ 1-5 star rating system
- 📝 Detailed review content with title and description
- ✅ Admin moderation and approval system
- 🌟 Featured testimonials for social proof
- 👍 Helpful/Unhelpful voting mechanism
- 🔐 Verified purchase badges
- 📊 Rating statistics and distribution charts

---

## Database Schema

### Reviews Table Structure

```sql
CREATE TABLE reviews (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL (FK to users),
    package_id BIGINT UNSIGNED (FK to packages),
    order_id BIGINT UNSIGNED NOT NULL (FK to orders),
    rating INT (1-5),
    title VARCHAR(255),
    content TEXT,
    comment TEXT (legacy field),
    helpful_count INT DEFAULT 0,
    unhelpful_count INT DEFAULT 0,
    is_verified BOOLEAN DEFAULT false,
    is_approved BOOLEAN DEFAULT false,
    is_featured BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP (soft delete)
);

-- Indexes
UNIQUE KEY unique_user_package (user_id, package_id)
INDEX idx_package_approved_rating (package_id, is_approved, rating)
```

### Field Descriptions

| Field | Type | Purpose |
|-------|------|---------|
| `user_id` | FK | Customer who submitted the review |
| `package_id` | FK | Wedding package being reviewed |
| `order_id` | FK | Associated order for the package |
| `rating` | INT (1-5) | Star rating from 1 to 5 |
| `title` | VARCHAR(255) | Short review headline |
| `content` | TEXT | Detailed review text (10-2000 chars) |
| `helpful_count` | INT | Number of helpful votes |
| `unhelpful_count` | INT | Number of unhelpful votes |
| `is_verified` | BOOLEAN | Whether purchase is verified |
| `is_approved` | BOOLEAN | Admin has approved the review |
| `is_featured` | BOOLEAN | Display as testimonial on homepage |

---

## Models

### Review Model (`app/Models/Review.php`)

**Relationships:**
```php
$review->user()        // User who submitted review
$review->package()     // Package being reviewed
$review->order()       // Associated order
```

**Key Methods:**

```php
// Get average helpful/unhelpful percentage
$review->getAverageHelpfulness()  // Returns percentage

// Get visual star representation
$review->getStarDisplay()         // Returns star HTML

// Get approved reviews (scope)
Review::approved()->get()

// Get featured reviews (scope)
Review::featured()->get()
```

**Attributes:**
```php
$fillable = [
    'user_id',
    'package_id',
    'order_id',
    'rating',
    'title',
    'content',
    'helpful_count',
    'unhelpful_count',
    'is_verified',
    'is_approved',
    'is_featured'
];
```

### Package Model (`app/Models/Package.php`)

**New Methods for Rating Display:**

```php
// Get all reviews for package
$package->reviews()

// Get only approved reviews (with priority to featured)
$package->approvedReviews()

// Get average rating (0-5)
$package->getAverageRating()  // Returns float

// Get rating distribution
$package->getRatingDistribution()  // Returns array [1=>count, 2=>count, ...]

// Get total approved reviews count
$package->getTotalReviews()  // Returns integer
```

---

## Controllers

### Admin ReviewController (`app/Http/Controllers/Admin/ReviewController.php`)

**Routes & Methods:**

| Method | Route | Purpose |
|--------|-------|---------|
| GET | `/admin/reviews` | List all reviews with pagination |
| GET | `/admin/reviews/{review}` | View review details |
| POST | `/admin/reviews/{review}/approve` | Approve a review |
| POST | `/admin/reviews/{review}/reject` | Reject and delete review |
| POST | `/admin/reviews/{review}/feature` | Toggle featured status |
| DELETE | `/admin/reviews/{review}` | Delete approved review |

**Features:**
- Paginated list (15 per page)
- Eager-load related data (user, package, order)
- Status tracking (pending/approved)
- Featured testimonial management

### Customer ReviewController (`app/Http/Controllers/Customer/ReviewController.php`)

**Routes & Methods:**

| Method | Route | Purpose |
|--------|-------|---------|
| GET | `/customer/orders/{order}/review` | Show review form |
| POST | `/customer/orders/{order}/review` | Submit review |
| POST | `/customer/reviews/{review}/helpful` | Vote helpful (AJAX) |
| POST | `/customer/reviews/{review}/unhelpful` | Vote unhelpful (AJAX) |

**Business Logic:**
- Only allow reviews for completed orders
- Prevent duplicate reviews (one per user per package)
- Auto-mark as verified purchase
- Validate rating (1-5) and content (10-2000 chars)

---

## Views

### Admin Views

#### 1. Reviews Index (`resources/views/admin/reviews/index.blade.php`)

**Features:**
- Dashboard stats (Total, Pending, Approved, Featured)
- Filter tabs (All, Pending, Approved, Featured)
- Responsive table with review data
- Quick action buttons (View, Approve, Reject, Feature, Delete)
- Pagination support
- Star rating display with badge

**Key Elements:**
```
Header: Admin stats cards
Filter Tabs: Quick filter navigation
Table Columns:
  - Customer (avatar + name + email)
  - Package name
  - Rating (stars + numeric)
  - Title & content preview
  - Approval status
  - Featured badge
  - Action buttons
```

#### 2. Review Detail (`resources/views/admin/reviews/show.blade.php`)

**Features:**
- Full review content display
- Customer information card
- Package details card
- Order information card
- Helpfulness statistics
- Moderation action buttons
- Links to customer and order pages

**Sections:**
```
Main Content:
  - Review title
  - Star rating with badge
  - Approval & verification status
  - Full review text
  - Helpful/unhelpful counts

Sidebar:
  - Customer profile
  - Package info
  - Order details
  - Action buttons (approve/feature/delete)
```

### Customer Views

#### Review Submission Form (`resources/views/customer/reviews/create.blade.php`)

**Features:**
- Order & package context display
- Interactive 5-star rating widget
- Title input (max 255 chars) with counter
- Content textarea (10-2000 chars) with counter
- Helpful tips for writing reviews
- Validation feedback
- Submit/Cancel buttons

**Form Fields:**
```
Rating:     5-star interactive selector
Title:      Text input (max 255)
Content:    Textarea (10-2000 chars)
```

**Styling:**
- Purple to pink gradient header
- Interactive star hover effects
- Real-time character counters
- Bootstrap form validation

---

## User Workflows

### Customer Review Submission

1. Customer completes an order
2. Navigates to order details page
3. Clicks "Write Review" button
4. Views review form with package context
5. Selects 1-5 stars (required)
6. Enters review title (max 255 chars)
7. Writes detailed review (10-2000 chars)
8. Reads helpful tips for good reviews
9. Submits review
10. Review awaits admin approval
11. Receives flash message confirming submission

### Admin Review Moderation

1. Admin logs in and navigates to Reviews
2. Views dashboard with key statistics
3. Filters pending reviews
4. Reviews details and customer info
5. Either approves or rejects review
6. If approved, can feature for testimonials
7. Can delete already-approved reviews
8. Receives success confirmation

---

## API Endpoints

### Admin Endpoints (Protected by `auth` + `role:admin`)

```
GET    /admin/reviews                    → ReviewController@index
GET    /admin/reviews/{review}           → ReviewController@show
POST   /admin/reviews/{review}/approve   → ReviewController@approve
POST   /admin/reviews/{review}/reject    → ReviewController@reject
POST   /admin/reviews/{review}/feature   → ReviewController@feature
DELETE /admin/reviews/{review}           → ReviewController@destroy
```

### Customer Endpoints (Protected by `auth` + `role:customer`)

```
GET    /customer/orders/{order}/review         → ReviewController@create
POST   /customer/orders/{order}/review         → ReviewController@store
POST   /customer/reviews/{review}/helpful      → ReviewController@markHelpful
POST   /customer/reviews/{review}/unhelpful    → ReviewController@markUnhelpful
```

---

## Validation Rules

### Review Submission

```php
[
    'rating' => 'required|integer|min:1|max:5',
    'title' => 'required|string|max:255',
    'content' => 'required|string|min:10|max:2000'
]
```

### Business Rules

```php
// Only allow if:
✓ User owns the order
✓ Order status is 'completed'
✓ User hasn't already reviewed this package
✓ Review is not empty

// Enforce:
✓ Unique constraint: (user_id, package_id)
✓ Rating range: 1-5
✓ Content length: 10-2000 characters
✓ One review per user per package
```

---

## Migration Information

### Migration File

**File:** `database/migrations/2026_01_04_091903_update_reviews_table_add_new_fields.php`

**Actions:**
- Add `package_id` FK column
- Add `title` string column
- Add `content` text column
- Add `helpful_count` integer (default 0)
- Add `unhelpful_count` integer (default 0)
- Add `is_verified` boolean (default false)
- Add `is_approved` boolean (default false)
- Add `is_featured` boolean (default false)
- Add unique index on (user_id, package_id)
- Add composite index on (package_id, is_approved, rating)

**Run Migration:**
```bash
php artisan migrate
```

---

## Seeding Sample Data

### ReviewSeeder (`database/seeders/ReviewSeeder.php`)

Includes 5 sample reviews with:
- Different star ratings (2-5 stars)
- Authentic Indonesian review content
- Mix of approved/pending status
- Featured testimonials
- Helpful/unhelpful vote counts

**Run Seeder:**
```bash
php artisan db:seed --class=ReviewSeeder
# or
php artisan migrate:fresh --seed
```

---

## Display on Package Pages

### Show Package Rating

```blade
<!-- In package detail/show page -->
@if($package->getTotalReviews() > 0)
    <div class="rating-summary">
        <div class="rating-stars">
            @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star {{ $i <= $package->getAverageRating() ? 'filled' : 'empty' }}"></i>
            @endfor
        </div>
        <span class="rating-value">{{ $package->getAverageRating() }}/5</span>
        <span class="review-count">({{ $package->getTotalReviews() }} reviews)</span>
    </div>
@endif
```

### Display Approved Reviews

```blade
<!-- Show approved reviews on package page -->
<div class="reviews-section">
    @forelse($package->approvedReviews()->take(5) as $review)
        <div class="review-card">
            <h5>{{ $review->title }}</h5>
            <div class="rating">{{ $review->rating }}/5</div>
            <p>{{ $review->content }}</p>
            <footer>{{ $review->user->name }} • {{ $review->created_at->format('M d, Y') }}</footer>
        </div>
    @empty
        <p>No reviews yet. Be the first to review!</p>
    @endforelse
</div>
```

---

## Testing

### Manual Testing Checklist

- [ ] Customer can submit review for completed order
- [ ] Review title limited to 255 characters
- [ ] Review content limited to 2000 characters, minimum 10
- [ ] Rating must be 1-5
- [ ] Customer prevented from reviewing incomplete orders
- [ ] Customer prevented from submitting duplicate reviews
- [ ] Admin can view all reviews with pagination
- [ ] Admin can approve pending reviews
- [ ] Admin can reject reviews
- [ ] Admin can feature approved reviews
- [ ] Admin can delete reviews
- [ ] Featured reviews display on package pages
- [ ] Helpful/unhelpful votes increment correctly
- [ ] Rating statistics calculated correctly
- [ ] Rating distribution chart shows correct counts

### Test Commands

```bash
# Run tests
php artisan test

# Seed test data
php artisan migrate:fresh --seed --class=ReviewSeeder

# Create admin account
php artisan tinker
> User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password'), 'role' => 'admin'])

# Create customer account
> User::create(['name' => 'Customer', 'email' => 'customer@test.com', 'password' => bcrypt('password'), 'role' => 'customer'])
```

---

## Performance Considerations

### Optimizations Applied

1. **Eager Loading:** Models eager-load relationships to prevent N+1 queries
2. **Indexing:** Composite indexes on (package_id, is_approved, rating)
3. **Pagination:** Admin list uses 15 per page limit
4. **Caching:** Consider caching rating statistics for homepage

### Query Examples

```php
// Efficient: Uses indexes
Review::where('package_id', 1)
    ->where('is_approved', true)
    ->orderBy('is_featured', 'desc')
    ->orderBy('created_at', 'desc')
    ->get();

// Efficient: Eager loads relationships
Review::with('user', 'package', 'order')->paginate(15);

// Consider caching this:
Cache::rememberForever("package_{$id}_stats", function() {
    return [
        'average_rating' => $package->getAverageRating(),
        'total_reviews' => $package->getTotalReviews(),
        'distribution' => $package->getRatingDistribution()
    ];
});
```

---

## Future Enhancements

Potential features to add:

1. **Review Images/Attachments** - Allow customers to upload photos with reviews
2. **Review Filters** - Filter by rating, helpful votes, date range
3. **Review Responses** - Allow admins to respond to reviews
4. **Verified Badge Animation** - Add verified purchase badge styling
5. **Email Notifications** - Notify admin of pending reviews
6. **Review Moderation Workflow** - Multi-step approval process
7. **Review Highlight** - Highlight most helpful reviews
8. **Rich Text Editor** - Allow formatted text in review content
9. **Review Analytics** - Dashboard showing review trends
10. **Review Email Reminder** - Remind customers to review after delivery

---

## Troubleshooting

### Common Issues

**Issue:** "You already reviewed this package"
- **Cause:** Unique constraint on (user_id, package_id)
- **Solution:** Check for existing reviews, allow user to edit instead

**Issue:** Review not appearing after approval
- **Cause:** Package page filtering by is_approved = true
- **Solution:** Verify review is_approved status in database

**Issue:** Rating not calculating correctly
- **Cause:** Including unapproved reviews in calculation
- **Solution:** Use approvedReviews() scope instead of all reviews

**Issue:** Migration fails with "Column already exists"
- **Cause:** Column already added in previous migration
- **Solution:** Check Schema::hasColumn() before adding

---

## File Locations

```
app/
  Models/
    Review.php               ← Review model with methods
    Package.php              ← Updated with rating methods
  Http/
    Controllers/
      Admin/
        ReviewController.php      ← Admin review management
      Customer/
        ReviewController.php      ← Customer review submission

resources/
  views/
    admin/
      reviews/
        index.blade.php           ← Review moderation list
        show.blade.php            ← Review detail & actions
    customer/
      reviews/
        create.blade.php          ← Review submission form

database/
  migrations/
    *_update_reviews_table_add_new_fields.php  ← Schema update
  seeders/
    ReviewSeeder.php         ← Sample review data

routes/
  web.php                    ← All routes configured
```

---

## Related Documentation

- [Package Model Documentation](PACKAGE_MODEL.md)
- [Order System](ORDER_SYSTEM.md)
- [Admin Dashboard](DASHBOARD_ADMIN_FINAL_SUMMARY.md)
- [User Roles & Permissions](USER_ROLES.md)

---

**Last Updated:** January 4, 2026
**Version:** 1.0 (Initial Implementation)
**Status:** ✅ Production Ready

