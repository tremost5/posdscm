<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Dashboard Admin</h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
        <div class="stat-card bg-gradient-to-br from-teal-600 to-cyan-600">
            <p class="text-white/80 text-sm">Total Kategori</p>
            <p class="text-3xl font-bold mt-1">{{ $stats['categories'] }}</p>
        </div>
        <div class="stat-card bg-gradient-to-br from-sky-600 to-indigo-600">
            <p class="text-white/80 text-sm">Total Produk</p>
            <p class="text-3xl font-bold mt-1">{{ $stats['products'] }}</p>
        </div>
        <div class="stat-card bg-gradient-to-br from-violet-600 to-fuchsia-600">
            <p class="text-white/80 text-sm">Total Supplier</p>
            <p class="text-3xl font-bold mt-1">{{ $stats['suppliers'] }}</p>
        </div>
        <div class="stat-card bg-gradient-to-br from-emerald-600 to-green-600">
            <p class="text-white/80 text-sm">Penjualan Hari Ini</p>
            <p class="text-3xl font-bold mt-1">Rp {{ number_format($stats['today_sales'], 0, ',', '.') }}</p>
        </div>
        <div class="stat-card bg-gradient-to-br from-orange-500 to-rose-500">
            <p class="text-white/80 text-sm">Margin Hari Ini</p>
            <p class="text-3xl font-bold mt-1">Rp {{ number_format($stats['today_margin'], 0, ',', '.') }}</p>
            <p class="text-xs text-white/80 mt-1">Kasir: {{ $stats['cashiers'] }} orang</p>
        </div>
    </div>

    <section class="card">
        <div class="p-5 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-semibold">Transaksi Terbaru</h3>
            <a href="{{ route('admin.sales.index') }}" class="btn-soft">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Invoice</th>
                        <th class="px-4 py-3 text-left">Kasir</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-right">Margin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentSales as $sale)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3 font-medium">{{ $sale->invoice_no }}</td>
                            <td class="px-4 py-3">{{ $sale->user->name }}</td>
                            <td class="px-4 py-3">{{ $sale->sold_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($sale->total_margin, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-4 text-center text-slate-500">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-app-layout>
