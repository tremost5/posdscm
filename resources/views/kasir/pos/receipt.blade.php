<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Detail Transaksi</h2>
    </x-slot>

    <section class="card p-5 space-y-4 max-w-3xl">
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
                        <th class="px-4 py-3 text-right">Harga</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $item->product_name }}</td>
                            <td class="px-4 py-3 text-right">{{ $item->qty }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="space-y-1 text-sm ml-auto max-w-sm">
            <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span></div>
            <div class="flex justify-between font-semibold"><span>Total</span><span>Rp {{ number_format($sale->total, 0, ',', '.') }}</span></div>
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

        <div class="flex gap-2 print:hidden">
            <button onclick="window.print()" class="btn-primary">Print</button>
            @if($sale->buyer_whatsapp)
                <form action="{{ route('kasir.sales.send-whatsapp', $sale) }}" method="POST">
                    @csrf
                    <button class="btn-primary" type="submit">Kirim ke WhatsApp</button>
                </form>
            @endif
            <a href="{{ route('kasir.pos.index') }}" class="btn-soft">Kembali</a>
        </div>
    </section>
</x-app-layout>
