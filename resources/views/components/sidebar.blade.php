    <aside
        id="sidebar"
        class="bg-green-900 text-white w-64 transition-transform duration-200 flex-shrink-0 fixed top-0 h-screen z-20 -translate-x-full lg:-translate-x-0"
      >
        <div class="p-6 border-b border-green-800 flex justify-between">

          <div>
            <div class="text-lg font-semibold">{{ config('app.name', 'Laravel') }}</div>
            <div class="text-sm text-green-100">Admin KBIH</div>

          </div>

          <button class="p-2 rounded-lg lg:hidden  transition" id="toggle-sidebar">
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
            </svg>
          </button>
        </div>

  

    <nav class="p-3 space-y-1">
      <a
        href="{{ route('admin.dashboard') }}"
        class="block rounded px-3 py-2 text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-green-800' : 'hover:bg-green-800' }}"
      >Dashboard</a>

      <a
        href="{{ route('admin.paket.index') }}"
        class="block rounded px-3 py-2 text-sm {{ request()->routeIs('admin.paket.*') ? 'bg-green-800' : 'hover:bg-green-800' }}"
      >Paket</a>

      <a
        href="{{ route('admin.orders.index') }}"
        class="block rounded px-3 py-2 text-sm {{ request()->routeIs('admin.orders.*') ? 'bg-green-800' : 'hover:bg-green-800' }}"
      >Order</a>

      <a
        href="{{ route('admin.kelas.index') }}"
        class="block rounded px-3 py-2 text-sm {{ request()->routeIs('admin.kelas.*') ? 'bg-green-800' : 'hover:bg-green-800' }}"
      >Kelas</a>
    </nav>

        <div class="p-3 mt-auto">
          <div class="rounded-lg bg-green-800/60 p-3">
            <div class="text-xs text-green-100">Login sebagai</div>
            {{-- <div class="text-sm font-medium">{{ $adminName }}</div> --}}
          </div>
        </div>
      </aside>

            <!-- overlay (mobile) -->
      <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-10 hidden lg:hidden"></div>