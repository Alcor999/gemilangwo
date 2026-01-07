<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;
    protected $title;

    public function __construct($data, $title = 'Revenue Report')
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                'date' => $item['date'] ?? $item['month'] ?? $item['year'],
                'revenue' => 'Rp ' . number_format($item['revenue'], 0, ',', '.'),
                'transactions' => $item['transactions'] ?? $item['orders'] ?? 0,
            ];
        });
    }

    public function headings(): array
    {
        return ['Date/Period', 'Revenue', 'Transactions'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '007BFF']],
            ],
        ];
    }
}
