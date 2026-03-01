<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Supplier</h2>
    </x-slot>

    <section class="card p-5 max-w-2xl">
        <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="text-sm text-slate-600">Nama Supplier</label>
                <input type="text" name="name" class="input mt-1" value="{{ old('name', $supplier->name) }}" required>
            </div>
            <div>
                <label class="text-sm text-slate-600">No. Telepon</label>
                <input type="text" name="phone" class="input mt-1" value="{{ old('phone', $supplier->phone) }}">
            </div>
            <div>
                <label class="text-sm text-slate-600">Alamat</label>
                <textarea name="address" class="input mt-1" rows="3">{{ old('address', $supplier->address) }}</textarea>
            </div>
            <div>
                <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $supplier->is_active))> Supplier aktif</label>
            </div>
            <div class="flex gap-2">
                <button class="btn-primary">Update</button>
                <a href="{{ route('admin.suppliers.index') }}" class="btn-soft">Kembali</a>
            </div>
        </form>
    </section>
</x-app-layout>
