<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Package;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after:today',
        ]);

        $date = Carbon::parse($request->date)->toDateString();
        
        // Get all packages and check availability
        $packages = Package::all();
        $availability = [];

        foreach ($packages as $package) {
            $availability[$package->id] = [
                'available' => true, // Default available unless dates booked
                'package' => $package->name,
                'orders' => $package->orders()
                    ->whereDate('created_at', $date)
                    ->count(),
            ];
        }

        return response()->json($availability);
    }

    public function getCalendar(Package $package)
    {
        $events = $package->orders()
            ->where('status', 'completed')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'title' => 'Booking: ' . $order->user->name,
                    'start' => $order->created_at->toDateString(),
                    'end' => $order->created_at->addDays(1)->toDateString(),
                    'backgroundColor' => '#b8860b',
                ];
            });

        return response()->json($events);
    }

    public function checkDateRange(Request $request)
    {
        $request->validate([
            'from' => 'required|date|after:today',
            'to' => 'required|date|after_or_equal:from',
            'package_id' => 'required|exists:packages,id',
        ]);

        $package = Package::find($request->package_id);
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $conflictingOrders = $package->orders()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->where('status', 'completed')
            ->count();

        return response()->json([
            'available' => $conflictingOrders === 0,
            'conflicts' => $conflictingOrders,
            'message' => $conflictingOrders === 0 
                ? 'Tanggal tersedia!' 
                : "Paket sudah dipesan {$conflictingOrders} kali pada rentang tanggal ini",
        ]);
    }
}
