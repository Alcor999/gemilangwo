# Testing Checklist - Wedding Organizer Booking System

## Pre-Testing Setup

### ✅ Environment Setup
- [ ] Database migrated successfully
  ```bash
  php artisan migrate
  ```
- [ ] Midtrans keys configured in `.env`
- [ ] Server running
  ```bash
  php artisan serve
  ```
- [ ] Accessible at `http://localhost:8000`

---

## Authentication & Authorization Testing

### Registration
- [ ] New user can register via `/register`
- [ ] Default role is 'customer' after registration
- [ ] Email validation works
- [ ] Password confirmation works
- [ ] Duplicate email rejected

### Login
- [ ] User can login with email & password
- [ ] Invalid credentials rejected
- [ ] "Remember me" functionality works
- [ ] Session persists across requests
- [ ] Logout clears session

### Role-Based Access Control
- [ ] Admin cannot access `/customer/` routes
- [ ] Customer cannot access `/admin/` routes
- [ ] Owner cannot access admin/customer routes
- [ ] Unauthenticated users redirected to login
- [ ] Invalid roles get 403 error

---

## Admin Features Testing

### Dashboard
- [ ] All 4 stat cards display correctly
  - [ ] Total Orders count
  - [ ] Total Customers count
  - [ ] Total Packages count
  - [ ] Total Revenue calculated
- [ ] Recent orders table populates
- [ ] Quick action buttons work

### Package Management

#### Create Package
- [ ] Form validates all required fields
- [ ] Price input accepts decimal numbers
- [ ] Image upload works (max 2MB)
- [ ] Status dropdown has active/inactive
- [ ] Features (if implemented) can be added
- [ ] Package saved to database
- [ ] Redirect to package list after creation

#### Edit Package
- [ ] Form pre-populates with existing data
- [ ] All fields can be edited
- [ ] Image can be replaced or left unchanged
- [ ] Changes saved to database
- [ ] Redirect after successful update

#### Delete Package
- [ ] Confirmation dialog appears
- [ ] Package removed from database
- [ ] List updated after deletion
- [ ] No orphaned orders (soft delete)

#### List Packages
- [ ] All packages displayed in table
- [ ] Shows: name, price, max_guests, status, date
- [ ] Edit button works for each package
- [ ] Delete button works for each package

### Order Management

#### Orders List
- [ ] All orders displayed with pagination (15 per page)
- [ ] Shows: order_id, customer, package, date, amount, status
- [ ] Status badges display with correct colors
- [ ] Payment status shown (paid/unpaid)
- [ ] View button navigates to order detail

#### Order Details
- [ ] Customer information displays
- [ ] Event information displays
- [ ] Payment information shows (if exists)
- [ ] Status dropdown can change status
- [ ] Cancel button works for pending orders
- [ ] Cannot cancel non-pending orders

### User Management

#### Users List
- [ ] All users displayed with pagination
- [ ] Shows: name, email, phone, role, created date
- [ ] Role badge displays correctly
- [ ] View button works

#### User Detail
- [ ] User information displays
- [ ] Role can be changed via dropdown
- [ ] Cannot change own role
- [ ] Changes saved immediately
- [ ] Deactivate button removes user account

---

## Customer Features Testing

### Dashboard
- [ ] Personal statistics display
  - [ ] Total Orders count
  - [ ] Completed orders count
  - [ ] Pending orders count
- [ ] Quick action buttons
  - [ ] Browse Packages button works
  - [ ] New Booking button works
  - [ ] View All Orders button works
- [ ] Recent bookings table populated
- [ ] Correct data shown for logged-in user

### Browse Packages
- [ ] All active packages displayed
- [ ] Inactive packages hidden
- [ ] Package cards show:
  - [ ] Image (or placeholder)
  - [ ] Name
  - [ ] Description (truncated)
  - [ ] Price
  - [ ] Max guests (if set)
- [ ] "View Details & Book" button works
- [ ] Clicking package card goes to detail page

### Package Detail
- [ ] Full description visible
- [ ] Features listed (if any)
- [ ] Price displayed prominently
- [ ] Max guests shown
- [ ] "Book This Package" button works
- [ ] "Back to Packages" button works

### Create Booking

