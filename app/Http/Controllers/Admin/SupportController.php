<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * List all support tickets (admin)
     */
    public function index()
    {
        $status = request()->get('status', '');
        $priority = request()->get('priority', '');
        $category = request()->get('category', '');
        $search = request()->get('search', '');
        
        $query = SupportTicket::with('user', 'assignedTo');

        // Filter by status
        if ($status && $status !== '') {
            $query->where('status', $status);
        }

        // Filter by priority
        if ($priority && $priority !== '') {
            $query->where('priority', $priority);
        }

        // Filter by category
        if ($category && $category !== '') {
            $query->where('category', $category);
        }

        // Search by subject or ID
        if ($search && $search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        $tickets = $query->latest()->paginate(15);

        $stats = [
            'open' => SupportTicket::open()->count(),
            'in_progress' => SupportTicket::inProgress()->count(),
            'resolved' => SupportTicket::resolved()->count(),
            'closed' => SupportTicket::closed()->count(),
            'urgent' => SupportTicket::where('priority', 'urgent')->count(),
        ];

        return view('admin.support.tickets.index', compact('tickets', 'stats', 'status', 'priority', 'category', 'search'));
    }

    /**
     * Show ticket with chat (admin)
     */
    public function show($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        // Mark messages as read
        $ticket->markMessagesAsRead();

        $messages = $ticket->messages()->with('sender')->get();
        $admins = User::where('role', 'admin')->get();

        return view('admin.support.tickets.show', compact('ticket', 'messages', 'admins'));
    }

    /**
     * Assign ticket to admin
     */
    public function assign(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $ticket->assign($validated['assigned_to']);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Ticket assigned successfully');
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,waiting_customer,resolved,closed',
        ]);

        $ticket->update(['status' => $validated['status']]);

        if ($validated['status'] === 'resolved') {
            $ticket->update(['resolved_at' => now()]);
        } elseif ($validated['status'] === 'closed') {
            $ticket->update(['closed_at' => now()]);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Status updated');
    }

    /**
     * Add message from admin
     */
    public function addMessage(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        $validated = $request->validate([
            'message' => 'required|string|min:1',
        ]);

        $ticket->addMessage(auth()->id(), $validated['message']);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Message sent');
    }

    /**
     * Add internal notes
     */
    public function addNotes(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        $validated = $request->validate([
            'internal_notes' => 'required|string',
        ]);

        $ticket->update(['internal_notes' => $validated['internal_notes']]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notes updated');
    }

    /**
     * Get new messages (AJAX for real-time updates)
     */
    public function getNewMessages($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        $lastMessageId = request()->query('last_message_id', 0);
        
        $messages = $ticket->messages()
            ->where('id', '>', $lastMessageId)
            ->with('sender')
            ->get();

        return response()->json([
            'messages' => $messages->map(fn($msg) => [
                'id' => $msg->id,
                'sender_name' => $msg->sender->name,
                'sender_type' => $msg->sender_type,
                'message' => $msg->message,
                'time' => $msg->getFormattedTime(),
                'is_admin' => $msg->sender_type === 'admin',
            ]),
            'unread_count' => $ticket->getUnreadCount(),
        ]);
    }

    /**
     * Dashboard widget - recent tickets
     */
    public function recentTickets()
    {
        $tickets = SupportTicket::with('user')
            ->whereIn('status', ['open', 'in_progress'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.support.widgets.recent-tickets', compact('tickets'));
    }
}
