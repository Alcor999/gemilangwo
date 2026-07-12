<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentScheme;
use Carbon\Carbon;

class PaymentSchemeService
{
    public function getActiveSchemes()
    {
        return PaymentScheme::active()->orderBy('id')->get();
    }

    public function isEligible(string $schemeCode, Carbon $eventDate): bool
    {
        $scheme = PaymentScheme::where('code', $schemeCode)->first();
        if (! $scheme || ! $scheme->is_active) {
            return $schemeCode === 'full_payment';
        }

        if ($schemeCode === 'full_payment') {
            return true;
        }

        $daysUntilEvent = now()->startOfDay()->diffInDays($eventDate->startOfDay(), false);

        return $daysUntilEvent >= $scheme->min_days_before_event;
    }

    public function calculateBreakdown(string $schemeCode, float $totalPrice, ?Carbon $eventDate = null): array
    {
        $scheme = PaymentScheme::where('code', $schemeCode)->first();
        $breakdown = $scheme?->breakdown ?? $this->defaultBreakdown($schemeCode);

        $today = now()->startOfDay()->addDay(); // Tomorrow as base for first payment due date
        $lastIndex = count($breakdown) - 1;

        // First compute all raw due dates
        $dueDates = [];
        foreach ($breakdown as $index => $item) {
            if ($index === 0) {
                $dueDates[0] = $today->copy();
            } else {
                $daysBefore = $item['days_before_event'] ?? 14;
                $rawDate = $eventDate ? $eventDate->copy()->startOfDay()->subDays($daysBefore) : null;
                $dueDates[$index] = $rawDate;
            }
        }

        // Adjust to be sequential and in the future
        $maxFinalDate = $eventDate ? $eventDate->copy()->startOfDay()->subDays(4) : null;
        if ($maxFinalDate && $maxFinalDate->isBefore($today)) {
            $maxFinalDate = $today->copy();
        }

        // If the final installment raw date is in the past, cap it at maxFinalDate
        if ($eventDate && ($dueDates[$lastIndex] === null || $dueDates[$lastIndex]->isBefore($today))) {
            $dueDates[$lastIndex] = $maxFinalDate->copy();
        }

        // Forward pass to space them out sequentially
        for ($i = 1; $i <= $lastIndex; $i++) {
            if ($dueDates[$i] === null || $dueDates[$i]->isBefore($dueDates[$i-1]->copy()->addDay())) {
                $daysRemaining = $dueDates[$lastIndex]->diffInDays($dueDates[$i-1], false);
                $stepsLeft = $lastIndex - $i + 1;
                if ($stepsLeft > 0 && $daysRemaining < 0) {
                    $increment = max(1, (int) round(abs($daysRemaining) / $stepsLeft));
                    $dueDates[$i] = $dueDates[$i-1]->copy()->addDays($increment);
                } else {
                    $dueDates[$i] = $dueDates[$i-1]->copy()->addDay();
                }
            }
        }

        // Final check to make sure they do not exceed maxFinalDate
        if ($maxFinalDate) {
            for ($i = 1; $i <= $lastIndex; $i++) {
                if ($dueDates[$i]->isAfter($maxFinalDate)) {
                    $dueDates[$i] = $maxFinalDate->copy();
                }
            }
        }

        $items = [];
        foreach ($breakdown as $index => $item) {
            $amount = round($totalPrice * ($item['percentage'] / 100), 2);
            $items[] = [
                'label' => $item['label'],
                'percentage' => $item['percentage'],
                'amount' => $amount,
                'due_date' => $dueDates[$index],
                'installment_number' => null,
            ];
        }

        if ($schemeCode === 'installment_3x' || $schemeCode === 'installment_5x') {
            foreach ($items as $i => &$item) {
                $item['installment_number'] = $i + 1;
                $item['payment_type'] = 'installment';
            }
            unset($item);
        } elseif (str_starts_with($schemeCode, 'dp_')) {
            $items[0]['payment_type'] = 'dp';
            if (isset($items[1])) {
                $items[1]['payment_type'] = 'remaining';
            }
        } else {
            $items[0]['payment_type'] = 'full';
        }

        return $items;
    }

    public function getPaymentSummary(Order $order): array
    {
        $breakdown = $this->calculateBreakdown(
            $order->payment_scheme,
            (float) $order->total_price,
            $order->event_date
        );

        return [
            'scheme_label' => $order->scheme_label,
            'total_price' => (float) $order->total_price,
            'total_paid' => (float) $order->total_paid,
            'remaining_amount' => (float) $order->remaining_amount,
            'payment_status' => $order->payment_status,
            'progress_percent' => $order->getPaymentProgress(),
            'schedule' => $breakdown,
            'payments' => $order->payments()->orderBy('created_at')->get(),
        ];
    }

    private function defaultBreakdown(string $schemeCode): array
    {
        return match ($schemeCode) {
            'dp_20' => [
                ['percentage' => 20, 'label' => 'Uang Muka (DP)', 'days_before_event' => null],
                ['percentage' => 80, 'label' => 'Pelunasan', 'days_before_event' => 14],
            ],
            'dp_30' => [
                ['percentage' => 30, 'label' => 'Uang Muka (DP)', 'days_before_event' => null],
                ['percentage' => 70, 'label' => 'Pelunasan', 'days_before_event' => 14],
            ],
            'dp_40' => [
                ['percentage' => 40, 'label' => 'Uang Muka (DP)', 'days_before_event' => null],
                ['percentage' => 60, 'label' => 'Pelunasan', 'days_before_event' => 14],
            ],
            'dp_50' => [
                ['percentage' => 50, 'label' => 'Uang Muka (DP)', 'days_before_event' => null],
                ['percentage' => 50, 'label' => 'Pelunasan', 'days_before_event' => 14],
            ],
            'installment_3x' => [
                ['percentage' => 40, 'label' => 'Cicilan ke-1', 'days_before_event' => null],
                ['percentage' => 30, 'label' => 'Cicilan ke-2', 'days_before_event' => 30],
                ['percentage' => 30, 'label' => 'Cicilan ke-3', 'days_before_event' => 14],
            ],
            'installment_5x' => [
                ['percentage' => 30, 'label' => 'Cicilan ke-1', 'days_before_event' => null],
                ['percentage' => 20, 'label' => 'Cicilan ke-2', 'days_before_event' => 60],
                ['percentage' => 20, 'label' => 'Cicilan ke-3', 'days_before_event' => 45],
                ['percentage' => 15, 'label' => 'Cicilan ke-4', 'days_before_event' => 30],
                ['percentage' => 15, 'label' => 'Cicilan ke-5', 'days_before_event' => 14],
            ],
            default => [
                ['percentage' => 100, 'label' => 'Lunas Penuh', 'days_before_event' => null],
            ],
        };
    }
}
