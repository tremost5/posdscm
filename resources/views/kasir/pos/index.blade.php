<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-xl">Kasir Penjualan</h2>
            <form action="{{ route('kasir.pos.stand') }}" method="POST" class="flex items-center gap-2">
                @csrf
                <label for="headerStandLocation" class="text-sm text-slate-600">Lokasi Stand</label>
                <select id="headerStandLocation" name="stand_location" class="input w-36" onchange="this.form.submit()">
                    <option value="NICC" @selected($activeStandLocation === 'NICC')>NICC</option>
                    <option value="GRASA" @selected($activeStandLocation === 'GRASA')>GRASA</option>
                </select>
            </form>
        </div>
    </x-slot>

    <div class="grid xl:grid-cols-3 gap-4">
        <section class="xl:col-span-2 space-y-4">
            <div class="card p-4">
                <form id="posFilterForm" method="GET" class="grid sm:grid-cols-3 gap-3">
                    <input id="posSearchInput" type="text" name="search" value="{{ request('search') }}" class="input sm:col-span-2" placeholder="Cari nama / SKU produk">
                    <select id="posCategoryInput" name="category_id" class="input">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((int) request('category_id') === $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="sm:col-span-3 flex gap-2">
                        <button class="btn-primary" type="submit">Filter</button>
                        <a href="{{ route('kasir.pos.index') }}" class="btn-soft">Reset</a>
                    </div>
                </form>
            </div>

            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-3">
                @forelse($products as $product)
                    <article class="card p-4 flex flex-col">
                        <p class="text-xs text-slate-500">{{ $product->sku }} • {{ $product->category->name }}</p>
                        <h3 class="font-semibold mt-1">{{ $product->name }}</h3>
                        <p class="text-teal-700 font-bold mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-500 mt-1">Stok: {{ $product->stock }}</p>
                        <form action="{{ route('kasir.pos.add') }}" method="POST" class="mt-3 flex gap-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="number" name="qty" min="1" max="{{ $product->stock }}" value="1" class="input">
                            <button class="btn-primary">Tambah</button>
                        </form>
                    </article>
                @empty
                    <div class="card p-6 text-center text-slate-500 sm:col-span-2 xl:col-span-3">Produk tidak ditemukan.</div>
                @endforelse
            </div>

            <div>{{ $products->links() }}</div>
        </section>

        <section class="card p-4 h-fit xl:sticky xl:top-20 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold">Keranjang</h3>
                <form action="{{ route('kasir.pos.clear') }}" method="POST">
                    @csrf
                    <button class="text-xs text-rose-600 hover:underline">Kosongkan</button>
                </form>
            </div>

            <div class="space-y-2 max-h-[360px] overflow-auto pr-1">
                @forelse($cart as $item)
                    <div class="rounded-lg border border-slate-200 p-3">
                        <p class="text-sm font-semibold">{{ $item['name'] }}</p>
                        <p class="text-xs text-slate-500">{{ $item['sku'] }} • Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        <form action="{{ route('kasir.pos.update') }}" method="POST" class="mt-2 flex gap-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                            <input type="number" name="qty" min="0" max="{{ $item['stock'] }}" value="{{ $item['qty'] }}" class="input">
                            <button class="btn-soft">Update</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada item di keranjang.</p>
                @endforelse
            </div>

            <div class="rounded-lg bg-slate-50 p-3 text-sm space-y-1">
                <div class="flex justify-between"><span>Stand Aktif</span><span class="font-semibold">{{ $activeStandLocation }}</span></div>
                <div class="flex justify-between"><span>Total Item</span><span>{{ $summary['items'] }}</span></div>
                <div class="flex justify-between font-semibold"><span>Subtotal</span><span>Rp {{ number_format($summary['subtotal'], 0, ',', '.') }}</span></div>
            </div>

            <form action="{{ route('kasir.pos.checkout') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                @csrf
                <input type="hidden" name="stand_location" value="{{ $activeStandLocation }}">
                <input type="text" name="buyer_name" value="{{ old('buyer_name') }}" class="input" placeholder="Nama Pembeli (opsional)">
                <input type="text" name="buyer_whatsapp" value="{{ old('buyer_whatsapp') }}" class="input" placeholder="No WhatsApp Pembeli (opsional)">
                <select id="paymentMethodInput" name="payment_method" class="input" required>
                    <option value="tunai" @selected(old('payment_method', 'tunai') === 'tunai')>Tunai</option>
                    <option value="transfer" @selected(old('payment_method') === 'transfer')>Transfer</option>
                    <option value="qris" @selected(old('payment_method') === 'qris')>QRIS</option>
                </select>
                <input id="paidInput" type="number" name="paid" min="0" step="0.01" value="{{ old('paid') }}" class="input" placeholder="Bayar (Tunai)">
                <div id="paymentProofWrap" class="hidden space-y-1">
                    <label class="text-xs text-slate-600">Bukti Pembayaran (opsional)</label>
                    <input id="paymentProofInput" type="file" name="payment_proof" accept="image/*" capture="environment" class="input">
                    <p class="text-[11px] text-slate-500">Bisa langsung ambil dari kamera HP.</p>
                </div>
                <textarea name="notes" class="input" rows="2" placeholder="Catatan (opsional)">{{ old('notes') }}</textarea>
                <button class="btn-primary w-full">Checkout</button>
            </form>
        </section>
    </div>
</x-app-layout>
