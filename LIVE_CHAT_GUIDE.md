# Live Chat & Support System - Implementation Guide

## Overview

The Live Chat & Support System is a comprehensive customer support solution integrated into the Gemilang WO platform. It allows customers to create support tickets, communicate with administrators through real-time messaging, and track the status of their issues.

**Status:** ✅ Production Ready (v1.0)

## Features

### Customer Features
- **Ticket Creation**: Create support tickets with subject, category, priority, and detailed description
- **Related Order**: Link tickets to specific orders for context
- **Real-time Chat**: Send and receive messages from support staff
- **Ticket Tracking**: View all tickets with status, priority, and message count
- **Automatic Notifications**: SMS/WhatsApp notifications on ticket updates
- **Status Visibility**: Track ticket progress through multiple status stages

### Admin Features
- **Dashboard Statistics**: View open, in-progress, resolved, and closed ticket counts
- **Urgent Ticket Alert**: See count of urgent tickets at a glance
- **Advanced Filtering**: Filter by status, priority, category, and search terms
- **Ticket Assignment**: Assign tickets to specific team members
- **Status Management**: Update ticket status with automatic timestamp tracking
- **Internal Notes**: Add private notes visible only to team members
- **Real-time Responses**: Send messages to customers instantly
- **Message Polling**: 3-second polling for new messages (upgradeable to WebSockets)

## Database Schema

### Support Tickets Table
```sql
CREATE TABLE support_tickets (
    id BIGINT PRIMARY KEY AUTO_INCREMENT
    user_id BIGINT NOT NULL FOREIGN KEY (users)
    assigned_to BIGINT NULLABLE FOREIGN KEY (users)
    order_id BIGINT NULLABLE FOREIGN KEY (orders)
    subject VARCHAR(255) NOT NULL
    description LONGTEXT NOT NULL
    category ENUM('general', 'order', 'payment', 'complaint', 'suggestion', 'other')
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium'
    status ENUM('open', 'in_progress', 'waiting_customer', 'resolved', 'closed') DEFAULT 'open'
    internal_notes LONGTEXT NULLABLE
    response_count INT DEFAULT 0
    first_response_at TIMESTAMP NULLABLE
    resolved_at TIMESTAMP NULLABLE
    closed_at TIMESTAMP NULLABLE
    created_at TIMESTAMP
    updated_at TIMESTAMP
    
    KEY user_id
    KEY assigned_to
    KEY status
    KEY priority
    KEY category
    KEY created_at
)
```

### Chat Messages Table
```sql
CREATE TABLE chat_messages (
    id BIGINT PRIMARY KEY AUTO_INCREMENT
    support_ticket_id BIGINT NOT NULL FOREIGN KEY (support_tickets)
    sender_id BIGINT NOT NULL FOREIGN KEY (users)
    message LONGTEXT NOT NULL
    sender_type ENUM('customer', 'admin') DEFAULT 'customer'
    attachments JSON NULLABLE
    is_read BOOLEAN DEFAULT false
    read_at TIMESTAMP NULLABLE
    created_at TIMESTAMP
    updated_at TIMESTAMP
    
    KEY support_ticket_id
    KEY sender_id
    KEY is_read
    KEY created_at
)
```

## Models

### SupportTicket Model
Location: `app/Models/SupportTicket.php`

**Relationships:**
- `user()` - The customer who created the ticket
- `assignedTo()` - The admin assigned to handle the ticket
- `order()` - Optional related order
- `messages()` - All chat messages in the ticket

**Key Methods:**
```php
// Add message to ticket
$ticket->addMessage($userId, $message, $senderType = 'customer');

// Change ticket status
$ticket->markAsResolved();
$ticket->markAsClosed();

// Manage assignment
$ticket->assign($adminId);
$ticket->unassign();

// Get unread message count
$unreadCount = $ticket->getUnreadCount();

// Mark messages as read
$ticket->markMessagesAsRead();
```

**Scopes:**
- `open()` - Tickets with status = open
- `inProgress()` - Tickets with status = in_progress
- `resolved()` - Tickets with status = resolved
- `closed()` - Tickets with status = closed
- `ofCategory($category)` - Filter by category
- `ofPriority($priority)` - Filter by priority
- `urgent()` - Tickets with priority = urgent
- `unassigned()` - Tickets with no admin assigned
- `byCustomer($userId)` - Tickets by specific customer

