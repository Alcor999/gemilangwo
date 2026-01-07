<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait DatabaseHelper
{
    /**
     * Get database driver name
     */
    public function getDatabaseDriver(): string
    {
        return config('database.default');
    }

    /**
     * Get MONTH extraction SQL based on database driver
     */
    public function getMonthRaw($column): string
    {
        $driver = $this->getDatabaseDriver();

        return match($driver) {
            'sqlite' => "CAST(strftime('%m', $column) AS INTEGER)",
            'pgsql' => "EXTRACT(MONTH FROM $column)",
            default => "MONTH($column)", // MySQL, MariaDB
        };
    }

    /**
     * Get YEAR extraction SQL based on database driver
     */
    public function getYearRaw($column): string
    {
        $driver = $this->getDatabaseDriver();

        return match($driver) {
            'sqlite' => "CAST(strftime('%Y', $column) AS INTEGER)",
            'pgsql' => "EXTRACT(YEAR FROM $column)",
            default => "YEAR($column)", // MySQL, MariaDB
        };
    }

    /**
     * Get DATE extraction SQL based on database driver
     */
    public function getDateRaw($column): string
    {
        $driver = $this->getDatabaseDriver();

        return match($driver) {
            'sqlite' => "DATE($column)",
            'pgsql' => "DATE($column)",
            default => "DATE($column)", // All support DATE()
        };
    }

    /**
     * Get YEAR-MONTH format SQL based on database driver
     */
    public function getYearMonthRaw($column): string
    {
        $driver = $this->getDatabaseDriver();

        return match($driver) {
            'sqlite' => "strftime('%Y-%m', $column)",
            'pgsql' => "TO_CHAR($column, 'YYYY-MM')",
            default => "DATE_FORMAT($column, '%Y-%m')", // MySQL, MariaDB
        };
    }

    /**
     * Group by month and year with proper ordering
     */
    public function groupByMonthYear($query, $column = 'created_at')
    {
        $driver = $this->getDatabaseDriver();

        if ($driver === 'sqlite') {
            return $query->groupBy(
                DB::raw("CAST(strftime('%Y', $column) AS INTEGER)"),
                DB::raw("CAST(strftime('%m', $column) AS INTEGER)")
            );
        } elseif ($driver === 'pgsql') {
            return $query->groupBy(
                DB::raw("EXTRACT(YEAR FROM $column)"),
                DB::raw("EXTRACT(MONTH FROM $column)")
            );
        }

        // MySQL, MariaDB
        return $query->groupBy(
            DB::raw("YEAR($column)"),
            DB::raw("MONTH($column)")
        );
    }
}
