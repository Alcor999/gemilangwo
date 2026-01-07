# Responsive Design Patterns Reference

## Common Patterns Used in Gemilang WO

### 1. Responsive Grid Layouts

#### 4-Column Desktop Layout (Admin Dashboard Cards)
```html
<div class="row mb-4">
    <div class="col-12 col-sm-6 col-md-3">
        <!-- Content stacks on mobile, 2 cols on tablet, 4 cols on desktop -->
    </div>
</div>
```
**Breakpoints:**
- Mobile (< 576px): `col-12` = 100% width, full row
- Tablet (576-767px): `col-sm-6` = 50% width, 2 per row
- Medium (768-991px): `col-md-3` = 25% width, 4 per row
- Large (992px+): `col-md-3` = 25% width, 4 per row

#### 3-Column Product Grid (Package Cards)
```html
<div class="row">
    <div class="col-12 col-sm-6 col-lg-4 mb-4">
        <!-- Content displays 1 per row mobile, 2 tablet, 3 desktop -->
    </div>
</div>
```
**Breakpoints:**
- Mobile: 1 column (100% width)
- Tablet (576px+): 2 columns (50% width)
- Desktop (992px+): 3 columns (33% width)

---

### 2. Responsive Tables

#### Horizontal Scroll on Mobile
```html
<div class="table-responsive">
    <table class="table table-sm mb-0">
        <!-- Table scrolls horizontally on mobile -->
    </table>
</div>
```

#### Hidden Columns on Small Screens
```html
<table class="table">
    <thead>
        <tr>
            <th>Always Show</th>
            <th class="d-none d-md-table-cell">Hide on Mobile</th>
            <th class="d-none d-sm-table-cell">Hide on Extra Small</th>
        </tr>
    </thead>
</table>
```

---

### 3. Responsive Typography

#### Heading Scaling
```html
<h1 style="font-size: 2rem;">Desktop Size</h1>

<style>
    @media (max-width: 768px) {
        h1 { font-size: 1.5rem; }
    }
    @media (max-width: 576px) {
        h1 { font-size: 1.25rem; }
    }
</style>
```

#### Font Reduction for Mobile
```html
<table>
    <tr style="font-size: 0.9rem;">
        <!-- Base size 0.9rem -->
    </tr>
</table>

<style>
    @media (max-width: 576px) {
        table { font-size: 0.75rem; }
    }
</style>
```

---

### 4. Responsive Sidebar Navigation

#### Mobile-Friendly Sidebar Toggle
```html
<!-- Navbar Toggle Button (Mobile Only) -->
<button class="navbar-toggler" type="button" id="sidebarToggle">
    <span class="navbar-toggler-icon"></span>
</button>

<!-- Sidebar Navigation -->
<nav class="sidebar" id="sidebar">
    <!-- Navigation Links -->
</nav>

<style>
    .sidebar {
        position: fixed;
        left: 0;
        width: 260px;
        top: 60px;
        transition: left 0.3s ease;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            left: -100%;
            position: absolute;
        }

        .sidebar.show {
            left: 0;
        }
    }
</style>
```

#### JavaScript for Sidebar Toggle
```javascript
document.getElementById('sidebarToggle')?.addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    sidebar?.classList.toggle('show');
});

// Close sidebar when link clicked on mobile
if (window.innerWidth <= 768) {
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar?.classList.remove('show');
        });
    });
}
```

---

### 5. Responsive Button Layout

#### Stacking Buttons on Mobile
```html
<div class="d-grid gap-2 d-sm-flex flex-wrap">
    <a href="#" class="btn btn-primary btn-sm">Button 1</a>
    <a href="#" class="btn btn-success btn-sm">Button 2</a>
    <a href="#" class="btn btn-info btn-sm">Button 3</a>
</div>
```
**Behavior:**
- Mobile: Stack vertically (full width each)
- Tablet+: Display horizontally with flex layout

---

### 6. Responsive Padding & Margins

#### Adaptive Spacing
```html
<div class="card-body p-2 p-md-3">
    <!-- padding: 0.5rem on mobile, 1rem on medium+ -->
</div>

<div class="stat-card">
    <!-- Responsive size on different screens -->
</div>

<style>
    @media (max-width: 576px) {
        .stat-card {
            padding: 1rem;
            margin-bottom: 1rem;
        }
    }
</style>
```

---

### 7. Responsive Container & Viewport

#### Mobile Viewport Configuration
```html
<head>
    <!-- Critical for responsive design -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
</head>
```

#### Container Fluid
```html
<div class="container-fluid">
    <!-- Takes 100% width on all screens -->
    <!-- Better for mobile than .container -->
</div>
```

---

### 8. Responsive Forms

