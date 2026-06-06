   <nav class="border-b border-black">
      <div
        class="max-w-[90%] md:max-w-3xl lg:max-w-6xl mx-auto flex items-center justify-between py-2 w-full"
      >
        <a href="/" class="text-2xl font-medium">Logo</a>

        <ul class="flex items-center gap-4">
          @auth
            <li>
              <a href="{{ route('dashboard') }}" class="hover:text-gray-600 transition-colors">Dashboard</a>
            </li>
          @else
            <li>
              <a href="{{ route('login') }}" class="hover:text-gray-600 transition-colors">Login</a>
            </li>
            <li>
              <a href="{{ route('register') }}" class="text-white bg-green-600 px-4 py-2 text-sm rounded-md font-medium transition-colors">Sign Up</a>
            </li>
          @endauth
        </ul>
      </div>
    </nav>