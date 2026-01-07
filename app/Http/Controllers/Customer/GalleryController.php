<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function show(Package $package)
    {
        $images = $package->galleryImages()->ordered()->get();
        return view('customer.gallery.show', compact('package', 'images'));
    }

    public function lightbox(Package $package)
    {
        $images = $package->galleryImages()->ordered()->get();
        return response()->json($images);
    }
}
