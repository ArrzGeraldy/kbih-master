<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Laravel') }} - Daftar</title>

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link
      href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
      rel="stylesheet"
    />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="font-sans antialiased bg-slate-50 text-slate-900">
    <main class="min-h-screen flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
      <div class="w-full max-w-md">
    

        <!-- Form -->
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                 <h1 class="text-2xl font-semibold text-slate-900">Register</h1>
          <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
              <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100"
                placeholder="Nama Anda"
              />
              @if ($errors->has('name'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->first('name') }}</p>
              @endif
            </div>

            <!-- Email Address -->
            <div>
              <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
              <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100"
                placeholder="nama@email.com"
              />
              @if ($errors->has('email'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</p>
              @endif
            </div>

            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
              <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100"
                placeholder="••••••••"
              />
              @if ($errors->has('password'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->first('password') }}</p>
              @endif
            </div>

            <!-- Confirm Password -->
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Konfirmasi Password</label>
              <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100"
                placeholder="••••••••"
              />
              @if ($errors->has('password_confirmation'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->first('password_confirmation') }}</p>
              @endif
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              class="w-full rounded-lg bg-green-600 px-4 py-3 text-sm font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
            >
              Buat Akun
            </button>
          </form>

          <!-- Divider -->
          <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-slate-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="bg-white px-2 text-slate-500">atau</span>
            </div>
          </div>

          <!-- Login Link -->
          <div class="text-center">
            <p class="text-sm text-slate-600">
              Sudah punya akun?
              <a href="{{ route('login') }}" class="font-semibold text-green-600 hover:text-green-700">
                Masuk di sini
              </a>
            </p>
          </div>
        </div>
      </div>
    </main>
  </body>
</html>
