<?php

namespace App\Services;

use App\Models\CalendarEvent;
use App\Models\BlockedDate;
use App\Models\Package;
use Carbon\Carbon;

class ICalExportService
{
    /**
     * Generate iCal format for calendar events
     */
    public function generateCalendarFile($packageId, $type = 'all')
    {
        $package = Package::find($packageId);
        if (!$package) {
            return null;
        }

        $events = [];
        
        if ($type === 'all' || $type === 'events') {
            $calendarEvents = CalendarEvent::where('package_id', $packageId)
                ->where('is_confirmed', true)
                ->get();
            $events = array_merge($events, $this->buildEventsFromCalendarEvents($calendarEvents));
        }

        if ($type === 'all' || $type === 'blocked') {
            $blockedDates = BlockedDate::where('package_id', $packageId)
                ->where('is_active', true)
                ->get();
            $events = array_merge($events, $this->buildEventsFromBlockedDates($blockedDates));
        }

        return $this->generateICalContent($package, $events);
    }

    /**
     * Build iCal events from CalendarEvent objects
     */
    private function buildEventsFromCalendarEvents($calendarEvents)
    {
        $events = [];
        
        foreach ($calendarEvents as $event) {
            $startDate = $event->event_start ?? $event->event_date;
            $endDate = $event->event_end ?? $event->event_date;

            $events[] = [
                'uid' => 'event-' . $event->id . '@gemilangwo.com',
                'dtstart' => $startDate->format('Ymd'),
                'dtend' => $endDate->addDay()->format('Ymd'), // iCal end date is exclusive
                'summary' => $this->buildEventSummary($event),
                'description' => $this->buildEventDescription($event),
                'location' => $event->order->event_location ?? '',
                'status' => 'CONFIRMED',
                'color' => '#22c55e', // Green for confirmed events
            ];
        }

        return $events;
    }

    /**
     * Build iCal events from BlockedDate objects
     */
    private function buildEventsFromBlockedDates($blockedDates)
    {
        $events = [];
        
        foreach ($blockedDates as $blocked) {
            $endDate = $blocked->end_date->copy()->addDay(); // iCal end date is exclusive

            $events[] = [
                'uid' => 'blocked-' . $blocked->id . '@gemilangwo.com',
                'dtstart' => $blocked->start_date->format('Ymd'),
                'dtend' => $endDate->format('Ymd'),
                'summary' => $this->buildBlockedDateSummary($blocked),
                'description' => $blocked->reason ?? $blocked->getTypeLabel(),
                'status' => 'TENTATIVE',
                'transp' => 'TRANSPARENT', // Don't show as busy
                'color' => $this->getBlockedDateColor($blocked),
            ];
        }

        return $events;
    }

    /**
     * Generate complete iCal content
     */
    private function generateICalContent($package, $events)
    {
        $now = Carbon::now()->format('Ymd\THis\Z');
        $content = "BEGIN:VCALENDAR\r\n";
        $content .= "VERSION:2.0\r\n";
        $content .= "PRODID:-//Wedding App//Wedding Package Calendar//EN\r\n";
        $content .= "CALSCALE:GREGORIAN\r\n";
        $content .= "METHOD:PUBLISH\r\n";
        $content .= "X-WR-CALNAME:" . $this->escapeString($package->name) . "\r\n";
        $content .= "X-WR-TIMEZONE:Asia/Jakarta\r\n";
        $content .= "BEGIN:VTIMEZONE\r\n";
        $content .= "TZID:Asia/Jakarta\r\n";
        $content .= "BEGIN:STANDARD\r\n";
        $content .= "DTSTART:19700101T000000\r\n";
        $content .= "TZOFFSETFROM:+0700\r\n";
        $content .= "TZOFFSETTO:+0700\r\n";
        $content .= "TZNAME:WIB\r\n";
        $content .= "END:STANDARD\r\n";
        $content .= "END:VTIMEZONE\r\n";

        foreach ($events as $event) {
            $content .= $this->buildVEvent($event, $now);
        }

        $content .= "END:VCALENDAR\r\n";

        return $content;
    }

    /**
     * Build individual VEVENT
     */
    private function buildVEvent($event, $timestamp)
    {
        $vEvent = "BEGIN:VEVENT\r\n";
        $vEvent .= "UID:" . $event['uid'] . "\r\n";
        $vEvent .= "DTSTAMP:" . $timestamp . "\r\n";
        $vEvent .= "DTSTART;VALUE=DATE:" . $event['dtstart'] . "\r\n";
        $vEvent .= "DTEND;VALUE=DATE:" . $event['dtend'] . "\r\n";
        $vEvent .= "SUMMARY:" . $this->escapeString($event['summary']) . "\r\n";
        
        if (!empty($event['description'])) {
            $vEvent .= "DESCRIPTION:" . $this->escapeString($event['description']) . "\r\n";
        }
        
        if (!empty($event['location'])) {
            $vEvent .= "LOCATION:" . $this->escapeString($event['location']) . "\r\n";
        }
        
        $vEvent .= "STATUS:" . $event['status'] . "\r\n";
        
        if (isset($event['transp'])) {
            $vEvent .= "TRANSP:" . $event['transp'] . "\r\n";
        } else {
            $vEvent .= "TRANSP:OPAQUE\r\n";
        }

        if (!empty($event['color'])) {
            $vEvent .= "COLOR:" . $event['color'] . "\r\n";
        }

        $vEvent .= "SEQUENCE:0\r\n";
        $vEvent .= "END:VEVENT\r\n";

        return $vEvent;
    }

    /**
     * Build event summary
     */
    private function buildEventSummary($event)
    {
        $order = $event->order;
        $package = $event->package;
        
        $summary = "Acara: " . $package->name;
        if ($order && $order->user) {
            $summary .= " - " . $order->user->name;
        }

        return $summary;
    }

    /**
     * Build event description
     */
    private function buildEventDescription($event)
    {
        $order = $event->order;
        $description = "Paket: " . $event->package->name . "\n";
        
        if ($order) {
            $description .= "Tanggal Acara: " . $order->event_date->format('d F Y') . "\n";
            $description .= "Lokasi: " . $order->event_location . "\n";
            $description .= "Jumlah Tamu: " . $order->guest_count . "\n";
            
            if ($event->notes) {
                $description .= "Catatan: " . $event->notes . "\n";
            }
        }

        return $description;
    }

    /**
     * Build blocked date summary
     */
    private function buildBlockedDateSummary($blocked)
    {
        return $blocked->getTypeLabel() . " - " . $blocked->package->name;
    }

    /**
     * Get color for blocked date type
     */
    private function getBlockedDateColor($blocked)
    {
        return match($blocked->block_type) {
            'unavailable' => '#ef4444', // Red
            'maintenance' => '#f97316', // Orange
            'reserved' => '#eab308',    // Yellow
            'personal' => '#a855f7',    // Purple
            default => '#6b7280',       // Gray
        };
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

    /**
     * Get calendar filename
     */
    public function getFilename($package, $type = 'all')
    {
        $slug = str_replace(' ', '-', strtolower($package->name));
        $date = now()->format('Y-m-d');
        
        return "calendar-{$slug}-{$date}-{$type}.ics";
    }
}
