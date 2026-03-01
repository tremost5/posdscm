<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Detail Transaksi</h2>
    </x-slot>

    @php
        $waPhone = preg_replace('/\D+/', '', (string) ($sale->buyer_whatsapp ?? ''));
        if ($waPhone !== '' && str_starts_with($waPhone, '0')) {
            $waPhone = '62'.substr($waPhone, 1);
        }
        if ($waPhone !== '' && ! str_starts_with($waPhone, '62')) {
            $waPhone = '62'.$waPhone;
        }

        $waLines = [
            'Halo '.($sale->buyer_name ?: 'Pelanggan').',',
            'Terima kasih sudah berbelanja di POS DSCM.',
            '',
            'No Nota: '.$sale->invoice_no,
            'Tanggal: '.$sale->sold_at->format('d-m-Y H:i'),
            'Stand: '.$sale->stand_location,
            'Metode: '.strtoupper($sale->payment_method),
            'Total: Rp '.number_format($sale->total, 0, ',', '.'),
            '',
            'Detail item:',
        ];
        foreach ($sale->items as $item) {
            $waLines[] = '- '.$item->product_name.' x'.$item->qty.' = Rp '.number_format($item->subtotal, 0, ',', '.');
        }
        $waLines[] = '';
        $waLines[] = 'Terima kasih.';
        $waMessage = rawurlencode(implode("\n", $waLines));
        $waUrl = $waPhone !== '' ? "https://wa.me/{$waPhone}?text={$waMessage}" : null;
    @endphp

    <section class="card p-5 space-y-4 max-w-5xl">
        <div class="flex flex-wrap justify-between gap-3">
            <div>
                <p class="text-sm text-slate-500">Invoice</p>
                <p class="font-semibold">{{ $sale->invoice_no }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Kasir</p>
                <p class="font-semibold">{{ $sale->user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Stand</p>
                <p class="font-semibold">{{ $sale->stand_location }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Metode</p>
                <p class="font-semibold uppercase">{{ $sale->payment_method }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Waktu</p>
                <p class="font-semibold">{{ $sale->sold_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Pembeli</p>
                <p class="font-semibold">{{ $sale->buyer_name ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">WhatsApp</p>
                <p class="font-semibold">{{ $sale->buyer_whatsapp ?: '-' }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Produk</th>
                        <th class="px-4 py-3 text-right">Qty</th>
                        <th class="px-4 py-3 text-right">Jual</th>
                        <th class="px-4 py-3 text-right">Beli</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                        <th class="px-4 py-3 text-right">Subtotal Modal</th>
                        <th class="px-4 py-3 text-right">Margin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $item->product_name }}</td>
                            <td class="px-4 py-3 text-right">{{ $item->qty }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($item->cost_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($item->subtotal_cost, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-semibold {{ $item->subtotal_margin < 0 ? 'text-rose-600' : 'text-emerald-600' }}">Rp {{ number_format($item->subtotal_margin, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="space-y-1 text-sm ml-auto max-w-sm">
            <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span>Total Omzet</span><span>Rp {{ number_format($sale->total, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span>Total Modal</span><span>Rp {{ number_format($sale->total_cost, 0, ',', '.') }}</span></div>
            <div class="flex justify-between font-semibold"><span>Margin Kotor</span><span>Rp {{ number_format($sale->total_margin, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span>Bayar</span><span>Rp {{ number_format($sale->paid, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span>Kembalian</span><span>Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span></div>
        </div>

        @if($sale->payment_proof_path)
            <div class="space-y-2">
                <p class="text-sm text-slate-600">Bukti Pembayaran</p>
                <a href="{{ \Illuminate\Support\Facades\Storage::url($sale->payment_proof_path) }}" target="_blank" class="inline-block">
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($sale->payment_proof_path) }}" alt="Bukti Pembayaran" class="max-h-56 rounded-lg border border-slate-200">
                </a>
            </div>
        @endif

        <div class="flex gap-2">
            @if($waUrl)
                <a href="{{ $waUrl }}" target="_blank" class="btn-primary">Kirim ke WhatsApp</a>
            @endif
            <a href="{{ route('admin.sales.index') }}" class="btn-soft w-fit">Kembali</a>
        </div>
    </section>
</x-app-layout>
