<?php

namespace App\Livewire\Customer;

use Livewire\Component;

class BookingForm extends Component
{
    public $step = 1;
    public $packages;
    public $package_id;
    public $event_date;
    public $event_location;
    public $guest_count;
    public $special_request;
    public $selected_vendors = [];
    public $total_price = 0;
    public $extra_guest_charge = 0;
    public $payment_scheme = 'full_payment';
    public $custom_due_dates = [];

    public function mount()
    {
        $this->packages = \App\Models\Package::where('status', 'active')->with('requiredVendorCategories.vendors')->get();
        
        // Handle pre-selected package if any
        if (request('package_id')) {
            $this->package_id = request('package_id');
            $this->updatedPackageId($this->package_id);
        }
    }

    public function updatedPackageId($value)
    {
        $pkg = \App\Models\Package::find($value);
        if ($pkg) {
            $this->total_price = $pkg->getDiscountedPrice();
            $this->guest_count = $pkg->max_guests;
            
            // Set default vendors
            foreach ($pkg->requiredVendorCategories as $category) {
                $this->selected_vendors[$category->id] = $category->pivot->default_vendor_id;
            }
        }
    }

    public function updatedSelectedVendors()
    {
        $this->calculateTotalPrice();
    }

    public function updatedPaymentScheme()
    {
        // trigger breakdown refresh
    }

    public function updatedGuestCount()
    {
        $this->calculateTotalPrice();
    }

    public function calculateExtraGuestCharge()
    {
        $threshold = config('gemilang.guests.threshold', 1000);
        $chargePerUnit = config('gemilang.guests.charge_per_unit', 1000000);
        $unitSize = config('gemilang.guests.unit_size', 100);

        if ((int)$this->guest_count > $threshold) {
            $extraGuests = (int)$this->guest_count - $threshold;
            $units = ceil($extraGuests / $unitSize);
            return $units * $chargePerUnit;
        }

        return 0;
    }

    public function calculateVendorAdjustment()
    {
        if (!$this->package_id) return 0;
        
        $pkg = \App\Models\Package::with('requiredVendorCategories.vendors')->find($this->package_id);
        $adjustment = 0;

        foreach ($pkg->requiredVendorCategories as $category) {
            $selectedVendorId = $this->selected_vendors[$category->id] ?? null;
            $defaultVendorId = $category->pivot->default_vendor_id;

            if ($selectedVendorId && $selectedVendorId != $defaultVendorId) {
                $selectedVendor = \App\Models\Vendor::find($selectedVendorId);
                $defaultVendor = $defaultVendorId ? \App\Models\Vendor::find($defaultVendorId) : null;
                
                if ($selectedVendor) {
                    $adjustment += $selectedVendor->price - ($defaultVendor?->price ?? 0);
                }
            }
        }

        return $adjustment;
    }

    public function calculateTotalPrice()
    {
        if (!$this->package_id) return;
        
        $pkg = \App\Models\Package::find($this->package_id);
        $this->extra_guest_charge = $this->calculateExtraGuestCharge();
        $this->total_price = $pkg->getDiscountedPrice() + $this->calculateVendorAdjustment() + $this->extra_guest_charge;
    }

