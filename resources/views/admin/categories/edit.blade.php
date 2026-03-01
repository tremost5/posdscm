<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Kategori</h2>
    </x-slot>

    <section class="card p-5 max-w-xl">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="text-sm text-slate-600">Nama Kategori</label>
                <input type="text" name="name" class="input mt-1" value="{{ old('name', $category->name) }}" required>
            </div>
            <div class="flex gap-2">
                <button class="btn-primary">Update</button>
                <a href="{{ route('admin.categories.index') }}" class="btn-soft">Kembali</a>
            </div>
        </form>
    </section>
</x-app-layout>
