<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    /**
     * List customer's support tickets
     */
    public function index()
    {
        $tickets = auth()->user()->supportTickets()
            ->latest()
            ->paginate(10);

        return view('customer.support.tickets.index', compact('tickets'));
    }

    /**
     * Create new support ticket form
     */
    public function create()
    {
        $orders = auth()->user()->orders()->latest()->get();
        return view('customer.support.tickets.create', compact('orders'));
    }

    /**
     * Store new support ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'category' => 'required|in:general,order,payment,complaint,suggestion,other',
            'order_id' => 'nullable|exists:orders,id',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket = auth()->user()->supportTickets()->create([
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'order_id' => $validated['order_id'],
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

        // Log initial message
        $ticket->messages()->create([
            'sender_id' => auth()->id(),
            'message' => $validated['description'],
            'sender_type' => 'customer',
        ]);

        return redirect()->route('customer.support.tickets.show', $ticket)
            ->with('success', 'Support ticket created successfully!');
    }

    /**
     * Show ticket with chat
     */
    public function show($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        // Check authorization
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        // Mark messages as read
        $ticket->markMessagesAsRead();

        $messages = $ticket->messages()->with('sender')->latest()->get();

        return view('customer.support.tickets.show', compact('ticket', 'messages'));
    }

    /**
     * Add message to ticket
     */
    public function addMessage(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        // Check authorization
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string|min:1',
        ]);

        $ticket->addMessage(auth()->id(), $validated['message']);

        // If response is AJAX, return JSON
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
            ]);
        }

        return back()->with('success', 'Message sent!');
    }

    /**
     * Close ticket
     */
    public function close($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->markAsClosed();

        return back()->with('success', 'Support ticket closed');
    }

    /**
     * Get new messages (AJAX for real-time updates)
     */
    public function getNewMessages($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

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
                'is_own' => $msg->sender_id === auth()->id(),
            ]),
            'ticket_status' => $ticket->status,
        ]);
    }
}