    public function nextStep()
    {
        $this->validateStep();
        $this->step++;

        if ($this->step >= 4) {
            $this->calculateTotalPrice();
        }
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function validateStep()
    {
        if ($this->step === 1) {
            $this->validate(['package_id' => 'required|exists:packages,id']);
        } elseif ($this->step === 2) {
            $minDate = \Carbon\Carbon::now()->addDays(4)->format('Y-m-d');
            $this->validate([
                'event_date' => 'required|date|after_or_equal:' . $minDate,
                'event_location' => 'required|string|max:255',
                'guest_count' => 'required|integer|min:1',
            ], [
                'event_date.after_or_equal' => 'Tanggal acara minimal 4 hari dari hari ini karena pembayaran harus lunas sebelum H-4.',
            ]);
            
            // Re-calculate to show extra guest charge if any
            $this->calculateTotalPrice();
        } elseif ($this->step === 3) {
            // Vendors are optional or pre-selected
        } elseif ($this->step === 4) {
            $this->validate([
                'payment_scheme' => 'required|in:full_payment,dp_20,dp_30,dp_40,dp_50,installment_3x,installment_5x',
            ]);

            $eventDate = \Carbon\Carbon::parse($this->event_date);
            if (! app(\App\Services\PaymentSchemeService::class)->isEligible($this->payment_scheme, $eventDate)) {
                $this->addError('payment_scheme', 'Skema ini tidak tersedia untuk tanggal acara yang dipilih.');
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment_scheme' => 'Pilih tanggal lebih jauh atau gunakan Bayar Lunas.',
                ]);
            }

            if (!empty($this->custom_due_dates)) {
                $maxDueDate = $eventDate->copy()->subDays(4)->endOfDay();
                foreach ($this->custom_due_dates as $index => $date) {
                    if (!$date) continue;
                    $parsedDate = \Carbon\Carbon::parse($date);
                    if ($parsedDate->isBefore(today())) {
                        $this->addError('custom_due_dates.'.$index, 'Tanggal tidak boleh di masa lalu.');
                        throw \Illuminate\Validation\ValidationException::withMessages(['custom_due_dates' => 'Tanggal tidak valid.']);
                    }
                    if ($parsedDate->isAfter($maxDueDate)) {
                        $this->addError('custom_due_dates.'.$index, 'Tanggal harus sebelum H-4 acara.');
                        throw \Illuminate\Validation\ValidationException::withMessages(['custom_due_dates' => 'Tanggal harus sebelum H-4 acara.']);
                    }
                }
            }
        }
    }

    public function submit()
    {
        $this->validateStep();
        
        $orderNumber = 'WO-'.substr(time(), -8).rand(10, 99);
        
        $dpPercentage = match ($this->payment_scheme) {
            'dp_20' => 20.00,
            'dp_30' => 30.00,
            'dp_40' => 40.00,
            'dp_50' => 50.00,
            default => null,
        };

        // Extract custom schedules if any, only keeping non-null strings
        $filteredSchedules = array_filter($this->custom_due_dates, fn($val) => !empty($val));
        $customSchedulesJson = !empty($filteredSchedules) ? json_encode($filteredSchedules) : null;

        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'package_id' => $this->package_id,
            'order_number' => $orderNumber,
            'event_date' => $this->event_date,
            'event_location' => $this->event_location,
            'guest_count' => $this->guest_count,
            'special_request' => $this->special_request,
            'total_price' => $this->total_price,
            'extra_guest_charge' => $this->extra_guest_charge,
            'payment_scheme' => $this->payment_scheme,
            'custom_schedules' => $customSchedulesJson ? json_decode($customSchedulesJson, true) : null,
            'dp_percentage' => $dpPercentage,
            'total_paid' => 0,
            'remaining_amount' => $this->total_price,
            'payment_status' => 'unpaid',
            'status' => 'pending',
        ]);

        // Save selected vendors
        foreach ($this->selected_vendors as $categoryId => $vendorId) {
            if ($vendorId) {
                $vendor = \App\Models\Vendor::find($vendorId);
                $category = \App\Models\VendorCategory::find($categoryId);
                
                $order->orderVendors()->create([
                    'vendor_id' => $vendorId,
                    'vendor_category_id' => $categoryId,
                    'vendor_name' => $vendor->name,
                    'vendor_category_name' => $category->name,
                    'price' => $vendor->price,
                ]);
            }
        }

        return redirect()->route('customer.orders.show', $order->id)
            ->with('success', 'Pesanan berhasil dibuat. Melanjutkan ke pembayaran...');
    }

    public function getSelectedPackageModelProperty()
    {
        return \App\Models\Package::find($this->package_id);
    }

    public function getPaymentBreakdownProperty()
    {
        if (! $this->package_id || ! $this->event_date) {
            return [];
        }

        $breakdown = app(\App\Services\PaymentSchemeService::class)->calculateBreakdown(
            $this->payment_scheme,
            (float) $this->total_price,
            \Carbon\Carbon::parse($this->event_date)
        );

        // Apply custom due dates dynamically if user entered them
        foreach ($breakdown as $i => &$item) {
            if (!empty($this->custom_due_dates[$i])) {
                $item['due_date'] = \Carbon\Carbon::parse($this->custom_due_dates[$i]);
            }
        }

        return $breakdown;
    }

    public function render()
    {
        return view('livewire.customer.booking-form');
    }
}
