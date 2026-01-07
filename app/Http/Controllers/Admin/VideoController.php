<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display list of all videos by package
     */
    public function index()
    {
        $packages = Package::with('videos')->get();
        
        return view('admin.videos.index', [
            'packages' => $packages,
        ]);
    }

    /**
     * Show videos for a specific package
     */
    public function show($packageId)
    {
        $package = Package::findOrFail($packageId);
        $videos = $package->videos()->orderBy('order')->get();

        return view('admin.videos.show', [
            'package' => $package,
            'videos' => $videos,
        ]);
    }

    /**
     * Show create video form
     */
    public function create($packageId)
    {
        $package = Package::findOrFail($packageId);

        return view('admin.videos.create', [
            'package' => $package,
        ]);
    }

    /**
     * Store new video
     */
    public function store(Request $request, $packageId)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:upload,youtube',
                'video_file' => 'nullable|required_if:type,upload|mimes:mp4,avi,mov,mkv|max:40960', // 40MB max (matches PHP post_max_size)
                'youtube_url' => 'nullable|required_if:type,youtube|url',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'is_active' => 'boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle file too large errors
            if ($request->hasFile('video_file') && $request->file('video_file')->getError() === UPLOAD_ERR_INI_SIZE) {
                return back()->withErrors(['video_file' => 'File terlalu besar. Maksimum 40MB.'])->withInput();
            }
            throw $e;
        }

        $package = Package::findOrFail($packageId);
        $videoPath = null;
        $thumbnailPath = null;

        // Handle video upload
        if ($request->type === 'upload' && $request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('videos/' . $packageId, 'public');
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails/videos', 'public');
        }

        // Get next order number
        $nextOrder = $package->videos()->max('order') ?? 0;

        Video::create([
            'package_id' => $packageId,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'video_path' => $videoPath,
            'youtube_url' => $validated['youtube_url'] ?? null,
            'thumbnail_path' => $thumbnailPath,
            'is_active' => $validated['is_active'] ?? true,
            'order' => $nextOrder + 1,
        ]);

        return redirect()->route('admin.videos.show', $packageId)
                       ->with('success', 'Video added successfully!');
    }

    /**
     * Show edit video form
     */
    public function edit($videoId)
    {
        $video = Video::findOrFail($videoId);

        return view('admin.videos.edit', [
            'video' => $video,
            'package' => $video->package,
        ]);
    }

    /**
     * Update video
     */
    public function update(Request $request, $videoId)
    {
        $video = Video::findOrFail($videoId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:upload,youtube',
            'video_file' => 'nullable|mimes:mp4,avi,mov,mkv|max:512000', // 500MB
            'youtube_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',
        ]);

        // Handle new video upload
        if ($request->hasFile('video_file')) {
            // Delete old video
            if ($video->video_path) {
                Storage::disk('public')->delete($video->video_path);
            }
            $video->video_path = $request->file('video_file')->store('videos/' . $video->package_id, 'public');
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail_path) {
                Storage::disk('public')->delete($video->thumbnail_path);
            }
            $video->thumbnail_path = $request->file('thumbnail')->store('thumbnails/videos', 'public');
        }

        $video->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'youtube_url' => $validated['youtube_url'] ?? null,
            'is_active' => $validated['is_active'] ?? false,
        ]);

        return redirect()->route('admin.videos.show', $video->package_id)
                       ->with('success', 'Video updated successfully!');
    }

    /**
     * Delete video
     */
    public function destroy($videoId)
    {
        $video = Video::findOrFail($videoId);
        $packageId = $video->package_id;

        // Delete video file
        if ($video->video_path) {
            Storage::disk('public')->delete($video->video_path);
        }

        // Delete thumbnail
        if ($video->thumbnail_path) {
            Storage::disk('public')->delete($video->thumbnail_path);
        }

        $video->delete();

        return redirect()->route('admin.videos.show', $packageId)
                       ->with('success', 'Video deleted successfully!');
    }

    /**
     * Toggle video active status
     */
    public function toggle($videoId)
    {
        $video = Video::findOrFail($videoId);
        $video->update(['is_active' => !$video->is_active]);

        return redirect()->back()->with('success', 'Video status updated!');
    }

    /**
     * Reorder videos
     */
    public function reorder(Request $request, $packageId)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|integer',
            'orders.*.order' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $item) {
            Video::find($item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}
