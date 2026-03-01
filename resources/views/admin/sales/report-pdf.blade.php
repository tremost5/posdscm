<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
        h1 { margin: 0; font-size: 18px; }
        .meta { margin-top: 8px; color: #475569; }
        .summary { margin-top: 14px; }
        .summary td { padding: 4px 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 7px; }
        th { background: #f1f5f9; text-align: left; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    @php
        $periodLabels = [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
        ];
    @endphp

    <h1>Laporan Penjualan {{ $periodLabels[$filters['period']] ?? 'Harian' }}</h1>
    <div class="meta">
        Periode: {{ $filters['start']->format('d M Y') }} - {{ $filters['end']->format('d M Y') }}
        @if(!empty($filters['stand_location']))
            <br>Stand: {{ $filters['stand_location'] }}
        @endif
    </div>

    <table class="summary">
        <tr>
            <td><strong>Total Omzet</strong></td>
            <td class="text-right">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</td>
            <td><strong>Total Modal</strong></td>
            <td class="text-right">Rp {{ number_format($summary['cost'], 0, ',', '.') }}</td>
            <td><strong>Total Margin</strong></td>
            <td class="text-right">Rp {{ number_format($summary['margin'], 0, ',', '.') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Stand</th>
                <th>Pembeli</th>
                <th>WhatsApp</th>
                <th>Metode</th>
                <th class="text-right">Omzet</th>
                <th class="text-right">Modal</th>
                <th class="text-right">Margin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->invoice_no }}</td>
                    <td>{{ $sale->sold_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $sale->user->name }}</td>
                    <td>{{ $sale->stand_location }}</td>
                    <td>{{ $sale->buyer_name ?: '-' }}</td>
                    <td>{{ $sale->buyer_whatsapp ?: '-' }}</td>
                    <td>{{ strtoupper($sale->payment_method) }}</td>
                    <td class="text-right">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($sale->total_cost, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($sale->total_margin, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">Tidak ada transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
