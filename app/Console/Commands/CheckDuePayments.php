<?php

namespace App\Console\Commands;

use App\Services\PaymentService;
use Illuminate\Console\Command;

class CheckDuePayments extends Command
{
    protected $signature = 'payment:check-due {--days=3 : Kirim reminder X hari sebelum jatuh tempo}';

    protected $description = 'Cek pembayaran jatuh tempo dan kirim reminder otomatis ke pelanggan';

    public function handle(PaymentService $paymentService): int
    {
        $days = (int) $this->option('days');
        $result = $paymentService->checkDuePayments($days);

        $this->info("Reminder terkirim: {$result['reminders_sent']}");
        $this->info("Pembayaran overdue: {$result['overdue_count']}");

        return self::SUCCESS;
    }
}
