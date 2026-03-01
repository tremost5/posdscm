<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Transaksi Penjualan</h2>
    </x-slot>

    @php
        $periodLabels = [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
        ];
        $activePeriodLabel = $periodLabels[$filters['period']] ?? 'Harian';
        $rangeLabel = $filters['start']->format('d M Y').' - '.$filters['end']->format('d M Y');
        $standLabel = $filters['stand_location'] ? ' | Stand: '.$filters['stand_location'] : '';
    @endphp

    <section class="card p-4 mb-4">
        <form method="GET" action="{{ route('admin.sales.index') }}" class="grid md:grid-cols-5 gap-3 items-end">
            <div>
                <label class="block text-sm text-slate-600 mb-1">Periode</label>
                <select name="period" class="input">
                    <option value="daily" @selected($filters['period'] === 'daily')>Harian</option>
                    <option value="weekly" @selected($filters['period'] === 'weekly')>Mingguan</option>
                    <option value="monthly" @selected($filters['period'] === 'monthly')>Bulanan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-slate-600 mb-1">Pilih Tanggal</label>
                <input type="date" name="date" value="{{ $filters['date'] }}" class="input">
            </div>
            <div>
                <label class="block text-sm text-slate-600 mb-1">Stand</label>
                <select name="stand_location" class="input">
                    <option value="">Semua Stand</option>
                    <option value="NICC" @selected(($filters['stand_location'] ?? null) === 'NICC')>NICC</option>
                    <option value="GRASA" @selected(($filters['stand_location'] ?? null) === 'GRASA')>GRASA</option>
                </select>
            </div>
            <div class="flex flex-wrap gap-2 md:col-span-2 md:justify-end">
                <button type="submit" class="btn-primary">Terapkan</button>
                <a href="{{ route('admin.sales.index') }}" class="btn-soft">Reset</a>
                <a href="{{ route('admin.sales.export.excel', request()->only('period', 'date', 'stand_location')) }}" class="btn-soft">Export Excel</a>
                <a href="{{ route('admin.sales.export.pdf', request()->only('period', 'date', 'stand_location')) }}" class="btn-soft">Export PDF</a>
            </div>
        </form>
        <p class="text-sm text-slate-500 mt-3">Laporan {{ $activePeriodLabel }} ({{ $rangeLabel }}){{ $standLabel }}</p>
    </section>

    <div class="grid md:grid-cols-3 gap-4 mb-4">
        <div class="card p-4">
            <p class="text-sm text-slate-500">Total Omzet</p>
            <p class="text-2xl font-bold">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="card p-4">
            <p class="text-sm text-slate-500">Total Modal</p>
            <p class="text-2xl font-bold">Rp {{ number_format($summary['cost'], 0, ',', '.') }}</p>
        </div>
        <div class="card p-4">
            <p class="text-sm text-slate-500">Total Margin Kotor</p>
            <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($summary['margin'], 0, ',', '.') }}</p>
        </div>
    </div>

    <section class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Invoice</th>
                        <th class="px-4 py-3 text-left">Stand</th>
                        <th class="px-4 py-3 text-left">Kasir</th>
                        <th class="px-4 py-3 text-left">Pembeli</th>
                        <th class="px-4 py-3 text-left">Metode</th>
                        <th class="px-4 py-3 text-left">Bukti</th>
                        <th class="px-4 py-3 text-left">Waktu</th>
                        <th class="px-4 py-3 text-right">Omzet</th>
                        <th class="px-4 py-3 text-right">Modal</th>
                        <th class="px-4 py-3 text-right">Margin</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3 font-medium">{{ $sale->invoice_no }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $sale->stand_location }}</td>
                            <td class="px-4 py-3">{{ $sale->user->name }}</td>
                            <td class="px-4 py-3">{{ $sale->buyer_name ?: '-' }}</td>
                            <td class="px-4 py-3 uppercase">{{ $sale->payment_method }}</td>
                            <td class="px-4 py-3">{{ $sale->payment_proof_path ? 'Ada' : '-' }}</td>
                            <td class="px-4 py-3">{{ $sale->sold_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($sale->total_cost, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-semibold {{ $sale->total_margin < 0 ? 'text-rose-600' : 'text-emerald-600' }}">Rp {{ number_format($sale->total_margin, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('admin.sales.show', $sale) }}" class="btn-soft">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="11" class="px-4 py-4 text-center text-slate-500">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $sales->links() }}</div>
    </section>
</x-app-layout>
