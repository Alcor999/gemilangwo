<?php

namespace Tests\Unit;

use App\Models\PaymentScheme;
use App\Services\PaymentSchemeService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentSchemeServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PaymentSchemeSeeder::class);
    }

    /**
     * Verifies that installment due dates are calculated correctly from the event date.
     * Regression test: previously due dates were off by 1 day due to UTC-vs-local
     * timezone mismatch when Carbon computed subDays without startOfDay normalization.
     */
    public function test_installment_5x_due_dates_calculated_correctly_from_event_date()
    {
        $service = new PaymentSchemeService();
        $eventDate = Carbon::parse('2026-10-02')->startOfDay(); // Oct 2

        $breakdown = $service->calculateBreakdown('installment_5x', 170_000_000, $eventDate);

        // Cicilan ke-2: H-60 from Oct 2 = Aug 3
        $this->assertEquals('2026-08-03', $breakdown[1]['due_date']->toDateString());

        // Cicilan ke-3: H-45 from Oct 2 = Aug 18
        $this->assertEquals('2026-08-18', $breakdown[2]['due_date']->toDateString());

        // Cicilan ke-4: H-30 from Oct 2 = Sep 2
        $this->assertEquals('2026-09-02', $breakdown[3]['due_date']->toDateString());

        // Cicilan ke-5: H-14 from Oct 2 = Sep 18
        $this->assertEquals('2026-09-18', $breakdown[4]['due_date']->toDateString());
    }

    public function test_installment_3x_due_dates_calculated_correctly_from_event_date()
    {
        $service = new PaymentSchemeService();
        $eventDate = Carbon::parse('2026-10-02')->startOfDay();

        $breakdown = $service->calculateBreakdown('installment_3x', 100_000_000, $eventDate);

        // Cicilan ke-2: H-30 from Oct 2 = Sep 2
        $this->assertEquals('2026-09-02', $breakdown[1]['due_date']->toDateString());

        // Cicilan ke-3: H-14 from Oct 2 = Sep 18
        $this->assertEquals('2026-09-18', $breakdown[2]['due_date']->toDateString());
    }

    public function test_dp_scheme_pelunasan_due_date_is_h14_before_event()
    {
        $service = new PaymentSchemeService();
        $eventDate = Carbon::parse('2026-12-25')->startOfDay(); // Dec 25

        foreach (['dp_20', 'dp_30', 'dp_40', 'dp_50'] as $scheme) {
            $breakdown = $service->calculateBreakdown($scheme, 100_000_000, $eventDate);
            // Pelunasan: H-14 from Dec 25 = Dec 11
            $this->assertEquals('2026-12-11', $breakdown[1]['due_date']->toDateString(), "Scheme $scheme failed");
        }
    }

    public function test_due_dates_in_the_past_are_clamped_to_tomorrow_but_before_h4()
    {
        $service = new PaymentSchemeService();
        // Event date 5 days from now - all installment due dates will be in the past
        $eventDate = Carbon::now()->startOfDay()->addDays(5);

        $breakdown = $service->calculateBreakdown('dp_30', 100_000_000, $eventDate);
        // Pelunasan H-14 would be in the past; fallback should be tomorrow
        // but capped at H-4 (event - 4 days = 1 day from now)
        $maxDate = $eventDate->copy()->subDays(4)->toDateString();

        $this->assertNotNull($breakdown[1]['due_date']);
        // Due date must be <= H-4
        $this->assertLessThanOrEqual($maxDate, $breakdown[1]['due_date']->toDateString());
        // Due date must be >= today
        $this->assertGreaterThanOrEqual(now()->toDateString(), $breakdown[1]['due_date']->toDateString());
    }
}
