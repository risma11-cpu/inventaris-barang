<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Services\EoqService;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(EoqService $eoq): View
    {
        $periode = EoqService::PERIODE_DEFAULT;
        $semua = $eoq->semua($periode);

        $perluPesan = $semua
            ->whereIn('status', ['pesan', 'kritis'])
            ->sortBy(fn ($r) => $r['status'] === 'kritis' ? 0 : 1)
            ->values();

        $ringkasan = [
            'jumlah_barang' => Barang::count(),
            'total_stok' => (int) Barang::sum('stok'),
            'perlu_pesan' => $perluPesan->count(),
            'transaksi_bulan_ini' => Transaksi::whereYear('tanggal', now()->year)
                ->whereMonth('tanggal', now()->month)
                ->count(),
        ];

        $transaksiTerbaru = Transaksi::with('barang')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $grafikStok = $this->grafikStok($semua);
        $grafikBulanan = $this->grafikBulanan();

        return view('dashboard', compact(
            'ringkasan', 'perluPesan', 'transaksiTerbaru', 'periode', 'grafikStok', 'grafikBulanan'
        ));
    }

    /**
     * 8 barang yang stoknya paling dekat (atau di bawah) titik pesan ulang.
     */
    private function grafikStok(Collection $semua): array
    {
        $dipilih = $semua
            ->sortBy(fn ($r) => $r['barang']->stok - $r['rop'])
            ->take(8)
            ->values();

        return [
            'label' => $dipilih->map(fn ($r) => $r['barang']->nama_barang)->all(),
            'stok' => $dipilih->map(fn ($r) => $r['barang']->stok)->all(),
            'rop' => $dipilih->map(fn ($r) => $r['rop'])->all(),
            'safety' => $dipilih->map(fn ($r) => $r['barang']->safety_stock)->all(),
        ];
    }

    /**
     * Total barang masuk dan keluar per bulan selama 12 bulan terakhir.
     */
    private function grafikBulanan(): array
    {
        $awal = now()->startOfMonth()->subMonths(11);
        $bulan = collect(range(0, 11))->map(fn ($i) => $awal->copy()->addMonths($i));

        $rekap = Transaksi::where('tanggal', '>=', $awal->toDateString())
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan, jenis, SUM(jumlah) as total")
            ->groupBy('bulan', 'jenis')
            ->get();

        $totalPerBulan = fn (string $jenis) => $bulan->map(
            fn ($b) => (int) $rekap
                ->where('bulan', $b->format('Y-m'))
                ->where('jenis', $jenis)
                ->sum('total')
        )->all();

        return [
            'label' => $bulan->map(fn ($b) => $b->copy()->locale('id')->translatedFormat('M y'))->all(),
            'masuk' => $totalPerBulan('masuk'),
            'keluar' => $totalPerBulan('keluar'),
        ];
    }
}