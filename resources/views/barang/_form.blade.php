<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Kode Barang</label>
        <input type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror"
               value="{{ old('kode_barang', $barang->kode_barang) }}">
        @error('kode_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-5">
        <label class="form-label">Nama Barang</label>
        <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror"
               value="{{ old('nama_barang', $barang->nama_barang) }}">
        @error('nama_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Satuan</label>
        <input type="text" name="satuan" placeholder="pcs, box, kg" class="form-control @error('satuan') is-invalid @enderror"
               value="{{ old('satuan', $barang->satuan) }}">
        @error('satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        @if ($barang->exists)
            <label class="form-label">Stok Saat Ini</label>
            <input type="number" class="form-control" value="{{ $barang->stok }}" disabled
                   style="background-color: #e4daff; cursor: not-allowed;">
            <div class="form-text fw-bold">
                Stok hanya berubah lewat menu <a href="{{ route('transaksi.create') }}">Transaksi</a>.
            </div>
        @else
            <label class="form-label">Stok Awal</label>
            <input type="number" name="stok" min="0" class="form-control @error('stok') is-invalid @enderror"
                   value="{{ old('stok', 0) }}">
            @error('stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text fw-bold">Dicatat otomatis sebagai transaksi masuk "Stok awal".</div>
        @endif
    </div>

    <div class="col-md-4">
        <label class="form-label">Harga Satuan (Rp)</label>
        <input type="number" name="harga_satuan" min="0" step="0.01" class="form-control @error('harga_satuan') is-invalid @enderror"
               value="{{ old('harga_satuan', $barang->harga_satuan ?? 0) }}">
        @error('harga_satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Lead Time (hari)</label>
        <input type="number" name="lead_time" min="0" class="form-control @error('lead_time') is-invalid @enderror"
               value="{{ old('lead_time', $barang->lead_time ?? 1) }}">
        @error('lead_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Biaya Pemesanan / Sekali Pesan (Rp)</label>
        <input type="number" name="biaya_pemesanan" min="0" step="0.01" class="form-control @error('biaya_pemesanan') is-invalid @enderror"
               value="{{ old('biaya_pemesanan', $barang->biaya_pemesanan ?? 0) }}">
        @error('biaya_pemesanan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Biaya Penyimpanan / Unit / Tahun (Rp)</label>
        <input type="number" name="biaya_penyimpanan" min="0" step="0.01" class="form-control @error('biaya_penyimpanan') is-invalid @enderror"
               value="{{ old('biaya_penyimpanan', $barang->biaya_penyimpanan ?? 0) }}">
        @error('biaya_penyimpanan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Safety Stock</label>
        <input type="number" name="safety_stock" min="0" class="form-control @error('safety_stock') is-invalid @enderror"
               value="{{ old('safety_stock', $barang->safety_stock ?? 0) }}">
        @error('safety_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>