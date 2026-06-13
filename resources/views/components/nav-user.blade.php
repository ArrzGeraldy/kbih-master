 <header class="border-b border-slate-200 bg-white/80 backdrop-blur">
      <div class="max-w-[90%] md:max-w-5xl mx-auto flex items-center justify-between py-4">
        <a href="/" class="text-2xl font-semibold tracking-tight">KBIH <span class="text-green-600">Care</span></a>

        <nav class="hidden md:flex items-center gap-6 text-sm text-slate-700">
          @auth
            <a href="{{ route('dashboard') }}" class="hover:text-slate-900 font-medium text-green-700">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
              @csrf
              <button type="submit" class="font-medium inline-flex items-center px-4 py-2 rounded-md bg-red-600 text-white shadow-sm hover:bg-red-500">Logout</button>
            </form>
          @else
            <a href="{{ route('login') }}" class="hover:text-slate-900">Login</a>
            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-green-600 text-white shadow-sm hover:bg-green-500">Daftar</a>
          @endauth
        </nav>
      </div>
    </header>