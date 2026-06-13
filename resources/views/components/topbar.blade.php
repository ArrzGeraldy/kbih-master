<header class="bg-white border-b border-gray-200 px-6 py-4">
          <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
              <button
                type="button"
                id="toggle-topbar"
                class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700"
              >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu-icon lucide-menu"><path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h16"/></svg>
              </button>

              <div>
                <h1 class="text-lg font-semibold text-gray-900">Dashboard</h1>
                <p class="text-sm text-gray-600">Ringkasan data KBIH</p>
              </div>
            </div>

            <div class="flex items-center gap-3">
               <form method="POST" action="{{ route('logout') }}" class="inline">
              @csrf
              <button type="submit" class="font-medium inline-flex items-center px-4 py-2 rounded-md bg-red-600 text-white shadow-sm hover:bg-red-500 text-sm">Logout</button>
            </form>
            </div>
          </div>
        </header>