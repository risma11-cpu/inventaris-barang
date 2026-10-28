<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class TransaksiController extends Controller
{
    public function index(Request $request): View
    {
        $jenis = $request->query('jenis');
        $barangId = $request->query('barang_id');

        $transaksis = Transaksi::with('barang')
            ->when($jenis, fn ($q) => $q->where('jenis', $jenis))
            ->when($barangId, fn ($q) => $q->where('barang_id', $barangId))
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $barangs = Barang::orderBy('nama_barang')->get();

        return view('transaksi.index', compact('transaksis', 'barangs', 'jenis', 'barangId'));
    }

    public function create(): View
    {
        $barangs = Barang::orderBy('nama_barang')->get();

        return view('transaksi.create', compact('barangs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'barang_id' => ['required', 'exists:barangs,id'],
            'jenis' => ['required', 'in:masuk,keluar'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function () use ($data) {
                $barang = Barang::lockForUpdate()->findOrFail($data['barang_id']);

                if ($data['jenis'] === 'keluar') {
                    if ($data['jumlah'] > $barang->stok) {
                        throw new RuntimeException(
                            "Stok {$barang->nama_barang} tidak cukup. Stok saat ini: {$barang->stok} {$barang->satuan}."
                        );
                    }
                    $barang->stok -= $data['jumlah'];
                } else {
                    $barang->stok += $data['jumlah'];
                }

                $barang->save();
                Transaksi::create($data);
            });
        } catch (RuntimeException $e) {
            return back()->withInput()->with('gagal', $e->getMessage());
        }

        return redirect()->route('transaksi.index')
            ->with('sukses', 'Transaksi berhasil disimpan dan stok sudah diperbarui.');
    }

    public function destroy(Transaksi $transaksi): RedirectResponse
    {
        try {
            DB::transaction(function () use ($transaksi) {
                $barang = Barang::lockForUpdate()->findOrFail($transaksi->barang_id);

                if ($transaksi->jenis === 'masuk') {
                    // Membatalkan barang masuk = stok dikurangi
                    if ($transaksi->jumlah > $barang->stok) {
                        throw new RuntimeException(
                            'Transaksi tidak bisa dihapus karena stok saat ini lebih kecil dari jumlah barang masuk tersebut.'
                        );
                    }
                    $barang->stok -= $transaksi->jumlah;
                } else {
                    // Membatalkan barang keluar = stok dikembalikan
                    $barang->stok += $transaksi->jumlah;
                }

                $barang->save();
                $transaksi->delete();
            });
        } catch (RuntimeException $e) {
            return redirect()->route('transaksi.index')->with('gagal', $e->getMessage());
        }

        return redirect()->route('transaksi.index')
            ->with('sukses', 'Transaksi dihapus dan stok dikembalikan.');
    }
}