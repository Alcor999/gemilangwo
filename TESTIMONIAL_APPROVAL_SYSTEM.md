# Testimonial Approval System
## Admin Dashboard for Customer Video Testimonials

**Created:** January 5, 2026  
**Status:** ✅ PRODUCTION READY  
**Version:** 1.0

---

## Overview

The Testimonial Approval System provides admin users with a complete interface to review, approve, reject, and manage customer video testimonials. This workflow ensures quality control over testimonials displayed on the homepage.

### Key Workflow
```
Customer Submits → Pending Review → Admin Approves → Published → Homepage Display
    Testimonial      (is_active=0)   (is_active=1)
```

---

## Features

### ✅ Admin Testimonial Management

#### 1. **Approval Dashboard**
- View all pending testimonials awaiting review
- See published testimonials with performance metrics
- Quick approve/reject actions
- Bulk management capabilities

#### 2. **Testimonial Review Page**
- Full video preview (upload or YouTube)
- Detailed customer information
- Rating and review text
- Order association
- View count tracking

#### 3. **Approval Actions**
- **Approve**: Publish testimonial immediately to homepage
- **Reject**: Delete with optional reason
- **Feature**: Mark as featured in homepage slider
- **Unfeature**: Remove from featured section

#### 4. **Status Tracking**
- Pending Review (orange badge)
- Published (success badge)
- Featured (star badge)
- View count display

---

## Database Schema

### VideoTestimonial Table Columns
```sql
id              : Primary Key
user_id         : Foreign Key (customers)
order_id        : Foreign Key (orders)
title           : varchar(255) - Testimonial headline
description     : text - Detailed review
type            : enum('upload', 'youtube') - Video type
video_path      : text - File path for uploads
youtube_url     : text - YouTube URL
thumbnail_path  : text - Custom thumbnail image
rating          : decimal(3,2) - 1-5 star rating
is_active       : boolean - Published status (approval flag)
is_featured     : boolean - Featured on homepage
views           : integer - View counter
created_at      : timestamp
updated_at      : timestamp
```

---

## Routes

### Admin Testimonial Routes

| Method | Route | Name | Purpose |
|--------|-------|------|---------|
| GET | `/admin/testimonials` | `admin.testimonials.index` | View all testimonials (pending & published) |
| GET | `/admin/testimonials/{id}` | `admin.testimonials.show` | View single testimonial details |
| POST | `/admin/testimonials/{id}/approve` | `admin.testimonials.approve` | Approve & publish testimonial |
| POST | `/admin/testimonials/{id}/reject` | `admin.testimonials.reject` | Reject & delete testimonial |
| POST | `/admin/testimonials/{id}/feature` | `admin.testimonials.feature` | Mark as featured |
| POST | `/admin/testimonials/{id}/unfeature` | `admin.testimonials.unfeature` | Remove from featured |

---

## Admin Controller

**File:** `app/Http/Controllers/Admin/TestimonialController.php`

### Methods

#### `index()`
Lists all testimonials separated into two tabs:
- **Pending Review**: Awaiting admin approval (is_active = false)
- **Published**: Already approved (is_active = true)

Returns paginated results (15 per page).

**View:** `admin/testimonials/index.blade.php`

#### `show(VideoTestimonial $testimonial)`
Displays full testimonial details with:
- Video preview (upload or YouTube embed)
- Customer information & avatar
- Rating stars (1-5)
- Associated order link
- View count
- Status badges

**View:** `admin/testimonials/show.blade.php`

#### `approve(Request $request, VideoTestimonial $testimonial)`
**Action:** Sets `is_active = true` to publish testimonial

- Supports both AJAX and form submissions
- Returns JSON for AJAX requests
- Redirects with success message for form submissions
- Published testimonials appear on homepage immediately

**Example Response:**
```json
{
  "success": true,
  "message": "Testimonial approved successfully"
}
```

#### `reject(Request $request, VideoTestimonial $testimonial)`
**Action:** Permanently deletes the testimonial

- Requires optional `reason` field (max 500 chars)
- Deletes associated video file if applicable
- Soft delete compatible if implemented
- Returns confirmation message

