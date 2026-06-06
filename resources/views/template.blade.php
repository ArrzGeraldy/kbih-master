<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config("app.name", "Laravel") }} - Admin KBIH</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link
      href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
      rel="stylesheet"
    />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/dashboard-admin.js'])
  </head>
  <body class="font-sans antialiased bg-gray-100">
    <div class="w-full min-h-screen flex relative">
      <!-- aside -->
      <x-sidebar />

      <div
        class="flex-1 flex flex-col overflow-hidden ms-0 lg:ms-64"
        id="content"
      >
        <!-- topbar -->
        <x-topbar />

        <!-- main content -->
        <main class="flex-1 overflow-y-auto p-6">
          <x-flash-message />

        </main>
      </div>
    </div>

  </body>
</html>
