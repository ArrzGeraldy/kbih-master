<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PaketController extends Controller
{
    private const TYPE_OPTIONS = ['BIMBINGAN_HAJI', 'UMROH'];

    public function index(Request $request): View
    {
        $type = $request->string('type')->trim()->toString();
        if (!in_array($type, self::TYPE_OPTIONS, true)) {
            $type = '';
        }

        $pakets = Paket::query()
            ->when(
                $type !== '',
                fn ($query) => $query->where('type', $type)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.paket.index', [
            'pakets' => $pakets,
            'filters' => [
                'type' => $type,
            ],
            'typeOptions' => self::TYPE_OPTIONS,
        ]);
    }

    public function create(): View
    {
        return view('admin.paket.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePaket($request);

        Paket::create($validated);

        return redirect()
            ->route('admin.paket.index')
            ->with('success', 'Paket berhasil dibuat.');
    }

    public function edit(Paket $paket): View
    {
        return view('admin.paket.edit', compact('paket'));
    }

    public function update(Request $request, Paket $paket): RedirectResponse
    {
        $validated = $this->validatePaket($request);

        $paket->update($validated);

        return redirect()
            ->route('admin.paket.index')
            ->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Paket $paket): RedirectResponse
    {
        $paket->delete();

        return redirect()
            ->route('admin.paket.index')
            ->with('success', 'Paket berhasil dihapus.');
    }

    private function validatePaket(Request $request): array
    {
        $fasilitasInput = $request->input('fasilitas');

        $rules = [
            'nama_paket' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:BIMBINGAN_HAJI,UMROH'],
            'description' => ['nullable', 'string'],

            'harga' => ['required', 'integer', 'min:0'],
            'dp' => ['required', 'integer', 'min:0'],
            'minimum_pembayaran' => ['required', 'integer', 'min:0'],
        ];

        if (is_array($fasilitasInput)) {
            $rules['fasilitas'] = ['required', 'array', 'min:1'];
            $rules['fasilitas.*'] = ['nullable', 'string', 'max:255'];
        } else {
            $rules['fasilitas'] = ['required', 'string'];
        }

        $validated = $request->validate($rules);

        $items = [];

        if (is_array($validated['fasilitas'] ?? null)) {
            $items = $validated['fasilitas'];
        } else {
            $items = preg_split('/\r\n|\r|\n|,|;/', (string) ($validated['fasilitas'] ?? '')) ?: [];
        }

        $items = array_values(
            array_filter(
                array_map(static fn ($item) => trim((string) $item), $items),
                static fn ($item) => $item !== ''
            )
        );

        if (count($items) < 1) {
            throw ValidationException::withMessages([
                'fasilitas' => 'Minimal isi 1 fasilitas.',
            ]);
        }

        $validated['fasilitas'] = $items;

        return $validated;
    }
}
