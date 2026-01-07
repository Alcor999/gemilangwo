<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('user', 'package', 'order')
            ->latest()
            ->paginate(15);
        
        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load('user', 'package', 'order');
        return view('admin.reviews.show', compact('review'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review approved successfully!');
    }

    public function reject(Review $review)
    {
        $review->delete();
        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review rejected successfully!');
    }

    public function feature(Review $review)
    {
        $review->update(['is_featured' => !$review->is_featured]);
        
        $message = $review->is_featured 
            ? 'Review featured successfully!' 
            : 'Review unfeatured successfully!';
        
        return redirect()
            ->route('admin.reviews.index')
            ->with('success', $message);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully!');
    }
}
