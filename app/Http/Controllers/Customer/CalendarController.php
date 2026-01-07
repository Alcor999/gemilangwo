<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Package;
use App\Models\CalendarEvent;
use App\Models\BlockedDate;
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
     * Show booking calendar with heatmap
     */
    public function bookingCalendar(Request $request, Package $package)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Get blocked dates for this package
        $blockedDates = BlockedDate::where('package_id', $package->id)
            ->where('is_active', true)
            ->where('start_date', '<=', $endOfMonth->toDateString())
            ->where('end_date', '>=', $startOfMonth->toDateString())
            ->get();

        // Get confirmed events for heatmap
        $confirmedEvents = CalendarEvent::where('package_id', $package->id)
            ->where('is_confirmed', true)
            ->whereYear('event_date', $year)
            ->whereMonth('event_date', $month)
            ->get();

        $heatmapData = $this->generateBookingHeatmap($package->id, $startOfMonth, $endOfMonth, $blockedDates, $confirmedEvents);

        $nextAvailableDates = $this->getNextAvailableDates($package->id, 5);

        return view('customer.calendar.booking', [
            'package' => $package,
            'month' => $month,
            'year' => $year,
            'startOfMonth' => $startOfMonth,
            'endOfMonth' => $endOfMonth,
            'heatmapData' => $heatmapData,
            'nextAvailableDates' => $nextAvailableDates,
            'blockedDates' => $blockedDates,
        ]);
    }

    /**
     * Show customer's booking confirmation calendar
     */
    public function confirmationCalendar()
    {
        $user = auth()->user();
        $month = request('month', now()->month);
        $year = request('year', now()->year);

        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Get customer's confirmed orders
        $confirmedOrders = Order::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->with('calendarEvent')
            ->get();

        // Get or create calendar events for these orders
        foreach ($confirmedOrders as $order) {
            if (!$order->calendarEvent) {
                CalendarEvent::createFromOrder($order);
            }
        }

        // Get all calendar events for this customer in the month
        $calendarEvents = CalendarEvent::whereIn('order_id', $confirmedOrders->pluck('id'))
            ->whereYear('event_date', $year)
            ->whereMonth('event_date', $month)
            ->get();

        $eventsData = [];
        foreach ($calendarEvents as $event) {
            $dateKey = $event->event_date->toDateString();
            if (!isset($eventsData[$dateKey])) {
                $eventsData[$dateKey] = [];
            }

            $eventsData[$dateKey][] = [
                'id' => $event->id,
                'orderId' => $event->order_id,
                'packageName' => $event->package->name,
                'status' => $event->status,
                'isConfirmed' => $event->is_confirmed,
                'eventDays' => $event->calculateEventDays(),
                'occupiedDates' => $event->getOccupiedDates(),
            ];
        }

        return view('customer.calendar.confirmation', [
            'month' => $month,
            'year' => $year,
            'startOfMonth' => $startOfMonth,
            'endOfMonth' => $endOfMonth,
            'eventsData' => $eventsData,
            'confirmedOrders' => $confirmedOrders,
        ]);
    }

    /**
     * Show details for a specific calendar event
     */
    public function eventDetails(CalendarEvent $event)
    {
        $user = auth()->user();

        // Check if user owns this order
        if ($event->order->user_id !== $user->id) {
            abort(403);
        }

        return view('customer.calendar.event-details', [
            'event' => $event,
            'order' => $event->order,
            'package' => $event->package,
        ]);
    }

    /**
     * Confirm calendar event
     */
    public function confirmEvent(CalendarEvent $event)
    {
        $user = auth()->user();

        if ($event->order->user_id !== $user->id) {
            abort(403);
        }

        $event->confirm();

        return response()->json([
            'success' => true,
            'message' => 'Kalender acara berhasil dikonfirmasi.',
        ]);
    }

    /**
     * Get calendar event data via AJAX
     */
    public function getEventData(Request $request, Package $package)
    {
        $month = $request->get('month');
        $year = $request->get('year');

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $blockedDates = BlockedDate::where('package_id', $package->id)
            ->where('is_active', true)
            ->where('start_date', '<=', $endOfMonth->toDateString())
            ->where('end_date', '>=', $startOfMonth->toDateString())
            ->get();

        $confirmedEvents = CalendarEvent::where('package_id', $package->id)
            ->where('is_confirmed', true)
            ->whereYear('event_date', $year)
            ->whereMonth('event_date', $month)
            ->get();

        $eventDates = [];
        $blockedDatesList = [];

        // Collect blocked dates
        foreach ($blockedDates as $blocked) {
            $current = $blocked->start_date->copy();
            while ($current <= $blocked->end_date && $current <= $endOfMonth) {
                if ($current >= $startOfMonth) {
                    $blockedDatesList[] = $current->toDateString();
                }
                $current->addDay();
            }
        }

        // Collect event dates
        foreach ($confirmedEvents as $event) {
            $startDate = $event->event_start ?? $event->event_date;
            $endDate = $event->event_end ?? $event->event_date;

            $current = $startDate->copy();
            while ($current <= $endDate && $current <= $endOfMonth) {
                if ($current >= $startOfMonth) {
                    $eventDates[] = $current->toDateString();
                }
                $current->addDay();
            }
        }

        return response()->json([
            'success' => true,
            'blockedDates' => $blockedDatesList,
            'eventDates' => $eventDates,
        ]);
    }

    /**
     * Export booking calendar to iCal
     */
    public function exportBookingCalendar(Request $request, Package $package)
    {
        $iCalContent = $this->iCalService->generateCalendarFile($package->id, 'all');
        $filename = $this->iCalService->getFilename($package, 'booking');

        return response($iCalContent, 200)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache');
    }

    /**
     * Export confirmation calendar to iCal
     */
    public function exportConfirmationCalendar()
    {
        $user = auth()->user();
        
        // Create a pseudo-package for user's events
        $content = "BEGIN:VCALENDAR\r\n";
        $content .= "VERSION:2.0\r\n";
        $content .= "PRODID:-//Wedding App//My Wedding Events//EN\r\n";
        $content .= "CALSCALE:GREGORIAN\r\n";
        $content .= "METHOD:PUBLISH\r\n";
        $content .= "X-WR-CALNAME:Acara Pernikahan Saya\r\n";
        $content .= "X-WR-TIMEZONE:Asia/Jakarta\r\n";

        $confirmedOrders = Order::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->with('calendarEvent')
            ->get();

        foreach ($confirmedOrders as $order) {
            if ($order->calendarEvent && $order->calendarEvent->is_confirmed) {
                $event = $order->calendarEvent;
                $startDate = $event->event_start ?? $event->event_date;
                $endDate = $event->event_end ?? $event->event_date;

                $vEvent = "BEGIN:VEVENT\r\n";
                $vEvent .= "UID:event-" . $event->id . "@gemilangwo.com\r\n";
                $vEvent .= "DTSTAMP:" . now()->format('Ymd\THis\Z') . "\r\n";
                $vEvent .= "DTSTART;VALUE=DATE:" . $startDate->format('Ymd') . "\r\n";
                $vEvent .= "DTEND;VALUE=DATE:" . $endDate->addDay()->format('Ymd') . "\r\n";
                $vEvent .= "SUMMARY:" . $this->escapeString("Acara: " . $event->package->name) . "\r\n";
                $vEvent .= "DESCRIPTION:" . $this->escapeString($this->buildEventDescription($event)) . "\r\n";
                $vEvent .= "LOCATION:" . $this->escapeString($order->event_location) . "\r\n";
                $vEvent .= "STATUS:CONFIRMED\r\n";
                $vEvent .= "TRANSP:OPAQUE\r\n";
                $vEvent .= "COLOR:#22c55e\r\n";
                $vEvent .= "END:VEVENT\r\n";

                $content .= $vEvent;
            }
        }

        $content .= "END:VCALENDAR\r\n";

        $filename = "my-weddings-" . now()->format('Y-m-d') . ".ics";

        return response($content, 200)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache');
    }

    /**
     * Generate booking heatmap data
     */
    private function generateBookingHeatmap($packageId, $startOfMonth, $endOfMonth, $blockedDates, $confirmedEvents)
    {
        $heatmapData = [];

        // Initialize all dates
        $current = $startOfMonth->copy();
        while ($current <= $endOfMonth) {
            $heatmapData[$current->toDateString()] = [
                'status' => 'available', // available, busy, blocked
                'isWeekend' => $current->isWeekend(),
                'isPast' => $current->isPast(),
            ];
            $current->addDay();
        }

        // Mark blocked dates
        foreach ($blockedDates as $blocked) {
            $current = $blocked->start_date->copy();
            while ($current <= $blocked->end_date && $current <= $endOfMonth) {
                if ($current >= $startOfMonth) {
                    $heatmapData[$current->toDateString()]['status'] = 'blocked';
                }
                $current->addDay();
            }
        }

        // Mark busy dates (events)
        foreach ($confirmedEvents as $event) {
            $startDate = $event->event_start ?? $event->event_date;
            $endDate = $event->event_end ?? $event->event_date;

            $current = $startDate->copy();
            while ($current <= $endDate && $current <= $endOfMonth) {
                if ($current >= $startOfMonth) {
                    if ($heatmapData[$current->toDateString()]['status'] !== 'blocked') {
                        $heatmapData[$current->toDateString()]['status'] = 'busy';
                    }
                }
                $current->addDay();
            }
        }

        return $heatmapData;
    }

    /**
     * Get next available dates
     */
    private function getNextAvailableDates($packageId, $count = 5)
    {
        $availableDates = [];
        $current = now()->addDay();
        $maxDays = 365;
        $daysChecked = 0;

        while (count($availableDates) < $count && $daysChecked < $maxDays) {
            if (!BlockedDate::isDateBlocked($packageId, $current) && !$current->isPast()) {
                $availableDates[] = $current->copy();
            }

            $current->addDay();
            $daysChecked++;
        }

        return $availableDates;
    }

    /**
     * Build event description
     */
    private function buildEventDescription($event)
    {
        $description = "Paket: " . $event->package->name . "\n";
        $description .= "Tanggal Acara: " . $event->event_date->format('d F Y') . "\n";
        $description .= "Lokasi: " . $event->order->event_location . "\n";
        $description .= "Jumlah Tamu: " . $event->order->guest_count . "\n";
        
        return $description;
    }

    /**
     * Escape string for iCal format
     */
    private function escapeString($string)
    {
        $string = str_replace('\\', '\\\\', $string);
        $string = str_replace(',', '\\,', $string);
        $string = str_replace(';', '\\;', $string);
        $string = str_replace("\n", '\\n', $string);
        $string = str_replace("\r", '', $string);
        
        return $string;
    }
}
