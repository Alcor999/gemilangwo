<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\VideoTestimonial;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    /**
     * Display customer's testimonials
     */
    public function index()
    {
        $user = Auth::user();
        $testimonials = $user->videoTestimonials()->latest()->get();

        return view('customer.testimonials.index', [
            'testimonials' => $testimonials,
        ]);
    }

    /**
     * Show create testimonial form
     */
    public function create()
    {
        $user = Auth::user();
        $completedOrders = $user->orders()->where('status', 'completed')->get();

        return view('customer.testimonials.create', [
            'orders' => $completedOrders,
        ]);
    }

    /**
     * Store new testimonial
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'type' => 'required|in:upload,youtube',
            'video_file' => 'nullable|required_if:type,upload|mimes:mp4,avi,mov,mkv|max:40960', // 40MB max (PHP limit)
            'youtube_url' => 'nullable|required_if:type,youtube|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'order_id' => 'nullable|exists:orders,id',
            'rating' => 'nullable|numeric|min:1|max:5',
        ]);

        $user = Auth::user();
        $videoPath = null;
        $thumbnailPath = null;

        // Handle video upload
        if ($request->type === 'upload' && $request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('testimonials/' . $user->id, 'public');
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails/testimonials', 'public');
        }

        VideoTestimonial::create([
            'user_id' => $user->id,
            'order_id' => $validated['order_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'video_path' => $videoPath,
            'youtube_url' => $validated['youtube_url'] ?? null,
            'thumbnail_path' => $thumbnailPath,
            'rating' => $validated['rating'] ?? null,
            'is_active' => false, // Needs admin approval
        ]);

        return redirect()->route('customer.testimonials.index')
                       ->with('success', 'Testimonial submitted successfully! It will be reviewed by our team.');
    }

    /**
     * Show edit testimonial form
     */
    public function edit($testimonialId)
    {
        $testimonial = VideoTestimonial::findOrFail($testimonialId);
        
        // Check if user owns this testimonial
        if ($testimonial->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        $completedOrders = $user->orders()->where('status', 'completed')->get();

        return view('customer.testimonials.edit', [
            'testimonial' => $testimonial,
            'orders' => $completedOrders,
        ]);
    }

    /**
     * Update testimonial
     */
    public function update(Request $request, $testimonialId)
    {
        $testimonial = VideoTestimonial::findOrFail($testimonialId);

        if ($testimonial->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'type' => 'required|in:upload,youtube',
            'video_file' => 'nullable|mimes:mp4,avi,mov,mkv|max:40960', // 40MB max (PHP limit)
            'youtube_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'rating' => 'nullable|numeric|min:1|max:5',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        // Handle new video upload
        if ($request->hasFile('video_file')) {
            if ($testimonial->video_path) {
                Storage::disk('public')->delete($testimonial->video_path);
            }
            $testimonial->video_path = $request->file('video_file')->store('testimonials/' . Auth::id(), 'public');
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            if ($testimonial->thumbnail_path) {
                Storage::disk('public')->delete($testimonial->thumbnail_path);
            }
            $testimonial->thumbnail_path = $request->file('thumbnail')->store('thumbnails/testimonials', 'public');
        }

        $testimonial->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'youtube_url' => $validated['youtube_url'] ?? null,
            'rating' => $validated['rating'] ?? null,
            'order_id' => $validated['order_id'] ?? null,
            'is_active' => false, // Reset to pending approval
        ]);

        return redirect()->route('customer.testimonials.index')
                       ->with('success', 'Testimonial updated and resubmitted for review!');
    }

    /**
     * Delete testimonial
     */
    public function destroy($testimonialId)
    {
        $testimonial = VideoTestimonial::findOrFail($testimonialId);

        if ($testimonial->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete video file
        if ($testimonial->video_path) {
            Storage::disk('public')->delete($testimonial->video_path);
        }

        // Delete thumbnail
        if ($testimonial->thumbnail_path) {
            Storage::disk('public')->delete($testimonial->thumbnail_path);
        }

        $testimonial->delete();

        return redirect()->route('customer.testimonials.index')
                       ->with('success', 'Testimonial deleted successfully!');
    }
}
