# 🎥 Video Gallery & Testimonials - Quick Reference

## 📍 Access Points

| Role | Feature | URL | Menu |
|------|---------|-----|------|
| Admin | Video Management | `/admin/videos` | Sidebar > Video Gallery |
| Customer | My Testimonials | `/customer/testimonials` | Sidebar > My Testimonials |
| Everyone | View Testimonials | `/` | Homepage > "Our Couples' Stories" |

---

## 🎬 Admin Video Management

### Upload Video
```
/admin/videos/package/{packageId}/create
→ Fill form → Choose type → Upload file/URL → Save
```

### Edit Video
```
/admin/videos → Manage → Click Edit → Update fields → Save
```

### Delete Video
```
/admin/videos → Click Delete icon → Confirm
```

### Reorder Videos
```
/admin/videos → Drag by grip handle → Auto-saves
```

### Toggle Active
```
/admin/videos → Click status badge → Toggles immediately
```

---

## ⭐ Customer Testimonials

### Create Testimonial
```
/customer/testimonials/create
→ Fill form → Upload video/YouTube URL → Add rating → Submit
```

### Edit Testimonial
```
/customer/testimonials → Click Edit → Update → Resubmit
```

### Delete Testimonial
```
/customer/testimonials → Click Delete → Confirm
```

---

## 📝 Database Tables

### Videos
```sql
id | package_id | title | description | type | video_path | youtube_url | thumbnail_path | is_active | order | created_at | updated_at
```

### Video Testimonials
```sql
id | user_id | order_id | title | description | type | video_path | youtube_url | thumbnail_path | rating | is_featured | is_active | views | created_at | updated_at
```

---

## 🔗 Routes Quick Map

### Admin Routes (9 routes)
```
GET    /admin/videos                           → admin.videos.index
GET    /admin/videos/package/{package}         → admin.videos.show
GET    /admin/videos/package/{package}/create  → admin.videos.create
POST   /admin/videos/package/{package}         → admin.videos.store
GET    /admin/videos/{video}/edit              → admin.videos.edit
PUT    /admin/videos/{video}                   → admin.videos.update
DELETE /admin/videos/{video}                   → admin.videos.destroy
POST   /admin/videos/{video}/toggle            → admin.videos.toggle
POST   /admin/videos/package/{package}/reorder → admin.videos.reorder
```

### Customer Routes (6 routes)
```
GET    /customer/testimonials                      → customer.testimonials.index
GET    /customer/testimonials/create               → customer.testimonials.create
POST   /customer/testimonials                      → customer.testimonials.store
GET    /customer/testimonials/{testimonial}/edit   → customer.testimonials.edit
PUT    /customer/testimonials/{testimonial}        → customer.testimonials.update
DELETE /customer/testimonials/{testimonial}        → customer.testimonials.destroy
```

---

## 💾 File Upload Specifications

### Video Files
- **Formats**: MP4, AVI, MOV, MKV
- **Max Size**: 500MB
- **Recommended**: 1920x1080 or 1280x720
- **Storage**: `/storage/videos/{packageId}/`

### Thumbnail Images
- **Formats**: JPEG, PNG, JPG
- **Max Size**: 2MB
- **Recommended**: 1280x720 pixels (16:9)
- **Storage**: `/storage/thumbnails/`

### Testimonial Videos
- **Formats**: MP4, AVI, MOV, MKV
- **Max Size**: 500MB
- **Storage**: `/storage/testimonials/{userId}/`

---

## 🎨 UI Components

### Admin Video List
```
Card Layout with:
- Thumbnail preview
- Video title & description
- Type badge (Upload/YouTube)
- Active status toggle
- Edit & Delete buttons
- Reorder handles
```

### Customer Testimonial Card
```
Grid Card with:
- Thumbnail (clickable)
- Type badge
- Title
- Star rating
- Description excerpt
- Status badge
- Edit/Delete buttons
```

### Homepage Testimonial Card
```
Grid Card with:
- Video thumbnail (lightbox trigger)
- Play button overlay
- Type badge
- Title & rating
- Description excerpt
- Customer avatar & name
- Package name
```

---

## 🔐 Security Rules

