<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Support\Collection;

class EoqService
{
    public const HARI_PER_TAHUN = 365;
    public const PERIODE_DEFAULT = 365;

    /**
     * Hitung EOQ, ROP, dan biaya untuk semua barang.
     * Kebutuhan pemakaian diambil dari transaksi "keluar" pada periode terakhir.
     */
    public function semua(int $periodeHari): Collection
    {
        $periodeHari = max(1, $periodeHari);
        $mulai = now()->subDays($periodeHari - 1)->toDateString();

        $totalKeluar = Transaksi::where('jenis', 'keluar')
            ->where('tanggal', '>=', $mulai)
            ->groupBy('barang_id')
            ->selectRaw('barang_id, SUM(jumlah) as total')
            ->pluck('total', 'barang_id');

        return Barang::orderBy('nama_barang')->get()->map(
            fn (Barang $barang) => $this->hitung(
                $barang,
                (float) ($totalKeluar[$barang->id] ?? 0),
                $periodeHari
            )
        );
    }

    public function hitung(Barang $barang, float $totalKeluar, int $periodeHari): array
    {
        $periodeHari = max(1, $periodeHari);

        $rataHarian = $totalKeluar / $periodeHari;                           // d
        $kebutuhanTahunan = ($totalKeluar * self::HARI_PER_TAHUN) / $periodeHari; // D
        $s = (float) $barang->biaya_pemesanan;                               // S
        $h = (float) $barang->biaya_penyimpanan;                             // H

        // EOQ = akar(2 x D x S / H)
        $eoq = 0;
        if ($kebutuhanTahunan > 0 && $s > 0 && $h > 0) {
            $eoq = (int) ceil(round(sqrt((2 * $kebutuhanTahunan * $s) / $h), 6));
        }

        // ROP = (pemakaian harian x lead time) + safety stock
        $kebutuhanLeadTime = ($totalKeluar * $barang->lead_time) / $periodeHari;
        $rop = (int) ceil(round($kebutuhanLeadTime, 6)) + $barang->safety_stock;

        $frekuensi = $eoq > 0 ? $kebutuhanTahunan / $eoq : 0;
        $siklusHari = $frekuensi > 0 ? self::HARI_PER_TAHUN / $frekuensi : 0;
        $biayaPemesanan = $frekuensi * $s;
        $biayaPenyimpanan = ($eoq / 2) * $h;

        if ($barang->stok <= $barang->safety_stock) {
            $status = 'kritis';
        } elseif ($kebutuhanTahunan <= 0) {
            $status = 'tanpa_data';
        } elseif ($barang->stok <= $rop) {
            $status = 'pesan';
        } else {
            $status = 'aman';
        }

        return [
            'barang' => $barang,
            'total_keluar' => (int) $totalKeluar,
            'rata_harian' => $rataHarian,
            'kebutuhan_tahunan' => $kebutuhanTahunan,
            'eoq' => $eoq,
            'rop' => $rop,
            'frekuensi' => $frekuensi,
            'siklus_hari' => $siklusHari,
            'biaya_pemesanan' => $biayaPemesanan,
            'biaya_penyimpanan' => $biayaPenyimpanan,
            'total_biaya' => $biayaPemesanan + $biayaPenyimpanan,
            'status' => $status,
        ];
    }
}