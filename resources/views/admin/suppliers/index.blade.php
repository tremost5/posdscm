<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Supplier / Vendor</h2>
    </x-slot>

    <div class="grid lg:grid-cols-3 gap-4">
        <section class="card p-5 space-y-3">
            <h3 class="font-semibold">Tambah Supplier</h3>
            <form action="{{ route('admin.suppliers.store') }}" method="POST" class="space-y-3">
                @csrf
                <input type="text" name="name" value="{{ old('name') }}" class="input" placeholder="Nama Supplier" required>
                <input type="text" name="phone" value="{{ old('phone') }}" class="input" placeholder="No. Telepon">
                <textarea name="address" class="input" rows="3" placeholder="Alamat">{{ old('address') }}</textarea>
                <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="is_active" value="1" checked> Aktif</label>
                <button class="btn-primary w-full">Simpan Supplier</button>
            </form>
        </section>

        <section class="card lg:col-span-2 overflow-hidden">
            <div class="p-5 border-b border-slate-200"><h3 class="font-semibold">Daftar Supplier</h3></div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Telepon</th>
                            <th class="px-4 py-3 text-left">Alamat</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <tr class="border-t border-slate-100">
                                <td class="px-4 py-3 font-medium">{{ $supplier->name }}</td>
                                <td class="px-4 py-3">{{ $supplier->phone ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $supplier->address ?: '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-1 text-xs {{ $supplier->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                        {{ $supplier->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn-soft">Edit</a>
                                        <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}" onsubmit="return confirm('Hapus supplier ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-soft">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-4 text-center text-slate-500">Data supplier belum ada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $suppliers->links() }}</div>
        </section>
    </div>
</x-app-layout>
