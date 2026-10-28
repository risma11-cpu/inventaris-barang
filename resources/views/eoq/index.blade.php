@extends('layouts.app')

@section('title', 'Analisis EOQ')

@section('content')
<h4 class="mb-1">Analisis EOQ</h4>
<p class="fw-bold mb-3">Berapa banyak harus dipesan, dan kapan waktunya pesan ulang.</p>

<div class="row g-2 align-items-center mb-3">
    <div class="col-12 col-lg">
        <form method="GET" action="{{ route('eoq.index') }}" class="row g-2 align-items-center">
            <div class="col-auto">
                <label for="periode" class="col-form-label fw-bold">Data pemakaian</label>
            </div>
            <div class="col-auto">
                <select name="periode" id="periode" class="form-select">
                    @foreach ($periodeTersedia as $p)
                        <option value="{{ $p }}" {{ $periode === $p ? 'selected' : '' }}>{{ $p }} hari terakhir</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">Terapkan</button>
            </div>
        </form>
    </div>

    <div class="col-12 col-lg-auto d-flex flex-wrap gap-2">
        <a href="{{ route('eoq.cetak', ['periode' => $periode]) }}" target="_blank" class="btn btn-secondary">Cetak / PDF</a>
        <a href="{{ route('eoq.ekspor', ['periode' => $periode]) }}" class="btn btn-secondary">Ekspor Excel (CSV)</a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Barang</th>
                    <th class="text-end">Stok</th>
                    <th class="text-end">Keluar ({{ $periode }} hr)</th>
                    <th class="text-end">D / Tahun</th>
                    <th class="text-end">EOQ</th>
                    <th class="text-end">ROP</th>
                    <th class="text-end">Frekuensi / Thn</th>
                    <th class="text-end">Total Biaya / Thn</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($hasil as $r)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $r['barang']->nama_barang }}</div>
                            <div class="small text-muted">{{ $r['barang']->kode_barang }}</div>
                        </td>
                        <td class="text-end">{{ number_format($r['barang']->stok, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($r['total_keluar'], 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($r['kebutuhan_tahunan'], 0, ',', '.') }}</td>
                        <td class="text-end fw-bold">
                            {{ $r['eoq'] > 0 ? number_format($r['eoq'], 0, ',', '.') . ' ' . $r['barang']->satuan : '-' }}
                        </td>
                        <td class="text-end">{{ number_format($r['rop'], 0, ',', '.') }}</td>
                        <td class="text-end">
                            {{ $r['eoq'] > 0 ? number_format($r['frekuensi'], 1, ',', '.') . 'x' : '-' }}
                            @if ($r['eoq'] > 0)
                                <div class="small text-muted">tiap {{ number_format($r['siklus_hari'], 0, ',', '.') }} hari</div>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            @if ($r['eoq'] > 0)
                                <div class="fw-bold">Rp {{ number_format($r['total_biaya'], 0, ',', '.') }}</div>
                                <div class="small text-muted">
                                    Pesan Rp {{ number_format($r['biaya_pemesanan'], 0, ',', '.') }}
                                    · Simpan Rp {{ number_format($r['biaya_penyimpanan'], 0, ',', '.') }}
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td>@include('eoq._status', ['status' => $r['status']])</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Cara membaca hasil</div>
    <div class="card-body">
        <ul class="mb-2">
            <li><b>D</b> (kebutuhan per tahun) = total barang keluar ÷ jumlah hari periode × 365</li>
            <li><b>EOQ</b> = √(2 × D × S ÷ H), dibulatkan ke atas. S = biaya pemesanan, H = biaya penyimpanan per unit per tahun</li>
            <li><b>ROP</b> = (rata-rata pemakaian harian × lead time) + safety stock</li>
            <li><b>Frekuensi</b> = D ÷ EOQ</li>
            <li><b>Total biaya</b> = (D ÷ EOQ × S) + (EOQ ÷ 2 × H)</li>
        </ul>
        <p class="mb-0 small">
            Status <b>Pesan Ulang</b> muncul saat stok ≤ ROP, dan <b>Kritis</b> saat stok ≤ safety stock.
            Kalau data transaksi belum sampai setahun, pilih periode yang lebih pendek supaya hasilnya lebih masuk akal.
        </p>
    </div>
</div>
@endsection