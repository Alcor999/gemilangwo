<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Package;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $wishlists = $user->wishlists()->with('package')->paginate(12);
        
        return view('customer.wishlist.index', compact('wishlists'));
    }

    public function add(Package $package)
    {
        $user = auth()->user();
        
        $exists = Wishlist::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('info', 'Paket sudah ada di wishlist Anda');
        }

        Wishlist::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
        ]);

        return redirect()->back()->with('success', 'Paket ditambahkan ke wishlist!');
    }

    public function remove(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== auth()->id()) {
            abort(403);
        }

        $wishlist->delete();

        return redirect()->back()->with('success', 'Paket dihapus dari wishlist!');
    }

    public function toggleAjax(Package $package)
    {
        $user = auth()->user();
        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json([
                'success' => true,
                'added' => false,
                'message' => 'Dihapus dari wishlist',
            ]);
        }

        Wishlist::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
        ]);

        return response()->json([
            'success' => true,
            'added' => true,
            'message' => 'Ditambahkan ke wishlist',
        ]);
    }

    public function isInWishlist(Package $package)
    {
        $user = auth()->user();
        $exists = Wishlist::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->exists();

        return response()->json(['in_wishlist' => $exists]);
    }
}
