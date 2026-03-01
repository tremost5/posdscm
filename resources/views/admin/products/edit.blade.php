<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Produk</h2>
    </x-slot>

    <section class="card p-5 max-w-3xl">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" class="grid md:grid-cols-2 gap-4">
            @csrf
            @method('PUT')
            <div>
                <label class="text-sm text-slate-600">Kategori</label>
                <select name="category_id" class="input mt-1" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm text-slate-600">Supplier</label>
                <select name="supplier_id" class="input mt-1">
                    <option value="">Tanpa Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected(old('supplier_id', $product->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm text-slate-600">SKU</label>
                <input name="sku" class="input mt-1" value="{{ old('sku', $product->sku) }}" required>
            </div>
            <div>
                <label class="text-sm text-slate-600">Nama Produk</label>
                <input name="name" class="input mt-1" value="{{ old('name', $product->name) }}" required>
            </div>
            <div>
                <label class="text-sm text-slate-600">Harga Beli</label>
                <input name="cost_price" type="number" step="0.01" min="0" class="input mt-1" value="{{ old('cost_price', $product->cost_price) }}" required>
            </div>
            <div>
                <label class="text-sm text-slate-600">Harga Jual</label>
                <input name="price" type="number" step="0.01" min="0" class="input mt-1" value="{{ old('price', $product->price) }}" required>
            </div>
            <div>
                <label class="text-sm text-slate-600">Stok</label>
                <input name="stock" type="number" min="0" class="input mt-1" value="{{ old('stock', $product->stock) }}" required>
            </div>
            <div class="md:col-span-2">
                <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))> Aktif dijual</label>
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button class="btn-primary">Update</button>
                <a href="{{ route('admin.products.index') }}" class="btn-soft">Kembali</a>
            </div>
        </form>
    </section>
</x-app-layout>
