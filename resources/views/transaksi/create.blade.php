@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<h4 class="mb-3">Tambah Transaksi</h4>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Barang</label>
                    <select name="barang_id" class="form-select @error('barang_id') is-invalid @enderror">
                        <option value="">-- Pilih barang --</option>
                        @foreach ($barangs as $b)
                            <option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->kode_barang }} - {{ $b->nama_barang }} (stok: {{ $b->stok }} {{ $b->satuan }})
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Jenis Transaksi</label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror">
                        <option value="">-- Pilih jenis --</option>
                        <option value="masuk" {{ old('jenis') === 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                        <option value="keluar" {{ old('jenis') === 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
                    </select>
                    @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" min="1" class="form-control @error('jumlah') is-invalid @enderror"
                           value="{{ old('jumlah') }}">
                    @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                           value="{{ old('tanggal', date('Y-m-d')) }}">
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label">Keterangan (opsional)</label>
                    <input type="text" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                           value="{{ old('keterangan') }}" placeholder="Contoh: pembelian dari supplier, pemakaian ruang TU">
                    @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection