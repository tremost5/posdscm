<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Manajemen User</h2>
    </x-slot>

    <div class="grid xl:grid-cols-3 gap-4">
        <section class="card p-5 space-y-3 xl:sticky xl:top-20 h-fit">
            <h3 class="font-semibold">Tambah User</h3>
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-3">
                @csrf
                <input name="name" class="input" placeholder="Nama" required>
                <input name="email" type="email" class="input" placeholder="Email" required>
                <select name="role" class="input" required>
                    <option value="admin">Admin</option>
                    <option value="kasir" selected>Kasir</option>
                </select>
                <input name="password" type="password" class="input" placeholder="Password" required>
                <input name="password_confirmation" type="password" class="input" placeholder="Konfirmasi Password" required>
                <button class="btn-primary w-full">Simpan User</button>
            </form>
        </section>

        <section class="card xl:col-span-2 overflow-hidden">
            <div class="p-5 border-b border-slate-200"><h3 class="font-semibold">Daftar User</h3></div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Role</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="border-t border-slate-100">
                                <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3 uppercase">{{ $user->role }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-soft">Edit</a>
                                        @if(auth()->id() !== $user->id)
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-soft">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $users->links() }}</div>
        </section>
    </div>
</x-app-layout>
