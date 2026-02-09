<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Package;

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
