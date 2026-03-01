<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit User</h2>
    </x-slot>

    <section class="card p-5 max-w-2xl">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="grid md:grid-cols-2 gap-4">
            @csrf
            @method('PUT')
            <div>
                <label class="text-sm text-slate-600">Nama</label>
                <input name="name" class="input mt-1" value="{{ old('name', $user->name) }}" required>
            </div>
            <div>
                <label class="text-sm text-slate-600">Email</label>
                <input type="email" name="email" class="input mt-1" value="{{ old('email', $user->email) }}" required>
            </div>
            <div>
                <label class="text-sm text-slate-600">Role</label>
                <select name="role" class="input mt-1" required>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                    <option value="kasir" @selected(old('role', $user->role) === 'kasir')>Kasir</option>
                </select>
            </div>
            <div>
                <label class="text-sm text-slate-600">Password Baru (opsional)</label>
                <input name="password" type="password" class="input mt-1">
            </div>
            <div class="md:col-span-2">
                <label class="text-sm text-slate-600">Konfirmasi Password Baru</label>
                <input name="password_confirmation" type="password" class="input mt-1">
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button class="btn-primary">Update</button>
                <a href="{{ route('admin.users.index') }}" class="btn-soft">Kembali</a>
            </div>
        </form>
    </section>
</x-app-layout>
