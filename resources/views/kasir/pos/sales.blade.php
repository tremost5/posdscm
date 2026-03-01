<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Riwayat Transaksi</h2>
    </x-slot>

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
                        <th class="px-4 py-3 text-right">Total</th>
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
                            <td class="px-4 py-3 text-right"><a href="{{ route('kasir.sales.show', $sale) }}" class="btn-soft">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="px-4 py-4 text-center text-slate-500">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $sales->links() }}</div>
    </section>
</x-app-layout>