#### Form Validation
- [ ] Package dropdown required
- [ ] Event date required
- [ ] Event location required
- [ ] Guest count required (min 1)
- [ ] Event date cannot be past date
- [ ] Guest count cannot exceed package max_guests
- [ ] Error messages display clearly

#### Form Submission
- [ ] Order created with correct data
- [ ] order_number generated (WO-{timestamp})
- [ ] Status set to 'pending'
- [ ] total_price = package.price
- [ ] User redirected to order detail page
- [ ] Success message displayed

### My Orders

#### List View
- [ ] All customer's orders displayed
- [ ] Pagination works (10 per page)
- [ ] Shows: order_id, package, date, location, amount, status, payment_status
- [ ] Status badges color-coded
- [ ] Payment status shows: Paid/Unpaid/Pending
- [ ] View button works
- [ ] Pay button shows only for unpaid pending orders

### Order Detail
- [ ] Order number, date, status displayed
- [ ] Customer info correct
- [ ] Event info correct
- [ ] Payment info shows (if exists)
- [ ] Total amount calculated correctly
- [ ] Only pending orders can be cancelled
- [ ] Cancel confirmation works

### Payment

#### Payment Page
- [ ] Order details summary
- [ ] Amount to pay correct
- [ ] Midtrans Snap embed appears
- [ ] Security message displayed

#### Test Payment (Sandbox)
- [ ] Snap payment form loads
- [ ] Can select payment method
- [ ] Test card works: `4011 1111 1111 1112`
- [ ] Payment success/failure handled
- [ ] Order status updates after payment
- [ ] Redirect to finish page

#### Payment Finish
- [ ] Success message if payment succeeded
- [ ] Info message if pending
- [ ] Order detail page updated with payment info
- [ ] Cannot pay already-paid order

---

## Owner Features Testing

### Dashboard
- [ ] All 4 stat cards display
  - [ ] Total Orders
  - [ ] Total Customers
  - [ ] Completed Revenue
  - [ ] Pending Revenue
- [ ] Orders by status breakdown shows
  - [ ] Pending count
  - [ ] Confirmed count
  - [ ] In Progress count
  - [ ] Completed count
  - [ ] Cancelled count
- [ ] Recent orders table shows latest orders
- [ ] Quick links to statistics & payments work

### Statistics
- [ ] Package orders summary displays
  - [ ] Shows count of orders per package
  - [ ] Shows revenue per package
- [ ] Status summary breakdown
  - [ ] Orders by status
  - [ ] Revenue by status
- [ ] Customer retention data
  - [ ] Shows repeat customer counts
  - [ ] Sorted by order frequency

### Payments
- [ ] Payment methods breakdown
  - [ ] Shows count per method
  - [ ] Shows total amount per method
- [ ] Payment status summary
  - [ ] Shows status distribution
  - [ ] Shows amount per status
- [ ] Recent payments list
  - [ ] Shows 20 most recent
  - [ ] Links to order numbers

---

## Database Integrity Testing

### Data Relationships
- [ ] User deleted → Orders soft-deleted
- [ ] Package deleted → Orders soft-deleted (or error)
- [ ] Order deleted → Payments cascade deleted
- [ ] Order deleted → Reviews cascade deleted

### Data Validation
- [ ] Cannot create order without user
- [ ] Cannot create order without package
- [ ] Cannot create order without dates
- [ ] Cannot create payment without order
- [ ] Guest count > 0
- [ ] Price > 0
- [ ] Rating 1-5 only

---

## UI/UX Testing

### Layout & Navigation
- [ ] Sidebar visible for authenticated users
- [ ] Admin sidebar shows admin links
- [ ] Customer sidebar shows customer links
- [ ] Owner sidebar shows owner links
- [ ] Navbar user dropdown works
- [ ] Logout works from dropdown

### Responsive Design
- [ ] Desktop view: full sidebar + content
- [ ] Tablet view: collapsed navigation
- [ ] Mobile view: hamburger menu
- [ ] All tables responsive (horizontal scroll)
- [ ] Buttons clickable on mobile

### Alerts & Messages
- [ ] Success messages appear & disappear
- [ ] Error messages display clearly
- [ ] Validation errors show per field
- [ ] Flash messages persist correctly

