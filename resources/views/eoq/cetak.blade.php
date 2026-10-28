<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan EOQ - {{ now()->format('d-m-Y') }}</title>
    <style>
        @page { size: A4 landscape; margin: 12mm; }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 24px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #111;
        }

        .toolbar { margin-bottom: 18px; display: flex; gap: 8px; }
        .toolbar button, .toolbar a {
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 700;
            color: #111;
            text-decoration: none;
            background: #ffd43b;
            border: 2px solid #111;
            border-radius: 8px;
            cursor: pointer;
        }
        .toolbar a { background: #fff; }

        h1 { margin: 0 0 2px; font-size: 18px; }
        .sub { margin: 0 0 14px; color: #444; }

        .meta { margin-bottom: 12px; }
        .meta td { padding: 1px 12px 1px 0; }

        table.laporan { width: 100%; border-collapse: collapse; }
        table.laporan th,
        table.laporan td {
            padding: 5px 6px;
            border: 1px solid #333;
            vertical-align: middle;
        }
        table.laporan th {
            background: #e4daff;
            font-size: 10px;
            text-transform: uppercase;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .kanan { text-align: right; }
        .tengah { text-align: center; }

        .rumus { margin-top: 16px; line-height: 1.6; }
        .rumus b { display: inline-block; min-width: 70px; }

        @media print {
            body { padding: 0; }
            .toolbar { display: none; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">Cetak / Simpan PDF</button>
        <a href="{{ route('eoq.index', ['periode' => $periode]) }}">Kembali</a>
    </div>

    <h1>Laporan Analisis Economic Order Quantity (EOQ)</h1>
    <p class="sub">Sistem Informasi Inventaris Barang</p>

    <table class="meta">
        <tr>
            <td>Tanggal cetak</td>
            <td>: {{ now()->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Periode data pemakaian</td>
            <td>: {{ $periode }} hari terakhir ({{ now()->subDays($periode - 1)->format('d/m/Y') }} s.d. {{ now()->format('d/m/Y') }})</td>
        </tr>
        <tr>
            <td>Jumlah barang</td>
            <td>: {{ $hasil->count() }}</td>
        </tr>
    </table>

    <table class="laporan">
        <thead>
            <tr>
                <th>No</th>
                <th>Barang</th>
                <th>Stok</th>
                <th>Biaya Pesan (S)</th>
                <th>Biaya Simpan (H)</th>
                <th>Lead Time</th>
                <th>Safety Stock</th>
                <th>D / Tahun</th>
                <th>EOQ</th>
                <th>ROP</th>
                <th>Frekuensi / Thn</th>
                <th>Total Biaya / Thn</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($hasil as $i => $r)
                @php $b = $r['barang']; @endphp
                <tr>
                    <td class="tengah">{{ $i + 1 }}</td>
                    <td>{{ $b->kode_barang }} - {{ $b->nama_barang }}</td>
                    <td class="kanan">{{ number_format($b->stok, 0, ',', '.') }} {{ $b->satuan }}</td>
                    <td class="kanan">Rp {{ number_format($b->biaya_pemesanan, 0, ',', '.') }}</td>
                    <td class="kanan">Rp {{ number_format($b->biaya_penyimpanan, 0, ',', '.') }}</td>
                    <td class="kanan">{{ $b->lead_time }} hari</td>
                    <td class="kanan">{{ number_format($b->safety_stock, 0, ',', '.') }}</td>
                    <td class="kanan">{{ number_format($r['kebutuhan_tahunan'], 0, ',', '.') }}</td>
                    <td class="kanan"><b>{{ $r['eoq'] > 0 ? number_format($r['eoq'], 0, ',', '.') : '-' }}</b></td>
                    <td class="kanan">{{ number_format($r['rop'], 0, ',', '.') }}</td>
                    <td class="kanan">{{ $r['eoq'] > 0 ? number_format($r['frekuensi'], 1, ',', '.') . 'x' : '-' }}</td>
                    <td class="kanan">{{ $r['eoq'] > 0 ? 'Rp ' . number_format($r['total_biaya'], 0, ',', '.') : '-' }}</td>
                    <td class="tengah">{{ $labelStatus[$r['status']] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="tengah">Belum ada data barang.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="rumus">
        <div><b>D</b> = total barang keluar ÷ jumlah hari periode × 365</div>
        <div><b>EOQ</b> = √(2 × D × S ÷ H), dibulatkan ke atas</div>
        <div><b>ROP</b> = (rata-rata pemakaian harian × lead time) + safety stock</div>
        <div><b>Frekuensi</b> = D ÷ EOQ</div>
        <div><b>Total biaya</b> = (D ÷ EOQ × S) + (EOQ ÷ 2 × H)</div>
    </div>
</body>
</html>