### ChatMessage Model
Location: `app/Models/ChatMessage.php`

**Relationships:**
- `ticket()` - Parent support ticket
- `sender()` - User who sent the message

**Key Methods:**
```php
// Mark message as read
$message->markAsRead();

// Format timestamps
$formattedTime = $message->getFormattedTime();
$formattedDate = $message->getFormattedDate();
```

**Scopes:**
- `unread()` - Messages not yet read
- `read()` - Messages that have been read
- `fromCustomer()` - Messages sent by customers
- `fromAdmin()` - Messages sent by admins

## Controllers

### Customer\SupportTicketController
Location: `app/Http/Controllers/Customer/SupportTicketController.php`

**Methods:**

| Method | Route | Description |
|--------|-------|-------------|
| `index()` | GET /customer/support/tickets | List user's tickets (paginated) |
| `create()` | GET /customer/support/tickets/create | Show create ticket form |
| `store()` | POST /customer/support/tickets | Create new ticket |
| `show()` | GET /customer/support/tickets/{id} | Display ticket with chat |
| `addMessage()` | POST /customer/support/tickets/{id}/messages | Add message to ticket (AJAX) |
| `close()` | POST /customer/support/tickets/{id}/close | Close ticket |
| `getNewMessages()` | GET /customer/support/tickets/{id}/messages | Get new messages (AJAX polling) |

**Features:**
- Authorization checks (customer can only access own tickets)
- Form validation with detailed error messages
- Automatic pagination (15 items per page)
- AJAX real-time message updates

### Admin\SupportController
Location: `app/Http/Controllers/Admin/SupportController.php`

**Methods:**

| Method | Route | Description |
|--------|-------|-------------|
| `index()` | GET /admin/support/tickets | List all tickets with stats |
| `show()` | GET /admin/support/tickets/{id} | Display ticket detail view |
| `assign()` | PATCH /admin/support/tickets/{id}/assign | Assign to admin |
| `updateStatus()` | PATCH /admin/support/tickets/{id}/status | Update ticket status |
| `addMessage()` | POST /admin/support/tickets/{id}/messages | Send admin response (AJAX) |
| `addNotes()` | PATCH /admin/support/tickets/{id}/notes | Update internal notes |
| `getNewMessages()` | GET /admin/support/tickets/{id}/messages | Get new messages (AJAX polling) |
| `recentTickets()` | GET /admin/support/recent-tickets | Dashboard widget data |

**Features:**
- Dashboard statistics (open, in_progress, resolved, closed, urgent counts)
- Advanced filtering by status, priority, category
- Search by subject and ticket ID
- Admin assignment with dropdown
- Real-time message polling
- Internal notes for team collaboration
- Widget for dashboard integration

## Routes

### Customer Routes
```php
// Get customer's support tickets
GET /customer/support/tickets
name: support.tickets.index

// Create new ticket form
GET /customer/support/tickets/create
name: support.tickets.create

// Store new ticket
POST /customer/support/tickets
name: support.tickets.store

// View ticket with chat
GET /customer/support/tickets/{supportTicket}
name: support.tickets.show

// Send message (AJAX)
POST /customer/support/tickets/{supportTicket}/messages
name: support.tickets.addMessage

// Close ticket
POST /customer/support/tickets/{supportTicket}/close
name: support.tickets.close

// Fetch new messages (AJAX polling)
GET /customer/support/tickets/{supportTicket}/messages
name: support.tickets.getNewMessages
```

### Admin Routes
```php
// List all tickets with stats
GET /admin/support/tickets
name: admin.support.tickets.index

// View ticket detail
GET /admin/support/tickets/{supportTicket}
name: admin.support.tickets.show

// Assign to admin
PATCH /admin/support/tickets/{supportTicket}/assign
name: admin.support.tickets.assign

// Update status
PATCH /admin/support/tickets/{supportTicket}/status
name: admin.support.tickets.updateStatus

// Send response (AJAX)
POST /admin/support/tickets/{supportTicket}/messages
name: admin.support.tickets.addMessage

// Update internal notes
PATCH /admin/support/tickets/{supportTicket}/notes
name: admin.support.tickets.addNotes

// Fetch new messages (AJAX polling)
GET /admin/support/tickets/{supportTicket}/messages
name: admin.support.tickets.getNewMessages

// Dashboard widget data
GET /admin/support/recent-tickets
name: admin.support.recentTickets
```

