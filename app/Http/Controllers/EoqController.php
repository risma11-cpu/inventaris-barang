<?php

namespace App\Http\Controllers;

use App\Services\EoqService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EoqController extends Controller
{
    private const PERIODE_TERSEDIA = [30, 90, 180, 365];

    private const LABEL_STATUS = [
        'aman' => 'Aman',
        'pesan' => 'Pesan Ulang',
        'kritis' => 'Kritis',
        'tanpa_data' => 'Belum Ada Data',
    ];

    public function index(Request $request, EoqService $eoq): View
    {
        $periode = $this->periode($request);
        $periodeTersedia = self::PERIODE_TERSEDIA;
        $hasil = $eoq->semua($periode);

        return view('eoq.index', compact('hasil', 'periode', 'periodeTersedia'));
    }

    public function cetak(Request $request, EoqService $eoq): View
    {
        $periode = $this->periode($request);
        $hasil = $eoq->semua($periode);
        $labelStatus = self::LABEL_STATUS;

        return view('eoq.cetak', compact('hasil', 'periode', 'labelStatus'));
    }

    public function ekspor(Request $request, EoqService $eoq): StreamedResponse
    {
        $periode = $this->periode($request);
        $hasil = $eoq->semua($periode);
        $namaFile = 'laporan-eoq-' . now()->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($hasil) {
            $out = fopen('php://output', 'w');

            // BOM supaya Excel membaca file sebagai UTF-8
            fwrite($out, "\xEF\xBB\xBF");

            // Pemisah titik koma dan desimal koma, sesuai Excel berbahasa Indonesia
            fputcsv($out, [
                'No', 'Kode Barang', 'Nama Barang', 'Satuan', 'Stok',
                'Biaya Pemesanan (S)', 'Biaya Penyimpanan (H)', 'Lead Time (hari)', 'Safety Stock',
                'Total Keluar Periode', 'Kebutuhan per Tahun (D)', 'EOQ', 'ROP',
                'Frekuensi Pesan per Tahun', 'Siklus Pesan (hari)',
                'Biaya Pemesanan per Tahun', 'Biaya Penyimpanan per Tahun', 'Total Biaya per Tahun',
                'Status',
            ], ';');

            foreach ($hasil as $i => $r) {
                $b = $r['barang'];

                fputcsv($out, [
                    $i + 1,
                    $b->kode_barang,
                    $b->nama_barang,
                    $b->satuan,
                    $b->stok,
                    round($b->biaya_pemesanan),
                    round($b->biaya_penyimpanan),
                    $b->lead_time,
                    $b->safety_stock,
                    $r['total_keluar'],
                    round($r['kebutuhan_tahunan']),
                    $r['eoq'],
                    $r['rop'],
                    number_format($r['frekuensi'], 2, ',', ''),
                    round($r['siklus_hari']),
                    round($r['biaya_pemesanan']),
                    round($r['biaya_penyimpanan']),
                    round($r['total_biaya']),
                    self::LABEL_STATUS[$r['status']] ?? '-',
                ], ';');
            }

            fclose($out);
        }, $namaFile, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function periode(Request $request): int
    {
        $periode = (int) $request->query('periode', EoqService::PERIODE_DEFAULT);

        return in_array($periode, self::PERIODE_TERSEDIA, true)
            ? $periode
            : EoqService::PERIODE_DEFAULT;
    }
}