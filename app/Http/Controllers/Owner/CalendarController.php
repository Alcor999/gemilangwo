<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\BlockedDate;
use App\Models\CalendarEvent;
use App\Services\ICalExportService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    protected $iCalService;

    public function __construct(ICalExportService $iCalService)
    {
        $this->iCalService = $iCalService;
    }

    /**
     * Show calendar overview for owner's packages
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $packages = $user->packages ?? Package::where('owner_id', $user->id)->get();
        
        $selectedPackageId = $request->get('package_id', $packages->first()->id ?? null);
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        if (!$selectedPackageId) {
            return redirect()->back()->with('warning', 'Tidak ada paket yang tersedia.');
        }

        $package = Package::findOrFail($selectedPackageId);
        
        // Check authorization
        if ($package->owner_id !== $user->id) {
            abort(403);
        }

        $blockedDates = BlockedDate::where('package_id', $selectedPackageId)
            ->where('is_active', true)
            ->get();

        $calendarEvents = CalendarEvent::where('package_id', $selectedPackageId)
            ->whereYear('event_date', $year)
            ->whereMonth('event_date', $month)
            ->where('is_confirmed', true)
            ->get();

        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $heatmapData = $this->generateHeatmapData($selectedPackageId, $startOfMonth, $endOfMonth);

        return view('owner.calendar.index', [
            'packages' => $packages,
            'selectedPackage' => $package,
            'blockedDates' => $blockedDates,
            'calendarEvents' => $calendarEvents,
            'month' => $month,
            'year' => $year,
            'startOfMonth' => $startOfMonth,
            'endOfMonth' => $endOfMonth,
            'heatmapData' => $heatmapData,
        ]);
    }

    /**
     * Show form to create blocked date
     */
    public function createBlocked(Request $request)
    {
        $user = auth()->user();
        $packageId = $request->get('package_id');
        
        $package = Package::findOrFail($packageId);
        
        if ($package->owner_id !== $user->id) {
            abort(403);
        }

        return view('owner.calendar.create-blocked', [
            'package' => $package,
        ]);
    }

    /**
     * Store blocked date
     */
    public function storeBlocked(Request $request, Package $package)
    {
        $user = auth()->user();
        
        if ($package->owner_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
            'block_type' => 'required|in:unavailable,maintenance,reserved,personal',
        ]);

        BlockedDate::create([
            'package_id' => $package->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'block_type' => $validated['block_type'],
            'is_active' => true,
        ]);

        return redirect()->route('owner.calendar.index', ['package_id' => $package->id])
            ->with('success', 'Tanggal diblokir berhasil ditambahkan.');
    }

    /**
     * Edit blocked date
     */
    public function editBlocked(BlockedDate $blockedDate)
    {
        $user = auth()->user();
        
        if ($blockedDate->package->owner_id !== $user->id) {
            abort(403);
        }

        return view('owner.calendar.edit-blocked', [
            'blockedDate' => $blockedDate,
            'package' => $blockedDate->package,
        ]);
    }

    /**
     * Update blocked date
     */
    public function updateBlocked(Request $request, BlockedDate $blockedDate)
    {
        $user = auth()->user();
        
        if ($blockedDate->package->owner_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
            'block_type' => 'required|in:unavailable,maintenance,reserved,personal',
            'is_active' => 'required|boolean',
        ]);

        $blockedDate->update($validated);

        return redirect()->route('owner.calendar.index', ['package_id' => $blockedDate->package_id])
            ->with('success', 'Tanggal blokir berhasil diperbarui.');
    }

    /**
     * Delete blocked date
     */
    public function destroyBlocked(BlockedDate $blockedDate)
    {
        $user = auth()->user();
        
        if ($blockedDate->package->owner_id !== $user->id) {
            abort(403);
        }

        $packageId = $blockedDate->package_id;
        $blockedDate->delete();

        return redirect()->route('owner.calendar.index', ['package_id' => $packageId])
            ->with('success', 'Tanggal blokir berhasil dihapus.');
    }

    /**
     * Get calendar data via AJAX
     */
    public function getCalendarData(Request $request, Package $package)
    {
        $user = auth()->user();
        
        if ($package->owner_id !== $user->id) {
            abort(403);
        }

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $blockedDates = BlockedDate::where('package_id', $package->id)
            ->where('is_active', true)
            ->whereBetween('start_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orWhereBetween('end_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orWhere(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->where('start_date', '<=', $endOfMonth->toDateString())
                      ->where('end_date', '>=', $startOfMonth->toDateString());
            })
            ->get();

        $calendarEvents = CalendarEvent::where('package_id', $package->id)
            ->whereYear('event_date', $year)
            ->whereMonth('event_date', $month)
            ->where('is_confirmed', true)
            ->get();

        $events = [];
        
        // Add blocked dates
        foreach ($blockedDates as $blocked) {
            $current = $blocked->start_date->copy();
            while ($current <= $blocked->end_date) {
                $dateStr = $current->toDateString();
                if (!isset($events[$dateStr])) {
                    $events[$dateStr] = [];
                }
                
                $events[$dateStr][] = [
                    'type' => 'blocked',
                    'reason' => $blocked->reason,
                    'blockType' => $blocked->block_type,
                    'label' => $blocked->getTypeLabel(),
                ];
                
                $current->addDay();
            }
        }

        // Add calendar events
        foreach ($calendarEvents as $event) {
            $dateStr = $event->event_date->toDateString();
            if (!isset($events[$dateStr])) {
                $events[$dateStr] = [];
            }

            $events[$dateStr][] = [
                'type' => 'event',
                'orderId' => $event->order_id,
                'customerName' => $event->order->user->name,
                'status' => $event->status,
                'label' => $event->order->user->name,
            ];
        }

        return response()->json([
            'success' => true,
            'events' => $events,
        ]);
    }

    /**
     * Export calendar to iCal format
     */
    public function exportCalendar(Request $request, Package $package)
    {
        $user = auth()->user();
        
        if ($package->owner_id !== $user->id) {
            abort(403);
        }

        $type = $request->get('type', 'all'); // 'all', 'events', 'blocked'
        
        $iCalContent = $this->iCalService->generateCalendarFile($package->id, $type);
        $filename = $this->iCalService->getFilename($package, $type);

        return response($iCalContent, 200)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache');
    }

    /**
     * Get heatmap data for visualization
     */
    private function generateHeatmapData($packageId, $startOfMonth, $endOfMonth)
    {
        $heatmapData = [];

        // Get all confirmed events and blocked dates in the month
        $blockedDates = BlockedDate::where('package_id', $packageId)
            ->where('is_active', true)
            ->where('start_date', '<=', $endOfMonth->toDateString())
            ->where('end_date', '>=', $startOfMonth->toDateString())
            ->get();

        $calendarEvents = CalendarEvent::where('package_id', $packageId)
            ->where('is_confirmed', true)
            ->whereYear('event_date', $startOfMonth->year)
            ->whereMonth('event_date', $startOfMonth->month)
            ->get();

        // Initialize all dates in month as available (0)
        $current = $startOfMonth->copy();
        while ($current <= $endOfMonth) {
            $heatmapData[$current->toDateString()] = 0;
            $current->addDay();
        }

        // Mark blocked dates as unavailable (1)
        foreach ($blockedDates as $blocked) {
            $current = $blocked->start_date->copy();
            while ($current <= $blocked->end_date && $current <= $endOfMonth) {
                if ($current >= $startOfMonth) {
                    $heatmapData[$current->toDateString()] = 1;
                }
                $current->addDay();
            }
        }

        // Mark event dates as busy (2)
        foreach ($calendarEvents as $event) {
            $startDate = $event->event_start ?? $event->event_date;
            $endDate = $event->event_end ?? $event->event_date;

            $current = $startDate->copy();
            while ($current <= $endDate && $current <= $endOfMonth) {
                if ($current >= $startOfMonth) {
                    if ($heatmapData[$current->toDateString()] !== 1) {
                        $heatmapData[$current->toDateString()] = 2;
                    }
                }
                $current->addDay();
            }
        }

        return $heatmapData;
    }
}
