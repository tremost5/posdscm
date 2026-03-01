<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Kategori Produk</h2>
    </x-slot>

    <div class="grid lg:grid-cols-3 gap-4">
        <section class="card p-5 space-y-3">
            <h3 class="font-semibold">Tambah Kategori</h3>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-3">
                @csrf
                <input type="text" name="name" class="input" placeholder="Contoh: Snack" required>
                <button class="btn-primary w-full">Simpan</button>
            </form>
        </section>

        <section class="card lg:col-span-2 overflow-hidden">
            <div class="p-5 border-b border-slate-200">
                <h3 class="font-semibold">Daftar Kategori</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Slug</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr class="border-t border-slate-100">
                                <td class="px-4 py-3 font-medium">{{ $category->name }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $category->slug }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn-soft">Edit</a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-soft">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-4 text-center text-slate-500">Data kosong.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $categories->links() }}</div>
        </section>
    </div>
</x-app-layout>
