<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Produk</h2>
    </x-slot>

    <div class="grid xl:grid-cols-3 gap-4">
        <section class="card p-5 space-y-3 xl:sticky xl:top-20 h-fit">
            <h3 class="font-semibold">Tambah Produk</h3>
            <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-3">
                @csrf
                <select name="category_id" class="input" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <select name="supplier_id" class="input">
                    <option value="">Tanpa Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
                <input name="sku" class="input" placeholder="SKU" required>
                <input name="name" class="input" placeholder="Nama Produk" required>
                <input name="cost_price" type="number" step="0.01" min="0" class="input" placeholder="Harga Beli" required>
                <input name="price" type="number" step="0.01" min="0" class="input" placeholder="Harga Jual" required>
                <input name="stock" type="number" min="0" class="input" placeholder="Stok" required>
                <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="is_active" value="1" checked> Aktif dijual</label>
                <button class="btn-primary w-full">Simpan Produk</button>
            </form>
        </section>

        <section class="card xl:col-span-2 overflow-hidden">
            <div class="p-5 border-b border-slate-200"><h3 class="font-semibold">Daftar Produk</h3></div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 text-left">SKU</th>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Kategori</th>
                            <th class="px-4 py-3 text-left">Supplier</th>
                            <th class="px-4 py-3 text-right">Harga Beli</th>
                            <th class="px-4 py-3 text-right">Harga Jual</th>
                            <th class="px-4 py-3 text-right">Margin/Unit</th>
                            <th class="px-4 py-3 text-right">Stok</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="border-t border-slate-100">
                                <td class="px-4 py-3">{{ $product->sku }}</td>
                                <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                                <td class="px-4 py-3">{{ $product->category->name }}</td>
                                <td class="px-4 py-3">{{ $product->supplier?->name ?: '-' }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-semibold {{ ($product->price - $product->cost_price) < 0 ? 'text-rose-600' : 'text-emerald-600' }}">Rp {{ number_format($product->price - $product->cost_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">{{ $product->stock }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn-soft">Edit</a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-soft">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="px-4 py-4 text-center text-slate-500">Data kosong.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $products->links() }}</div>
        </section>
    </div>
</x-app-layout>