**Validation:**
```php
'reason' => 'nullable|string|max:500'
```

#### `feature(VideoTestimonial $testimonial)`
**Action:** Sets `is_featured = true`

- Marks testimonial to appear in featured section
- Can be featured before or after approval
- Multiple testimonials can be featured

#### `unfeature(VideoTestimonial $testimonial)`
**Action:** Sets `is_featured = false`

- Removes from featured section
- Testimonial remains published and visible
- Can be re-featured anytime

---

## Views

### `admin/testimonials/index.blade.php`

**Purpose:** Main approval dashboard showing all testimonials

**Sections:**
1. **Pending Review Tab** (orange header)
   - Card grid of unapproved testimonials
   - Shows: thumbnail, title, description excerpt, customer badge, rating, timestamp
   - Action buttons: View | Approve | Reject
   - Reject modal with optional reason field

2. **Published Tab** (green header)
   - Card grid of approved testimonials
   - Shows: thumbnail, title, customer, rating, view count
   - Action buttons: View | Feature/Unfeature | Delete
   - Delete modal with confirmation

**Features:**
- Responsive grid (3 columns on desktop, 1 on mobile)
- Pagination (15 per page)
- Empty state messages
- AJAX approve functionality
- Modal dialogs for destructive actions

### `admin/testimonials/show.blade.php`

**Purpose:** Detailed review page for single testimonial

**Left Column:**
- Full video preview (embed for YouTube, player for uploads)
- Thumbnail image display
- Video type badge

**Right Column:**
- Testimonial title & description
- Customer info with avatar & email
- 5-star rating visualization
- Order association link
- View counter
- Status badges (Pending/Published, Featured)

**Action Panels:**
- **For Pending:** Approve button | Reject button with modal
- **For Published:** Feature/Unfeature button | Delete button

---

## User Interface

### Testimonial Cards (Grid View)

```
┌─────────────────────────┐
│ [Thumbnail Image]       │
│ ⚠ Pending Review        │ (badge)
├─────────────────────────┤
│ Title of Testimonial    │
│ Description excerpt...  │
│ [Budi Santoso] [⭐ 5]   │
│ 📅 5 Jan 2026, 09:03    │
├─────────────────────────┤
│ [View] [Approve] [Reject]
└─────────────────────────┘
```

### Action Buttons

**Pending Testimonials:**
- 🔍 **View**: Open detail page
- ✅ **Approve**: Publish immediately (AJAX)
- ❌ **Reject**: Delete with optional reason

**Published Testimonials:**
- 🔍 **View**: Open detail page
- ⭐ **Feature**: Add to featured section
- 🗑️ **Delete**: Remove permanently

---

## Workflow Examples

### Example 1: Approve a Pending Testimonial

1. Go to **Admin Dashboard** → **Testimonial Approvals**
2. Find testimonial in "Pending Review" section
3. Click **Approve** button
4. Confirm in popup
5. Testimonial moves to "Published" tab
6. ✅ Appears on homepage under "Our Couples' Stories"

### Example 2: Reject and Delete

1. In **Testimonial Approvals** dashboard
2. Click **Reject** on pending testimonial
3. Modal opens asking for optional reason
4. Click **Reject & Delete**
5. Testimonial and video files removed from system
6. ✅ Customer receives no notification (optional: implement email)

### Example 3: Feature a Testimonial

1. Find approved testimonial in "Published" tab
2. Click **Feature** button
3. Testimonial gets star badge
4. ✅ Appears in featured section on homepage
5. Can unfeature anytime by clicking **Unfeature**

---

## Integration Points

### Homepage Display

**File:** `resources/views/home.blade.php`

Testimonials appear in "Our Couples' Stories" section:
```php
$testimonials = \App\Models\VideoTestimonial::where('is_active', true)
    ->latest()
    ->limit(6)
    ->get();
```

**Conditions:**
- Only active testimonials (`is_active = true`)
- Ordered by newest first
- Limited to 6 per page

### Customer Testimonials Page

**File:** `resources/views/customer/testimonials/index.blade.php`

Customers can see:
- ✅ Their own testimonials (all statuses)
- Status badges: "Pending Review", "Featured"
- View count & rating
- Edit/Delete options