## Views

### Customer Views

#### Ticket List (`resources/views/customer/support/tickets/index.blade.php`)
Displays all customer's support tickets in a paginated table with:
- Ticket ID
- Subject
- Category badge
- Priority badge
- Status badge
- Message count
- Creation date
- View action button

#### Create Ticket (`resources/views/customer/support/tickets/create.blade.php`)
Form for creating new support ticket with:
- Subject input
- Category dropdown
- Priority dropdown
- Optional related order selection
- Description textarea
- Success/error validation messages
- Tips and guidelines

#### Ticket Chat (`resources/views/customer/support/tickets/show.blade.php`)
Main chat interface with:
- Message display area with auto-scroll
- Message input form
- Sidebar with ticket information:
  - Status, priority, category
  - Assigned admin (if any)
  - Timestamps
  - Related order link
  - Close ticket button
- Real-time message polling (3 seconds)

### Admin Views

#### Ticket Dashboard (`resources/views/admin/support/tickets/index.blade.php`)
Admin dashboard showing:
- Statistics cards (open, in-progress, urgent, total)
- Advanced filtering form:
  - Search by subject/ID
  - Filter by status
  - Filter by priority
  - Filter by category
- Paginated tickets table with:
  - Ticket ID
  - Subject and message count
  - Customer name and email
  - Category, priority, status badges
  - Assigned admin
  - View action

#### Ticket Detail (`resources/views/admin/support/tickets/show.blade.php`)
Admin chat interface with:
- Chat area with message history
- Message input form
- Right sidebar with:
  - Ticket information
  - Status dropdown with update button
  - Priority display
  - Admin assignment dropdown
  - Internal notes textarea
  - Related order link
- Real-time message polling

## Usage Examples

### Create a Support Ticket (Customer)

```php
// POST /customer/support/tickets
$validated = request()->validate([
    'subject' => 'required|string|max:255',
    'category' => 'required|in:general,order,payment,complaint,suggestion,other',
    'priority' => 'required|in:low,medium,high,urgent',
    'description' => 'required|string|min:20',
    'order_id' => 'nullable|exists:orders,id',
]);

$ticket = auth()->user()->supportTickets()->create($validated);
// Returns: 201 Created with ticket details
```

### Send Message

```php
// POST /customer/support/tickets/{id}/messages
$message = $ticket->addMessage(
    userId: auth()->id(),
    message: 'Saya memiliki pertanyaan tentang layanan...',
    senderType: 'customer'
);

// Response (AJAX JSON)
{
    "success": true,
    "message": "Pesan terkirim",
    "messageId": 1
}
```

### Get New Messages (Polling)

```php
// GET /customer/support/tickets/{id}/messages
// Response (AJAX JSON)
{
    "messages": [
        {
            "id": 1,
            "sender_id": 2,
            "sender_name": "Admin Support",
            "message": "Terima kasih telah menghubungi kami...",
            "created_at": "2026-01-04 10:30:00",
            "sender_type": "admin"
        }
    ],
    "count": 1
}
```

### Update Ticket Status (Admin)

```php
// PATCH /admin/support/tickets/{id}/status
$ticket->update(['status' => 'in_progress']);
// Auto-increments response_count and updates updated_at
```

### Assign Ticket (Admin)

```php
// PATCH /admin/support/tickets/{id}/assign
$ticket->assign($adminId);
// Sets assigned_to and updates updated_at
```

## Integrations

### With Email System
- Automatic email notifications when ticket is created
- Email to admin when new message arrives
- Email to customer when assigned or status changes

### With SMS/WhatsApp System
- SMS notification when ticket is created
- SMS notification when assigned
- SMS notification when status changes to resolved
- SMS notification when new message arrives (if enabled)

### With User Preferences
- Respects user's notification preferences (email, SMS, WhatsApp)
- Stores SMS logs for audit trail

## Real-Time Features

### Current Implementation (Message Polling)
- JavaScript polls every 3 seconds via AJAX
- Endpoint: `GET /support/tickets/{id}/messages`
- Automatic page reload when new messages detected
- Works without additional dependencies

