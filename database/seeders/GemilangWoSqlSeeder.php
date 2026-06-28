<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GemilangWoSqlSeeder extends Seeder
{
    /** Tabel yang tidak di-import (runtime / framework). */
    protected array $skipTables = [
        'migrations',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
    ];

    /** Urutan truncate (anak → induk). */
    protected array $truncateOrder = [
        'chat_messages',
        'support_tickets',
        'order_vendors',
        'payments',
        'calendar_events',
        'reviews',
        'wishlists',
        'discount_package',
        'discounts',
        'orders',
        'package_vendor_category',
        'vendors',
        'vendor_categories',
        'payment_schemes',
        'gallery_images',
        'videos',
        'video_testimonials',
        'blocked_dates',
        'availabilities',
        'notifications',
        'sms_logs',
        'packages',
        'banks',
        'users',
    ];

    public function run(): void
    {
        $path = base_path('gemilangwo-9.sql');

        if (! file_exists($path)) {
            $this->command->error('File gemilangwo-9.sql tidak ditemukan di root proyek.');

            return;
        }

        $this->command->info('Mengimpor data dari gemilangwo-9.sql ...');

        Schema::disableForeignKeyConstraints();
        $this->truncateSeedTables();
        Schema::enableForeignKeyConstraints();

        Schema::disableForeignKeyConstraints();

        $statements = $this->extractInsertStatements(file_get_contents($path));
        $imported = 0;

        foreach ($statements as $statement) {
            if ($this->shouldSkipStatement($statement)) {
                continue;
            }

            DB::unprepared($statement);
            $imported++;
        }

        Schema::enableForeignKeyConstraints();

        $this->command->info("✅ {$imported} blok INSERT berhasil diimpor dari gemilangwo-9.sql");
        $this->command->info('');
        $this->command->info('Akun login (password: password123):');
        $this->command->info('  Admin    : admin@gemilangwo.test');
        $this->command->info('  Owner    : owner@gemilangwo.test');
        $this->command->info('  Customer : budi@gemilangwo.test');
    }

    protected function truncateSeedTables(): void
    {
        foreach ($this->truncateOrder as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            DB::table($table)->delete();
        }
    }

    /** @return list<string> */
    protected function extractInsertStatements(string $sql): array
    {
        if (preg_match_all('/INSERT INTO `[^`]+`[^;]*;/si', $sql, $matches)) {
            return $matches[0];
        }

        return [];
    }

    protected function shouldSkipStatement(string $statement): bool
    {
        foreach ($this->skipTables as $table) {
            if (preg_match('/INSERT INTO `'.preg_quote($table, '/').'`/i', $statement)) {
                return true;
            }
        }

        return false;
    }
}