### Forms
- [ ] All inputs have proper labels
- [ ] Required fields marked with *
- [ ] Placeholder text helpful
- [ ] Dropdown options clear
- [ ] Submit buttons clearly labeled
- [ ] Cancel buttons available

---

## Performance Testing

### Page Load Times
- [ ] Dashboard loads in < 1 second
- [ ] Package list loads in < 1 second
- [ ] Order list with pagination smooth
- [ ] Images load properly

### Database Queries
- [ ] No N+1 queries in lists
- [ ] Pagination limits results
- [ ] Soft deletes don't break queries

---

## Error Handling

### 404 Errors
- [ ] Invalid order ID → 404
- [ ] Invalid package ID → 404
- [ ] Invalid user ID → 404

### 403 Errors
- [ ] Customer accessing admin routes → 403
- [ ] Accessing other user's order → 403
- [ ] Role mismatch → 403

### 500 Errors
- [ ] Database connection error handled
- [ ] Missing config handled
- [ ] Payment service error handled

---

## Midtrans Integration Testing

### Configuration
- [ ] Server key loaded correctly
- [ ] Client key loaded correctly
- [ ] Sandbox mode enabled/disabled correctly
- [ ] Config accessible in controller

### Snap Token Generation
- [ ] Token generated successfully
- [ ] Token includes order details
- [ ] Token includes customer details
- [ ] Token includes item details

### Payment Flow
- [ ] Snap embed displays correctly
- [ ] Payment methods available
- [ ] Test card accepted in sandbox
- [ ] OTP flow works

### Webhook Handling
- [ ] Notification endpoint accessible
- [ ] Payment success updates order
- [ ] Payment failure updates order
- [ ] Webhook payload parsed correctly

---

## Edge Cases Testing

### Boundary Conditions
- [ ] Guest count = 0 rejected
- [ ] Guest count > max_guests rejected
- [ ] Event date in past rejected
- [ ] Price = 0 rejected

### Concurrent Operations
- [ ] Two users booking same package simultaneously
- [ ] Two payments for same order (second rejected)
- [ ] Admin changing order while customer viewing

### Missing Data
- [ ] Order without payment info
- [ ] Package without description
- [ ] User without phone number
- [ ] Order without special request

---

## Security Testing

### Authorization
- [ ] URL manipulation to access other users' data fails
- [ ] Direct /admin/ access without admin role fails
- [ ] Session hijacking attempt fails

### Input Validation
- [ ] SQL injection attempts fail
- [ ] XSS attempts fail
- [ ] File upload restrictions enforced
- [ ] CSRF token validated

---

## Final Checklist

### Pre-Production
- [ ] All tests passed
- [ ] No console errors
- [ ] Database backup created
- [ ] Midtrans keys verified
- [ ] Email templates setup (if applicable)
- [ ] Error logging configured
- [ ] Backup strategy documented

### Documentation
- [ ] SETUP_GUIDE.md reviewed
- [ ] QUICKSTART.md reviewed
- [ ] DATABASE_SCHEMA.md reviewed
- [ ] Code comments complete
- [ ] API documentation (if applicable)

### Ready for Deployment
- [ ] Environment variables set correctly
- [ ] Database migrations up-to-date
- [ ] Static assets compiled
- [ ] Error handling robust
- [ ] Performance acceptable

---

## Test Results Summary

| Category | Status | Notes |
|----------|--------|-------|
| Authentication | ⬜ | |
| Authorization | ⬜ | |
| Admin Features | ⬜ | |
| Customer Features | ⬜ | |
| Owner Features | ⬜ | |
| Payment Integration | ⬜ | |
| Database | ⬜ | |
| UI/UX | ⬜ | |
| Performance | ⬜ | |
| Error Handling | ⬜ | |
| Security | ⬜ | |

**Legend**: 
- ⬜ Not Started
- 🔵 In Progress
- ✅ Passed
- ❌ Failed

---

**Test Date**: _____________
**Tester Name**: _____________
**Approval**: _____________

---

**Next Steps After Testing**:
1. Fix any failed tests
2. Re-run failed test cases
3. Get approval from stakeholders
4. Deploy to production
5. Monitor for issues

Good luck! 🚀
