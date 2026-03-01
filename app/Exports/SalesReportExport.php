<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesReportExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(private readonly Collection $sales) {}

    public function collection()
    {
        return $this->sales;
    }

    public function headings(): array
    {
        return [
            'Invoice',
            'Tanggal',
            'Kasir',
            'Stand',
            'Pembeli',
            'No WhatsApp',
            'Metode',
            'Omzet',
            'Modal',
            'Margin',
            'Bayar',
            'Kembalian',
            'Catatan',
        ];
    }

    /**
     * @param  Sale  $sale
     */
    public function map($sale): array
    {
        return [
            $sale->invoice_no,
            $sale->sold_at?->format('Y-m-d H:i:s'),
            $sale->user?->name,
            $sale->stand_location,
            $sale->buyer_name,
            $sale->buyer_whatsapp,
            strtoupper((string) $sale->payment_method),
            (float) $sale->total,
            (float) $sale->total_cost,
            (float) $sale->total_margin,
            (float) $sale->paid,
            (float) $sale->change_amount,
            $sale->notes,
        ];
    }
}
