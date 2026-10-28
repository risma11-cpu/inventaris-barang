@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<h4 class="mb-3">Tambah Barang</h4>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('barang.store') }}" method="POST">
            @csrf
            @include('barang._form', ['barang' => new \App\Models\Barang])

            <div class="mt-4">
                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection