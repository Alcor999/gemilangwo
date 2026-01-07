<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assigned_to',
        'order_id',
        'subject',
        'description',
        'category',
        'priority',
        'status',
        'internal_notes',
        'response_count',
        'first_response_at',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->oldest();
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOfPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    public function scopeByCustomer($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function addMessage($senderId, $message, $attachments = null)
    {
        $msg = $this->messages()->create([
            'sender_id' => $senderId,
            'message' => $message,
            'sender_type' => auth()->user()->role === 'admin' ? 'admin' : 'customer',
            'attachments' => $attachments,
        ]);

        // Update response count
        $this->increment('response_count');

        // Update first response time if this is first message from admin
        if (!$this->first_response_at && auth()->user()->role === 'admin') {
            $this->update(['first_response_at' => now()]);
        }

        // Auto-mark as in_progress if first response
        if ($this->status === 'open' && auth()->user()->role === 'admin') {
            $this->update(['status' => 'in_progress']);
        }

        return $msg;
    }

    public function markAsResolved($notes = null)
    {
        return $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'internal_notes' => $notes,
        ]);
    }

    public function markAsClosed()
    {
        return $this->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }

    public function assign($adminId)
    {
        return $this->update(['assigned_to' => $adminId]);
    }

    public function unassign()
    {
        return $this->update(['assigned_to' => null]);
    }

    public function getUnreadCount()
    {
        return $this->messages()->where('is_read', false)->count();
    }

    public function markMessagesAsRead()
    {
        $this->messages()
            ->where('is_read', false)
            ->where('sender_id', '!=', auth()->id())
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
}