| Action | Admin | Customer | Rules |
|--------|-------|----------|-------|
| View Videos | ✅ | ❌ | Admin only |
| Create Video | ✅ | ❌ | Admin only |
| Edit Video | ✅ | ❌ | Admin only |
| Delete Video | ✅ | ❌ | Admin only |
| View Testimonials | ✅ | ✅ | Own/All |
| Create Testimonial | ❌ | ✅ | Own only |
| Edit Testimonial | ❌ | ✅ | Own only |
| Delete Testimonial | ❌ | ✅ | Own only |
| Approve Testimonial | ✅ | ❌ | Admin only |

---

## 🎯 Key Methods in Models

### Video Model
```php
$video->package          // Get package
$video->getYoutubeId()   // Extract YouTube ID
$video->getEmbedUrl()    // Get embed URL
$video->scopeActive()    // Get active videos only
```

### VideoTestimonial Model
```php
$testimonial->user                // Get user
$testimonial->order               // Get order
$testimonial->getYoutubeId()      // Extract YouTube ID
$testimonial->getEmbedUrl()       // Get embed URL
$testimonial->scopeActive()       // Get published only
$testimonial->scopeFeatured()     // Get featured only
$testimonial->incrementViews()    // Add view count
```

---

## 📊 Status Values

### Video Status
- `true` = Active/Published
- `false` = Inactive/Draft

### Testimonial Status
- `is_active: true` = Published & visible
- `is_active: false` = Pending admin approval
- `is_featured: true` = Highlighted on homepage
- `is_featured: false` = Regular testimonial

---

## 🛠️ Common Tasks

### Add video to package
```php
Video::create([
    'package_id' => 1,
    'title' => 'Wedding Highlights',
    'type' => 'upload',
    'video_path' => 'path/to/file.mp4',
    'is_active' => true,
]);
```

### Create testimonial
```php
VideoTestimonial::create([
    'user_id' => 1,
    'order_id' => 5,
    'title' => 'Our Perfect Day',
    'type' => 'youtube',
    'youtube_url' => 'https://youtube.com/watch?v=...',
    'rating' => 5,
]);
```

### Approve testimonial
```php
$testimonial->update(['is_active' => true]);
```

### Get all active testimonials
```php
$testimonials = VideoTestimonial::active()->get();
```

### Get featured testimonials
```php
$featured = VideoTestimonial::featured()->active()->get();
```

---

## 📦 Dependencies

- Laravel 10+
- Bootstrap 5.3.0
- GLightbox (CDN)
- SortableJS (CDN)
- Font Awesome 6.4.0

---

## 🎬 Media Examples

### YouTube URL Format
```
https://www.youtube.com/watch?v=VIDEO_ID
https://youtu.be/VIDEO_ID
```

### Lightbox Data Attribute
```html
<a data-glightbox data-gallery="testimonials" href="video.mp4">
    Click to play
</a>
```

---

## ⚡ Performance Tips

1. Compress videos before uploading
2. Upload thumbnails in 1280x720 resolution
3. Use YouTube for long videos (saves storage)
4. Keep descriptions under 500 characters
5. Limit homepage display to ~6 latest testimonials

---

## 🔄 Workflow

### Admin Video Upload
1. Access `/admin/videos`
2. Select package
3. Click "Add Video"
4. Choose type & upload
5. Set thumbnail & title
6. Toggle active
7. Videos appear on package page & homepage gallery

### Customer Testimonial Submission
1. Access `/customer/testimonials`
2. Click "Add Testimonial"
3. Fill form & upload video
4. Submit for review
5. Admin approves/rejects
6. Published testimonials appear on homepage

### Homepage Display
1. Testimonials section auto-loads active videos
2. Click thumbnail to play in lightbox
3. Automatic autoplay when opened
4. Close with X or click outside

---

## 🆘 Troubleshooting

| Issue | Solution |
|-------|----------|
| Video not uploading | Check file size & format |
| YouTube not showing | Verify full URL format |
| Thumbnail not displaying | Upload image file |
| Lightbox not working | Check GLightbox CDN |
| Can't reorder videos | Drag by handle (≡) icon |
| Testimonial not approved | Admin needs to approve |

---

## 📱 Mobile Compatibility

- ✅ Upload from mobile device
- ✅ Touch-friendly buttons
- ✅ Responsive video player
- ✅ Mobile-optimized forms
- ✅ Swipe-friendly lightbox

---

**Quick Reference v1.0** | Jan 5, 2026
