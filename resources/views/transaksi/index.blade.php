@extends('layouts.app')

@section('title', 'Transaksi Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Transaksi Barang</h4>
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary">+ Tambah Transaksi</a>
</div>

<form method="GET" action="{{ route('transaksi.index') }}" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="barang_id" class="form-select">
            <option value="">Semua barang</option>
            @foreach ($barangs as $b)
                <option value="{{ $b->id }}" {{ (string) $barangId === (string) $b->id ? 'selected' : '' }}>
                    {{ $b->kode_barang }} - {{ $b->nama_barang }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="jenis" class="form-select">
            <option value="">Semua jenis</option>
            <option value="masuk" {{ $jenis === 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
            <option value="keluar" {{ $jenis === 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-secondary">Filter</button>
        @if ($jenis || $barangId)
            <a href="{{ route('transaksi.index') }}" class="btn btn-link">Reset</a>
        @endif
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th class="text-end">Jumlah</th>
                    <th>Keterangan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksis as $i => $t)
                    <tr>
                        <td>{{ $transaksis->firstItem() + $i }}</td>
                        <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $t->barang->kode_barang }}</td>
                        <td>{{ $t->barang->nama_barang }}</td>
                        <td>
                            @if ($t->jenis === 'masuk')
                                <span class="badge bg-success">Masuk</span>
                            @else
                                <span class="badge bg-danger">Keluar</span>
                            @endif
                        </td>
                        <td class="text-end">{{ number_format($t->jumlah, 0, ',', '.') }} {{ $t->barang->satuan }}</td>
                        <td>{{ $t->keterangan ?: '-' }}</td>
                        <td class="text-center">
                            <form action="{{ route('transaksi.destroy', $t) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus transaksi ini? Stok barang akan disesuaikan kembali.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $transaksis->links() }}
</div>
@endsection