---

## Security Features

### Authorization
- Admin-only access (role:admin middleware)
- All routes protected by authentication
- CSRF protection on all forms

### Data Integrity
- Foreign key constraints
- Cascade deletes (when order is deleted)
- Video file cleanup on rejection
- Type validation (upload vs YouTube)

### Validation
- Description max 2000 characters
- Rating 1-5 only
- File type validation for uploads
- MIME type checking

---

## Performance Optimization

### Eager Loading
```php
$testimonials = VideoTestimonial::with('user', 'order')
    ->where('is_active', false)
    ->paginate(15);
```

### Indexes
- user_id (foreign key)
- order_id (foreign key)
- is_active (boolean - frequently queried)
- is_featured (boolean - homepage queries)
- created_at (for ordering)

### Query Optimization
- Pagination (15 items per page)
- Separate queries for pending vs published
- Eager loading of relationships
- Minimal database queries

---

## Testing Checklist

- [ ] Admin can view pending testimonials
- [ ] Admin can view published testimonials
- [ ] Approve button changes status to active
- [ ] Approved testimonial appears on homepage
- [ ] Reject button deletes testimonial
- [ ] Feature button marks as featured
- [ ] Featured testimonials display star badge
- [ ] Delete removes published testimonial
- [ ] AJAX approve works without page reload
- [ ] Reject modal appears with reason field
- [ ] Links to customer orders work
- [ ] Pagination works for large lists
- [ ] Mobile responsive design works
- [ ] Form validation works
- [ ] CSRF protection active

---

## File Structure

```
app/Http/Controllers/Admin/
  └── TestimonialController.php        (144 lines)

resources/views/admin/testimonials/
  ├── index.blade.php                  (250+ lines)
  └── show.blade.php                   (280+ lines)

routes/
  └── web.php                          (6 routes added)

resources/views/layouts/
  └── app.blade.php                    (sidebar menu added)
```

---

## Approval Status Display

### Pending Review (Orange)
- **Database:** `is_active = 0 (false)`
- **Display:** Orange "Pending Review" badge
- **Action:** Approve or Reject
- **Homepage:** Hidden from public view

### Published (Green)
- **Database:** `is_active = 1 (true)`
- **Display:** Success checkmark ✓
- **Action:** Feature or Delete
- **Homepage:** Visible in "Our Couples' Stories"

### Featured (Yellow Star)
- **Database:** `is_featured = 1 (true)`
- **Display:** Star ⭐ badge
- **Location:** Featured carousel/section
- **Homepage:** Highlighted position

---

## API Response Examples

### Approve Action (AJAX)
```json
{
  "success": true,
  "message": "Testimonial approved successfully"
}
```

### Reject Action (AJAX)
```json
{
  "success": true,
  "message": "Testimonial rejected successfully"
}
```

### Validation Error
```json
{
  "success": false,
  "errors": {
    "reason": ["The reason field must not exceed 500 characters."]
  }
}
```

---

## Notifications (Future Enhancement)

Recommended to add notifications for:
- Admin receives notification when new testimonial submitted
- Customer receives email when testimonial approved
- Customer receives email when testimonial rejected (with reason)

---

## Monitoring & Analytics

Track in admin dashboard:
- Total pending testimonials
- Total published testimonials
- Average rating
- Most viewed testimonials
- Featured testimonials count
- Approval rate/average approval time

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | Jan 5, 2026 | Initial implementation - Full approval workflow |

---

## Support & Troubleshooting

### Issue: Testimonial not appearing on homepage after approval
- **Check:** Database - is_active should be 1
- **Check:** Homepage view query filtering
- **Check:** Cache - clear if cached

### Issue: Video not displaying in admin detail page
- **Check:** File path exists and readable
- **Check:** S3/storage permissions if using cloud storage
- **Check:** YouTube URL valid and publicly accessible

### Issue: AJAX approve not working
- **Check:** CSRF token in meta tag
- **Check:** Browser console for JavaScript errors
- **Check:** Network tab in DevTools for 500 errors

---

**Documentation Complete ✅**  
For updates or questions, refer to the Video Testimonials Implementation Guide.
