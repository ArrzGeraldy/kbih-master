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
          <div class="max-w-4xl">
            <div class="flex items-start justify-between gap-4 mb-6">
              <div>
                <h2 class="text-xl font-semibold text-gray-900">Edit Paket</h2>
                <p class="text-sm text-gray-600">Perbarui data paket.</p>
              </div>

              <a
                href="{{ route('admin.paket.index') }}"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
              >
                Kembali
              </a>
            </div>

          <x-flash-message />

            @if ($errors->any())
              <div class="mb-6 rounded-md border border-red-200 bg-red-50 p-4">
                <div class="text-sm font-medium text-red-800">Periksa input kamu:</div>
                <ul class="mt-2 list-disc ps-5 text-sm text-red-700">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="rounded-lg border border-gray-200 bg-white p-6">
              <form method="POST" action="{{ route('admin.paket.update', $paket) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                  <div class="sm:col-span-2">
                    <label for="nama_paket" class="block text-sm font-medium text-gray-700">Nama Paket</label>
                    <input
                      type="text"
                      id="nama_paket"
                      name="nama_paket"
                      value="{{ old('nama_paket', $paket->nama_paket) }}"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                    />
                  </div>

                  <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Tipe Paket</label>
                    <select
                      id="type"
                      name="type"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                    >
                      <option value="BIMBINGAN_HAJI" {{ old('type', $paket->type) === 'BIMBINGAN_HAJI' ? 'selected' : '' }}>Bimbingan Haji</option>
                      <option value="UMROH" {{ old('type', $paket->type) === 'UMROH' ? 'selected' : '' }}>Umroh</option>
                    </select>
                  </div>

                  <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <input
                      type="number"
                      inputmode="numeric"
                      id="harga"
                      name="harga"
                      value="{{ old('harga', $paket->harga) }}"
                      min="0"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                    />
                    <p class="mt-1 text-xs text-gray-500">Simpan uang sebagai integer rupiah.</p>
                  </div>

                  <div>
                    <label for="dp" class="block text-sm font-medium text-gray-700">DP (Rp)</label>
                    <input
                      type="number"
                      inputmode="numeric"
                      id="dp"
                      name="dp"
                      value="{{ old('dp', $paket->dp) }}"
                      min="0"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                    />
                    <p class="mt-1 text-xs text-gray-500">Nominal DP dalam rupiah (integer).</p>
                  </div>

                  <div>
                    <label for="minimum_pembayaran" class="block text-sm font-medium text-gray-700">Minimum Pembayaran (Rp)</label>
                    <input
                      type="number"
                      inputmode="numeric"
                      id="minimum_pembayaran"
                      name="minimum_pembayaran"
                      value="{{ old('minimum_pembayaran', $paket->minimum_pembayaran) }}"
                      min="0"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                    />
                  </div>


                  <div class="sm:col-span-2">
                    <label for="fasilitas" class="block text-sm font-medium text-gray-700">Fasilitas</label>
                    @php
                      $fasilitasValues = old('fasilitas');

                      if (is_string($fasilitasValues)) {
                          $fasilitasValues = preg_split('/\r\n|\r|\n|,|;/', $fasilitasValues) ?: [];
                      }

                      if (!is_array($fasilitasValues)) {
                          $fasilitasValues = is_array($paket->fasilitas) ? $paket->fasilitas : [];
                      }

                      $fasilitasValues = array_values(array_filter(array_map(fn ($item) => trim((string) $item), $fasilitasValues), fn ($item) => $item !== ''));

                      if (count($fasilitasValues) === 0) {
                          $fasilitasValues = [''];
                      }
                    @endphp

                    <div class="mt-1">
                      <div class="flex items-center justify-between gap-3">
                        <p class="text-xs text-gray-500">Klik + untuk tambah baris fasilitas.</p>
                        <button
                          type="button"
                          id="addFasilitasBtn"
                          class="inline-flex items-center rounded-md bg-green-600 text-white px-4 py-1.5  font-medium "
                        >
                          +
                        </button>
                      </div>

                      <div id="fasilitasList" class="mt-2 space-y-2">
                        @foreach ($fasilitasValues as $index => $item)
                          <div class="flex items-center gap-2 fasilitas-row">
                            <input
                              type="text"
                              name="fasilitas[]"
                              value="{{ $item }}"
                              class="block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                              placeholder="Contoh: Manasik, Buku panduan, dll"
                            />
                            <button
                              type="button"
                              data-remove-fasilitas
                              class="inline-flex items-center rounded-md border bg-red-600 text-white px-3 py-2 text-xs font-medium hover:bg-red-700"
                              aria-label="Hapus fasilitas"
                            >
                              Hapus
                            </button>
                          </div>
                        @endforeach
                      </div>

                      @error('fasilitas')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>

                  <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (opsional)</label>
                    <textarea
                      id="description"
                      name="description"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                    >{{ old('description', $paket->description) }}</textarea>
                  </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                  <a
                    href="{{ route('admin.paket.index') }}"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                  >
                    Batal
                  </a>
                  <button
                    type="submit"
                    class="inline-flex items-center rounded-md bg-green-900 px-4 py-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-900 focus:ring-offset-2"
                  >
                    Update Paket
                  </button>
                </div>
              </form>
            </div>
          </div>
        </main>
      </div>
    </div>
    <script>
      
(function () {
    var list = document.getElementById("fasilitasList");
    var addBtn = document.getElementById("addFasilitasBtn");

    if (!list || !addBtn) return;

    function addRow(value) {
        var row = document.createElement("div");
        row.className = "flex items-center gap-2 fasilitas-row";

        var input = document.createElement("input");
        input.type = "text";
        input.name = "fasilitas[]";
        input.value = value || "";
        input.placeholder = "Contoh: Manasik, Buku panduan, dll";
        input.className =
            "block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900";

        var removeBtn = document.createElement("button");
        removeBtn.type = "button";
        removeBtn.setAttribute("data-remove-fasilitas", "");
        removeBtn.setAttribute("aria-label", "Hapus fasilitas");
        removeBtn.className =
            "inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50";
        removeBtn.textContent = "Hapus";

        row.appendChild(input);
        row.appendChild(removeBtn);
        list.appendChild(row);
        input.focus();
    }

    function removeRow(buttonEl) {
        var row = buttonEl.closest(".fasilitas-row");
        if (!row) return;

        var rows = list.querySelectorAll(".fasilitas-row");
        if (rows.length <= 1) {
            var input = row.querySelector("input[name='fasilitas[]']");
            if (input) input.value = "";
            return;
        }

        row.remove();
    }

    addBtn.addEventListener("click", function () {
        addRow("");
    });

    list.addEventListener("click", function (e) {
        var target = e.target;
        if (!(target instanceof Element)) return;
        if (!target.matches("[data-remove-fasilitas]")) return;
        removeRow(target);
    });
})();

    </script>
  </body>
</html>
