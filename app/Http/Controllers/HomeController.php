<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Review;
use App\Models\VideoTestimonial;

class HomeController extends Controller
{
    /**
     * Show the homepage
     */
    public function index()
    {
        $packages = Package::where('status', 'active')->get();

        $reviews = Review::with(['user', 'package'])
            ->where('is_approved', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $videoTestimonials = VideoTestimonial::with(['user', 'order.package'])
            ->where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('home', [
            'packages' => $packages,
            'reviews' => $reviews,
            'videoTestimonials' => $videoTestimonials,
        ]);
    }
}
