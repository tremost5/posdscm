<nav x-data="{ open: false }" class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-teal-600 text-white font-bold">P</span>
                    <span class="font-semibold text-slate-800">POS DSCM</span>
                </a>

                <div class="hidden sm:flex sm:items-center sm:ms-8 gap-2 text-sm font-medium">
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-pill {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.categories.index') }}" class="nav-pill {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Kategori</a>
                        <a href="{{ route('admin.suppliers.index') }}" class="nav-pill {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">Supplier</a>
                        <a href="{{ route('admin.products.index') }}" class="nav-pill {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Produk</a>
                        <a href="{{ route('admin.sales.index') }}" class="nav-pill {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">Transaksi</a>
                        <a href="{{ route('admin.users.index') }}" class="nav-pill {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">User</a>
                    @else
                        <a href="{{ route('kasir.dashboard') }}" class="nav-pill {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('kasir.pos.index') }}" class="nav-pill {{ request()->routeIs('kasir.pos.*') ? 'active' : '' }}">Kasir</a>
                        <a href="{{ route('kasir.sales.index') }}" class="nav-pill {{ request()->routeIs('kasir.sales.*') ? 'active' : '' }}">Riwayat</a>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-2">
                <a href="{{ route('profile.edit') }}" class="nav-pill">{{ auth()->user()->name }} ({{ strtoupper(auth()->user()->role) }})</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="nav-pill">Logout</button>
                </form>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-200 bg-white">
        <div class="px-4 pt-3 pb-4 space-y-2 text-sm">
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="block nav-pill {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.categories.index') }}" class="block nav-pill {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Kategori</a>
                <a href="{{ route('admin.suppliers.index') }}" class="block nav-pill {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">Supplier</a>
                <a href="{{ route('admin.products.index') }}" class="block nav-pill {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Produk</a>
                <a href="{{ route('admin.sales.index') }}" class="block nav-pill {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">Transaksi</a>
                <a href="{{ route('admin.users.index') }}" class="block nav-pill {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">User</a>
            @else
                <a href="{{ route('kasir.dashboard') }}" class="block nav-pill {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('kasir.pos.index') }}" class="block nav-pill {{ request()->routeIs('kasir.pos.*') ? 'active' : '' }}">Kasir</a>
                <a href="{{ route('kasir.sales.index') }}" class="block nav-pill {{ request()->routeIs('kasir.sales.*') ? 'active' : '' }}">Riwayat</a>
            @endif

            <a href="{{ route('profile.edit') }}" class="block nav-pill">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left nav-pill">Logout</button>
            </form>
        </div>
    </div>
</nav>
