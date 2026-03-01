<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Dashboard Kasir</h2>
    </x-slot>

    <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="stat-card bg-gradient-to-br from-teal-600 to-cyan-600 md:col-span-1">
            <p class="text-white/80 text-sm">Transaksi Hari Ini</p>
            <p class="text-3xl font-bold mt-1">{{ $salesCount }}</p>
        </div>
        <div class="stat-card bg-gradient-to-br from-orange-500 to-rose-500 md:col-span-1 xl:col-span-1">
            <p class="text-white/80 text-sm">Omzet Hari Ini</p>
            <p class="text-3xl font-bold mt-1">Rp {{ number_format($salesTotal, 0, ',', '.') }}</p>
            <a href="{{ route('kasir.pos.index') }}" class="inline-flex mt-3 rounded-lg bg-white/20 px-3 py-2 text-sm font-medium hover:bg-white/30">Mulai Transaksi</a>
        </div>
        <div class="stat-card bg-gradient-to-br from-indigo-600 to-blue-600 md:col-span-1">
            <p class="text-white/80 text-sm">Omzet Stand NICC</p>
            <p class="text-3xl font-bold mt-1">Rp {{ number_format($locationTotals['NICC'], 0, ',', '.') }}</p>
        </div>
        <div class="stat-card bg-gradient-to-br from-emerald-600 to-lime-600 md:col-span-1">
            <p class="text-white/80 text-sm">Omzet Stand GRASA</p>
            <p class="text-3xl font-bold mt-1">Rp {{ number_format($locationTotals['GRASA'], 0, ',', '.') }}</p>
        </div>
    </div>

    <section class="card overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex justify-between items-center">
            <h3 class="font-semibold">Transaksi Terbaru</h3>
            <a href="{{ route('kasir.sales.index') }}" class="btn-soft">Semua Riwayat</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Invoice</th>
                        <th class="px-4 py-3 text-left">Stand</th>
                        <th class="px-4 py-3 text-left">Kasir</th>
                        <th class="px-4 py-3 text-left">Waktu</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestSales as $sale)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3 font-medium">{{ $sale->invoice_no }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $sale->stand_location }}</td>
                            <td class="px-4 py-3">{{ $sale->user->name }}</td>
                            <td class="px-4 py-3">{{ $sale->sold_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-4 text-center text-slate-500">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-app-layout>