#### Mobile-Friendly Form Layout
```html
<div class="mb-3">
    <label for="email" class="form-label fw-600">Email Address</label>
    <div class="input-group">
        <span class="input-group-text border-0">
            <i class="fas fa-envelope"></i>
        </span>
        <input id="email" type="email" class="form-control border-0">
    </div>
</div>

<style>
    @media (max-width: 576px) {
        label {
            font-size: 0.85rem;
        }
        input {
            font-size: 0.9rem;
        }
    }
</style>
```

---

### 9. Responsive Images

#### Scaling Images with CSS
```html
<img src="image.jpg" alt="Description" style="width: 100%; height: auto;">

<!-- Or for fixed aspect ratio -->
<div style="width: 100%; padding-bottom: 66.66%; position: relative;">
    <img src="image.jpg" style="position: absolute; width: 100%; height: 100%; object-fit: cover;">
</div>
```

#### Responsive Card Images
```html
<img src="image.jpg" class="card-img-top" alt="Name" style="height: 200px; object-fit: cover;">

<style>
    @media (max-width: 576px) {
        .card-img-top {
            height: 150px !important;
        }
    }
</style>
```

---

### 10. Responsive Navbar

#### Mobile Navbar Collapse
```html
<nav class="navbar navbar-expand-lg">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <!-- Navigation items -->
        </ul>
    </div>
</nav>

<style>
    .navbar-brand {
        font-size: 1.5rem;
    }

    @media (max-width: 768px) {
        .navbar-brand {
            font-size: 1.25rem;
        }
    }
</style>
```

---

## Bootstrap Responsive Classes Quick Reference

| Class | Applies | Size |
|-------|---------|------|
| `col-12` | All sizes | 100% |
| `col-sm-*` | 576px+ | Small devices |
| `col-md-*` | 768px+ | Medium devices |
| `col-lg-*` | 992px+ | Large devices |
| `col-xl-*` | 1200px+ | Extra large |
| `col-xxl-*` | 1400px+ | XXL devices |

| Display Class | Behavior |
|---------------|----------|
| `d-none` | Always hidden |
| `d-sm-block` | Show on 576px+ |
| `d-md-table-cell` | Show as table cell on 768px+ |
| `d-lg-flex` | Show as flex on 992px+ |
| `d-grid` | Grid layout |
| `d-sm-flex` | Flex layout on 576px+ |

---

## Performance Tips for Responsive Design

1. **Mobile-First CSS**
   - Write base styles for mobile
   - Add media queries to enhance for larger screens
   - Reduces CSS payload on mobile

2. **Responsive Images**
   - Use `width: 100%` and `height: auto`
   - Use `object-fit` for consistent sizing
   - Lazy load images when possible

3. **Optimize Font Loading**
   - Use system fonts or subset Google Fonts
   - Implement font-display: swap
   - Load fonts asynchronously

4. **Touch Optimization**
   - Minimum button size: 44px x 44px
   - Adequate spacing between interactive elements
   - Touch keyboard friendly inputs

5. **Viewport Configuration**
   - Always include viewport meta tag
   - Set initial-scale to 1.0
   - Allow user zoom for accessibility

---

## Common Responsive Issues & Solutions

### Issue: Text Too Small on Mobile
```css
/* Solution: Scale font sizes responsively */
@media (max-width: 576px) {
    body { font-size: 14px; }
    h1 { font-size: 1.25rem; }
    h2 { font-size: 1.1rem; }
}
```

### Issue: Sidebar Overlapping Content
```css
/* Solution: Use different positioning for mobile */
@media (max-width: 768px) {
    .sidebar {
        position: absolute;
        left: -100%;
    }
    .main-content {
        margin-left: 0;
    }
}
```

### Issue: Table Not Scrollable on Mobile
```html
<!-- Solution: Wrap table in responsive div -->
<div class="table-responsive">
    <table class="table"><!-- ... --></table>
</div>
```

### Issue: Buttons Not Touch-Friendly
```css
/* Solution: Increase size on mobile */
@media (max-width: 576px) {
    .btn {
        padding: 0.6rem 1.2rem;
        min-height: 44px;
        min-width: 44px;
    }
}
```

---

## Testing Checklist for Responsive Design

- [ ] Viewport meta tag is set correctly
- [ ] No horizontal scrollbar on mobile
- [ ] Text is readable without zoom (16px minimum)
- [ ] Buttons are touch-friendly (44px minimum)
- [ ] Images scale properly
- [ ] Tables scroll horizontally on mobile
- [ ] Navigation accessible on all devices
- [ ] Forms work with touch keyboard
- [ ] Spacing looks good on all sizes
- [ ] No layout breaks at any breakpoint
- [ ] Performance acceptable on mobile
- [ ] Landscape orientation works
- [ ] Browser compatibility verified

---

**Reference Guide Version:** 1.0  
**Last Updated:** January 2026
