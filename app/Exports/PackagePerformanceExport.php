<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PackagePerformanceExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                'Package' => $item->name,
                'Price' => 'Rp ' . number_format($item->price, 0, ',', '.'),
                'Bookings' => $item->total_bookings,
                'Revenue' => 'Rp ' . number_format($item->total_revenue, 0, ',', '.'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Package Name', 'Price', 'Total Bookings', 'Total Revenue'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '28A745']],
            ],
        ];
    }
}
