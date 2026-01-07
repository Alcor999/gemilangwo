<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoTestimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display list of all testimonials (pending & published)
     */
    public function index()
    {
        $pendingTestimonials = VideoTestimonial::where('is_active', false)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $publishedTestimonials = VideoTestimonial::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.testimonials.index', [
            'pendingTestimonials' => $pendingTestimonials,
            'publishedTestimonials' => $publishedTestimonials,
        ]);
    }

    /**
     * Display a specific testimonial for review
     */
    public function show(VideoTestimonial $testimonial)
    {
        return view('admin.testimonials.show', [
            'testimonial' => $testimonial,
        ]);
    }

    /**
     * Approve a testimonial (make it active/published)
     */
    public function approve(Request $request, VideoTestimonial $testimonial)
    {
        if ($request->expectsJson()) {
            // AJAX request
            $testimonial->update(['is_active' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'Testimonial approved successfully',
            ]);
        }

        // Form submission
        $testimonial->update(['is_active' => true]);
        
        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial approved successfully');
    }

    /**
     * Reject a testimonial (delete it)
     */
    public function reject(Request $request, VideoTestimonial $testimonial)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $testimonial->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Testimonial rejected successfully',
            ]);
        }

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial rejected successfully');
    }

    /**
     * Feature a testimonial
     */
    public function feature(VideoTestimonial $testimonial)
    {
        $testimonial->update(['is_featured' => true]);

        return back()->with('success', 'Testimonial marked as featured');
    }

    /**
     * Unfeature a testimonial
     */
    public function unfeature(VideoTestimonial $testimonial)
    {
        $testimonial->update(['is_featured' => false]);

        return back()->with('success', 'Testimonial removed from featured');
    }
}
