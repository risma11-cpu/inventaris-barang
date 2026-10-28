<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BarangController extends Controller
{
    public function index(Request $request): View
    {
        $cari = $request->query('cari');

        $barangs = Barang::query()
            ->when($cari, function ($query) use ($cari) {
                $query->where('nama_barang', 'like', "%{$cari}%")
                      ->orWhere('kode_barang', 'like', "%{$cari}%");
            })
            ->orderBy('nama_barang')
            ->paginate(10)
            ->withQueryString();

        return view('barang.index', compact('barangs', 'cari'));
    }

    public function create(): View
    {
        return view('barang.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        DB::transaction(function () use ($data) {
            $barang = Barang::create($data);

            // Stok awal dicatat sebagai transaksi masuk supaya riwayat selalu cocok dengan stok
            if ($barang->stok > 0) {
                Transaksi::create([
                    'barang_id' => $barang->id,
                    'jenis' => 'masuk',
                    'jumlah' => $barang->stok,
                    'tanggal' => now()->toDateString(),
                    'keterangan' => 'Stok awal',
                ]);
            }
        });

        return redirect()->route('barang.index')
            ->with('sukses', 'Data barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang): View
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang): RedirectResponse
    {
        // Stok sengaja tidak divalidasi di sini, jadi tidak ikut diperbarui.
        // Stok hanya boleh berubah lewat transaksi masuk atau keluar.
        $data = $request->validate($this->rules($barang->id, false));

        $barang->update($data);

        return redirect()->route('barang.index')
            ->with('sukses', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang): RedirectResponse
    {
        $barang->delete();

        return redirect()->route('barang.index')
            ->with('sukses', 'Data barang berhasil dihapus.');
    }

    private function rules(?int $id = null, bool $denganStok = true): array
    {
        $rules = [
            'kode_barang' => [
                'required', 'string', 'max:30',
                Rule::unique('barangs', 'kode_barang')->ignore($id),
            ],
            'nama_barang' => ['required', 'string', 'max:255'],
            'satuan' => ['required', 'string', 'max:20'],
            'harga_satuan' => ['required', 'numeric', 'min:0'],
            'biaya_pemesanan' => ['required', 'numeric', 'min:0'],
            'biaya_penyimpanan' => ['required', 'numeric', 'min:0'],
            'lead_time' => ['required', 'integer', 'min:0'],
            'safety_stock' => ['required', 'integer', 'min:0'],
        ];

        if ($denganStok) {
            $rules['stok'] = ['required', 'integer', 'min:0'];
        }

        return $rules;
    }
}