<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = auth()->user()->reviews()->with('package')->paginate(10);
        return view('customer.reviews.index', compact('reviews'));
    }

    public function create(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $existingReview = Review::where('order_id', $order->id)->first();
        if ($existingReview) {
            return redirect()
                ->route('customer.orders.show', $order)
                ->with('info', 'You already reviewed this package.');
        }

        if ($order->status !== 'completed') {
            return redirect()
                ->route('customer.orders.show', $order)
                ->with('error', 'You can only review completed orders.');
        }

        return view('customer.reviews.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10|max:2000',
        ]);

        if (Review::where('order_id', $order->id)->exists()) {
            return redirect()
                ->route('customer.orders.show', $order)
                ->with('error', 'You already reviewed this order.');
        }

        Review::create([
            'user_id' => auth()->id(),
            'package_id' => $order->package_id,
            'order_id' => $order->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'is_verified' => true,
        ]);

        return redirect()
            ->route('customer.orders.show', $order)
            ->with('success', 'Review submitted! Thank you for your feedback.');
    }

    public function markHelpful(Review $review)
    {
        $review->increment('helpful_count');
        return response()->json(['helpful_count' => $review->helpful_count]);
    }

    public function markUnhelpful(Review $review)
    {
        $review->increment('unhelpful_count');
        return response()->json(['unhelpful_count' => $review->unhelpful_count]);
    }
}
