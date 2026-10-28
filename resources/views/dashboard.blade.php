@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<h4 class="mb-1">Ringkasan Inventaris</h4>
<p class="fw-bold mb-4">Pantau stok dan tahu kapan harus pesan ulang.</p>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-ungu">
            <div class="stat-label">Jenis Barang</div>
            <div class="stat-value">{{ number_format($ringkasan['jumlah_barang'], 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-kuning">
            <div class="stat-label">Total Stok</div>
            <div class="stat-value">{{ number_format($ringkasan['total_stok'], 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-pink">
            <div class="stat-label">Perlu Pesan Ulang</div>
            <div class="stat-value">{{ number_format($ringkasan['perlu_pesan'], 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-hijau">
            <div class="stat-label">Transaksi Bulan Ini</div>
            <div class="stat-value">{{ number_format($ringkasan['transaksi_bulan_ini'], 0, ',', '.') }}</div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('barang.create') }}" class="btn btn-primary">+ Tambah Barang</a>
    <a href="{{ route('transaksi.create') }}" class="btn btn-secondary">+ Tambah Transaksi</a>
    <a href="{{ route('eoq.index') }}" class="btn btn-secondary">Lihat Analisis EOQ</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">Barang Masuk vs Keluar (12 Bulan Terakhir)</div>
            <div class="card-body">
                <div style="position: relative; height: 300px;">
                    <canvas id="grafikBulanan"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">Stok vs Titik Pesan Ulang (ROP)</div>
            <div class="card-body">
                <div style="position: relative; height: 300px;">
                    <canvas id="grafikStok"></canvas>
                </div>
            </div>
            <div class="card-footer small">8 barang yang paling dekat dengan titik pesan ulang.</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">Perlu Dipesan Ulang</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th class="text-end">Stok</th>
                            <th class="text-end">ROP</th>
                            <th class="text-end">Pesan (EOQ)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($perluPesan->take(5) as $r)
                            <tr>
                                <td>
                                    {{ $r['barang']->nama_barang }}
                                    <div class="small text-muted">{{ $r['barang']->kode_barang }}</div>
                                </td>
                                <td class="text-end">{{ number_format($r['barang']->stok, 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($r['rop'], 0, ',', '.') }}</td>
                                <td class="text-end">
                                    {{ $r['eoq'] > 0 ? number_format($r['eoq'], 0, ',', '.') . ' ' . $r['barang']->satuan : '-' }}
                                </td>
                                <td>@include('eoq._status', ['status' => $r['status']])</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Semua stok masih aman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer small">
                @if ($perluPesan->count() > 5)
                    + {{ $perluPesan->count() - 5 }} barang lain. Lihat selengkapnya di Analisis EOQ.
                @else
                    Dihitung dari pemakaian {{ $periode }} hari terakhir.
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">Transaksi Terbaru</div>
            <ul class="list-group list-group-flush">
                @forelse ($transaksiTerbaru as $t)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">{{ $t->barang->nama_barang }}</div>
                            <div class="small text-muted">
                                {{ $t->tanggal->format('d/m/Y') }}{{ $t->keterangan ? ' · ' . $t->keterangan : '' }}
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $t->jenis === 'masuk' ? 'success' : 'danger' }}">
                                {{ $t->jenis === 'masuk' ? 'Masuk' : 'Keluar' }}
                            </span>
                            <div class="small fw-bold mt-1">{{ number_format($t->jumlah, 0, ',', '.') }} {{ $t->barang->satuan }}</div>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted py-4">Belum ada transaksi.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    Chart.defaults.font.family = '"Space Grotesk", sans-serif';
    Chart.defaults.font.weight = '700';
    Chart.defaults.color = '#111';

    const bulanan = @json($grafikBulanan);
    const stok = @json($grafikStok);

    const gaya = (warna) => ({
        backgroundColor: warna,
        borderColor: '#111',
        borderWidth: 2.5,
        borderRadius: 6,
    });

    const opsi = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: { grid: { display: false }, border: { color: '#111', width: 2 } },
            y: { beginAtZero: true, grid: { color: '#ddd6f7' }, border: { color: '#111', width: 2 } },
        },
        plugins: { legend: { position: 'bottom' } },
    };

    new Chart(document.getElementById('grafikBulanan'), {
        type: 'bar',
        data: {
            labels: bulanan.label,
            datasets: [
                { label: 'Barang Masuk', data: bulanan.masuk, ...gaya('#b8f2c7') },
                { label: 'Barang Keluar', data: bulanan.keluar, ...gaya('#ff8fa8') },
            ],
        },
        options: opsi,
    });

    new Chart(document.getElementById('grafikStok'), {
        type: 'bar',
        data: {
            labels: stok.label,
            datasets: [
                { label: 'Stok', data: stok.stok, ...gaya('#c4b5fd') },
                { label: 'ROP', data: stok.rop, ...gaya('#ffd43b') },
                { label: 'Safety Stock', data: stok.safety, ...gaya('#ff8fa8') },
            ],
        },
        options: opsi,
    });
</script>
@endsection