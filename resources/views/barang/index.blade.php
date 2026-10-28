@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Barang</h4>
    <a href="{{ route('barang.create') }}" class="btn btn-primary">+ Tambah Barang</a>
</div>

<form method="GET" action="{{ route('barang.index') }}" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="cari" value="{{ $cari }}" class="form-control" placeholder="Cari kode atau nama barang...">
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-secondary">Cari</button>
        @if ($cari)
            <a href="{{ route('barang.index') }}" class="btn btn-link">Reset</a>
        @endif
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th class="text-end">Stok</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Biaya Pesan</th>
                    <th class="text-end">Biaya Simpan</th>
                    <th class="text-end">Lead Time</th>
                    <th class="text-end">Safety Stock</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangs as $i => $b)
                    <tr>
                        <td>{{ $barangs->firstItem() + $i }}</td>
                        <td>{{ $b->kode_barang }}</td>
                        <td>{{ $b->nama_barang }}</td>
                        <td>{{ $b->satuan }}</td>
                        <td class="text-end">{{ number_format($b->stok, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($b->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($b->biaya_pemesanan, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($b->biaya_penyimpanan, 0, ',', '.') }}</td>
                        <td class="text-end">{{ $b->lead_time }} hari</td>
                        <td class="text-end">{{ number_format($b->safety_stock, 0, ',', '.') }}</td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('barang.edit', $b) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('barang.destroy', $b) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus barang ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-4">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $barangs->links() }}
</div>
@endsection