### Upgrade Path to WebSockets
To upgrade to real-time WebSockets (Pusher, Laravel WebSockets, etc.):

1. **Install Laravel Echo** and WebSocket broadcaster
2. **Replace polling** with event-based updates:
```javascript
Echo.channel('support-ticket.' + ticketId)
    .listen('MessageAdded', (e) => {
        loadNewMessages();
    });
```

3. **Broadcast events** from controllers:
```php
broadcast(new MessageAdded($ticket, $message))->toOthers();
```

## Security

### Authorization
- Customers can only access their own tickets
- Admins can access all tickets
- Middleware checks role and ownership

### Validation
- All form inputs are validated
- XSS protection via Blade escaping
- CSRF token on all forms
- SQL injection prevented via Eloquent ORM

### Data Protection
- Soft deletes support added to models
- Internal notes visible only to admin
- Message history preserved for audit trail

## Performance Considerations

### Pagination
- Customer tickets paginated (15 per page)
- Admin tickets paginated (15 per page)
- Reduces initial page load time

### Indexing
- Database indexes on: user_id, assigned_to, status, priority, category, created_at
- Enables fast filtering and sorting

### Caching (Future)
- Cache ticket statistics on admin dashboard
- Invalidate on new message/status change
- Reduces database queries

## Testing

### Manual Testing Routes
Available for development/testing purposes:
```
GET /admin/support/tickets - View all tickets
GET /customer/support/tickets - View customer tickets
```

### Test Scenarios
1. **Create Ticket**: Fill form with valid data
2. **Send Message**: Type message and click send
3. **Receive Response**: Admin sends message, customer receives notification
4. **Update Status**: Admin changes status, timestamps auto-update
5. **Assign Ticket**: Admin assigns to team member
6. **Close Ticket**: Customer/Admin closes ticket

## Troubleshooting

### Messages Not Appearing
1. Check browser console for JavaScript errors
2. Verify polling is active (Network tab)
3. Clear browser cache
4. Check if user is authorized to view ticket

### Migrations Failed
```bash
# Check migration status
php artisan migrate:status

# Rollback and re-run
php artisan migrate:rollback
php artisan migrate
```

### Controller Errors
```bash
# Clear cached routes
php artisan route:clear

# Verify controllers exist
ls app/Http/Controllers/Customer/SupportTicketController.php
ls app/Http/Controllers/Admin/SupportController.php
```

## Future Enhancements

1. **File Attachments**: Support file uploads in messages
2. **WebSockets**: Real-time messaging via Pusher/Echo
3. **Canned Responses**: Pre-written responses for common issues
4. **Rating/Feedback**: Customer satisfaction surveys
5. **Ticket Templates**: Quick ticket creation with templates
6. **Auto-Assignment**: Automatic load-based ticket distribution
7. **SLA Tracking**: Response time metrics and alerts
8. **Knowledge Base**: Self-service FAQ integration
9. **Multi-language**: Support tickets in multiple languages
10. **Mobile App**: Native mobile chat interface

## Files Created/Modified

### New Files Created
- `database/migrations/2026_01_04_120000_create_support_tickets_table.php`
- `database/migrations/2026_01_04_120001_create_chat_messages_table.php`
- `app/Models/SupportTicket.php`
- `app/Models/ChatMessage.php`
- `app/Http/Controllers/Customer/SupportTicketController.php`
- `app/Http/Controllers/Admin/SupportController.php`
- `resources/views/customer/support/tickets/index.blade.php`
- `resources/views/customer/support/tickets/create.blade.php`
- `resources/views/customer/support/tickets/show.blade.php`
- `resources/views/admin/support/tickets/index.blade.php`
- `resources/views/admin/support/tickets/show.blade.php`

### Modified Files
- `routes/web.php` - Added support ticket routes and controller imports
- `app/Models/User.php` - Added support ticket relationships

## Support & Maintenance

For issues, bug reports, or feature requests:
1. Create a GitHub issue with details
2. Include error logs from `storage/logs/laravel.log`
3. Specify reproduction steps
4. Attach relevant database/request information

---

**Last Updated:** January 4, 2026
**Version:** 1.0 (Production Ready)
**Maintained By:** Development Team
